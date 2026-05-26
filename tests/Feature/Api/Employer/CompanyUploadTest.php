<?php

namespace Tests\Feature\Api\Employer;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CompanyUploadTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected string $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => 'employer']);
        $this->token = $this->user->createToken('test', ['employer'])->plainTextToken;
    }

    public function test_upload_registration_document_stores_file_and_updates_company(): void
    {
        Storage::fake('public');

        Company::factory()->create([
            'user_id' => $this->user->id,
            'onboarding_completed_at' => null,
            'registration_document_url' => null,
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->postJson('/api/v1/employer/company/registration-document', [
                'document' => UploadedFile::fake()->create('registration.pdf', 512, 'application/pdf'),
            ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Company registration document uploaded.',
            ]);

        $company = $this->user->fresh()->company;

        $this->assertNotNull($company->registration_document_url);
        Storage::disk('public')->assertExists($company->registration_document_url);
    }

    public function test_upload_registration_document_replaces_previous_file(): void
    {
        Storage::fake('public');

        $company = Company::factory()->create([
            'user_id' => $this->user->id,
            'onboarding_completed_at' => null,
            'registration_document_url' => 'company-registration-documents/old.pdf',
        ]);

        Storage::disk('public')->put($company->registration_document_url, 'old file');

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->postJson('/api/v1/employer/company/registration-document', [
                'document' => UploadedFile::fake()->create('updated.pdf', 512, 'application/pdf'),
            ]);

        $response->assertOk();

        $company->refresh();

        Storage::disk('public')->assertMissing('company-registration-documents/old.pdf');
        Storage::disk('public')->assertExists($company->registration_document_url);
    }

    public function test_upload_registration_document_requires_company(): void
    {
        Storage::fake('public');
        $this->user->company()->delete();

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->postJson('/api/v1/employer/company/registration-document', [
                'document' => UploadedFile::fake()->create('registration.pdf', 512, 'application/pdf'),
            ]);

        $response->assertNotFound()
            ->assertJson([
                'success' => false,
                'message' => 'Company profile not found.',
            ]);
    }
}
