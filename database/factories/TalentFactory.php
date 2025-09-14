<?php

namespace Database\Factories;

use App\Models\Talent;
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
        $whiteCollarPositions = [
            'System Analyst',
            'IT Consultant',
            'Business Analyst',
            'Product Manager',
            'Product Owner',
            'Scrum Master',
            'UI/UX Designer',
            'UX Researcher',
            'Data Scientist',
            'Data Analyst',
            'DevOps Engineer',
            'Backend Developer',
            'Frontend Developer',
            'Fullstack Developer',
            'Project Manager',
            'Quality Assurance',
            'Business Development Manager',
        ];

        $blueCollarPositions = [
            'Head of Warehouse',
            'Cleaning Service',
            'Housekeeping',
            'Operator',
            'QA/QC',
            'Forklift Operator',
            'Welder',
            'Electrician',
            'Security Guard',
            'Technician',
            'Machine Operator',
        ];

        $whiteCollarSkills = [
            'PHP', 'Laravel', 'JavaScript', 'React', 'Vue.js', 'Python',
            'Django', 'MySQL', 'PostgreSQL', 'Docker', 'AWS', 'Git',
            'HTML', 'CSS', 'Node.js', 'TypeScript', 'Java', 'C#',
            'Go', 'Kubernetes', 'Microservices', 'Machine Learning', 'Data Science',
            'Cloud Computing', 'Cybersecurity', 'Strategic Planning', 'Risk Management',
            'Project Management', 'Agile Methodologies', 'Scrum', 'Kanban',
            'Stakeholder Management', 'Budgeting', 'Public Speaking', 'Leadership',
            'Teamwork', 'Communication', 'Problem Solver', 'Diagram Experts', 'Dokumentation',
        ];

        $blueCollarSkills = [
            'Equipment Maintenance', 'Troubleshooting', 'Safety Protocol',
            'Logistics Management', 'Welding', 'Heavy Machinery Operation',
            'Quality Control', 'Inventory Management', 'Basic Repair',
            'Driving License B2', 'Forklift Operation License', 'Crane Operation',
        ];

        $allPositions = array_merge($whiteCollarPositions, $blueCollarPositions);

        $selectedPosition = fake()->randomElement($allPositions);
        $isWhiteCollar = in_array($selectedPosition, $whiteCollarPositions);
        $selectedSkills = $isWhiteCollar
            ? fake()->randomElements($whiteCollarSkills, rand(1, 5))
            : fake()->randomElements($blueCollarSkills, rand(1, 5));

        return [
            'name' => fake()->name(),
            'position' => $selectedPosition,
            'birthdate' => fake()->date(),
            'summary' => fake()->text(200),
            'skills' => $selectedSkills,
            'educations' => [
                fake()->randomElement([
                    'SMKN 1 Jakarta', 'SMKN 2 Jakarta', 'SMKN 3 Jakarta',
                    'SMAN 1 Jakarta', 'SMAN 2 Bekasi', 'SMAN 3 Bogor',
                ]),
                fake()->randomElement([
                    'Universitas Indonesia', 'Institut Teknologi Bandung',
                    'Universitas Gadjah Mada', 'Universitas Airlangga',
                    'Universitas Brawijaya', 'Universitas Hasanuddin',
                ]),
            ],
            'status' => fake()->randomElement([1, 2, 100, 200, 300]),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function(Talent $talent) {
            $talent->createUser();
        });
    }
}
