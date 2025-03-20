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
        Schema::create('inventory_invoices', function (Blueprint $table) {
            $table->id();
            $table->integer('StoreId')->unsigned()->nullable(); // معرف المخزن
            $table->integer('InventoryOfficerId')->unsigned()->nullable(); // مسؤول الجرد (يمكن أن يكون فارغًا)
            $table->integer('User_id')->unsigned(); // معرف المستخدم الذي أنشأ الفاتورة
            $table->string('InventoryTitle'); // عنوان الجرد
            $table->integer('accounting_period_id')->unsigned(); // معرف فترة المحاسبة
            $table->timestamps(); // أعمدة created_at و updated_at
            // العلاقات
/*             $table->foreign('StoreId')->references('sub_account_id')->on('sub_accounts')->onDelete('set null');
            $table->foreign('User_id')->references('id')->on('users');
            $table->foreign('InventoryOfficerId')->references('id')->on('users'); */
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_invoices');
    }
};
