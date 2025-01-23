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
        Schema::table('sales', function (Blueprint $table) 
        {
        $table->decimal('Profit', 15, 2)->nullable()->after('Purchase_price')->comment('الربح المعتمد للمنتج');
        });
          // ... (الكود السابق لإضافة العمود إذا لزم الأمر)

        // استرجاع جميع السجلات من جدول sales
        $sales = Sale::all();

        // التحقق من وجود السجلات
        if ($sales->isNotEmpty()) {
            foreach ($sales as $sale) {
                // حساب الربح
                $sellingPrice = $sale->Selling_price ?? 0;
                $purchasePrice =$sale->Purchase_price ?? 0;

                // حساب الربح
                $profit = $sellingPrice - $purchasePrice;

                // تعيين الربح
                $sale->Profit = $profit;

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
            $table->dropColumn('Profit'); // حذف العمود إذا تم التراجع
        });
    }
};
