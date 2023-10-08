<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'slug' => fake()->slug,
            'title' => fake()->sentence,
            'description' => fake()->paragraph,
        ];
    }

    /**
     * Indicate that the course is released.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
     */
    public function released(Carbon $date = null): Factory
    {
        return $this->state([
            'released_at' => $date ?? now(),
        ]);
    }
}
