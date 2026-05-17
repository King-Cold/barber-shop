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
        Barber::updateOrCreate(
            ['email' => 'antonio@barbershop.com'],
            [
                'name' => 'Antonio López',
                'specialty' => 'Cortes Clásicos y Navaja',
                'phone' => '555-123-4567'
            ]
        );

        Barber::updateOrCreate(
            ['email' => 'ricardo@barbershop.com'],
            [
                'name' => 'Ricardo Silva',
                'specialty' => 'Urban Style & Fade Masters',
                'phone' => '555-987-6543'
            ]
        );

        Barber::updateOrCreate(
            ['email' => 'gabriel@barbershop.com'],
            [
                'name' => 'Gabriel Martínez',
                'specialty' => 'Barbería Tradicional y Barba',
                'phone' => '555-456-7890'
            ]
        );
    }
}
