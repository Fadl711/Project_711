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
        Schema::create('sales', function (Blueprint $table) {
            $table->increments('sale_id');
            $table->string('Product_name');
            $table->integer('accounting_period_id')->unsigned();

            $table->bigInteger('Barcode')->unsigned()->nullable(); // إضافة العمود Barcode
            $table->integer('Quantity');
            $table->decimal('Selling_price', 15, 2); // Adjust precision as needed
            $table->decimal('Total', 15, 2); // Adjust precision as needed
            $table->decimal('Allowed_discount', 15, 2); // Adjust precision as needed
            $table->string('note');
            $table->integer('Store_id')->unsigned();
            $table->integer('User_id')->unsigned();
            $table->integer('Invoice_id')->unsigned();

            $table->timestamps();

            $table->foreign('Store_id')->references('warehouse_id')->on('warehouses');
            $table->foreign('User_id')->references('id')->on('users');
            $table->foreign('Invoice_id')->references('sales_invoice_id')->on('sales_invoices');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
