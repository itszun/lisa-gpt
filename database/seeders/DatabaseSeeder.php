<?php

namespace Database\Seeders;

use App\Jobs\FeederAll;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Talent;
use App\Models\Company;
use App\Models\Candidate;
use App\Models\JobOpening;
use App\Models\CompanyProperty;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Talent::factory(100)->create();
        Company::factory(100)->withProperties()->create();
        JobOpening::factory(100)->create();
        Candidate::factory(100)->create();

        FeederAll::dispatchSync();
    }
}
