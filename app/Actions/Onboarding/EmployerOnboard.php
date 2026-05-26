<?php

namespace App\Actions\Onboarding;

use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Str;

class EmployerOnboard
{
    /**
     * Handle employer onboarding step updates and create company if missing.
     */
    public function handle(User $user, array $data): Company
    {
        $company = $user->company;

        if (! $company) {
            $name = trim((string) $user->name) !== '' ? $user->name."'s Company" : 'New Company';
            $slugBase = Str::slug($name) ?: 'company-'.$user->id;
            $slug = $slugBase;
            $suffix = 2;

            while (Company::where('slug', $slug)->exists()) {
                $slug = $slugBase.'-'.$suffix;
                $suffix++;
            }

            $company = Company::create([
                'user_id' => $user->id,
                'name' => $name,
                'slug' => $slug,
                'onboarding_step' => 1,
                'verification_status' => Company::STATUS_PENDING,
            ]);
        }

        $step = isset($data['step']) ? (int) $data['step'] : $company->onboarding_step;
        $company->onboarding_step = $step;

        if ($step >= 4 && ! $company->isOnboarded()) {
            $company->onboarding_completed_at = now();
        }

        $company->save();

        return $company;
    }
}
