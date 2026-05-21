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
        $barbers = [
            [
                'email' => 'antonio@barbershop.com',
                'name' => 'Antonio López',
                'specialty' => 'Cortes Clásicos y Navaja',
                'phone' => '555-123-4567'
            ],
            [
                'email' => 'ricardo@barbershop.com',
                'name' => 'Ricardo Silva',
                'specialty' => 'Urban Style & Fade Masters',
                'phone' => '555-987-6543'
            ],
            [
                'email' => 'gabriel@barbershop.com',
                'name' => 'Gabriel Martínez',
                'specialty' => 'Barbería Tradicional y Barba',
                'phone' => '555-456-7890'
            ]
        ];

        foreach ($barbers as $data) {
            \Illuminate\Support\Facades\DB::transaction(function () use ($data) {
                $user = \App\Models\User::updateOrCreate(
                    ['email' => $data['email']],
                    [
                        'name' => $data['name'],
                        'password' => \Illuminate\Support\Facades\Hash::make('password'),
                        'role_id' => 3, // Barbero
                    ]
                );

                Barber::updateOrCreate(
                    ['email' => $data['email']],
                    [
                        'user_id' => $user->id,
                        'name' => $data['name'],
                        'specialty' => $data['specialty'],
                        'phone' => $data['phone']
                    ]
                );
            });
        }
    }
}
