<?php

namespace Database\Seeders;

use App\Models\ApplicantProfile;
use App\Models\Company;
use App\Models\JobPosting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->createCompanies();
        $this->createJobs();
        $this->createCandidates();
    }

    private function createCompanies(): void
    {
        $companies = [
            ['name' => 'SparkClean Janitorial', 'industry' => 'business_process', 'location' => 'Los Angeles, CA'],
            ['name' => 'Prime Facility Care', 'industry' => 'administrative_support', 'location' => 'New York, NY'],
            ['name' => 'ShinePro Commercial', 'industry' => 'consulting', 'location' => 'San Francisco, CA'],
            ['name' => 'Global Janitorial Partners', 'industry' => 'human_resources', 'location' => 'Chicago, IL'],
            ['name' => 'CleanDesk Solutions', 'industry' => 'accounting', 'location' => 'Miami, FL'],
            ['name' => 'BrightEdge Facility', 'industry' => 'research_development', 'location' => 'Seattle, WA'],
            ['name' => 'MetroSpark Cleaning', 'industry' => 'marketing', 'location' => 'Austin, TX'],
            ['name' => 'JanServe Staffing', 'industry' => 'business_process', 'location' => 'Denver, CO'],
        ];

        foreach ($companies as $data) {
            Company::firstOrCreate(
                ['slug' => Str::slug($data['name'])],
                [
                    'name' => $data['name'],
                    'logo_url' => 'https://api.dicebear.com/7.x/shapes/svg?seed='.urlencode($data['name']),
                    'location' => $data['location'],
                    'industry' => $data['industry'],
                    'established_year' => rand(2005, 2020),
                    'employees_count' => rand(20, 100),
                    'verified' => rand(0, 1),
                    'rating' => rand(35, 50) / 10,
                    'rating_count' => rand(50, 500),
                ]
            );
        }
    }

    private function createJobs(): void
    {
        $companies = Company::all();
        $categories = ['operations_va', 'admin_support', 'scheduling_dispatch', 'client_success', 'back_office'];
        $employmentTypes = ['full_time', 'part_time', 'contract', 'freelance'];
        $recruiterTypes = ['direct', 'agency', 'staffing', 'headhunter'];
        $industryTypes = ['research_development', 'accounting', 'business_process', 'consulting', 'administrative_support', 'human_resources', 'marketing'];

        $jobTitles = [
            'Operations Virtual Assistant',
            'Scheduling & Dispatch VA',
            'Admin Support Specialist',
            'Client Success Manager',
            'Back Office Coordinator',
            'Janitorial Operations Lead',
            'Billing & Accounting VA',
            'HR & Onboarding Assistant',
            'Proposal & Bidding VA',
            'Customer Service Representative',
            'Data Entry Specialist',
            'Project Coordinator',
            'Executive Assistant',
            'Recruitment Coordinator',
            'Quality Assurance VA',
        ];

        foreach ($companies as $company) {
            $numJobs = rand(2, 4);
            for ($i = 0; $i < $numJobs; $i++) {
                $title = $jobTitles[array_rand($jobTitles)];
                $slug = Str::slug($title.'-'.$company->id.'-'.Str::random(4));

                JobPosting::firstOrCreate(
                    ['slug' => $slug],
                    [
                        'company_id' => $company->id,
                        'title' => $title,
                        'location' => $company->location,
                        'category' => $categories[array_rand($categories)],
                        'employment_type' => $employmentTypes[array_rand($employmentTypes)],
                        'recruiter_type' => $recruiterTypes[array_rand($recruiterTypes)],
                        'industry_type' => $industryTypes[array_rand($industryTypes)],
                        'remote_type' => 'remote',
                        'vacancies' => rand(1, 25),
                        'status' => 'open',
                        'salary_min' => rand(500, 1000),
                        'salary_max' => rand(1200, 2500),
                        'salary_currency' => 'USD',
                        'experience_min_years' => rand(1, 3),
                        'experience_max_years' => rand(4, 7),
                        'summary' => 'Looking for a skilled '.$title.' to join our team.',
                        'posted_at' => now()->subDays(rand(1, 30)),
                    ]
                );
            }
        }
    }

    private function createCandidates(): void
    {
        $expertiseCategories = ['Administrative', 'Customer Service', 'Operations', 'Scheduling & Dispatch', 'Data Entry'];
        $availabilities = ['Immediate', '1 Week Notice', '2 Weeks Notice', '1 Month Notice', 'Negotiable'];
        $jobTypes = ['Full Time', 'Part Time', 'Contract', 'Freelance', 'Any'];
        $languages = ['English', 'Spanish', 'Filipino', 'Mandarin', 'French'];
        $workModes = ['Remote', 'On-site', 'Hybrid', 'Flexible'];

        $firstNames = ['Maria', 'John', 'Sarah', 'Michael', 'Emily', 'David', 'Jessica', 'James', 'Ashley', 'Robert', 'Jennifer', 'William', 'Amanda', 'Christopher', 'Stephanie', 'Daniel', 'Nicole', 'Matthew', 'Elizabeth', 'Andrew'];
        $lastNames = ['Garcia', 'Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Miller', 'Davis', 'Martinez', 'Anderson', 'Taylor', 'Thomas', 'Moore', 'Jackson', 'Martin', 'Lee', 'Thompson', 'White', 'Harris', 'Clark'];
        $titles = ['Virtual Assistant', 'Operations Specialist', 'Admin Coordinator', 'Customer Service Rep', 'Data Entry Clerk', 'Project Manager', 'Executive Assistant', 'Scheduling Coordinator'];
        $locations = ['Manila, Philippines', 'Cebu, Philippines', 'Los Angeles, CA', 'New York, NY', 'London, UK', 'Sydney, Australia', 'Toronto, Canada'];

        for ($i = 1; $i <= 30; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $name = $firstName.' '.$lastName;
            $email = strtolower($firstName.'.'.$lastName.$i.'@example.com');

            // Create user
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'role' => 'applicant',
                ]
            );

            // Random expertise (1-3 categories)
            $numExpertise = rand(1, 3);
            $selectedExpertise = array_slice($expertiseCategories, 0, $numExpertise);
            shuffle($selectedExpertise);

            // Random languages (1-2)
            $numLangs = rand(1, 2);
            $selectedLangs = array_slice($languages, 0, $numLangs);
            shuffle($selectedLangs);

            // Random tools (2-4)
            $allTools = ['Slack', 'Zoom', 'Trello', 'Asana', 'Monday.com', 'HubSpot', 'Salesforce', 'QuickBooks', 'Zendesk', 'Freshdesk', 'Notion', 'ClickUp', 'Jira', 'Microsoft Teams', 'Google Meet'];
            $numTools = rand(2, 4);
            shuffle($allTools);
            $selectedTools = array_slice($allTools, 0, $numTools);

            // Random currency - mix of USD and PHP
            $currencies = ['USD', 'USD', 'USD', 'PHP', 'PHP', 'EUR']; // weighted towards USD
            $currency = $currencies[array_rand($currencies)];

            // Adjust salary based on currency
            if ($currency === 'PHP') {
                $salaryMin = rand(15000, 35000);
                $salaryMax = rand(40000, 80000);
            } elseif ($currency === 'EUR') {
                $salaryMin = rand(400, 900);
                $salaryMax = rand(1000, 2200);
            } else {
                $salaryMin = rand(500, 1000);
                $salaryMax = rand(1200, 2500);
            }

            // Create applicant profile
            ApplicantProfile::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'display_name' => $name,
                    'title' => $titles[array_rand($titles)],
                    'location' => $locations[array_rand($locations)],
                    'work_mode' => $workModes[array_rand($workModes)],
                    'degree' => rand(0, 1) ? "Bachelor's Degree" : "Associate's Degree",
                    'years_experience' => rand(1, 10),
                    'availability' => $availabilities[array_rand($availabilities)],
                    'job_type' => $jobTypes[array_rand($jobTypes)],
                    'expertise_categories' => json_encode($selectedExpertise),
                    'expected_salary_min' => $salaryMin,
                    'expected_salary_max' => $salaryMax,
                    'salary_currency' => $currency,
                    'verified' => rand(0, 1),
                    'rating' => rand(30, 50) / 10,
                    'rating_count' => rand(10, 200),
                    'headline' => 'Experienced '.$titles[array_rand($titles)].' with '.rand(2, 8).'+ years',
                    'about' => 'Dedicated professional with expertise in '.implode(', ', $selectedExpertise).'.',
                    'languages' => json_encode($selectedLangs),
                    'skills' => json_encode(['Microsoft Office', 'Google Workspace', 'CRM', 'Data Entry', 'Communication']),
                    'tools_used' => json_encode($selectedTools),
                ]
            );
        }
    }
}
