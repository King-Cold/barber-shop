<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Client::updateOrCreate(
            ['email' => 'juan@example.com'],
            [
                'name' => 'Juan Pérez',
                'phone' => '555-010-1010',
                'address' => 'Calle Principal 123'
            ]
        );

        Client::updateOrCreate(
            ['email' => 'carlos@example.com'],
            [
                'name' => 'Carlos Rodríguez',
                'phone' => '555-020-2020',
                'address' => 'Avenida Central 456'
            ]
        );

        Client::updateOrCreate(
            ['email' => 'roberto@example.com'],
            [
                'name' => 'Roberto Gómez',
                'phone' => '555-030-3030',
                'address' => 'Plaza Mayor 789'
            ]
        );

        Client::updateOrCreate(
            ['email' => 'luis@example.com'],
            [
                'name' => 'Luis Fernández',
                'phone' => '555-040-4040',
                'address' => 'Paseo del Sol 321'
            ]
        );
    }
}
