<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Device>
 */
class DeviceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'group_id' => $this->faker->numberBetween(1, 10),
            'active' => $this->faker->boolean,
            'ip_address' => $this->faker->ipv4,
            'secret' => $this->faker->randomAscii,
            'description' => $this->faker->text,
            'registered' => $this->faker->boolean,
            'updated_at' => $this->faker->dateTimeThisYear,
        ];
    }
}
