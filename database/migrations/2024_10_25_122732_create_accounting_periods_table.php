<?php

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
        Schema::create('accounting_periods', function (Blueprint $table) {
            $table->increments('accounting_period_id')->unsigned();
            // معرف الفترة المحاسبية
            $table->integer('Year'); // السنة
            $table->integer('Month'); // الشهر
            $table->date('Today'); // التاريخ الحالي
            $table->date('start_date')->nullable(); // تاريخ البدء
            $table->date('end_date')->nullable(); // تاريخ الانتهاء
            $table->boolean('is_closed')->default(false); // حالة الإغلاق
            $table->timestamps(); // تاريخ الإنشاء والتحديث
      
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounting_periods');
    }
};
