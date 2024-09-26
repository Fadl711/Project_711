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
        Schema::create('sales_invoices', function (Blueprint $table) {
            $table->increments('sales_invoice_id')->unsigned();
            $table->integer('Customer_id')->unsigned();
            $table->decimal('Total_invoice', 8, 2); // Adjust precision as needed
            $table->decimal('Paid', 8, 2); // Adjust precision as needed
            $table->decimal('Remaining', 8, 2); // Adjust precision as needed
            $table->integer('User_id')->unsigned();
            $table->integer('Payment_type_id')->unsigned();
            $table->integer('Currency_id')->unsigned();

            $table->foreign('Customer_id')->references('id')->on('users');
            $table->foreign('User_id')->references('id')->on('users');
            $table->foreign('Payment_type_id')->references('payment_types_id')->on('payment_types');
            $table->foreign('Currency_id')->references('currency_id')->on('currencies');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_invoices');
    }
};
