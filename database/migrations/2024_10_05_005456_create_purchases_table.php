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
            $table->string('Barcode');
            $table->integer('Quantity');
            $table->decimal('Purchase_price', 8, 2);
            $table->decimal('Selling_price', 8, 2);
            $table->decimal('Total', 8, 2);
            $table->decimal('Cost', 8, 2);
            $table->decimal('Discount_earned', 8, 2);
            $table->decimal('Profit', 8, 2);
            $table->decimal('Exchange rate', 8, 2);
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
