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
  $table->integer('production_order_id')->nullable()->comment('أمر الإنتاج المرتبط');
    $table->integer('item_id')->nullable()->comment('المادة/المنتج');
    $table->decimal('quantity', 12, 3)->comment('الكمية');
    $table->enum('transaction_type', ['receipt', 'issue', 'return', 'product_in', 'waste_out'])->comment('نوع الحركة');
    $table->integer('warehouse_id')->comment('المخزن');
    $table->integer('location_id')->nullable()->comment('الموقع الدقيق');
    $table->decimal('unit_cost', 15, 5)->comment('التكلفة للوحدة وقت الحركة');
    $table->decimal('total_cost', 15, 2)->comment('التكلفة الإجمالية');
    $table->integer('created_by')->comment('منفذ الحركة');
    $table->timestamp('transaction_date')->useCurrent()->comment('تاريخ الحركة');
    $table->text('notes')->nullable()->comment('ملاحظات');
    $table->timestamps();
    $table->index(['item_id', 'transaction_date'])->comment('فهرس لتسريع استعلامات الحركات');        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
};
