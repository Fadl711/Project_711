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
            $table->string('sub_name'); // اسم الحساب الفرعي
            $table->decimal('debtor_amount', 15, 2)->nullable(); // رصيد المدين
            $table->decimal('creditor_amount', 15, 2)->nullable(); // رصيد الدائن
            $table->string('name_The_known')->nullable(); // الاسم المعروف
            $table->string('Known_phone')->nullable(); // الهاتف المعروف
            $table->integer('User_id')->unsigned(); // ID المستخدم
            $table->integer('Main_id')->unsigned(); // ID الحساب الرئيسي
            $table->string('Phone')->nullable(); // رقم الهاتف (أفضل أن يكون من نوع string بدلاً من integer)
            $table->integer('AccountClass')->unsigned(); // فئة الحساب
            $table->integer('typeAccount')->unsigned(); // نوع الحساب
        
            $table->timestamps();
        
            // العلاقات الأجنبية
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
