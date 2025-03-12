<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

class DBBackup extends Command
{
    protected $signature = 'db:backup';

    protected $description = 'Create a database backup';

    public function handle()
    {
        // المسار المؤقت لحفظ النسخة الاحتياطية
        $backupPath = storage_path('app/backups');

        // إنشاء المجلد إذا لم يكن موجودًا
        if (!is_dir($backupPath)) {
            mkdir($backupPath, 0755, true);
        }

        // اسم الملف بناءً على التاريخ والوقت الحالي
        $fileName = Carbon::now()->format('Y_m_d_H_i_s') . ".sql";
        $fullPath = $backupPath . '/' . $fileName;

        // أمر mysqldump لإنشاء النسخة الاحتياطية
        $command = "mysqldump --user=" . env('DB_USERNAME') . " --password=" . env('DB_PASSWORD') . " --host=" . env('DB_HOST') . " " . env("DB_DATABASE") . " > " . $fullPath;
        exec($command);

        $this->info('Backup completed successfully. File saved at: ' . $fullPath);
    }
}
