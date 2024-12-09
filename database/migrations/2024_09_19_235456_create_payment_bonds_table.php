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
        Schema::create('payment_bonds', function (Blueprint $table) {
            $table->increments('payment_bond_id');
            $table->integer('payment_type')->nullable()->comment('1: نقداً, 2: أجل, 3: تحويل بنكي, 4: شيك');
            $table->integer('Main_debit_account_id')->unsigned();
            $table->integer('Debit_sub_account_id')->unsigned();
            $table->decimal('Amount_debit', 8, 2);
            $table->integer('accounting_period_id')->unsigned();

            $table->integer('Main_Credit_account_id')->unsigned();
            $table->integer('Credit_sub_account_id')->unsigned();
            $table->string('Statement');
            $table->string('transaction_type');
            $table->integer('Currency_id')->unsigned();
            $table->integer('User_id')->unsigned();
            $table->timestamps();

            $table->foreign('Main_debit_account_id')->references('main_account_id')->on('main_accounts');
            $table->foreign('Debit_sub_account_id')->references('sub_account_id')->on('sub_accounts');
            $table->foreign('Main_Credit_account_id')->references('main_account_id')->on('main_accounts');
            $table->foreign('Credit_sub_account_id')->references('sub_account_id')->on('sub_accounts');
            $table->foreign('Currency_id')->references('currency_id')->on('currencies');
            $table->foreign('User_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_bonds');
    }
};
