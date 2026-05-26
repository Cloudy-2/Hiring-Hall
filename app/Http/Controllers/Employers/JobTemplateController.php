<?php

namespace App\Http\Controllers\Employers;

use App\Http\Controllers\Controller;
use App\Models\DropdownOption;
use App\Models\JobTemplate;
use Illuminate\Http\Request;

class JobTemplateController extends Controller
{
    protected function ensureEmployer(Request $request)
    {
        $user = $request->user();

        if (! $user || $user->role !== 'employer') {
            abort(403);
        }

        return $user;
    }

    protected function getDropdownOptions(): array
    {
        return [
            'locations' => DropdownOption::active()->ofType('location')->ordered()->pluck('label', 'value')->toArray(),
            'categories' => DropdownOption::active()->ofType('job_category')->ordered()->pluck('label', 'value')->toArray(),
            'industryTypes' => DropdownOption::active()->ofType('industry_type')->ordered()->pluck('label', 'value')->toArray(),
            'recruiterTypes' => DropdownOption::active()->ofType('recruiter_type')->ordered()->pluck('label', 'value')->toArray(),
            'employmentTypes' => DropdownOption::active()->ofType('employment_type')->ordered()->pluck('label', 'value')->toArray(),
            'remoteTypes' => DropdownOption::active()->ofType('remote_type')->ordered()->pluck('label', 'value')->toArray(),
            'currencies' => DropdownOption::active()->ofType('currency')->ordered()->pluck('label', 'value')->toArray(),
            'workSetups' => DropdownOption::active()->ofType('work_setup')->ordered()->pluck('label', 'value')->toArray(),
            'shiftSchedules' => DropdownOption::active()->ofType('shift_schedule')->ordered()->pluck('label', 'value')->toArray(),
        ];
    }

    public function index(Request $request)
    {
        $user = $this->ensureEmployer($request);

        $templates = JobTemplate::accessibleBy($user)
            ->with('company')
            ->withCount('activeJobPostings')
            ->latest()
            ->paginate(12);

        return view('employers.templates.index', compact('templates'));
    }

    public function create(Request $request)
    {
        $user = $this->ensureEmployer($request);
        $companies = $user->companies()->verified()->get();
        $dropdownOptions = $this->getDropdownOptions();

        return view('employers.templates.create', compact('companies', 'dropdownOptions'));
    }

    public function store(Request $request)
    {
        $user = $this->ensureEmployer($request);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_id' => 'nullable|exists:companies,id',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'requirements' => 'nullable|string',
            'benefits' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'industry_type' => 'nullable|string|max:100',
            'recruiter_type' => 'nullable|string|max:100',
            'employment_type' => 'nullable|string|max:50',
            'remote_type' => 'nullable|string|max:50',
            'vacancies' => 'nullable|integer|min:1',
            'experience_min_years' => 'nullable|integer|min:0',
            'experience_max_years' => 'nullable|integer|min:0',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
            'salary_currency' => 'nullable|string|max:10',
            'location' => 'nullable|string|max:255',
            'highlight_work_setup' => 'nullable|string|max:100',
            'highlight_shift_schedule' => 'nullable|string|max:100',
            'is_default' => 'boolean',
        ]);

        if (! empty($validated['company_id'])) {
            $company = $user->companies()->find($validated['company_id']);
            if (! $company) {
                abort(403, 'You do not own this company.');
            }
        }

        $validated['user_id'] = $user->id;
        $validated['is_default'] = $validated['is_default'] ?? false;
        $validated['salary_currency'] = $validated['salary_currency'] ?? 'PHP';

        JobTemplate::create($validated);

        return redirect()->route('employer.templates.index')
            ->with('status', 'Job template created successfully!');
    }

    public function edit(Request $request, JobTemplate $template)
    {
        $user = $this->ensureEmployer($request);

        if ($template->user_id !== $user->id && ! $user->companies()->where('id', $template->company_id)->exists()) {
            abort(403);
        }

        $companies = $user->companies()->verified()->get();
        $dropdownOptions = $this->getDropdownOptions();

        return view('employers.templates.edit', compact('template', 'companies', 'dropdownOptions'));
    }

    public function update(Request $request, JobTemplate $template)
    {
        $user = $this->ensureEmployer($request);

        if ($template->user_id !== $user->id && ! $user->companies()->where('id', $template->company_id)->exists()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_id' => 'nullable|exists:companies,id',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'requirements' => 'nullable|string',
            'benefits' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'industry_type' => 'nullable|string|max:100',
            'recruiter_type' => 'nullable|string|max:100',
            'employment_type' => 'nullable|string|max:50',
            'remote_type' => 'nullable|string|max:50',
            'vacancies' => 'nullable|integer|min:1',
            'experience_min_years' => 'nullable|integer|min:0',
            'experience_max_years' => 'nullable|integer|min:0',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
            'salary_currency' => 'nullable|string|max:10',
            'location' => 'nullable|string|max:255',
            'highlight_work_setup' => 'nullable|string|max:100',
            'highlight_shift_schedule' => 'nullable|string|max:100',
            'is_default' => 'boolean',
        ]);

        if (! empty($validated['company_id'])) {
            $company = $user->companies()->find($validated['company_id']);
            if (! $company) {
                abort(403, 'You do not own this company.');
            }
        }

        $validated['is_default'] = $validated['is_default'] ?? false;

        $template->update($validated);

        return redirect()->route('employer.templates.index')
            ->with('status', 'Job template updated successfully!');
    }

    public function destroy(Request $request, JobTemplate $template)
    {
        $user = $this->ensureEmployer($request);

        if ($template->user_id !== $user->id && ! $user->companies()->where('id', $template->company_id)->exists()) {
            abort(403);
        }

        $template->delete();

        return response()->json(['success' => true]);
    }

    public function show(Request $request, JobTemplate $template)
    {
        $user = $this->ensureEmployer($request);

        if ($template->user_id !== $user->id && ! $user->companies()->where('id', $template->company_id)->exists()) {
            abort(403);
        }

        return response()->json($template);
    }
}
