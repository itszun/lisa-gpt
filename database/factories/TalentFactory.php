<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Talent>
 */
class TalentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'position' => fake()->randomElement([
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
            'birthdate' => fake()->date(),
            'summary' => fake()->text(200),
            'skills' => fake()->randomElements([
                'PHP', 'Laravel', 'JavaScript', 
                'React', 'Vue.js', 'Python', 
                'Django', 'MySQL', 'PostgreSQL', 
                'Docker', 'AWS', 'Git',
                'HTML', 'CSS', 'Node.js',
                'TypeScript', 'Java', 'C#',
                'Go', 'Kubernetes','Logic Pro',
                'Diagram Experts', 'Dokumentation', 'Problem Solver',
                'Leadership', 'Teamwork', 'Communication'
            ], rand(1, 5)),
            'educations' => [
                fake()->randomElement([
                    'SMKN 1 Jakarta',
                    'SMKN 2 Jakarta',
                    'SMKN 3 Jakarta',
                    'SMAN 1 Jakarta',
                    'SMAN 2 Bekasi',
                    'SMAN 3 Bogor',
                ]),
                fake()->randomElement([
                    'Universitas Indonesia',
                    'Institut Teknologi Bandung',
                    'Universitas Gadjah Mada',
                    'Universitas Airlangga',
                    'Universitas Brawijaya',
                    'Universitas Hasanuddin',
                ]),
            ],
        ];
    }
}
