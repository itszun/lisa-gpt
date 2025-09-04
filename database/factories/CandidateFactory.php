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
            'status' => fake()->randomElement([1, 2, 100, 200, 201, 202, 203, 901, 902, 903]),
            'screening' => [
                fake()->randomElements([
                    "Kandidat sangat berpotensi dibidang yang dituju dan bisa langsung dihubungi untuk interview.",
                    "Kandidat memiliki pengalaman yang relevan dan dapat memberikan kontribusi yang signifikan.",
                    "Kandidat menunjukkan sikap yang baik dan mampu bekerja dalam tim.",
                ]),
                fake()->randomElements([
                    "Kandidat memiliki keterampilan komunikasi yang baik dan mampu beradaptasi dengan cepat.",
                    "Kandidat memiliki pemahaman yang baik tentang industri dan tren terkini.",
                    "Kandidat memiliki motivasi yang tinggi untuk belajar dan berkembang."
                ]),
            ],
            'regist_at' => fake()->dateTime(),
            'interview_schedule' => fake()->optional()->dateTimeBetween('+1 week', '+1 month'),
            'notified_at' => function (array $attributes) {
                $interviewDate = $attributes['interview_schedule'] ?? null;
                return $interviewDate ? Carbon::parse($interviewDate)->subWeek() : null;
            },
        ];
    }
}
