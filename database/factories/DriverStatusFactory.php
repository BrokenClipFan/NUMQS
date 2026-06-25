<?php

namespace Database\Factories;

use App\Models\DriverStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DriverStatus>
 */
class DriverStatusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        'latitude'      => $this->faker->latitude(10.28, 10.35),
        'longitude'     => $this->faker->longitude(123.85, 123.95),
        'dispatched_to' => $this->faker->randomElement(['to_naga', 'to_uling']),
        'state'         => $this->faker->randomElement(['idle', 'in_route', 'queue']),
        'is_online'     => true,
        ];
    }
}
