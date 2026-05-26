<?php

namespace App\Http\Controllers\Employers;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    protected function ensureEmployer(Request $request)
    {
        $user = $request->user();

        if (! $user || $user->role !== 'employer') {
            abort(403);
        }

        return $user;
    }

    public function index(Request $request)
    {
        $user = $this->ensureEmployer($request);

        $templates = EmailTemplate::accessibleBy($user)
            ->with('company')
            ->latest()
            ->paginate(12);

        return view('employers.email-templates.index', compact('templates'));
    }

    public function create(Request $request)
    {
        $user = $this->ensureEmployer($request);
        $companies = $user->companies()->verified()->get();
        $types = EmailTemplate::TYPES;
        $placeholders = EmailTemplate::AVAILABLE_PLACEHOLDERS;

        return view('employers.email-templates.create', compact('companies', 'types', 'placeholders'));
    }

    public function store(Request $request)
    {
        $user = $this->ensureEmployer($request);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_id' => 'nullable|exists:companies,id',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'type' => 'required|string|in:'.implode(',', array_keys(EmailTemplate::TYPES)),
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

        EmailTemplate::create($validated);

        return redirect()->route('employer.email-templates.index')
            ->with('status', 'Email template created successfully!');
    }

    public function edit(Request $request, EmailTemplate $emailTemplate)
    {
        $user = $this->ensureEmployer($request);

        if ($emailTemplate->user_id !== $user->id && ! $user->companies()->where('id', $emailTemplate->company_id)->exists()) {
            abort(403);
        }

        $companies = $user->companies()->verified()->get();
        $types = EmailTemplate::TYPES;
        $placeholders = EmailTemplate::AVAILABLE_PLACEHOLDERS;

        return view('employers.email-templates.edit', compact('emailTemplate', 'companies', 'types', 'placeholders'));
    }

    public function update(Request $request, EmailTemplate $emailTemplate)
    {
        $user = $this->ensureEmployer($request);

        if ($emailTemplate->user_id !== $user->id && ! $user->companies()->where('id', $emailTemplate->company_id)->exists()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_id' => 'nullable|exists:companies,id',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'type' => 'required|string|in:'.implode(',', array_keys(EmailTemplate::TYPES)),
            'is_default' => 'boolean',
        ]);

        if (! empty($validated['company_id'])) {
            $company = $user->companies()->find($validated['company_id']);
            if (! $company) {
                abort(403, 'You do not own this company.');
            }
        }

        $validated['is_default'] = $validated['is_default'] ?? false;

        $emailTemplate->update($validated);

        return redirect()->route('employer.email-templates.index')
            ->with('status', 'Email template updated successfully!');
    }

    public function destroy(Request $request, EmailTemplate $emailTemplate)
    {
        $user = $this->ensureEmployer($request);

        if ($emailTemplate->user_id !== $user->id && ! $user->companies()->where('id', $emailTemplate->company_id)->exists()) {
            abort(403);
        }

        $emailTemplate->delete();

        return response()->json(['success' => true]);
    }

    public function show(Request $request, EmailTemplate $emailTemplate)
    {
        $user = $this->ensureEmployer($request);

        if ($emailTemplate->user_id !== $user->id && ! $user->companies()->where('id', $emailTemplate->company_id)->exists()) {
            abort(403);
        }

        return response()->json($emailTemplate);
    }

    public function preview(Request $request, EmailTemplate $emailTemplate)
    {
        $user = $this->ensureEmployer($request);

        if ($emailTemplate->user_id !== $user->id && ! $user->companies()->where('id', $emailTemplate->company_id)->exists()) {
            abort(403);
        }

        $sampleData = [
            'applicant_name' => 'John Doe',
            'applicant_first_name' => 'John',
            'applicant_email' => 'john.doe@example.com',
            'job_title' => 'Senior Software Engineer',
            'company_name' => $emailTemplate->company?->name ?? 'Your Company',
            'employer_name' => $user->name,
            'application_status' => 'Under Review',
            'interview_date' => now()->addDays(3)->format('F j, Y'),
            'interview_time' => '10:00 AM',
            'interview_location' => 'Via Zoom',
            'today_date' => now()->format('F j, Y'),
        ];

        $rendered = $emailTemplate->render($sampleData);

        return response()->json([
            'subject' => $rendered['subject'],
            'body' => $rendered['body'],
        ]);
    }
}
