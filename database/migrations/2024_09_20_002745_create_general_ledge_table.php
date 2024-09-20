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
        Schema::create('general_ledge', function (Blueprint $table) {
            $table->increments('general_ledge_id')->unsigned();
            $table->integer('Account_id')->unsigned();
            $table->string('debtor');
            $table->string('creditor');
            $table->decimal('residual',8,2);
            $table->timestamps();

            $table->foreign('Account_id')->references('sub_account_id')->on('sub_accounts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_ledge');
    }
};
