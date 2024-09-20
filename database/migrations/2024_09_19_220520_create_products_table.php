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
        Schema::create('products', function (Blueprint $table) {
            $table->increments('product_id')->unsigned();
            $table->integer('barcod')->unsigned()->unique();
            $table->string('product_name');
            $table->integer('Categorie_id')->unsigned();
            $table->decimal('Product_price', 8, 2);
            $table->decimal('Regular_discount', 8, 2);
            $table->decimal('Special_discount', 8, 2);
            $table->integer('user_id')->unsigned();
            $table->integer('Currency_id')->unsigned();
            $table->decimal('Total_price', 8, 2);
            $table->timestamps();

            $table->foreign('Categorie_id')->references('categorie_id')->on('categories');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('Currency_id')->references('currencie_id')->on('currencies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
