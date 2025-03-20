<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('daily_entries', function (Blueprint $table) {
            $table->dropForeign(['account_Credit_id']); // حذف المفتاح الأجنبي القديم
/*             $table->foreign('account_Credit_id')
                  ->references('sub_account_id')
                  ->on('sub_accounts')
                  ->onDelete('restrict'); // منع الحذف */
        });
    }

    public function down()
    {
        Schema::table('daily_entries', function (Blueprint $table) {
            $table->dropForeign(['account_Credit_id']);
            $table->foreign('account_Credit_id')
                  ->references('sub_account_id')
                  ->on('sub_accounts')
                  ->onDelete('set null'); // استعادة القديم عند التراجع
        });
    }
};
