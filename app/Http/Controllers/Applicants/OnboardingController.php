<?php

namespace App\Http\Controllers\Applicants;

use App\Http\Controllers\Controller;
use App\Models\ApplicantProfile;
use App\Services\DropdownService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OnboardingController extends Controller
{
    protected const TOTAL_STEPS = 5;

    protected array $stepTitles = [
        1 => 'Welcome & Basics',
        2 => 'Skills & Expertise',
        3 => 'Experience & Resume',
        4 => 'Career & Details',
        5 => 'Review & Launch',
    ];

    /**
     * Show the given onboarding step.
     */
    public function show(Request $request, int $step = 1)
    {
        $user = $request->user();

        if (! $user || ! $user->isApplicant()) {
            abort(403);
        }

        $profile = ApplicantProfile::firstOrCreate(
            ['user_id' => $user->id],
            ['display_name' => $user->name],
        );

        if ($profile->isOnboarded()) {
            return redirect()->route('applicant.dashboard');
        }

        $step = max(1, min(self::TOTAL_STEPS, $step));

        $dropdownOptions = DropdownService::getApplicantProfileOptions();

        return view("applicants.onboarding.step-{$step}", [
            'user' => $user,
            'profile' => $profile,
            'dropdownOptions' => $dropdownOptions,
            'step' => $step,
            'totalSteps' => self::TOTAL_STEPS,
            'stepTitles' => $this->stepTitles,
        ]);
    }

    /**
     * Store data for the given step and advance.
     */
    public function storeStep(Request $request, int $step)
    {
        $user = $request->user();

        if (! $user || ! $user->isApplicant()) {
            abort(403);
        }

        if ($step === 5) {
            $request->validate([
                'terms_agreed' => ['required', 'in:1'],
            ], [
                'terms_agreed.required' => 'You must accept the Terms and Agreement for data sharing before launching your profile.',
                'terms_agreed.in' => 'You must accept the Terms and Agreement for data sharing before launching your profile.',
            ]);
        }

        $profile = ApplicantProfile::firstOrCreate(
            ['user_id' => $user->id],
            ['display_name' => $user->name],
        );

        match ($step) {
            1 => $this->processStep1($request, $profile),
            2 => $this->processStep2($request, $profile),
            3 => $this->processStep3($request, $profile),
            4 => $this->processStep4($request, $profile),
            5 => $this->processStep5($request, $profile),
            default => null,
        };

        $profile->onboarding_step = $step;
        $profile->save();

        if ($step >= self::TOTAL_STEPS) {
            $profile->onboarding_completed_at = now();
            $profile->save();

            $request->user()->update(['terms_shared_data_at' => now()]);

            return redirect()->route('applicant.dashboard')
                ->with('status', '🎉 Welcome aboard! Your profile is live.');
        }

        return redirect()->route('applicant.onboarding.show', ['step' => $step + 1]);
    }

    protected function processStep1(Request $request, ApplicantProfile $profile): void
    {
        $data = $request->validate([
            'display_name' => ['required', 'string', 'max:255'],
            'job_title' => ['required', 'string', 'max:100'],
            'location' => ['required', 'string', 'max:255'],
            'work_mode' => ['required', 'string', 'max:100'],
            'headline' => ['required', 'string', 'max:500'],
            'about' => ['nullable', 'string'],
        ]);

        $profile->fill($data);

        // Store selected job title role as expertise_categories
        if (! empty($data['job_title'])) {
            $profile->expertise_categories = json_encode([$data['job_title']]);
        }
    }

    protected function processStep2(Request $request, ApplicantProfile $profile): void
    {
        $data = $request->validate([
            'specializations' => ['nullable', 'array'],
            'specializations.*' => ['nullable', 'string', 'max:100'],
            'skills' => ['nullable', 'array'],
            'skills.*' => ['nullable', 'string'],
            'tools_used' => ['nullable', 'array'],
            'tools_used.*' => ['nullable', 'string'],
            'languages' => ['nullable', 'array'],
            'languages.*' => ['nullable', 'string'],
        ]);

        $skills = array_values(array_filter(array_map('trim', $data['skills'] ?? []), fn ($v) => $v !== ''));
        $tools = array_values(array_filter(array_map('trim', $data['tools_used'] ?? []), fn ($v) => $v !== ''));
        $languages = array_values(array_filter(array_map('trim', $data['languages'] ?? []), fn ($v) => $v !== ''));
        $expertise = array_values(array_filter(array_map('trim', $data['specializations'] ?? []), fn ($v) => $v !== ''));

        $profile->skills = ! empty($skills) ? json_encode($skills) : null;
        $profile->tools_used = ! empty($tools) ? json_encode($tools) : null;
        $profile->languages = ! empty($languages) ? json_encode($languages) : null;

        // Merge with existing expertise_categories from Step 1 (job title role)
        $existingExpertise = json_decode($profile->expertise_categories, true) ?? [];
        $allExpertise = array_unique(array_merge($existingExpertise, $expertise));
        $profile->expertise_categories = ! empty($allExpertise) ? json_encode(array_values($allExpertise)) : null;
    }

    protected function processStep3(Request $request, ApplicantProfile $profile): void
    {
        $data = $request->validate([
            'years_experience' => ['required', 'integer', 'min:0'],
            'degree' => ['nullable', 'string', 'max:255'],
            'availability' => ['required', 'string', 'max:100'],
            'job_type' => ['required', 'string', 'max:100'],
            'expected_salary_min' => ['nullable', 'numeric', 'min:0'],
            'expected_salary_max' => ['nullable', 'numeric', 'min:0'],
            'salary_currency' => ['nullable', 'string', 'max:3'],
            'cv_file' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
            'experience_position' => ['nullable', 'string', 'max:255'],
            'experience_company' => ['nullable', 'string', 'max:255'],
            'experience_location' => ['nullable', 'string', 'max:255'],
            'experience_start' => ['nullable', 'date'],
            'experience_end' => ['nullable', 'date'],
            'experience_current' => ['nullable', 'boolean'],
        ]);

        if (isset($data['expected_salary_min'], $data['expected_salary_max'])
            && $data['expected_salary_min'] !== null
            && $data['expected_salary_max'] !== null
            && (float) $data['expected_salary_min'] > (float) $data['expected_salary_max']) {
            throw ValidationException::withMessages([
                'expected_salary_min' => 'Minimum salary cannot be higher than maximum salary.',
                'expected_salary_max' => 'Maximum salary cannot be lower than minimum salary.',
            ]);
        }

        if (! empty($data['experience_start'])
            && ! empty($data['experience_end'])
            && strtotime((string) $data['experience_end']) < strtotime((string) $data['experience_start'])) {
            throw ValidationException::withMessages([
                'experience_start' => 'Start date cannot be later than end date.',
                'experience_end' => 'End date cannot be earlier than start date.',
            ]);
        }

        $profile->years_experience = $data['years_experience'];
        $profile->degree = $data['degree'] ?? null;
        $profile->availability = $data['availability'];
        $profile->job_type = $data['job_type'];
        $profile->expected_salary_min = $data['expected_salary_min'] ?? null;
        $profile->expected_salary_max = $data['expected_salary_max'] ?? null;
        $profile->salary_currency = $data['salary_currency'] ?? 'USD';

        if ($request->hasFile('cv_file')) {
            if ($profile->cv_path && \Storage::exists($profile->cv_path)) {
                \Storage::delete($profile->cv_path);
            }
            $profile->cv_path = $request->file('cv_file')->store('candidate-cvs', 'public');
        }

        $hasExp = ! empty($data['experience_position']) || ! empty($data['experience_company']);
        if ($hasExp) {
            $endDate = $data['experience_end'] ?? null;
            if (! empty($data['experience_current'])) {
                $endDate = null;
            }
            $profile->experience_overview = json_encode([
                'position' => $data['experience_position'] ?? null,
                'company' => $data['experience_company'] ?? null,
                'location' => $data['experience_location'] ?? null,
                'start_date' => $data['experience_start'] ?? null,
                'end_date' => $endDate,
                'responsibilities' => [],
            ]);
        }
    }

    protected function processStep4(Request $request, ApplicantProfile $profile): void
    {
        $data = $request->validate([
            'career_objective' => ['nullable', 'string'],
            'education' => ['nullable', 'array'],
            'education.*.course' => ['nullable', 'string', 'max:255'],
            'education.*.school' => ['nullable', 'string', 'max:255'],
            'education.*.start_year' => ['nullable', 'string', 'max:10'],
            'education.*.end_year' => ['nullable', 'string', 'max:10'],
            'certifications' => ['nullable', 'array'],
            'certifications.*.title' => ['nullable', 'string', 'max:255'],
            'certifications.*.provider' => ['nullable', 'string', 'max:255'],
            'key_achievements' => ['nullable', 'array'],
            'key_achievements.*' => ['nullable', 'string'],
            'social_links' => ['nullable', 'array'],
            'social_links.*' => ['nullable', 'url', 'max:500'],
        ]);

        $profile->career_objective = $data['career_objective'] ?? null;

        // Education
        $education = array_filter($data['education'] ?? [], fn ($e) => ! empty($e['course']) || ! empty($e['school']));
        $profile->education_details = ! empty($education) ? json_encode(array_values($education)) : null;

        // Certifications
        $certs = array_filter($data['certifications'] ?? [], fn ($c) => ! empty($c['title']));
        $profile->certifications = ! empty($certs) ? json_encode(array_values($certs)) : null;

        // Key Achievements
        $achievements = array_values(array_filter(array_map('trim', $data['key_achievements'] ?? []), fn ($v) => $v !== ''));
        $profile->key_achievements = ! empty($achievements) ? json_encode($achievements) : null;

        // Social Links
        $socials = array_filter($data['social_links'] ?? [], fn ($v) => ! empty($v));
        $profile->social_links = ! empty($socials) ? json_encode($socials) : null;
    }

    protected function processStep5(Request $request, ApplicantProfile $profile): void
    {
        //
    }
}
