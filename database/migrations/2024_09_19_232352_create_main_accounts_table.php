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
            $table->integer('typeAccount')->unsigned();
            $table->integer('Type_migration');
            $table->integer('User_id')->unsigned();

            $table->timestamps();

            $table->foreign('User_id')->references('id')->on('users');
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
