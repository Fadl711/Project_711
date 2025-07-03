<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Operation;

class OperationsSeeder extends Seeder
{
    public function run()
    {
        for ($i = 0; $i < 10; $i++) {
            Operation::create([
                'accounting_period_id' => 1,
                'message' => 'تم حذف الفاتورة رقم ' . $i,
                'type' => 'حذف',
                'model_type' => 'Invoice',
                'model_id' => $i,
                'user_id' => 1,
                'is_seen' => 0, // إضافة هذا الحقل إذا كان موجوداً في الجدول
            ]);
        }
    }
}
