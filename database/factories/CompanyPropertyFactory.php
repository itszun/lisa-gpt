<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CompanyProperty>
 */
class CompanyPropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $options = [
            'Gaji' => ['5.000.000', '7.000.000', '10.000.000', '15.000.000', '20.000.000'],
            'Jam Kerja' => ['08:00 - 17:00', '09:00 - 18:00', '10:00 - 19:00'],
            'Penempatan Kerja' => ['Jakarta', 'Bandung', 'Surabaya'],
            'Asuransi Kesehatan' => ['BPJS Kesehatan', 'Asuransi Swasta'],
            'Jenjang Karir' => ['Junior', 'Mid', 'Senior'],
            'Fasilitas' => ['Laptop', 'Transportasi', 'Tunjangan Makan'],
        ];

        return [
            'company_id' => Company::inRandomOrder()->first()->id,
            'key' => $key = fake()->randomElement(array_keys($options)),
            'value' => fake()->randomElement($options[$key]),
        ];
    }
}
