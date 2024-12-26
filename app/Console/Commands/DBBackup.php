<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

class DBBackup extends Command
{
    protected $signature = 'db:backup {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'database backup ';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $path = $this->argument('path');

        // تحقق من أن المسار ليس فارغًا
        if (empty($path)) {
            $this->error('The path cannot be empty.');
            return;
        }

        $fileName = Carbon::now()->format('Y_m_d_H_i_s').".sql";
        $backupPath = rtrim($path, '/'); // إزالة الشرطة المائلة الأخيرة إذا كانت موجودة

        // تحقق من أن المسار صالح
        if (!is_dir($backupPath) && !mkdir($backupPath, 0755, true)) {
            $this->error('Invalid path: ' . $backupPath);
            return;
        }

        $command = "mysqldump --user=".env('DB_USERNAME')." --password=".env('DB_PASSWORD')." --host=".env('DB_HOST')." ".env("DB_DATABASE")." > ".$backupPath.'/'.$fileName;
        exec($command);

        $this->info('Backup completed successfully.');
    }
}
