<?php

namespace App\Actions\Onboarding;

use App\Models\ApplicantProfile;
use App\Models\User;

class ApplicantOnboard
{
    /**
     * Handle applicant onboarding step updates.
     * Returns the updated ApplicantProfile.
     */
    public function handle(User $user, array $data): ApplicantProfile
    {
        $profile = $user->applicantProfile;

        if (! $profile) {
            $profile = ApplicantProfile::create([
                'user_id' => $user->id,
                'display_name' => $user->name,
                'onboarding_step' => 1,
            ]);
        }

        $step = isset($data['step']) ? (int) $data['step'] : $profile->onboarding_step;
        $profile->onboarding_step = $step;

        // If the caller provided explicit consent for shared data, record it on the user
        if (! empty($data['terms_agreed'])) {
            $user->terms_shared_data_at = $user->terms_shared_data_at ?? now();
            $user->save();
        }

        // Mark onboarding complete for applicants at step 5 or greater
        if ($step >= 5 && ! $profile->isOnboarded()) {
            $profile->onboarding_completed_at = now();
        }

        $profile->save();

        return $profile;
    }
}
