<?php

namespace Database\Factories;

use App\Models\Link;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Http;

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
        // $ip_address = $this->faker->ipv4();
        // $geoloc = Http::get("http://ip-api.com/json/{$ip_address}")->json();

        return [
            'link_id' => Link::inRandomOrder()->first()->id,
            /* 'ip_address' => $ip_address,
            'country' => $geoloc['country'] ?? null,
            'city' => $geoloc['city'] ?? null, */
            'ip_address' => $this->faker->ipv4(),
            'country' => $this->faker->country(),
            'city' => $this->faker->city(),
            'created_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
