<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'id' => 1,
            'name' => 'Fadl almatari',
            'email' => 'fadl@example.com',
            'password' => 'qweasdzxc',
        ]);
        Currency::create([
            'currency_name' => 'يمني',
        ]);
    }
}
