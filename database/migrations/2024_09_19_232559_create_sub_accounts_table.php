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
        Schema::create('sub_accounts', function (Blueprint $table) {
            $table->increments('sub_account_id')->unsigned();
            $table->string('sub_name');
            $table->double('debtor_amount')->nullable();
            $table->double('creditor_amount')->nullable();
            $table->string('name_The_known')->nullable();
            $table->string('Known_phone')->nullable();
            $table->integer('User_id')->unsigned();
            $table->integer('Main_id')->unsigned();
            $table->integer('Phone')->nullable();
            $table->integer('AccountClass')->unsigned();
            $table->integer('typeAccount')->unsigned();


            $table->timestamps();

            $table->foreign('Main_id')->references('main_account_id')->on('main_accounts')->onDelete('cascade');
            $table->foreign('User_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_accounts');
    }
};
