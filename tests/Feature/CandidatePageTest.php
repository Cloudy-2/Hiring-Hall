<?php

namespace Tests\Feature;

use App\Models\ApplicantProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CandidatePageTest extends TestCase
{
    use RefreshDatabase;

    protected User $employer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\DropdownOptionsSeeder::class);
        $this->employer = User::factory()->create(['role' => 'employer']);
    }

    public function test_candidates_page_loads(): void
    {
        $response = $this->actingAs($this->employer)->get('/applicants');
        $response->assertStatus(200);
    }

    public function test_candidates_page_shows_candidates(): void
    {
        $user = User::factory()->create(['role' => 'applicant']);
        ApplicantProfile::create([
            'user_id' => $user->id,
            'display_name' => 'Test Candidate',
            'title' => 'Virtual Assistant',
            'availability' => 'Immediate',
            'job_type' => 'Full Time',
            'expertise_categories' => json_encode(['Administrative']),
            'languages' => json_encode(['English']),
        ]);

        $response = $this->actingAs($this->employer)->get('/applicants');
        $response->assertStatus(200);
        $response->assertSee('Test Candidate');
    }

    public function test_candidates_filter_by_single_expertise(): void
    {
        $user1 = User::factory()->create(['role' => 'applicant']);
        ApplicantProfile::create([
            'user_id' => $user1->id,
            'display_name' => 'Admin Expert',
            'expertise_categories' => json_encode(['Administrative']),
        ]);

        $user2 = User::factory()->create(['role' => 'applicant']);
        ApplicantProfile::create([
            'user_id' => $user2->id,
            'display_name' => 'Data Expert',
            'expertise_categories' => json_encode(['Data Entry']),
        ]);

        $response = $this->actingAs($this->employer)->get('/applicants?expertise[]=Administrative');
        $response->assertStatus(200);
        $response->assertSee('Admin Expert');
        $response->assertDontSee('Data Expert');
    }

    public function test_candidates_filter_by_multiple_expertise_uses_or_logic(): void
    {
        $user1 = User::factory()->create(['role' => 'applicant']);
        ApplicantProfile::create([
            'user_id' => $user1->id,
            'display_name' => 'Admin Only',
            'expertise_categories' => json_encode(['Administrative']),
        ]);

        $user2 = User::factory()->create(['role' => 'applicant']);
        ApplicantProfile::create([
            'user_id' => $user2->id,
            'display_name' => 'Data Only',
            'expertise_categories' => json_encode(['Data Entry']),
        ]);

        $user3 = User::factory()->create(['role' => 'applicant']);
        ApplicantProfile::create([
            'user_id' => $user3->id,
            'display_name' => 'Operations Only',
            'expertise_categories' => json_encode(['Operations']),
        ]);

        // Filter by Administrative OR Data Entry - should show both, not Operations
        $response = $this->actingAs($this->employer)->get('/applicants?expertise[]=Administrative&expertise[]=Data Entry');
        $response->assertStatus(200);
        $response->assertSee('Admin Only');
        $response->assertSee('Data Only');
        $response->assertDontSee('Operations Only');
    }

    public function test_candidates_filter_by_availability(): void
    {
        $user1 = User::factory()->create(['role' => 'applicant']);
        ApplicantProfile::create([
            'user_id' => $user1->id,
            'display_name' => 'Immediate Candidate',
            'availability' => 'Immediate',
        ]);

        $user2 = User::factory()->create(['role' => 'applicant']);
        ApplicantProfile::create([
            'user_id' => $user2->id,
            'display_name' => 'Month Notice Candidate',
            'availability' => '1 Month Notice',
        ]);

        $response = $this->actingAs($this->employer)->get('/applicants?availability[]=Immediate');
        $response->assertStatus(200);
        $response->assertSee('Immediate Candidate');
        $response->assertDontSee('Month Notice Candidate');
    }

    public function test_candidates_filter_by_job_type(): void
    {
        $user1 = User::factory()->create(['role' => 'applicant']);
        ApplicantProfile::create([
            'user_id' => $user1->id,
            'display_name' => 'Full Timer',
            'job_type' => 'Full Time',
        ]);

        $user2 = User::factory()->create(['role' => 'applicant']);
        ApplicantProfile::create([
            'user_id' => $user2->id,
            'display_name' => 'Part Timer',
            'job_type' => 'Part Time',
        ]);

        $response = $this->actingAs($this->employer)->get('/applicants?job_type[]=Full Time');
        $response->assertStatus(200);
        $response->assertSee('Full Timer');
        $response->assertDontSee('Part Timer');
    }

    public function test_candidates_search_by_keyword(): void
    {
        $user1 = User::factory()->create(['role' => 'applicant']);
        ApplicantProfile::create([
            'user_id' => $user1->id,
            'display_name' => 'John Developer',
            'title' => 'Senior Developer',
        ]);

        $user2 = User::factory()->create(['role' => 'applicant']);
        ApplicantProfile::create([
            'user_id' => $user2->id,
            'display_name' => 'Jane Designer',
            'title' => 'UI Designer',
        ]);

        $response = $this->actingAs($this->employer)->get('/applicants?keyword=Developer');
        $response->assertStatus(200);
        $response->assertSee('John Developer');
        $response->assertDontSee('Jane Designer');
    }

    public function test_candidate_dashboard_requires_auth(): void
    {
        $response = $this->get('/applicant/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_candidate_dashboard_loads_for_candidate(): void
    {
        $user = User::factory()->create(['role' => 'applicant']);
        ApplicantProfile::create([
            'user_id' => $user->id,
            'display_name' => 'Test Candidate',
            'onboarding_step' => 4,
            'onboarding_completed_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/applicant/dashboard');
        $response->assertStatus(200);
    }
}
