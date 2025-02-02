<?php

namespace Database\Factories;

use App\Models\Talk;
use App\Models\Speaker;
use App\Models\Conference;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class SpeakerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Speaker::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $qualficationsCount = $this->faker->numberBetween(0,3);
        $qualifcations = $this->faker->randomElements(array_keys(Speaker::QUALFICATIONS), $qualficationsCount);

        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'bio' => fake()->text(),
            'qualfications' => $qualifcations,
            'twitter_handle' => fake()->word(),
            'conference_id' => Conference::factory(),
        ];
    }

    public function withTalks(int $count = 1): self
    {
        return $this->has(Talk::factory()->count($count), 'talks');
    }
}
