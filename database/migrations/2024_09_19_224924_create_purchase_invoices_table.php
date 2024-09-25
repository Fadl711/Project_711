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
        Schema::create('purchase_invoices', function (Blueprint $table) {
            $table->increments('purchase_invoice_id')->unsigned();
            $table->integer('Supplier_id')->unsigned();
            $table->integer('Receipt_number')->unsigned();
            $table->decimal('Total_invoice', 8, 2);
            $table->decimal('Paid', 8, 2);
            $table->decimal('Remaining', 8, 2);
            $table->decimal('Total_cost', 8, 2);
            $table->integer('User_id')->unsigned();
            $table->integer('Payment_type_id')->unsigned();
            $table->integer('Currency_id')->unsigned();
            $table->timestamps();

            $table->foreign('Supplier_id')->references('id')->on('users');
            $table->foreign('User_id')->references('id')->on('users');
            $table->foreign('Payment_type_id')->references('payment_types_id')->on('payment_types');
            $table->foreign('Currency_id')->references('currency_id')->on('currencies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_invoices');
    }
};
