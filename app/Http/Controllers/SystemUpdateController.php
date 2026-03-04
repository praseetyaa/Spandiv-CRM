<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SystemUpdateController extends Controller
{
    private function gitCmd(string $command): string
    {
        $basePath = base_path();
        $fullCmd = "cd " . escapeshellarg($basePath) . " && git {$command} 2>&1";

        if (PHP_OS_FAMILY === 'Windows') {
            $fullCmd = "cd /d " . escapeshellarg($basePath) . " && git {$command} 2>&1";
        }

        $output = shell_exec($fullCmd);
        return trim($output ?? '');
    }

    public function checkUpdate()
    {
        try {
            // Detect current branch
            $branch = $this->gitCmd('rev-parse --abbrev-ref HEAD') ?: 'main';

            // Local version info
            $localHash = $this->gitCmd('rev-parse --short HEAD') ?: 'Unknown';
            $localDate = $this->gitCmd('log -1 --format=%cd --date=short') ?: 'Unknown';
            $localMessage = $this->gitCmd('log -1 --format=%s') ?: '';

            // Fetch remote updates
            $this->gitCmd("fetch origin {$branch}");

            $remoteHash = $this->gitCmd("rev-parse --short origin/{$branch}") ?: 'Unknown';
            $remoteDate = $this->gitCmd("log -1 --format=%cd --date=short origin/{$branch}") ?: 'Unknown';
            $remoteMessage = $this->gitCmd("log -1 --format=%s origin/{$branch}") ?: '';

            // Count commits behind
            $behindCount = (int) ($this->gitCmd("rev-list HEAD..origin/{$branch} --count") ?: '0');
            $hasUpdate = $behindCount > 0;

            return response()->json([
                'success' => true,
                'branch' => $branch,
                'local_hash' => $localHash,
                'local_date' => $localDate,
                'local_message' => $localMessage,
                'remote_hash' => $remoteHash,
                'remote_date' => $remoteDate,
                'remote_message' => $remoteMessage,
                'has_update' => $hasUpdate,
                'behind_count' => $behindCount,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Gagal mengecek update: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function runUpdate()
    {
        try {
            $basePath = base_path();
            $logs = [];

            // Check for uncommitted changes
            $dirtyFiles = $this->gitCmd('status --porcelain');
            if (!empty($dirtyFiles)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Update dibatalkan: Terdapat file yang belum di-commit.',
                    'dirty_files' => $dirtyFiles,
                    'logs' => [],
                ], 422);
            }

            $branch = $this->gitCmd('rev-parse --abbrev-ref HEAD') ?: 'main';

            // Step 1: Git Pull
            $gitOutput = $this->gitCmd("pull origin {$branch}");
            $gitSuccess = !str_contains(strtolower($gitOutput), 'fatal') && !str_contains(strtolower($gitOutput), 'error');
            $logs[] = ['step' => 'Git Pull', 'success' => $gitSuccess, 'output' => $gitOutput];

            if (!$gitSuccess) {
                return response()->json(['success' => false, 'error' => 'Git pull gagal.', 'logs' => $logs], 500);
            }

            // Step 2: Composer Install
            $composerCmd = PHP_OS_FAMILY === 'Windows'
                ? "cd /d " . escapeshellarg($basePath) . " && composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader 2>&1"
                : "cd " . escapeshellarg($basePath) . " && composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader 2>&1";
            $composerOutput = trim(shell_exec($composerCmd) ?? '');
            $composerSuccess = !str_contains(strtolower($composerOutput), 'fatal error');
            $logs[] = ['step' => 'Composer Install', 'success' => $composerSuccess, 'output' => $composerOutput ?: 'Done'];

            if (!$composerSuccess) {
                return response()->json(['success' => false, 'error' => 'Composer install gagal.', 'logs' => $logs], 500);
            }

            // Step 3: Migrate
            $migrateCmd = PHP_OS_FAMILY === 'Windows'
                ? "cd /d " . escapeshellarg($basePath) . " && php artisan migrate --force 2>&1"
                : "cd " . escapeshellarg($basePath) . " && php artisan migrate --force 2>&1";
            $migrateOutput = trim(shell_exec($migrateCmd) ?? '');
            $migrateSuccess = !str_contains(strtolower($migrateOutput), 'error');
            $logs[] = ['step' => 'Database Migration', 'success' => $migrateSuccess, 'output' => $migrateOutput ?: 'Done'];

            // Step 4: Clear caches
            shell_exec("cd /d " . escapeshellarg($basePath) . " && php artisan config:clear && php artisan cache:clear && php artisan view:clear 2>&1");
            $logs[] = ['step' => 'Clear Cache', 'success' => true, 'output' => 'Cache berhasil dibersihkan.'];

            return response()->json([
                'success' => true,
                'message' => 'Sistem berhasil di-update!',
                'logs' => $logs,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Update gagal: ' . $e->getMessage(),
                'logs' => $logs ?? [],
            ], 500);
        }
    }
}
