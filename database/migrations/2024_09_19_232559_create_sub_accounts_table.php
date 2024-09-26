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
            $table->integer('Main_id')->unsigned();
            $table->decimal('debtor', 8, 2)->nullable();
            $table->decimal('creditor', 8, 2)->nullable();
            $table->string('name_The_known')->nullable();
            $table->string('Known_phone')->nullable();
            $table->integer('User_id')->unsigned();
            $table->integer('Phone')->nullable();
            $table->timestamps();

            $table->foreign('Main_id')->references('main_account_id')->on('main_accounts');
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
