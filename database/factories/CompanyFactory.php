<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->company();

        return [
            'user_id' => User::factory()->create(['role' => 'employer'])->id,
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->randomNumber(5),
            'location' => fake()->city().', '.fake()->country(),
            'industry' => fake()->randomElement(['Technology', 'Healthcare', 'Finance', 'Education', 'Retail']),
            'description' => fake()->paragraph(),
            'website' => fake()->url(),
            'email' => fake()->companyEmail(),
            'phone' => fake()->phoneNumber(),
            'contact_name' => fake()->name(),
            'contact_position' => fake()->jobTitle(),
            'established_year' => fake()->year(),
            'employees_count' => fake()->randomElement(['1-10', '11-50', '51-200', '201-500', '500+']),
            // Verification fields
            'registration_type' => fake()->randomElement(['SEC', 'DTI', 'BIR', 'Other']),
            'registration_number' => fake()->bothify('??####-######'),
            'registration_document_url' => 'company-documents/sample-document.pdf',
            // Business address
            'business_address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'province' => fake()->state(),
            'postal_code' => fake()->postcode(),
            'country' => 'Philippines',
            // Status
            'verification_status' => Company::STATUS_PENDING,
            'onboarding_completed_at' => now(),
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'verification_status' => Company::STATUS_APPROVED,
            'verified' => true,
            'verified_at' => now(),
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'verification_status' => Company::STATUS_PENDING,
            'verified' => false,
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'verification_status' => Company::STATUS_REJECTED,
            'verified' => false,
            'rejection_reason' => fake()->sentence(),
        ]);
    }

    public function forUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }
}
