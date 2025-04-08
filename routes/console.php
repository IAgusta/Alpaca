<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('logs:smart-clear', function () {
    $logPath = storage_path('logs/laravel.log');
    $maxSize = 5 * 1024 * 1024; // 5 MB in bytes

    if (!File::exists($logPath)) {
        $this->warn('⚠️ No laravel.log file found.');
        return;
    }

    $size = File::size($logPath);

    if ($size < $maxSize) {
        $this->info("✅ Log file size is OK (" . round($size / 1024, 2) . " KB). No action taken.");
        return;
    }

    // Backup the current log
    $backupName = 'laravel-' . now()->format('Y-m-d_H-i-s') . '.log.bak';
    $backupPath = storage_path('logs/backups');

    if (!File::exists($backupPath)) {
        File::makeDirectory($backupPath, 0755, true);
    }

    File::copy($logPath, $backupPath . '/' . $backupName);
    File::put($logPath, '');

    $this->info("🗂️ Log file backed up to logs/backups/{$backupName}");
    $this->info('🧹 laravel.log has been cleared.');
})->describe('Clear laravel.log only if size exceeds 5MB, with backup.');