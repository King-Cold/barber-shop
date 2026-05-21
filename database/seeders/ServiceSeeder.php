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
        // Servicios básicos (duración <= 30 minutos)
        Service::updateOrCreate(
            ['name' => 'Corte Clásico'],
            [
                'price' => 150.00,
                'duration' => 30,
            ]
        );

        Service::updateOrCreate(
            ['name' => 'Corte Moderno'],
            [
                'price' => 200.00,
                'duration' => 30,
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
                'duration' => 30,
            ]
        );

        // Nuevos servicios (todos <= 30 minutos)
        Service::updateOrCreate(
            ['name' => 'Lavado de Cabello'],
            [
                'price' => 80.00,
                'duration' => 20,
            ]
        );

        Service::updateOrCreate(
            ['name' => 'Tinte Rapido'],
            [
                'price' => 120.00,
                'duration' => 30,
            ]
        );

        Service::updateOrCreate(
            ['name' => 'Peinado Express'],
            [
                'price' => 90.00,
                'duration' => 25,
            ]
        );

        Service::updateOrCreate(
            ['name' => 'Desvanecimiento con Line Up'],
            [
                'price' => 130.00,
                'duration' => 30,
            ]
        );

        Service::updateOrCreate(
            ['name' => 'Recorte de Barba'],
            [
                'price' => 70.00,
                'duration' => 15,
            ]
        );
    }
}
