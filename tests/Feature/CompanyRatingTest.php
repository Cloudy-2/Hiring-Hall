<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyRatingTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_rate_company_and_aggregates_update(): void
    {
        $company = Company::factory()->approved()->create([
            'rating' => null,
            'rating_count' => 0,
        ]);
        $rater = User::factory()->create(['role' => 'employer']);

        $response = $this->actingAs($rater)
            ->postJson(route('companies.rate', ['company' => $company->id]), [
                'rating' => 4,
            ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'rating' => 4,
                'average_rating' => 4.0,
                'rating_count' => 1,
            ]);

        $company->refresh();
        $this->assertSame(4.0, (float) $company->rating);
        $this->assertSame(1, $company->rating_count);
    }

    public function test_same_user_updating_rating_does_not_duplicate_count(): void
    {
        $company = Company::factory()->approved()->create([
            'rating' => null,
            'rating_count' => 0,
        ]);
        $rater = User::factory()->create(['role' => 'employer']);

        $this->actingAs($rater)
            ->postJson(route('companies.rate', ['company' => $company->id]), ['rating' => 3])
            ->assertOk();

        $this->actingAs($rater)
            ->postJson(route('companies.rate', ['company' => $company->id]), ['rating' => 5])
            ->assertOk()
            ->assertJson([
                'success' => true,
                'average_rating' => 5.0,
                'rating_count' => 1,
            ]);

        $company->refresh();
        $this->assertSame(5.0, (float) $company->rating);
        $this->assertSame(1, $company->rating_count);
    }

    public function test_get_company_rating_returns_average_and_count_and_user_rating_when_authenticated(): void
    {
        $company = Company::factory()->approved()->create([
            'rating' => null,
            'rating_count' => 0,
        ]);
        $rater = User::factory()->create(['role' => 'employer']);

        $this->actingAs($rater)
            ->postJson(route('companies.rate', ['company' => $company->id]), ['rating' => 4])
            ->assertOk();

        $response = $this->actingAs($rater)
            ->getJson(route('companies.rating', ['company' => $company->id]));

        $response->assertOk()
            ->assertJsonStructure(['user_rating', 'average_rating', 'rating_count'])
            ->assertJson([
                'user_rating' => 4,
                'average_rating' => 4.0,
                'rating_count' => 1,
            ]);
    }

    public function test_unauthenticated_request_to_post_company_rate_returns_unauthorized(): void
    {
        $company = Company::factory()->approved()->create();

        $response = $this->postJson(route('companies.rate', ['company' => $company->id]), [
            'rating' => 4,
        ]);

        $response->assertStatus(401);
    }
}
