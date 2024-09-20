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
        Schema::create('main_accounts', function (Blueprint $table) {
            $table->increments('main_account_id')->unsigned();
            $table->string('Nature_account');
            $table->string('account_name');
            $table->integer('Type_account_id')->unsigned();
            $table->integer('User_id')->unsigned();
            $table->timestamps();

            $table->foreign('Type_account_id')->references('type_account_id')->on('type_accounts');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('main_accounts');
    }
};
