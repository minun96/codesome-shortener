<?php

namespace Database\Factories;

use App\Models\Link;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Click>
 */
class ClickFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'link_id' => Link::inRandomOrder()->first()->id,
            'created_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
