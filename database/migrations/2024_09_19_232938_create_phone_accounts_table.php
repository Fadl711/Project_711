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
        Schema::create('phone_accounts', function (Blueprint $table) {
            $table->increments('phone_account_id')->unsigned();
            $table->string('phone')->unique();
            $table->integer('SubAccount_id')->unsigned();
            $table->timestamps();

            $table->foreign('SubAccount_id')->references('sub_account_id')->on('sub_accounts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phone_accounts');
    }
};
