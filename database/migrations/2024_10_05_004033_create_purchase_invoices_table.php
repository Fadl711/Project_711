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
            $table->integer('Receipt_number')->unsigned();
            $table->decimal('Total_invoice', 8, 2)->nullable();
            $table->decimal('Total_cost', 8, 2)->nullable();
             $table->integer('User_id')->unsigned();
            $table->string('Payment_type')->nullable();
            $table->integer('Supplier_id')->unsigned();
           
            $table->timestamps();
            $table->foreign('Supplier_id')->references('sub_account_id')->on('sub_accounts')
            ->onDelete('cascade');
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
