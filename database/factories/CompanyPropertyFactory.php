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
            'Bidang Usaha' => [
                'Teknologi', 'Kesehatan', 'Pendidikan', 
                'Keuangan', 'Manufaktur', 'Pariwisata'
            ],
            'Gaji Pokok' => [
                '5.000.000', '7.000.000', '10.000.000',
                '15.000.000', '20.000.000', '5.500.000',
            ],
            'Tunjangan & Bonus' => [
                'Tunjangan Makan', 
                'Tunjangan Transportasi', 
                'Bonus Tahunan', 
                'Bonus Proyek', 
                'Asuransi Kesehatan Swasta'
            ],
            'Jam Kerja Reguler' => [
                'Fleksibel', '8 jam/hari', 'Senin - Jumat'
            ],
            'Fleksibilitas Kerja' => [
                'Remote', 'Hybrid', 'On-site'
            ],
            'Fasilitas Kantor' => [
                'Pantry dengan minuman dan camilan gratis', 'Area Istirahat', 'Ruang Olahraga',
                'Parkir Gratis', 'Ruang Meditasi', 'Kantin dengan Harga Terjangkau'
            ],
            'Budaya Kerja' => [
                'Inklusif dan Beragam', 'Kolaboratif', 'Berorientasi pada Hasil',
                'Pengembangan Karyawan', 'Kasual', 'Meritokrasi'
            ],
            'Pelatihan & Pengembangan' => [
                'Pelatihan Teknis', 'Workshop Soft Skills', 'Kursus Online',
                'Program Mentorship', 'Sertifikasi Profesional'
            ],
            
        ];

        return [
            'company_id' => Company::inRandomOrder()->first()->id,
            'key' => $key = fake()->randomElement(array_keys($options)),
            'value' => fake()->randomElement($options[$key]),
        ];
    }
}
