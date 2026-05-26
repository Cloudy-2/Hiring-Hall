<?php

namespace App\Http\Controllers\Employers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CompanyController extends Controller
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

        $companies = Company::forUser($user->id)
            ->latest()
            ->paginate(10);

        return view('employers.companies.index', compact('companies'));
    }

    public function create(Request $request)
    {
        $user = $this->ensureEmployer($request);

        return view('employers.companies.create');
    }

    public function store(Request $request)
    {
        $user = $this->ensureEmployer($request);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'industry' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'website' => 'nullable|url|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'established_year' => 'nullable|integer|min:1800|max:'.date('Y'),
            'employees_count' => 'nullable|integer|min:1',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
            // Verification fields
            'registration_type' => 'required|in:SEC,DTI,BIR,Other',
            'registration_number' => 'required|string|max:50',
            'registration_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            // Business address
            'business_address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'required|string|max:100',
            'terms_agreed' => 'required|in:1',
        ], [
            'terms_agreed.required' => 'You must accept the Agreement and Terms before registering your company.',
            'terms_agreed.in' => 'You must accept the Agreement and Terms before registering your company.',
        ]);

        $slug = Str::slug($validated['name']);
        $originalSlug = $slug;
        $counter = 1;
        while (Company::where('slug', $slug)->exists()) {
            $slug = $originalSlug.'-'.$counter++;
        }

        $logoUrl = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('company-logos', 'public');
            $logoUrl = '/storage/'.$logoPath;
        }

        $registrationDocumentUrl = null;
        if ($request->hasFile('registration_document')) {
            $registrationDocumentUrl = $request->file('registration_document')->store('company-documents', 'public');
        }

        $company = Company::create([
            'user_id' => $user->id,
            'name' => $validated['name'],
            'slug' => $slug,
            'logo_url' => $logoUrl,
            'location' => $validated['location'] ?? null,
            'industry' => $validated['industry'] ?? null,
            'description' => $validated['description'] ?? null,
            'website' => $validated['website'] ?? null,
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'established_year' => $validated['established_year'] ?? null,
            'employees_count' => $validated['employees_count'] ?? null,
            // Verification fields
            'registration_type' => $validated['registration_type'],
            'registration_number' => $validated['registration_number'],
            'registration_document_url' => $registrationDocumentUrl,
            // Business address
            'business_address' => $validated['business_address'],
            'city' => $validated['city'],
            'province' => $validated['province'],
            'postal_code' => $validated['postal_code'] ?? null,
            'country' => $validated['country'],
            'terms_agreed_at' => now(),
            // Status
            'verification_status' => Company::STATUS_PENDING,
            'verified' => false,
        ]);

        return redirect()->route('employer.companies.index')
            ->with('status', 'Company registered successfully! It will be reviewed by our team.');
    }

    public function edit(Request $request, Company $company)
    {
        $user = $this->ensureEmployer($request);

        if ($company->user_id !== $user->id) {
            abort(403);
        }

        return view('employers.companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $user = $this->ensureEmployer($request);

        if ($company->user_id !== $user->id) {
            abort(403);
        }

        // Registration document is required only if not already uploaded
        $documentRequired = $company->registration_document_url ? 'nullable' : 'required';

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'industry' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'website' => 'nullable|url|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'established_year' => 'nullable|integer|min:1800|max:'.date('Y'),
            'employees_count' => 'nullable|integer|min:1',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
            // Verification fields
            'registration_type' => 'required|in:SEC,DTI,BIR,Other',
            'registration_number' => 'required|string|max:50',
            'registration_document' => $documentRequired.'|file|mimes:pdf,jpg,jpeg,png|max:10240',
            // Business address
            'business_address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'required|string|max:100',
        ]);

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('company-logos', 'public');
            $company->logo_url = '/storage/'.$logoPath;
        }

        if ($request->hasFile('registration_document')) {
            $company->registration_document_url = $request->file('registration_document')->store('company-documents', 'public');
        }

        $company->fill([
            'name' => $validated['name'],
            'location' => $validated['location'] ?? null,
            'industry' => $validated['industry'] ?? null,
            'description' => $validated['description'] ?? null,
            'website' => $validated['website'] ?? null,
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'established_year' => $validated['established_year'] ?? null,
            'employees_count' => $validated['employees_count'] ?? null,
            // Verification fields
            'registration_type' => $validated['registration_type'],
            'registration_number' => $validated['registration_number'],
            // Business address
            'business_address' => $validated['business_address'],
            'city' => $validated['city'],
            'province' => $validated['province'],
            'postal_code' => $validated['postal_code'] ?? null,
            'country' => $validated['country'],
        ]);

        // If company was rejected and is being updated, set back to pending
        if ($company->isRejected()) {
            $company->verification_status = Company::STATUS_PENDING;
            $company->rejection_reason = null;
        }

        $company->save();

        return redirect()->route('employer.companies.index')
            ->with('status', 'Company updated successfully!');
    }
}
