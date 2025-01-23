<?php

use App\Models\Sale;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->decimal('Purchase_price', 15, 2)->nullable()->after('Selling_price'); // إضافة العمود

            //
        });
        $sales = Sale::all();

        if ($sales->isNotEmpty()) {
            foreach ($sales as $sale) {
                $purchasePrice = DB::table('categories')
                    ->where('product_id', $sale->product_id)
                    ->where('Categorie_name', $sale->Category_name)
                    ->value('Purchase_price');

                // تعيين Purchase_price إلى 0 إذا لم يتم العثور على سعر الشراء
                $sale->Purchase_price = $purchasePrice ?? 0;
                $sale->save(); // حفظ التغيير
            }
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('Purchase_price'); // حذف العمود إذا تم التراجع
        });
    }
};
