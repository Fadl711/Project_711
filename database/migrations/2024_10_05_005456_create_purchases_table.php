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
            $table->string('Barcode');
            $table->integer('Quantity');
            $table->double('Purchase_price');
            $table->double('Selling_price');
            $table->double('Total');
            $table->double('Cost');
            $table->double('Discount_earned');
            $table->double('Profit');
            $table->double('Exchange_rate');
            $table->string('note');
            $table->integer('Currency_id')->unsigned();
            $table->integer('Store_id')->unsigned();
            $table->integer('User_id')->unsigned();
            $table->integer('Purchase_invoice_id')->unsigned();
            $table->foreign('Purchase_invoice_id')->references('Purchase_invoice_id')->on('purchase_invoices')
            ->onDelete('cascade');
            $table->integer('Supplier_id')->unsigned();
            $table->foreign('Supplier_id')->references('sub_account_id')->on('sub_accounts')
            ->onDelete('cascade');
            $table->timestamps();

            $table->foreign('Currency_id')->references('currency_id')->on('currencies');
            $table->foreign('Store_id')->references('warehouse_id')->on('warehouses');
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
