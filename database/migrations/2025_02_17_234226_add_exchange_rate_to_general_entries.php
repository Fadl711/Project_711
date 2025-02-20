<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('general_entries', function (Blueprint $table) {
            // إضافة عمود سعر الصرف
            $table->decimal('exchange_rate', 10, 3)->default(1.000)->after('Currency_name')->comment('سعر الصرف للعملة عند القيد');

        });
    }

    public function down()
    {
        Schema::table('general_entries', function (Blueprint $table) {
            // حذف عمود سعر الصرف في حالة التراجع
            $table->dropColumn('exchange_rate');
        });
    }
};
