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
        Schema::create('currency_settings', function (Blueprint $table) {
            $table->increments('currency_settings_id');
            $table->integer('Currency_id')->unsigned();
            $table->string('currency_name')->unique();
            $table->string('currency_symbol')->unique()->nullable();
            $table->decimal('exchange_rate',8,2)->unsigned()->nullable();
            $table->timestamps();
            $table->foreign('Currency_id')->references('currency_id')->on('currencies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currency_settings');
    }
};
