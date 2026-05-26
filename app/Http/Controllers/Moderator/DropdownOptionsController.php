<?php

namespace App\Http\Controllers\Moderator;

use App\Http\Controllers\Controller;
use App\Models\DropdownOption;
use Illuminate\Http\Request;

class DropdownOptionsController extends Controller
{
    protected function ensureStaffAccess(Request $request)
    {
        $user = $request->user();
        $allowedRoles = ['moderator', 'admin', 'super_admin'];

        if (! $user || ! in_array($user->role, $allowedRoles)) {
            abort(403, 'Access denied. Staff role required.');
        }

        return $user;
    }

    /**
     * Job Form Options page
     */
    public function jobFormOptions(Request $request)
    {
        $user = $this->ensureStaffAccess($request);

        $optionTypes = [
            'job_category' => ['label' => 'Job Categories', 'icon' => 'ri-folder-line'],
            'employment_type' => ['label' => 'Employment Types', 'icon' => 'ri-briefcase-line'],
            'remote_type' => ['label' => 'Remote Types', 'icon' => 'ri-remote-control-line'],
            'currency' => ['label' => 'Currencies', 'icon' => 'ri-money-dollar-circle-line'],
            'work_setup' => ['label' => 'Work Setup', 'icon' => 'ri-building-line'],
            'shift_schedule' => ['label' => 'Shift Schedules', 'icon' => 'ri-time-line'],
            'industry_type' => ['label' => 'Industry Types', 'icon' => 'ri-building-4-line'],
            'recruiter_type' => ['label' => 'Recruiter Types', 'icon' => 'ri-user-search-line'],
            'location' => ['label' => 'Locations', 'icon' => 'ri-map-pin-line'],
        ];

        $options = [];
        foreach ($optionTypes as $type => $config) {
            $options[$type] = DropdownOption::ofType($type)->ordered()->get();
        }

        return view('moderator.job-form-options', [
            'user' => $user,
            'optionTypes' => $optionTypes,
            'options' => $options,
        ]);
    }

    /**
     * Applicant Profile Options page
     */
    public function applicantProfileOptions(Request $request)
    {
        $user = $this->ensureStaffAccess($request);

        $optionTypes = [
            'applicant_title' => ['label' => 'Title / Role', 'icon' => 'ri-user-star-line'],
            'work_mode' => ['label' => 'Work Mode', 'icon' => 'ri-home-office-line'],
            'availability' => ['label' => 'Availability', 'icon' => 'ri-calendar-check-line'],
            'job_type' => ['label' => 'Job Type', 'icon' => 'ri-briefcase-4-line'],
            'expertise_category' => ['label' => 'Expertise / Categories', 'icon' => 'ri-award-line'],
            'language' => ['label' => 'Languages', 'icon' => 'ri-translate-2'],
        ];

        $options = [];
        foreach ($optionTypes as $type => $config) {
            $options[$type] = DropdownOption::ofType($type)->ordered()->get();
        }

        return view('moderator.applicant-profile-options', [
            'user' => $user,
            'optionTypes' => $optionTypes,
            'options' => $options,
        ]);
    }

    /**
     * Store a new option
     */
    public function store(Request $request)
    {
        $this->ensureStaffAccess($request);

        $data = $request->validate([
            'type' => ['required', 'string', 'max:100'],
            'value' => ['required', 'string', 'max:255'],
            'label' => ['required', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ], [
            'value.required' => 'Enter an internal value (e.g. full_time).',
            'value.max' => 'Internal value must be 255 characters or less.',
            'label.required' => 'Enter the text shown to users in the dropdown.',
            'label.max' => 'Label must be 255 characters or less.',
        ]);

        $option = DropdownOption::create([
            'type' => $data['type'],
            'value' => $data['value'],
            'label' => $data['label'],
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => true,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['status' => 'ok', 'option' => $option]);
        }

        return back()->with('status', 'Option added successfully.');
    }

    /**
     * Update an option
     */
    public function update(Request $request, DropdownOption $option)
    {
        $this->ensureStaffAccess($request);

        $data = $request->validate([
            'value' => ['sometimes', 'string', 'max:255'],
            'label' => ['sometimes', 'string', 'max:255'],
            'sort_order' => ['sometimes', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ], [
            'value.max' => 'Internal value must be 255 characters or less.',
            'label.max' => 'Label must be 255 characters or less.',
        ]);

        $option->update($data);

        if ($request->wantsJson()) {
            return response()->json(['status' => 'ok', 'option' => $option]);
        }

        return back()->with('status', 'Option updated successfully.');
    }

    /**
     * Delete an option
     */
    public function destroy(Request $request, DropdownOption $option)
    {
        $this->ensureStaffAccess($request);

        $option->delete();

        if ($request->wantsJson()) {
            return response()->json(['status' => 'ok']);
        }

        return back()->with('status', 'Option deleted successfully.');
    }
}
