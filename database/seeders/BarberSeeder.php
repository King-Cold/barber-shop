<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barber;

class BarberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Barber::create([
            'name' => 'Antonio López',
            'specialty' => 'Cortes Clásicos y Navaja',
            'email' => 'antonio@barbershop.com',
            'phone' => '555-123-4567'
        ]);

        Barber::create([
            'name' => 'Ricardo Silva',
            'specialty' => 'Urban Style & Fade Masters',
            'email' => 'ricardo@barbershop.com',
            'phone' => '555-987-6543'
        ]);

        Barber::create([
            'name' => 'Gabriel Martínez',
            'specialty' => 'Barbería Tradicional y Barba',
            'email' => 'gabriel@barbershop.com',
            'phone' => '555-456-7890'
        ]);
    }
}
