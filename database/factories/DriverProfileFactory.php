<?php

namespace Database\Factories;

use App\Models\DriverProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DriverProfile>
 */
class DriverProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->name(),
            'last_name' => fake()->name(),
            'middle_name' => fake()->name(),
            'phone'           => $this->faker->phoneNumber,
            'address'         => $this->faker->address,
            'emergency_name'  => $this->faker->name,
            'emergency_phone' => $this->faker->phoneNumber,
            'birthdate'       => $this->faker->dateTimeBetween('-50 years', '-20 years')->format('Y-m-d'),
            'license_number'  => $this->faker->bothify('???-######'), 
            'plate_number'    => strtoupper($this->faker->bothify('???-####')),
            'jeep_icon'       => 'icons/' . fake()->randomElement([
                                'jeep-black.png',
                                'jeep-blue.png',
                                'jeep-brown.png',
                                'jeep-darkblue.png',
                                'jeep-darkgreen.png',
                                'jeep-green.png',
                                'jeep-orange.png',
                                'jeep-pink.png',
                                'jeep-pinkish.png',
                                'jeep-purple.png',
                                'jeep-red.png',
                                'jeep-white.png',
                                'jeep-yellow.png',
                            ])
        ];
    }
}
