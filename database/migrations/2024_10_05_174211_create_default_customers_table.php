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
        Schema::create('default_customers', function (Blueprint $table) {
            $table->id();
            $table->integer('subaccount_id')->unsigned()->nullable();
            $table->integer('warehouse_id')->unsigned()->nullable();
            $table->integer('financial_account_id')->unsigned()->nullable();
/*             $table->foreign('subaccount_id')->references('sub_account_id')->on('sub_accounts')->onDelete('cascade');
            $table->foreign('warehouse_id')->references('sub_account_id')->on('sub_accounts')->onDelete('cascade');
            $table->foreign('financial_account_id')->references('sub_account_id')->on('sub_accounts')->onDelete('cascade'); */
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('default_customers');
    }
};
