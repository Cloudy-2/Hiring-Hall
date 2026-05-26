<?php

namespace App\Http\Controllers\Jobs;

use App\Http\Controllers\Controller;
use App\Models\ApplicantProfile;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\User;
use App\Notifications\NewApplicationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class JobApplicationController extends Controller
{
    public function store(Request $request, JobPosting $job)
    {
        // Reject applications for jobs past their deadline
        if ($job->closes_at && $job->closes_at->isPast()) {
            return back()->withErrors(['deadline' => 'This job is no longer accepting applications. The deadline has passed.']);
        }

        $user = $request->user();

        // Check if applicant account is verified
        $profile = ApplicantProfile::where('user_id', $user->id)->first();
        if (! $profile || $profile->verification_status !== 'verified') {
            return back()->withErrors(['account' => 'Your account is pending verification. Please wait for approval from our team to apply for jobs.']);
        }

        $data = $request->validate([
            'cover_letter' => ['nullable', 'string'],
            'terms_agreed' => ['required', 'in:1'],
        ], [
            'terms_agreed.required' => 'You must accept the Agreement and Terms before applying.',
            'terms_agreed.in' => 'You must accept the Agreement and Terms before applying.',
        ]);

        $profile = ApplicantProfile::firstOrCreate(
            ['user_id' => $user->id],
            [
                'display_name' => $user->name,
            ],
        );

        $lookup = [
            'job_posting_id' => $job->id,
            'user_id' => $user->id,
        ];

        $values = [
            'status' => 'applied',
            'cover_letter' => $data['cover_letter'] ?? null,
            'applied_at' => now(),
        ];

        if (Schema::hasColumn('job_applications', 'candidate_profile_id')) {
            $lookup['candidate_profile_id'] = $profile->id;
            $values['candidate_profile_id'] = $profile->id;
        }

        if (Schema::hasColumn('job_applications', 'applicant_profile_id')) {
            $lookup['applicant_profile_id'] = $profile->id;
            $values['applicant_profile_id'] = $profile->id;
        }

        $application = JobApplication::firstOrCreate($lookup, $values);

        if (! $application->wasRecentlyCreated && ! $application->terms_agreed_at) {
            $application->update(['terms_agreed_at' => now()]);
        }

        // Notify the employer who owns this job's company
        if ($application->wasRecentlyCreated && $job->company) {
            $employer = User::where('id', $job->company->user_id)->first();
            if ($employer) {
                $employer->notify(new NewApplicationNotification($application));
            }
        }

        return back()->with('status', 'Application submitted.');
    }

    public function destroy(Request $request, JobApplication $application)
    {
        $user = $request->user();

        if (! $user || $user->id !== $application->user_id) {
            abort(403);
        }

        // Prevent withdrawal if application is beyond initial stages
        if (! in_array($application->status, ['applied', 'submitted'])) {
            return back()->withErrors(['status' => 'This application can no longer be withdrawn as it is already being processed.']);
        }

        $application->delete();

        if ($request->wantsJson()) {
            return response()->json(['status' => 'deleted']);
        }

        return redirect()->route('applicant.applications.index')
            ->with('status', 'Application cancelled.');
    }
}
