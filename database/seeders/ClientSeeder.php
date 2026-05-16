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
        Client::create([
            'name' => 'Juan Pérez',
            'phone' => '555-010-1010',
            'email' => 'juan@example.com',
            'address' => 'Calle Principal 123'
        ]);

        Client::create([
            'name' => 'Carlos Rodríguez',
            'phone' => '555-020-2020',
            'email' => 'carlos@example.com',
            'address' => 'Avenida Central 456'
        ]);

        Client::create([
            'name' => 'Roberto Gómez',
            'phone' => '555-030-3030',
            'email' => 'roberto@example.com',
            'address' => 'Plaza Mayor 789'
        ]);

        Client::create([
            'name' => 'Luis Fernández',
            'phone' => '555-040-4040',
            'email' => 'luis@example.com',
            'address' => 'Paseo del Sol 321'
        ]);
    }
}
