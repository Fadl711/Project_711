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
            $table->integer('Receipt_number')->unsigned()->nullable();
            $table->decimal('Total_invoice', 15, 2)->nullable();
            $table->decimal('Total_cost', 15, 2)->nullable();
            $table->decimal('Paid',15, 2)->nullable();
            $table->integer('User_id')->unsigned();
            $table->string('Invoice_type')->nullable();
            $table->unsignedTinyInteger('transaction_type')->comment('يحدد نوع المعاملة');
            $table->integer('Supplier_id')->unsigned()->nullable();
            $table->integer('accounting_period_id')->unsigned();
          $table->timestamps();
            $table->foreign('Supplier_id')->references('sub_account_id')->on('sub_accounts')->onDelete('set null');
            $table->foreign('User_id')->references('id')->on('users');
            
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
