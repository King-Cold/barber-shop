<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Service::updateOrCreate(
            ['name' => 'Corte Clásico'],
            [
                'price' => 150.00,
                'duration' => 30,
            ]
        );

        Service::updateOrCreate(
            ['name' => 'Corte Moderno / Fade'],
            [
                'price' => 200.00,
                'duration' => 45,
            ]
        );

        Service::updateOrCreate(
            ['name' => 'Perfilado de Barba'],
            [
                'price' => 100.00,
                'duration' => 20,
            ]
        );

        Service::updateOrCreate(
            ['name' => 'Afeitado Tradicional'],
            [
                'price' => 180.00,
                'duration' => 40,
            ]
        );

        Service::updateOrCreate(
            ['name' => 'Combo Corte + Barba'],
            [
                'price' => 250.00,
                'duration' => 60,
            ]
        );
    }
}
