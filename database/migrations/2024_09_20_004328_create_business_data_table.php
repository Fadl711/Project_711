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
        Schema::create('business_data', function (Blueprint $table) {
            $table->increments('business_data_id');
            $table->string('Company_Name');
            $table->string('Company_Logo');
            $table->string('Phone_Number');
            $table->string('Company_Address');
            $table->string('Services');
            $table->string('Company_NameE');
            $table->string('Company_AddressE');
            $table->string('ServicesE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_data');
    }
};
