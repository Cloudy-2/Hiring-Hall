<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, mixed>  $input
     */
    public function update(User $user, array $input): void
    {
        $input['phone'] = $this->sanitizePhone($input['phone'] ?? null);

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png,webp,gif,bmp,svg', 'max:10240'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', 'string', 'max:20'],
            'phone' => ['nullable', 'string', 'max:50', 'regex:/^\+?[0-9()\-\s]*$/'],
            'marital_status' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string'],
            'social_facebook' => ['nullable', 'string', 'max:255'],
            'social_twitter' => ['nullable', 'string', 'max:255'],
            'social_instagram' => ['nullable', 'string', 'max:255'],
            'social_github' => ['nullable', 'string', 'max:255'],
            'social_youtube' => ['nullable', 'string', 'max:255'],
        ])->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        if ($input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill([
                'name' => $input['name'],
                'email' => $input['email'],
                'date_of_birth' => $input['date_of_birth'] ?? null,
                'gender' => $input['gender'] ?? null,
                'phone' => $input['phone'] ?? null,
                'marital_status' => $input['marital_status'] ?? null,
                'address' => $input['address'] ?? null,
                'social_facebook' => $input['social_facebook'] ?? null,
                'social_twitter' => $input['social_twitter'] ?? null,
                'social_instagram' => $input['social_instagram'] ?? null,
                'social_github' => $input['social_github'] ?? null,
                'social_youtube' => $input['social_youtube'] ?? null,
            ])->save();
        }

        $this->syncApplicantLocationFromAddress($user, $input);
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, string>  $input
     */
    protected function updateVerifiedUser(User $user, array $input): void
    {
        $user->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }

    /**
     * Keep applicant_profiles.location in sync with profile address updates.
     *
     * @param  array<string, mixed>  $input
     */
    protected function syncApplicantLocationFromAddress(User $user, array $input): void
    {
        if ($user->role !== 'applicant') {
            return;
        }

        if (! array_key_exists('address', $input)) {
            return;
        }

        $location = trim((string) ($input['address'] ?? ''));

        $user->applicantProfile()->updateOrCreate(
            ['user_id' => $user->id],
            ['location' => $location !== '' ? $location : null]
        );
    }

    private function sanitizePhone(mixed $phone): ?string
    {
        if ($phone === null) {
            return null;
        }

        $value = trim((string) $phone);
        if ($value === '') {
            return null;
        }

        // Keep only phone-safe characters and normalize + at the beginning.
        $value = preg_replace('/[^0-9+()\-\s]/', '', $value) ?? '';
        $value = preg_replace('/(?!^)\+/', '', $value) ?? '';

        return trim($value) !== '' ? trim($value) : null;
    }
}
