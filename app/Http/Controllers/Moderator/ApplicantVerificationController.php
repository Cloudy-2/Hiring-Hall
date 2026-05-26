<?php

namespace App\Http\Controllers\Moderator;

use App\Http\Controllers\Controller;
use App\Models\ApplicantProfile;
use Illuminate\Http\Request;

class ApplicantVerificationController extends Controller
{
    protected function ensureModerator(Request $request)
    {
        $user = $request->user();

        if (! $user || ! in_array($user->role, ['moderator', 'admin', 'super_admin'])) {
            abort(403);
        }

        return $user;
    }

    public function index(Request $request)
    {
        $this->ensureModerator($request);

        $status = $request->input('status', 'pending');
        $search = $request->input('search');

        $query = ApplicantProfile::with(['user', 'verifiedByUser'])
            ->whereHas('user');

        if ($status === 'pending') {
            $query->pending();
        } elseif ($status === 'verified') {
            $query->verified();
        } elseif ($status === 'rejected') {
            $query->rejected();
        }

        if (! empty($search)) {
            $term = '%'.trim($search).'%';
            $query->where(function ($q) use ($term) {
                $q->where('display_name', 'like', $term)
                    ->orWhere('job_title', 'like', $term)
                    ->orWhere('title', 'like', $term)
                    ->orWhereHas('user', function ($uq) use ($term) {
                        $uq->where('name', 'like', $term)
                            ->orWhere('email', 'like', $term);
                    });
            });
        }

        $applicants = $query->latest()->paginate(15)->withQueryString();

        $counts = [
            'pending' => ApplicantProfile::pending()->count(),
            'verified' => ApplicantProfile::verified()->count(),
            'rejected' => ApplicantProfile::rejected()->count(),
        ];

        return view('moderator.applicants.index', compact('applicants', 'status', 'counts', 'search'));
    }

    public function show(Request $request, ApplicantProfile $applicant)
    {
        $this->ensureModerator($request);

        $applicant->load(['user', 'verifiedByUser', 'applications']);

        return view('moderator.applicants.show', compact('applicant'));
    }

    public function verify(Request $request, ApplicantProfile $applicant)
    {
        $user = $this->ensureModerator($request);

        $applicant->update([
            'verification_status' => ApplicantProfile::STATUS_VERIFIED,
            'verified' => true,
            'verified_by' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes'),
        ]);

        return redirect()->back()->with('status', 'Applicant profile has been verified.');
    }

    public function reject(Request $request, ApplicantProfile $applicant)
    {
        $user = $this->ensureModerator($request);

        $validated = $request->validate([
            'notes' => 'required|string|max:1000',
        ]);

        $applicant->update([
            'verification_status' => ApplicantProfile::STATUS_REJECTED,
            'verified' => false,
            'verified_by' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $validated['notes'],
        ]);

        return redirect()->back()->with('status', 'Applicant profile has been rejected.');
    }

    public function resetToPending(Request $request, ApplicantProfile $applicant)
    {
        $this->ensureModerator($request);

        $applicant->update([
            'verification_status' => ApplicantProfile::STATUS_PENDING,
            'verified' => false,
            'verified_by' => null,
            'verified_at' => null,
            'verification_notes' => null,
        ]);

        return redirect()->back()->with('status', 'Applicant verification has been reset to pending.');
    }
}
