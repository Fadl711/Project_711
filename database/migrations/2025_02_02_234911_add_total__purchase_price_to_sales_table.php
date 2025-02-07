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
            $table->decimal('total_purchasePrice', 15, 2)->nullable()->comment(' اجمالي التكتفه  للمنتج');
            
        });
        // التحقق من وجود السجلات
        $sales =Sale::all();
          if ($sales->isNotEmpty()) {
            foreach ($sales as $sale) {
                $Purchase_price = $sale->Purchase_price ?? 0;
                $Quantityprice =$sale->Quantityprice ?? 0;
                $totalPurchase_price = $Purchase_price * $Quantityprice ;
                $sale->total_purchasePrice = $totalPurchase_price ??0;
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
            $table->dropColumn('total_purchasePrice'); // حذف العمود إذا تم التراجع

            //
        });
    }
};
