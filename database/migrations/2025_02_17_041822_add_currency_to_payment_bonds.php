<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\PaymentBond;
use App\Models\Currency;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payment_bonds', function (Blueprint $table) {
            $table->string('Currency_name')->nullable()->after('Currency_id')->comment('اسم العملة');
            $table->decimal('exchange_rate', 10, 3)->default(1.000)->after('Currency_name')->comment('سعر الصرف للعملة عند الدفع');
        });

        // تحديث اسم العملة لجميع السندات الموجودة
        $pb = PaymentBond::all();
        if ($pb) {
        foreach ($pb as $p) {
            $cc = Currency::where('currency_id', $p->Currency_id)->first();
            if ($cc) 
            {
                $p->Currency_name = $cc->currency_name ;
                $p->save();
            }
              
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_bonds', function (Blueprint $table) {
            if (Schema::hasColumn('payment_bonds', 'Currency_name')) {
                $table->dropColumn('Currency_name');
            }
            if (Schema::hasColumn('payment_bonds', 'exchange_rate')) {
                $table->dropColumn('exchange_rate');
            }
        });
    }
};
