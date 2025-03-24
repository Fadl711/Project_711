<?php

use App\Models\DailyEntrie;
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
        Schema::table('daily_entries', function (Blueprint $table) {
        $table->decimal('exchange_rate', 10, 2)->nullable();
        });
           $pb = DailyEntrie::all();
           if ($pb) {
        foreach ($pb as $p)
         {


                $p->currency_name = "ريال.يمني";
                $p->save();
        }
    }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_entries', function (Blueprint $table) {
            $table->dropColumn('exchange_rate');
        });
    }
};
