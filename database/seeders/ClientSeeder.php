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
        $clients = [
            [
                'email' => 'juan@example.com',
                'name' => 'Juan Pérez',
                'phone' => '555-010-1010',
                'address' => 'Calle Principal 123'
            ],
            [
                'email' => 'carlos@example.com',
                'name' => 'Carlos Rodríguez',
                'phone' => '555-020-2020',
                'address' => 'Avenida Central 456'
            ],
            [
                'email' => 'roberto@example.com',
                'name' => 'Roberto Gómez',
                'phone' => '555-030-3030',
                'address' => 'Plaza Mayor 789'
            ],
            [
                'email' => 'luis@example.com',
                'name' => 'Luis Fernández',
                'phone' => '555-040-4040',
                'address' => 'Paseo del Sol 321'
            ]
        ];

        foreach ($clients as $data) {
            \Illuminate\Support\Facades\DB::transaction(function () use ($data) {
                $user = \App\Models\User::updateOrCreate(
                    ['email' => $data['email']],
                    [
                        'name' => $data['name'],
                        'password' => \Illuminate\Support\Facades\Hash::make('password'),
                        'role_id' => 4, // Cliente
                    ]
                );

                Client::updateOrCreate(
                    ['email' => $data['email']],
                    [
                        'user_id' => $user->id,
                        'name' => $data['name'],
                        'phone' => $data['phone'],
                        'address' => $data['address']
                    ]
                );
            });
        }
    }
}
