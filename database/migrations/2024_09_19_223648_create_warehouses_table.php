warehouses
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
        Schema::create('warehouses', function (Blueprint $table) {
            $table->increments('warehouse_id')->unsigned();
            $table->string('Store_name');
            $table->string('Store_location');
            $table->integer('Product_id')->unsigned();
            $table->integer('Stock_level');
            $table->integer('user_id')->unsigned();
            $table->timestamps();

            // Foreign Key
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('Product_id')->references('product_id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};