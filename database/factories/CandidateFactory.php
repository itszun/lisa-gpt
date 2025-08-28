<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\Talent;
use App\Models\JobOpening;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Candidate>
 */
class CandidateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'talent_id' => Talent::inRandomOrder()->first()->id,
            'job_opening_id' => JobOpening::inRandomOrder()->first()->id,
            'status' => fake()->randomElement([0, 1]),
            'regist_at' => fake()->dateTime(),
            'interview_schedule' => fake()->optional()->dateTimeBetween('+1 week', '+1 month'),
            'notified_at' => function (array $attributes) {
                $interviewDate = $attributes['interview_schedule'] ?? null;
                return $interviewDate ? Carbon::parse($interviewDate)->subWeek() : null;
            },
        ];
    }
}
