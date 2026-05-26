<?php

namespace App\Http\Controllers\Jobs;

use App\Http\Controllers\Controller;
use App\Models\ApplicantProfile;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\SavedJob;
use Illuminate\Http\Request;

class SavedJobController extends Controller
{
    public function store(Request $request, JobPosting $job)
    {
        $user = $request->user();

        if (! $user || $user->role !== 'applicant') {
            abort(403);
        }

        $profile = ApplicantProfile::firstOrCreate(
            ['user_id' => $user->id],
            ['display_name' => $user->name],
        );

        $existing = SavedJob::where('job_posting_id', $job->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $status = 'removed';
            $message = 'Job removed from saved jobs.';
        } else {
            SavedJob::create([
                'job_posting_id' => $job->id,
                'user_id' => $user->id,
                'applicant_profile_id' => $profile->id,
                'saved_at' => now(),
            ]);
            $status = 'saved';
            $message = 'Job saved.';
        }

        if ($request->wantsJson()) {
            return response()->json(['status' => $status]);
        }

        if (in_array($request->input('redirect'), ['saved-jobs', 'recommended-jobs', 'recommended jobs'])) {
            return redirect()
                ->route('applicant.recommended-jobs')
                ->with('status', $message);
        }

        return back()->with('status', $message);
    }

    public function bulkRemove(Request $request)
    {
        $user = $request->user();

        if (! $user || $user->role !== 'applicant') {
            abort(403);
        }

        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return response()->json(['message' => 'No jobs selected'], 400);
        }

        SavedJob::where('user_id', $user->id)
            ->whereIn('job_posting_id', $ids)
            ->delete();

        return response()->json(['message' => 'Jobs removed from saved list']);
    }

    public function bulkApply(Request $request)
    {
        $user = $request->user();

        if (! $user || $user->role !== 'applicant') {
            abort(403);
        }

        // Check if applicant account is verified
        $profile = ApplicantProfile::where('user_id', $user->id)->first();
        if (! $profile || $profile->verification_status !== 'verified') {
            return response()->json(['message' => 'Your account is pending verification. Please wait for approval from our team to apply for jobs.'], 403);
        }

        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return response()->json(['message' => 'No jobs selected'], 400);
        }

        $profile = ApplicantProfile::firstOrCreate(
            ['user_id' => $user->id],
            ['display_name' => $user->name],
        );

        $appliedCount = 0;
        $skippedDeadline = 0;
        foreach ($ids as $jobId) {
            // Check if job deadline has passed
            $jobPosting = JobPosting::find($jobId);
            if ($jobPosting && $jobPosting->closes_at && $jobPosting->closes_at->isPast()) {
                $skippedDeadline++;

                continue;
            }

            // Check if already applied
            $existing = JobApplication::where('user_id', $user->id)
                ->where('job_posting_id', $jobId)
                ->first();

            if (! $existing) {
                JobApplication::create([
                    'user_id' => $user->id,
                    'job_posting_id' => $jobId,
                    'applicant_profile_id' => $profile->id,
                    'status' => 'applied',
                    'applied_at' => now(),
                ]);
                $appliedCount++;
            }
        }

        $message = $appliedCount.' application(s) submitted';
        if ($skippedDeadline > 0) {
            $message .= '. '.$skippedDeadline.' job(s) skipped (deadline passed)';
        }

        return response()->json(['message' => $message]);
    }
}
