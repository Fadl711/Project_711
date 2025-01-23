<?php

use App\Models\Sale;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            //      
              $table->decimal('total_Profit', 15, 2)->nullable()->after('Profit')->comment(' اجمالي الربح المعتمد للمنتج ') ;
                      });
                       // ... (الكود السابق لإضافة العمود إذا لزم الأمر)

        // استرجاع جميع السجلات من جدول sales
        $sales = Sale::all();

        // التحقق من وجود السجلات
        if ($sales->isNotEmpty()) {
            foreach ($sales as $sale) {
                // حساب الربح
                $Profit = $sale->Profit ?? 0;
                $Quantityprice =$sale->Quantityprice ?? 0;
                // حساب الربح
                $totalProfit = $Profit * $Quantityprice;

                // تعيين الربح
                $sale->total_Profit = $totalProfit;

                // حفظ التغيير
                $sale->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('total_Profit'); // حذف العمود إذا تم التراجع
        });
    }
};
