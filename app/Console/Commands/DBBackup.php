<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DBBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';

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
        $fileName = Carbon::now()->format('Y_m_d_H_i_s').".sql";
        $command = "mysqldump --user=".env('DB_USERNAME')." --password=".env('DB_PASSWORD')." --host=".env('DB_HOST')." ".env("DB_DATABASE")." > "."D:/".$fileName;
        exec($command);


    }
}
