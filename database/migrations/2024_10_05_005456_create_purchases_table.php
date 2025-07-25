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
        Schema::create('purchases', function (Blueprint $table) {
            $table->increments('purchase_id')->unsigned();
            $table->string('Product_name');
            $table->integer('product_id');
            $table->bigInteger('Barcode')->unsigned()->nullable(); // إضافة العمود Barcode
            $table->decimal('Purchase_price', 15, 3)->nullable();
            $table->decimal('Selling_price', 15, 3)->nullable();
            $table->decimal('Total', 15, 5);
            $table->decimal('Cost' ,15, 3)->nullable();
            $table->decimal('Discount_earned', 15, 3)->nullable();
            $table->decimal('Profit', 15, 3)->nullable();
            $table->decimal('Exchange_rate', 15, 3)->nullable();
            $table->string('note')->nullable();
            $table->integer('Currency_id')->unsigned()->nullable();
            $table->integer('User_id')->unsigned();
            $table->decimal('quantity',15,3)->default(0)->comment('كمية المنتج في المعاملة');
            $table->decimal('Quantityprice', 15, 5)->comment('كمية المنتج  المباعه حسب الوحدة');
            $table->integer('Purchase_invoice_id')->nullable()->unsigned();
            $table->integer('accounting_period_id')->unsigned();
            $table->unsignedTinyInteger('transaction_type')->comment('نوع المعاملة: 1 للشراء، 2 للبيع، 3 للترحيل المخزني');
            $table->integer('account_id')->unsigned()->nullable()->comment('الحساب المرتبط بدفع');
            $table->integer('warehouse_from_id')->unsigned()->nullable()->comment('المخزن المصدر (للتحويل المخزني)');
            $table->integer('warehouse_to_id')->unsigned()->nullable()->comment('المخزن الاستيراد (للتحويل المخزني)');
            $table->integer('Supplier_id')->unsigned()->nullable();
            $table->string('categorie_id')->nullable();
            $table->timestamps();
/*             $table->foreign('Purchase_invoice_id')->references('Purchase_invoice_id')->on('purchase_invoices')
            ->onDelete('set null');
            $table->foreign('account_id')->references('sub_account_id')->on('sub_accounts')
            ->onDelete('set null');
            $table->foreign('warehouse_to_id')->references('sub_account_id')->on('sub_accounts')->onDelete('set null');
            $table->foreign('warehouse_from_id')->references('sub_account_id')->on('sub_accounts')->onDelete('set null');
            $table->foreign('Supplier_id')->references('sub_account_id')->on('sub_accounts')->onDelete('set null');


            $table->foreign('User_id')->references('id')->on('users'); */


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
