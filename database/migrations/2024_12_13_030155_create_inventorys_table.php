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
        Schema::create('inventorys', function (Blueprint $table) {
            $table->id();
$table->integer('product_id')->unsigned(); // معرف المستخدم الذي أنشأ الفاتورة
$table->decimal('quantity',9,2)->default(0)->comment('كمية المنتج في المعاملة');
$table->decimal('Quantityprice', 9, 2)->default(0)->comment('كمية المنتج  المباعه حسب الوحدة');
$table->integer('StoreId')->unsigned()->nullable(); // معرف المخزن
$table->decimal('CostPrice',15,2)->default(0)->comment('سعر التكلفه');
$table->decimal('TotalCost',15,2)->default(0)->comment(' اجمالي التكلفه');
$table->unsignedBigInteger('InventoryInvoiceId'); //فاتورة الجرد
$table->integer('InventoryOfficerId')->unsigned()->nullable(); //مسؤول الجرد
$table->integer('categorie_id')->unsigned()->nullable(); // الجرد
$table->integer('accounting_period_id')->unsigned();
$table->integer('User_id')->unsigned();
$table->timestamps();
/* $table->foreign('InventoryInvoiceId')->references('id')->on('inventory_invoices')->onDelete('cascade');
$table->foreign('StoreId')->references('sub_account_id')->on('sub_accounts');
$table->foreign('product_id')->references('product_id')->on('products');
$table->foreign('categorie_id')->references('categorie_id')->on('categories');
$table->foreign('User_id')->references('id')->on('users');
$table->foreign('InventoryOfficerId')->references('id')->on('users')->onDelete('set null'); */

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventorys');
    }
};
