<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PDO;
use PDOException;

class SyncController extends Controller
{
    public function sync()
    {
        // $host = getenv('DB_HOST_PGSQL') ?: 'ep-wild-hall-a10da78e.aws-ap-southeast-1.pg.laravel.cloud';
        // $dbname = getenv('DB_DATABASE_PGSQL') ?: 'main';
        // $port = getenv('DB_PORT_PGSQL') ?: 5432;
        // $username = getenv('DB_USERNAME_PGSQL') ?: 'laravel';
        // $password = getenv('DB_PASSWORD_PGSQL') ?: 'npg_Q0tpsTS2bFgM';
        
        // try {
        //     $dsn = "pgsql:host={$host};dbname={$dbname};port={$port}";
        
        //     $pdo = new PDO($dsn, $username, $password);
        //     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //     $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        
        //     // Optional: Use logging instead of echo in production
        //     echo "¡Conexión exitosa, gracias a mi guía divina!";
            
        //     // Your queries can go here
        
        // } catch (PDOException $e) {
        //     error_log("Error de conexión a la base de datos: " . $e->getMessage());
        //     echo "¡Conexión fallida! Un error ha sido registrado.";
        // } finally {
        //     $pdo = null; // Explicitly closing the connection
        // }
        try {
            // بدء عملية المزامنة
            DB::connection('pgsql')->beginTransaction();

            $totalRows = DB::connection('mysql')->table('sales')->count();
            $processedRows = 0;

            DB::connection('mysql')->table('sales')->orderBy('sale_id')->chunkById(100, function ($rows) use (&$processedRows) {
                foreach ($rows as $row) {
                    $exists = DB::connection('pgsql')->table('sales')
                        ->where('sale_id', $row->sale_id)
                        ->exists();

                    if (!$exists) {
                        DB::connection('pgsql')->table('sales')->insert((array)$row);
                    }

                    $processedRows++;
                }
            });

            DB::connection('pgsql')->commit();
            return redirect()->back()->with('success', "تمت مزامنة $processedRows من أصل $totalRows سجلات بنجاح!");

        } catch (\Exception $e) {
            DB::connection('pgsql')->rollBack();
            Log::error('فشل مزامنة البيانات: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء المزامنة: ' . $e->getMessage());
        }
    
}
}
