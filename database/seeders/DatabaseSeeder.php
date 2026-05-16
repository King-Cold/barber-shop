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
        // Seed the 4 roles
        \App\Models\Role::updateOrCreate(['id' => 1], ['name' => 'Administrador']);
        \App\Models\Role::updateOrCreate(['id' => 2], ['name' => 'Super Administrador']);
        \App\Models\Role::updateOrCreate(['id' => 3], ['name' => 'Barbero']);
        \App\Models\Role::updateOrCreate(['id' => 4], ['name' => 'Cliente']);
        // 1 Admin User (Ensure Super Admin role_id 2)
        User::updateOrCreate(
            ['email' => 'admin@barber.com'],
            [
                'name' => 'Administrator',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role_id' => 2, // Super Admin
            ]
        );

        $this->call([
            ServiceSeeder::class,
            BarberSeeder::class,
            ClientSeeder::class,
        ]);
    }
}
