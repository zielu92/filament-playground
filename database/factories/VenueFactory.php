<?php

namespace Database\Factories;

use App\Enums\Region;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Venue;

class VenueFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Venue::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'citiy' => fake()->word(),
            'country' => fake()->country(),
            'postal_code' => fake()->postcode(),
            'region' => $this->faker->randomElement(Region::class)
        ];
    }
}
