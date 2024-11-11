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

            $table->double('Purchase_price', 15, 2)->nullable();
            $table->double('Selling_price', 15, 2)->nullable();
            $table->double('Total', 15, 2);
            $table->double('Cost' ,15, 2)->nullable();
            $table->double('Discount_earned', 15, 2)->nullable();
            $table->double('Profit', 15, 2)->nullable();
            $table->double('Exchange_rate', 15, 2)->nullable();
            $table->string('note')->nullable();
            $table->integer('Currency_id')->unsigned()->nullable();
            $table->integer('User_id')->unsigned();
            $table->integer('quantity')->default(0)->comment('كمية المنتج في المعاملة');
            $table->integer('Purchase_invoice_id')->unsigned();
            $table->integer('accounting_period_id')->unsigned();
            $table->unsignedTinyInteger('transaction_type')->comment('يحدد نوع المعاملة');
            $table->integer('account_id')->unsigned()->nullable()->comment('الحساب المرتبط بالعملية');
            $table->integer('warehouse_from_id')->unsigned()->nullable()->comment('المخزن المصدر (للتحويل المخزني)');
            $table->integer('warehouse_to_id')->unsigned()->nullable()->comment('المخزن الوجهة (للتحويل المخزني)');
            $table->integer('Supplier_id')->unsigned()->nullable();
            $table->timestamps();
            $table->foreign('Purchase_invoice_id')->references('Purchase_invoice_id')->on('purchase_invoices')
            ->onDelete('cascade');
            $table->foreign('account_id')->references('sub_account_id')->on('sub_accounts')
            ->onDelete('cascade');
            $table->foreign('warehouse_to_id')->references('sub_account_id')->on('sub_accounts')->onDelete('set null');
            $table->foreign('warehouse_from_id')->references('sub_account_id')->on('sub_accounts')->onDelete('set null');
            $table->foreign('Supplier_id')->references('sub_account_id')->on('sub_accounts')->onDelete('set null');
        

            $table->foreign('User_id')->references('id')->on('users');
      
       
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
