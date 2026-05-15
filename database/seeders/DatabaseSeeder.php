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
        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@barber.com',
            'password' => 'password', // Already hashed by factory if using Hash::make, but factory uses 'password' by default
            'role' => 'admin',
        ]);

        // 2 Barbers
        $barbers = collect([
            \App\Models\Barber::factory()->create(['name' => 'John Doe', 'specialty' => 'Classic Cuts & Fades']),
            \App\Models\Barber::factory()->create(['name' => 'Jane Smith', 'specialty' => 'Beard Grooming & Styling']),
        ]);

        // 5 Services
        $services = collect([
            \App\Models\Service::factory()->create(['name' => 'Classic Cut', 'price' => 20, 'duration' => 30]),
            \App\Models\Service::factory()->create(['name' => 'Beard Trim', 'price' => 15, 'duration' => 20]),
            \App\Models\Service::factory()->create(['name' => 'Full Combo', 'price' => 30, 'duration' => 45]),
            \App\Models\Service::factory()->create(['name' => 'Hair Coloring', 'price' => 40, 'duration' => 60]),
            \App\Models\Service::factory()->create(['name' => 'Kids Cut', 'price' => 15, 'duration' => 25]),
        ]);

        // 10 Clients
        $clients = \App\Models\Client::factory(10)->create();

        // 5 Appointments
        for ($i = 0; $i < 5; $i++) {
            \App\Models\Appointment::factory()->create([
                'client_id' => $clients->random()->id,
                'barber_id' => $barbers->random()->id,
                'service_id' => $services->random()->id,
            ]);
        }
    }
}
