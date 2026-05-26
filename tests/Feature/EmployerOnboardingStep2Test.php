<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployerOnboardingStep2Test extends TestCase
{
    use RefreshDatabase;

    public function test_employer_onboarding_step_2_stores_company_information_and_contact_person(): void
    {
        $user = User::factory()->create(['role' => 'employer']);
        Company::factory()->forUser($user)->create([
            'onboarding_step' => 2,
            'onboarding_completed_at' => null,
            'name' => 'Test Co',
            'slug' => 'test-co-1',
        ]);

        $response = $this->actingAs($user)->post(route('employer.onboarding.store', ['step' => 2]), [
            'location' => 'New York, NY',
            'email' => 'careers@testco.com',
            'phone' => '+1 555 000 0000',
            'contact_name' => 'Jane Smith',
            'contact_position' => 'HR Manager',
            'contact_availability_time' => 'Mon–Fri 9am–5pm EST',
            'contact_person_email' => 'jane.smith@testco.com',
            'contact_person_phone' => '+1 555 111 1111',
        ]);

        $response->assertRedirect(route('employer.onboarding.show', ['step' => 3]));

        $user->company->refresh();
        $this->assertSame('New York, NY', $user->company->location);
        $this->assertSame('careers@testco.com', $user->company->email);
        $this->assertSame('Jane Smith', $user->company->contact_name);
        $this->assertSame('Mon–Fri 9am–5pm EST', $user->company->contact_availability_time);
        $this->assertSame('jane.smith@testco.com', $user->company->contact_person_email);
        $this->assertSame('+1 555 111 1111', $user->company->contact_person_phone);
    }

    public function test_employer_onboarding_step_2_validates_contact_person_email_and_phone(): void
    {
        $user = User::factory()->create(['role' => 'employer']);
        Company::factory()->forUser($user)->create([
            'onboarding_step' => 2,
            'onboarding_completed_at' => null,
        ]);

        $response = $this->actingAs($user)->post(route('employer.onboarding.store', ['step' => 2]), [
            'location' => 'New York, NY',
            'email' => 'careers@testco.com',
            'phone' => '+1 555 000 0000',
            'contact_name' => 'Jane Smith',
            'contact_position' => 'HR Manager',
            'contact_availability_time' => 'Mon–Fri 9am–5pm EST',
            'contact_person_email' => 'not-an-email',
            'contact_person_phone' => '',
        ]);

        $response->assertSessionHasErrors(['contact_person_email', 'contact_person_phone']);
    }

    public function test_employer_onboarding_step_2_validates_availability_from_dropdown(): void
    {
        $user = User::factory()->create(['role' => 'employer']);
        Company::factory()->forUser($user)->create([
            'onboarding_step' => 2,
            'onboarding_completed_at' => null,
        ]);

        $response = $this->actingAs($user)->post(route('employer.onboarding.store', ['step' => 2]), [
            'location' => 'New York, NY',
            'email' => 'careers@testco.com',
            'phone' => '+1 555 000 0000',
            'contact_name' => 'Jane Smith',
            'contact_position' => 'HR Manager',
            'contact_availability_time' => 'Random typed text',
            'contact_person_email' => 'jane@testco.com',
            'contact_person_phone' => '+1 555 111 1111',
        ]);

        $response->assertSessionHasErrors(['contact_availability_time']);
    }
}
