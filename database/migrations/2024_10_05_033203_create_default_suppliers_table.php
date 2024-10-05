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
        Schema::create('default_suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('subaccount_id')->unsigned();
            $table->double('debtor_amount')->nullable();
            $table->double('creditor_amount')->nullable();
            $table->string('name_The_known')->nullable();
            $table->string('Known_phone')->nullable();
            $table->integer('User_id')->unsigned();
            $table->integer('Phone')->nullable();
            $table->timestamps();
            $table->foreign('subaccount_id')->references('sub_account_id')->on('sub_accounts')->onDelete('cascade');
            $table->foreign('User_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('default_suppliers');
    }
};
