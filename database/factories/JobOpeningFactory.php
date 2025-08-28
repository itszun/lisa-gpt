<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobOpening>
 */
class JobOpeningFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => Company::inRandomOrder()->first()->id,
            'title' => fake()->randomElement([
                'Backend Developer',
                'Frontend Developer',
                'Fullstack Developer',
                'Data Scientist',
                'DevOps Engineer',
                'UI/UX Designer',
                'Product Manager',
                'Project Manager',
                'Business Analyst',
                'Quality Assurance',
            ]),
            'body' => fake()->paragraph(),
            'due_date' => fake()->dateTimeBetween('+1 week', '+1 month'),
            'status' => fake()->randomElement([0, 1]),
        ];
    }
}
