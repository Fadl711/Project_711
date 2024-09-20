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
        Schema::create('daily_entries', function (Blueprint $table) {
            $table->increments('entrie_id')->unsigned();
            $table->decimal('Amount_debit', 8, 2);
            $table->integer('account_debit_id')->unsigned();
            $table->decimal('Amount_Credit', 8, 2);
            $table->integer('account_Credit_id')->unsigned();
            $table->string('Statement');
            $table->integer('Daily_page_id')->unsigned();
            $table->integer('Currency_id')->unsigned();
            $table->integer('User_id')->unsigned();
            $table->timestamps();

            $table->foreign('account_debit_id')->references('sub_account_id')->on('sub_accounts');
            $table->foreign('account_Credit_id')->references('sub_account_id')->on('sub_accounts');
            $table->foreign('Currency_id')->references('currencie_id')->on('currencies');
            $table->foreign('User_id')->references('id')->on('users');
            $table->foreign('Daily_page_id')->references('page_id')->on('general_journal');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_entries');
    }
};
