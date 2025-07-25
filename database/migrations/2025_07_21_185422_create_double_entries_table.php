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
        Schema::create('double_entries', function (Blueprint $table) {
            $table->id();
            $table->integer('account_id');
            $table->text('Statement')->nullable();
            $table->unsignedInteger('User_id');
            $table->integer('currency_id')->nullable();
            $table->integer('accounting_period_id')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('double_entries');
    }
};
