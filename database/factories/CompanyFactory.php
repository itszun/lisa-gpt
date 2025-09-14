<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'description' => fake()->text(200),
            'status' => rand(0, 1)
        ];
    }

    public function withProperties(int $count = 5): static
    {
        return $this->afterCreating(function ($company) use ($count) {
            $faker = Faker::create();

            $options = [
                'Bidang Usaha' => ['Teknologi', 'Kesehatan', 'Pendidikan', 'Keuangan', 'Manufaktur', 'Pariwisata'],
                'Gaji Pokok' => ['5.000.000', '7.000.000', '10.000.000', '15.000.000', '20.000.000', '5.500.000'],
                'Tunjangan & Bonus' => ['Tunjangan Makan', 'Tunjangan Transportasi', 'Bonus Tahunan', 'Bonus Proyek', 'Asuransi Kesehatan Swasta'],
                'Jam Kerja Reguler' => ['Fleksibel', '8 jam/hari', 'Senin - Jumat'],
                'Fleksibilitas Kerja' => ['Remote', 'Hybrid', 'On-site'],
                'Fasilitas Kantor' => ['Pantry dengan minuman dan camilan gratis', 'Area Istirahat', 'Ruang Olahraga', 'Parkir Gratis', 'Ruang Meditasi', 'Kantin dengan Harga Terjangkau'],
                'Budaya Kerja' => ['Inklusif dan Beragam', 'Kolaboratif', 'Berorientasi pada Hasil', 'Pengembangan Karyawan', 'Kasual', 'Meritokrasi'],
                'Pelatihan & Pengembangan' => ['Pelatihan Teknis', 'Workshop Soft Skills', 'Kursus Online', 'Program Mentorship', 'Sertifikasi Profesional'],
            ];

            // Tentukan properti yang wajib ada
            $requiredKeys = ['Bidang Usaha', 'Gaji Pokok'];

            // Buat properti wajib
            foreach ($requiredKeys as $key) {
                if (isset($options[$key])) {
                    $company->properties()->create([
                        'key' => $key,
                        'value' => $faker->randomElement($options[$key]),
                    ]);
                    // Hapus kunci yang sudah dipakai dari list
                    unset($options[$key]);
                }
            }

            // Hitung sisa properti yang bisa diambil secara acak
            $remainingCount = $count - count($requiredKeys);

            // Ambil properti sisa secara acak dari list yang tersisa
            if ($remainingCount > 0) {
                $randomKeys = $faker->randomElements(array_keys($options), $remainingCount, true);
                foreach ($randomKeys as $key) {
                    $company->properties()->create([
                        'key' => $key,
                        'value' => $faker->randomElement($options[$key]),
                    ]);
                }
            }
        })->afterCreating(function(Company $company) {
            $company->createUser();
        });
    }
}
