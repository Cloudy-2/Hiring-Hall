<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\JobPosting;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Seed primary test user (idempotent)
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'applicant',
            ],
        );

        // Seed additional test user from Nash branch (idempotent)
        $nash = User::firstOrCreate(
            ['email' => 'testnash@example.com'],
            [
                'name' => 'Test Nash',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'applicant',
            ],
        );

        // Seed role-based demo accounts (idempotent)
        $moderator = User::firstOrCreate(
            ['email' => 'moderator@example.com'],
            [
                'name' => 'Moderator User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'moderator',
            ],
        );

        $candidateUser = User::firstOrCreate(
            ['email' => 'candidate@example.com'],
            [
                'name' => 'Candidate User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'applicant',
            ],
        );

        $employerUser = User::firstOrCreate(
            ['email' => 'employer@example.com'],
            [
                'name' => 'Employer User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'employer',
            ],
        );

        $company = Company::firstOrCreate(
            ['slug' => 'sparkclean-janitorial-services'],
            [
                'name' => 'SparkClean Janitorial Services',
                'logo_url' => 'https://api.dicebear.com/7.x/shapes/svg?seed=SparkClean%20Janitorial%20Services',
                'location' => 'Los Angeles, CA',
                'industry' => 'business_process',
                'established_year' => 2015,
                'employees_count' => 45,
                'verified' => true,
                'rating' => 4.5,
                'rating_count' => 245,
            ],
        );

        JobPosting::firstOrCreate(
            ['slug' => 'operations-virtual-assistant-janitorial'],
            [
                'company_id' => $company->id,
                'title' => 'Operations Virtual Assistant (Janitorial)',
                'location' => 'Remote · Supporting US / Canada janitorial clients',
                'category' => 'Operations VA',
                'employment_type' => 'full_time',
                'remote_type' => 'remote',
                'vacancies' => 3,
                'status' => 'open',
                'salary_min' => 800,
                'salary_max' => 1200,
                'salary_currency' => 'USD',
                'experience_min_years' => 2,
                'experience_max_years' => 4,
                'summary' => 'Janitorial Operations Virtual Assistant role supporting US/Canada clients.',
                'posted_at' => now()->subDays(5),
            ],
        );

        // Seed dropdown options for dynamic forms
        $this->call(DropdownOptionsSeeder::class);

        // // Seed test data for filtering
        // $this->call(TestDataSeeder::class);

        // // Seed FAQs
        // $this->call(FaqSeeder::class);

        // // Seed release notes
        // $this->call(ReleaseNoteSeeder::class);
    }
}
