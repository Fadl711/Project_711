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
            $table->integer('Main_debit_account_id')->unsigned();
            $table->integer('Debit_sub_account_id')->unsigned();
            $table->decimal('Amount_debit', 8, 2);
            $table->integer('Main_Credit_account_id')->unsigned();
            $table->integer('Credit_sub_account_id')->unsigned();
            $table->string('Statement');
            $table->integer('Currency_id')->unsigned();
            $table->integer('User_id')->unsigned();
            $table->integer('Payment_type_id')->unsigned();
            $table->timestamps();

            $table->foreign('Main_debit_account_id')->references('main_account_id')->on('main_accounts');
            $table->foreign('Debit_sub_account_id')->references('sub_account_id')->on('sub_accounts');
            $table->foreign('Main_Credit_account_id')->references('main_account_id')->on('main_accounts');
            $table->foreign('Credit_sub_account_id')->references('sub_account_id')->on('sub_accounts');
            $table->foreign('Currency_id')->references('currencie_id')->on('currencies');
            $table->foreign('User_id')->references('id')->on('users');
            $table->foreign('Payment_type_id')->references('payment_types_id')->on('payment_types');
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
