<?php

namespace Tests\Feature;

use App\Models\Relationship;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RelationshipTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_authenticated_user_can_access_create_page(): void
    {
        $response = $this->actingAs($this->user)->get('/relationship/create');
        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_create_relationship(): void
    {
        $data = [
            'company_name' => 'Test Company',
            'type' => 'Client',
            'phone' => '1234567890',
            'city' => 'Test City',
            'state' => 'TS',
            'zip' => '12345',
        ];

        $response = $this->actingAs($this->user)->post('/relationship/contact', $data);

        $response->assertRedirect('/relationship/list');
        $this->assertDatabaseHas('relationships', [
            'company_name' => 'Test Company',
            'phone' => '1234567890',
        ]);
    }

    public function test_relationship_listing_returns_data(): void
    {
        Relationship::create([
            'company_name' => 'List Company',
            'type' => 'Vendor',
            'phone' => '9876543210',
        ]);

        $response = $this->actingAs($this->user)->get('/relationship/company');

        $response->assertStatus(200);
        $response->assertJsonPath('data.0.company_name', 'List Company');
    }

    public function test_authenticated_user_can_delete_relationship(): void
    {
        $relationship = Relationship::create([
            'company_name' => 'Delete Me',
            'type' => 'Prospect',
            'phone' => '0000000000',
        ]);

        $response = $this->actingAs($this->user)->delete("/relationship/{$relationship->getRouteKey()}");

        $response->assertRedirect();
        $this->assertSoftDeleted('relationships', [
            'id' => $relationship->id,
        ]);
    }
}
