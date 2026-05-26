<?php

namespace App\Http\Controllers\Employers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Services\DropdownService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployerOnboardingController extends Controller
{
    public function show(Request $request, $step = 1)
    {
        $user = $request->user();
        if ($user->role !== 'employer') {
            abort(403);
        }

        $company = $user->company;

        // If no company and not on step 1, redirect to step 1
        if (! $company && $step != 1) {
            return redirect()->route('employer.onboarding.show', ['step' => 1]);
        }

        // If onboarding complete, redirect to dashboard
        if ($company && $company->isOnboarded()) {
            return redirect()->route('employer.dashboard');
        }

        // Enforce sequential progress ?
        // For now, let's allow jumping back, but jumping forward only if company exists.
        // If company exists, we allow checking any step up to current + 1.
        // Simplified: just show the requested step.

        $view = "employers.onboarding.step-{$step}";

        if (! view()->exists($view)) {
            abort(404);
        }

        $data = [
            'step' => $step,
            'company' => $company,
        ];

        if ($step == 1) {
            $data = array_merge($data, DropdownService::getJobPostingOptions()); // Contains industryTypes, locations, etc.
        }

        return view($view, $data);
    }

    public function storeStep(Request $request, $step)
    {
        $user = $request->user();
        $company = $user->company;

        // Step 1: Company Basics
        if ($step == 1) {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'industry' => ['required', 'string', 'max:255'],
                'organization_type' => ['nullable', 'string', 'max:255'], // Recruiter Type?
                'website' => ['nullable', 'url', 'max:255'],
                'established_year' => ['nullable', 'integer', 'min:1800', 'max:'.(date('Y') + 1)],
                'employees_count' => ['nullable', 'string', 'max:255'],
            ]);

            if (! $company) {
                $company = new Company;
                $company->user_id = $user->id;
            }

            $company->name = $validated['name'];
            $company->industry = $validated['industry'];
            $company->website = $validated['website'] ?? null;
            $company->established_year = $validated['established_year'] ?? null;
            $company->employees_count = $validated['employees_count'] ?? null;
            // $company->recruiter_type = $validated['organization_type'] ?? null; // If column exists? Checking Model...

            // Generate slug if new or name changed
            if ($company->isDirty('name')) {
                $company->slug = \Illuminate\Support\Str::slug($validated['name']).'-'.uniqid();
            }

            $company->onboarding_step = 2;
            $company->save();

            return redirect()->route('employer.onboarding.show', ['step' => 2]);
        }

        // Step 2: Company information & Contact Person
        if ($step == 2) {
            $availabilityOptions = [
                'Mon–Fri 9am–5pm EST',
                'Mon–Fri 8am–4pm CST',
                'Mon–Fri 9am–6pm',
                'Weekdays 8am–5pm',
                'Flexible',
                'By appointment',
            ];

            $validated = $request->validate([
                'location' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255'],
                'phone' => ['required', 'string', 'max:255'],
                'contact_name' => ['required', 'string', 'max:255'],
                'contact_position' => ['required', 'string', 'max:255'],
                'contact_availability_time' => ['required', 'string', 'max:255', 'in:'.implode(',', $availabilityOptions)],
                'contact_person_email' => ['required', 'email', 'max:255'],
                'contact_person_phone' => ['required', 'string', 'max:255'],
            ]);

            if (! $company) {
                return redirect()->route('employer.onboarding.show', ['step' => 1]);
            }

            $company->location = $validated['location'];
            $company->email = $validated['email'];
            $company->phone = $validated['phone'];
            $company->contact_name = $validated['contact_name'];
            $company->contact_position = $validated['contact_position'];
            $company->contact_availability_time = $validated['contact_availability_time'];
            $company->contact_person_email = $validated['contact_person_email'];
            $company->contact_person_phone = $validated['contact_person_phone'];

            $company->onboarding_step = 3;
            $company->save();

            return redirect()->route('employer.onboarding.show', ['step' => 3]);
        }

        // Step 3: Media & Overview
        if ($step == 3) {
            $validated = $request->validate([
                'description' => ['required', 'string', 'min:20'],
                'logo' => ['nullable', 'image', 'max:5120'],
            ]);

            if (! $company) {
                return redirect()->route('employer.onboarding.show', ['step' => 1]);
            }

            $company->description = $validated['description'];

            if ($request->hasFile('logo')) {
                $path = $request->file('logo')->store('company-logos', 'public');
                $company->logo_url = Storage::url($path);
            }

            $company->onboarding_step = 4;
            $company->save();

            return redirect()->route('employer.onboarding.show', ['step' => 4]);
        }

        // Step 4: Complete (requires terms agreement)
        if ($step == 4) {
            $request->validate([
                'terms_agreed' => ['required', 'in:1'],
            ], [
                'terms_agreed.required' => 'You must accept the Terms and Agreement for data sharing before finishing setup.',
                'terms_agreed.in' => 'You must accept the Terms and Agreement for data sharing before finishing setup.',
            ]);

            if (! $company) {
                return redirect()->route('employer.onboarding.show', ['step' => 1]);
            }

            $company->onboarding_completed_at = now();
            $company->save();

            $request->user()->update(['terms_shared_data_at' => now()]);

            return redirect()->route('employer.dashboard');
        }

        return back();
    }
}
