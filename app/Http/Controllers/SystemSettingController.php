<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;

class SystemSettingController extends Controller
{
    /**
     * Display the system settings page.
     */
    public function index()
    {
        $settings = Setting::getMany();
        return view('settings.system', compact('settings'));
    }

    /**
     * Update general/integration settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'app_name' => 'nullable|string|max:100',
            'app_tagline' => 'nullable|string|max:200',
            'support_email' => 'nullable|email|max:255',
            'currency' => 'nullable|string|max:10',
            'timezone' => 'nullable|string|max:50',
            'system_logo' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'system_favicon' => 'nullable|image|mimes:png,ico|max:1024',
            'mail_host' => 'nullable|string|max:255',
            'mail_port' => 'nullable|string|max:10',
            'mail_username' => 'nullable|string|max:255',
            'mail_password' => 'nullable|string|max:255',
            'mail_encryption' => 'nullable|string|in:tls,ssl,',
            'mail_from_address' => 'nullable|email|max:255',
            'mail_from_name' => 'nullable|string|max:100',
            'whatsapp_api_url' => 'nullable|url|max:500',
            'whatsapp_api_key' => 'nullable|string|max:500',
            'storage_driver' => 'nullable|string|in:local,s3',
        ]);

        $data = $request->except(['_token', 'system_logo', 'system_favicon']);

        // Handle logo upload
        if ($request->hasFile('system_logo')) {
            // Delete old logo
            $oldLogo = Setting::get('system_logo');
            if ($oldLogo && Storage::disk('public')->exists(str_replace('storage/', '', $oldLogo))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $oldLogo));
            }
            $path = $request->file('system_logo')->store('settings', 'public');
            Setting::set('system_logo', 'storage/' . $path);
        }

        // Handle favicon upload
        if ($request->hasFile('system_favicon')) {
            $oldFavicon = Setting::get('system_favicon');
            if ($oldFavicon && Storage::disk('public')->exists(str_replace('storage/', '', $oldFavicon))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $oldFavicon));
            }
            $path = $request->file('system_favicon')->store('settings', 'public');
            Setting::set('system_favicon', 'storage/' . $path);
        }

        // Save all other settings
        foreach ($data as $key => $value) {
            Setting::set($key, $value ?? '');
        }

        return redirect()->back()->with('success', 'Pengaturan berhasil disimpan.');
    }

    /**
     * Toggle maintenance mode on/off.
     */
    public function toggleMaintenance()
    {
        if (app()->isDownForMaintenance()) {
            Artisan::call('up');
            return redirect()->back()->with('success', 'Aplikasi kembali ONLINE.');
        }

        Artisan::call('down', [
            '--secret' => 'spandiv-bypass-' . substr(md5(config('app.key')), 0, 8),
        ]);

        $secret = 'spandiv-bypass-' . substr(md5(config('app.key')), 0, 8);

        return redirect()->back()->with('warning', "Aplikasi dalam MAINTENANCE MODE. Bypass URL: /{$secret}");
    }

    /**
     * Run database backup using mysqldump and download the SQL file.
     */
    public function runBackup()
    {
        try {
            $dbHost = config('database.connections.mysql.host', '127.0.0.1');
            $dbPort = config('database.connections.mysql.port', '3306');
            $dbName = config('database.connections.mysql.database');
            $dbUser = config('database.connections.mysql.username');
            $dbPassword = config('database.connections.mysql.password');

            $filename = 'backup_' . $dbName . '_' . date('Y-m-d_His') . '.sql';
            $filepath = storage_path('app/' . $filename);

            // Build mysqldump command
            $command = sprintf(
                'mysqldump --host=%s --port=%s --user=%s --password=%s %s > %s',
                escapeshellarg($dbHost),
                escapeshellarg($dbPort),
                escapeshellarg($dbUser),
                escapeshellarg($dbPassword),
                escapeshellarg($dbName),
                escapeshellarg($filepath)
            );

            $result = Process::run($command);

            if ($result->failed()) {
                return redirect()->back()->with('error', 'Backup gagal: ' . $result->errorOutput());
            }

            // Check if file was created and has content
            if (!file_exists($filepath) || filesize($filepath) === 0) {
                return redirect()->back()->with('error', 'Backup gagal: File kosong atau tidak terbentuk. Pastikan mysqldump tersedia di server.');
            }

            return response()->download($filepath, $filename)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Backup gagal: ' . $e->getMessage());
        }
    }

    /**
     * Send a test email using current SMTP config.
     */
    public function testEmail(Request $request)
    {
        $request->validate(['test_email' => 'required|email']);

        try {
            // Apply latest SMTP settings from DB at runtime
            $smtpSettings = Setting::getMany([
                'mail_host',
                'mail_port',
                'mail_username',
                'mail_password',
                'mail_encryption',
                'mail_from_address',
                'mail_from_name'
            ]);

            if (!empty($smtpSettings['mail_host'])) {
                Config::set('mail.mailers.smtp.host', $smtpSettings['mail_host']);
            }
            if (!empty($smtpSettings['mail_port'])) {
                Config::set('mail.mailers.smtp.port', $smtpSettings['mail_port']);
            }
            if (!empty($smtpSettings['mail_username'])) {
                Config::set('mail.mailers.smtp.username', $smtpSettings['mail_username']);
            }
            if (!empty($smtpSettings['mail_password'])) {
                Config::set('mail.mailers.smtp.password', $smtpSettings['mail_password']);
            }
            if (isset($smtpSettings['mail_encryption'])) {
                Config::set('mail.mailers.smtp.encryption', $smtpSettings['mail_encryption'] ?: null);
            }
            if (!empty($smtpSettings['mail_from_address'])) {
                Config::set('mail.from.address', $smtpSettings['mail_from_address']);
            }
            if (!empty($smtpSettings['mail_from_name'])) {
                Config::set('mail.from.name', $smtpSettings['mail_from_name']);
            }

            Mail::raw('Ini adalah test email dari Spandiv CRM. Konfigurasi SMTP Anda berfungsi dengan baik! 🎉', function ($message) use ($request) {
                $message->to($request->test_email)
                    ->subject('Test Koneksi SMTP — Spandiv CRM');
            });

            return back()->with('success', 'Email berhasil dikirim! Konfigurasi SMTP berfungsi.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim email: ' . $e->getMessage());
        }
    }
}
