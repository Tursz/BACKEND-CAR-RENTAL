<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone_number' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'birth_date' => $this->faker->date(),
            'cpf' => $this->faker->numberBetween(10000000000, 99999999999),
            'cnh' => $this->faker->numberBetween(10000000000, 99999999999),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
