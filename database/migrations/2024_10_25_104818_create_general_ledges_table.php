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
        Schema::create('general_ledges', function (Blueprint $table) {
            $table->increments('general_ledge_id')->unsigned();

            
           $table->integer('Account_id')->unsigned();
            $table->integer('Main_id')->unsigned();
            $table->integer('User_id')->unsigned();
            $table->integer('accounting_id');
            $table->timestamps();
            // $table->foreign('accounting_id')->references('accounting_period_id')->on('accounting_periods');
            $table->foreign('User_id')->references('id')->on('users');
            $table->foreign('Main_id')->references('main_account_id')->on('main_accounts');
            $table->foreign('Account_id')->references('sub_account_id')->on('sub_accounts');
    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
