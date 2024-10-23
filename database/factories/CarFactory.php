<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = [
            'Ford Mustang',
            'Chevrolet Camaro',
            'Toyota Corolla',
            'Honda Civic',
            'Nissan Altima',
            'BMW Série 3',
            'Audi A4',
            'Mercedes-Benz C-Class',
            'Volkswagen Golf',
            'Hyundai Elantra',
            'Subaru Impreza',
            'Kia Forte',
            'Dodge Charger',
            'Mazda3',
            'Tesla Model 3',
            'Lexus IS',
            'Jeep Wrangler',
            'Chevrolet Silverado',
            'Ford F-150',
            'Ram 1500'
        ];
        return [
            'name' => $this->faker->unique()->randomElement($name),
            'color_id' => $this->faker->numberBetween(1, 15),
            'type_id' => $this->faker->numberBetween(1, 5),
            'brand_id' => $this->faker->numberBetween(1, 15),
            'chassi' => strtoupper(Str::random(17)),
            'plate' => substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 3).$this->faker->numberBetween(100,999),
            'price' => $this->faker->numberBetween(300,2000),
            'year' => $this->faker->year($max = 'now')
        ];
    }
}
