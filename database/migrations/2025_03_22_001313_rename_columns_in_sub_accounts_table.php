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
        Schema::table('sub_accounts', function (Blueprint $table) {
            //

            $table->renameColumn('name_The_known', 'name_the_known');
            $table->renameColumn('Known_phone', 'known_phone');
            $table->renameColumn('User_id', 'user_id');
            $table->renameColumn('Main_id', 'main_id');
            $table->renameColumn('Phone', 'phone');
            $table->renameColumn('AccountClass', 'account_class');
            $table->renameColumn('typeAccount', 'type_account');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_accounts', function (Blueprint $table) {

            $table->renameColumn('name_the_known', 'name_The_known');
            $table->renameColumn('known_phone', 'known_phone');
            $table->renameColumn('user_id', 'User_id');
            $table->renameColumn('main_id', 'Main_id');
            $table->renameColumn('phone', 'Phone');
            $table->renameColumn('account_class', 'AccountClass');
            $table->renameColumn('type_account', 'typeAccount');
        });
    }
};
