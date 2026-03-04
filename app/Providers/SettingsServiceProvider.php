<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;

class SettingsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        try {
            if (!Schema::hasTable('settings')) {
                return;
            }

            $settings = Setting::getMany();

            // Application settings
            if (isset($settings['app_name'])) {
                Config::set('app.name', $settings['app_name']);
            }

            if (isset($settings['timezone'])) {
                Config::set('app.timezone', $settings['timezone']);
                date_default_timezone_set($settings['timezone']);
            }

            // SMTP settings
            $smtpKeys = [
                'mail_host' => 'mail.mailers.smtp.host',
                'mail_port' => 'mail.mailers.smtp.port',
                'mail_username' => 'mail.mailers.smtp.username',
                'mail_password' => 'mail.mailers.smtp.password',
                'mail_encryption' => 'mail.mailers.smtp.encryption',
                'mail_from_address' => 'mail.from.address',
                'mail_from_name' => 'mail.from.name',
            ];

            foreach ($smtpKeys as $settingKey => $configKey) {
                if (isset($settings[$settingKey]) && $settings[$settingKey] !== '') {
                    Config::set($configKey, $settings[$settingKey]);
                }
            }
        } catch (\Exception $e) {
            // Fail silently — table may not exist yet during first migration
        }
    }
}
