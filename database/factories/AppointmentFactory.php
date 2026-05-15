<?php

namespace Database\Factories;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id' => \App\Models\Client::factory(),
            'barber_id' => \App\Models\Barber::factory(),
            'service_id' => \App\Models\Service::factory(),
            'date' => fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'time' => fake()->time('H:i'),
            'status' => fake()->randomElement(['pending', 'confirmed', 'completed', 'canceled']),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
