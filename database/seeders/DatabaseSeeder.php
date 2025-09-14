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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Spatie\Permission\Models\Role;

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

        Role::create([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);
        Role::create([
            'name' => 'talent',
            'guard_name' => 'web',
        ]);
        Role::create([
            'name' => 'company',
            'guard_name' => 'web',
        ]);

        $user = User::create([
            'name' => "Admin",
            'email' => "admin@altateknologi.com",
            'password' => Hash::make('1'),
        ]);
        // $user->assignRole("super_admin");

        Talent::factory(20)->create();
        Talent::createAllUser();
        Company::factory(5)->withProperties()->create();
        Company::createAllUser();
        JobOpening::factory(5)->create();
        // Candidate::factory(100)->create();

    }
}
