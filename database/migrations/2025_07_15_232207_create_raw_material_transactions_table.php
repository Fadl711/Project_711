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
        Schema::create('raw_material_transactions', function (Blueprint $table) {
            $table->id()->comment('المعرف الفريد للحركة');
            $table->integer('production_order_id')
            ->comment('أمر الإنتاج المرتبط');
            $table->integer('accounting_period_id')->unsigned();
    $table->integer('material_id')
    ->comment('المادة الخام');
    // الكميات
    $table->decimal('planned_quantity', 12, 3)->comment('الكمية المخططة');
    $table->decimal('actual_quantity', 12, 3)->comment('الكمية الفعلية المستخدمة');
    $table->decimal('returned_quantity', 12, 3)->default(0)->comment('الكمية المرتجعة');
    
    // التكلفة
    $table->decimal('unit_cost', 15, 5)->comment('التكلفة للوحدة');
    $table->decimal('total_cost', 15, 3)->comment('التكلفة الإجمالية');
    
    // المخزن
    $table->integer('warehouse_id')->nullable()->comment('مخزن الصرف');
    $table->integer('location_id')->nullable()
          ->comment('موقع الصرف');
    
    // التتبع   

    $table->integer('issued_by')->comment('مسؤول الصرف');
    $table->timestamp('issue_date')->useCurrent()->comment('تاريخ الصرف');
    $table->integer('received_by')->nullable()
          ->comment('مسؤول الاستلام');
    $table->timestamp('return_date')->nullable()->comment('تاريخ الإرجاع');
    $table->text('notes')->nullable()->comment('ملاحظات');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raw_material_transactions');
    }
};
