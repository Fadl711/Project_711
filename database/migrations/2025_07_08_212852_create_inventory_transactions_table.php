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
    Schema::create('inventory_transactions', function (Blueprint $table) {
    $table->id();
                $table->integer('accounting_period_id')->unsigned();

    $table->integer('production_order_id')->nullable()->comment('أمر الإنتاج المرتبط');
    $table->integer('item_id')->nullable()->comment('المادة/المنتج');
    $table->decimal('quantity', 12, 3)->comment('الكمية');
       $table->integer('transaction_type')->comment('نوع الحركة');
    //   transaction_type  [
    //     'receipt',       // استلام مواد خام
    //     'issue',         // صرف مواد للإنتاج
    //     'return',        // إرجاع فائض
    //     'product_in',    // إدخال منتج نهائي
    //     'waste_out' ,     // إخراج مخلفات
    //     'consumption'    // استهلاك
    // ]
    
    $table->integer('warehouse_id')->comment('الجهة اصرف   ' );
    $table->integer('to_warehouse_id')->comment(' الجهة المنصرف لها');
   $table->integer('currency_id')->nullable();
   $table->integer('payment_type')->nullable();
   $table->decimal('exchange_rate',8,3)->default(1);
    $table->integer('location_id')->nullable()->comment('الموقع الدقيق');
    $table->integer('unit_id')->comment('وحدة القياس'); 
    $table->decimal('unit_cost', 15, 5)->comment('التكلفة للوحدة وقت الحركة');
    $table->decimal('total_cost', 15, 3)->comment('التكلفة الإجمالية');
    $table->integer('created_by')->comment('منفذ الحركة');
    $table->timestamp('transaction_date')->useCurrent()->comment('تاريخ الحركة');
    $table->text('notes')->nullable()->comment('ملاحظات');
    $table->timestamps();
    $table->index(['item_id', 'transaction_date'])->comment('فهرس لتسريع استعلامات الحركات'); 

       });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
};
