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
        // 1 Admin User
        if (!User::where('email', 'admin@barber.com')->exists()) {
            User::factory()->create([
                'name' => 'Administrator',
                'email' => 'admin@barber.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'admin',
            ]);
        }

        $this->call([
            ServiceSeeder::class,
            BarberSeeder::class,
            ClientSeeder::class,
        ]);
    }
}
