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
        Schema::create('operations', function (Blueprint $table) {
            $table->id();
            $table->integer('accounting_period_id')->unsigned();
            $table->text('message')->nullable();
            $table->string('type'); // مثل: حذف، تعديل، تنبيه
        $table->string('model_type')->nullable(); // مثل: Invoice, Product
        $table->unsignedBigInteger('model_id')->nullable(); // ID المرتبط
        $table->unsignedBigInteger('user_id')->nullable(); // 
            $table->boolean('is_seen')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operations');
    }
};
