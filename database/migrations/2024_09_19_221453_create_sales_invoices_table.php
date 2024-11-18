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
        Schema::disableForeignKeyConstraints();

        Schema::create('sales_invoices', function (Blueprint $table) {
    $table->increments('sales_invoice_id')->unsigned();
    $table->integer('Customer_id')->unsigned()->nullable();
    // $table->enum('payment_status', ['paid', 'unpaid', 'partial'])->default('unpaid'); 
    $table->decimal('total_price', 15, 2); 
    $table->decimal('total_price_sale', 15, 2); 
    $table->integer('User_id')->unsigned(); 
    $table->decimal('paid_amount', 15, 2)->default(0); 
    $table->decimal('remaining_amount', 15, 2)->default(0); 
    $table->enum('payment_type', ['cash', 'on_credit', 'transfer']);
    $table->integer('currency_id')->unsigned(); 
    $table->decimal('exchange_rate', 15, 2)->nullable(); 
    $table->string('transaction_type'); 
    $table->enum('shipping_bearer', ['customer', 'merchant'])->default('customer');

    $table->integer('accounting_period_id')->unsigned(); 
    $table->timestamps();

    $table->foreign('Customer_id')->references('sub_account_id')->on('sub_accounts')->onDelete('set null');
    $table->foreign('User_id')->references('id')->on('users');
});
Schema::enableForeignKeyConstraints();

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_invoices');
    }
};
