<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SiteActivityFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'schedule' => $this->faker->randomElement(['Weekly', 'Monthly', 'One-off']),
            'time' => '7:00 PM',
            'location' => $this->faker->city(),
            'category' => $this->faker->randomElement(['Weekly', 'Special']),
            'poster' => null,
            'featured' => $this->faker->boolean(20),
        ];
    }
}