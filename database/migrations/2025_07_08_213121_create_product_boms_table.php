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
        Schema::create('product_boms', function (Blueprint $table) {
    $table->id();
    $table->integer('product_id')->comment('المنتج النهائي');
    $table->integer('material_id')->comment('المادة الخام');
    $table->decimal('quantity', 12, 3)->comment('الكمية المطلوبة لكل وحدة منتج');
    $table->integer('unit_id')->comment('وحدة القياس');
    $table->decimal('waste_factor',5, 2)->default(0)->comment('نسبة الهدر %');
    $table->integer('default_warehouse_id')->comment('المخزن الافتراضي للصرف');
    $table->decimal('standard_cost', 15, 5)->comment('التكلفة المعيارية للوحدة');
    $table->boolean('is_active')->default(true)->comment('هل السجل مفعل؟');
    $table->timestamps();
    $table->unique(['product_id', 'material_id'])->comment('ضمان عدم التكرار');        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_boms');
    }
};
