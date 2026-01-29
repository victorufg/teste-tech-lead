<?php

namespace Database\Factories;

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
            'name' => fake()->sentence(3),
            'type' => fake()->randomElement(['online', 'presencial']),
            'max_students' => fake()->numberBetween(10, 50),
            'enrollment_deadline' => fake()->dateTimeBetween('now', '+1 month'),
        ];
    }
}
