<?php

namespace App\Http\Controllers\Moderator;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Notifications\CompanyVerificationNotification;
use Illuminate\Http\Request;

class CompanyVerificationController extends Controller
{
    protected function ensureModerator(Request $request)
    {
        $user = $request->user();

        if (! $user || ! $user->isModerator()) {
            abort(403);
        }

        return $user;
    }

    public function index(Request $request)
    {
        $this->ensureModerator($request);

        $status = $request->input('status', 'pending');
        $search = $request->input('search');

        $query = Company::with('user');

        if ($status === 'pending') {
            $query->pending();
        } elseif ($status === 'approved') {
            $query->approved();
        } elseif ($status === 'rejected') {
            $query->where('verification_status', Company::STATUS_REJECTED);
        }

        if (! empty($search)) {
            $term = '%'.trim($search).'%';
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', $term)
                    ->orWhere('industry', 'like', $term)
                    ->orWhere('website', 'like', $term)
                    ->orWhereHas('user', function ($uq) use ($term) {
                        $uq->where('name', 'like', $term)
                            ->orWhere('email', 'like', $term);
                    });
            });
        }

        $companies = $query->latest()->paginate(15)->withQueryString();

        $counts = [
            'pending' => Company::pending()->count(),
            'approved' => Company::approved()->count(),
            'rejected' => Company::where('verification_status', Company::STATUS_REJECTED)->count(),
        ];

        return view('moderator.companies.index', compact('companies', 'status', 'counts', 'search'));
    }

    public function show(Request $request, Company $company)
    {
        $this->ensureModerator($request);

        $company->load(['user', 'verifiedByUser', 'jobPostings']);

        return view('moderator.companies.show', compact('company'));
    }

    public function agreement(Request $request, Company $company)
    {
        $this->ensureModerator($request);

        $agencyName = config('agency.name');
        $agencyAddress = config('agency.address');
        $companyAddress = implode(', ', array_filter([
            $company->business_address,
            $company->city,
            $company->province,
            $company->postal_code,
            $company->country,
        ]));

        return view('moderator.companies.agreement', [
            'company' => $company,
            'agencyName' => $agencyName,
            'agencyAddress' => $agencyAddress,
            'companyAddress' => $companyAddress ?: '—',
        ]);
    }

    public function approve(Request $request, Company $company)
    {
        $user = $this->ensureModerator($request);

        $company->update([
            'verification_status' => Company::STATUS_APPROVED,
            'verified' => true,
            'verified_at' => now(),
            'verified_by' => $user->id,
            'rejection_reason' => null,
        ]);

        // Notify the employer
        if ($company->user) {
            $company->user->notify(new CompanyVerificationNotification($company, 'approved'));
        }

        return redirect()->back()->with('status', 'Company "'.$company->name.'" has been approved.');
    }

    public function reject(Request $request, Company $company)
    {
        $user = $this->ensureModerator($request);

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ], [
            'rejection_reason.required' => 'Please provide a reason for rejection so the company owner can address it.',
            'rejection_reason.max' => 'Rejection reason must be 500 characters or less.',
        ]);

        $company->update([
            'verification_status' => Company::STATUS_REJECTED,
            'verified' => false,
            'verified_at' => null,
            'verified_by' => $user->id,
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        // Notify the employer
        if ($company->user) {
            $company->user->notify(new CompanyVerificationNotification($company, 'rejected', $validated['rejection_reason']));
        }

        return redirect()->back()->with('status', 'Company "'.$company->name.'" has been rejected.');
    }
}
