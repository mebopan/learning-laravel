<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'comment' => \fake()->realText(),
            'rating' => \fake()->numberBetween(1, 10),
            'created_at' => \fake()->dateTimeBetween('-2 years'),
            'updated_at' => function (array $attributes) {
                return \fake()->dateTimeBetween($attributes['created_at']);
            },
        ];
    }
}
