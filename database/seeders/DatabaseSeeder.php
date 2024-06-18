<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->withDticket()->create([
            'btu_id' => '756edf498829da38dd9c5bb48b784b12',
            'is_admin' => true,
        ]);

        $this->call([
            DticketSeeder::class,
            BabyWelcomeRequestSeeder::class,
        ]);
    }
}
