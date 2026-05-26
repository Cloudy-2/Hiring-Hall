<?php

namespace App\Http\Controllers\Employers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\RatingController;
use App\Models\Company;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\SavedApplicant;
use App\Notifications\ApplicationStatusChangedNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class EmployerPanelController extends Controller
{
    protected function ensureEmployer(Request $request)
    {
        $user = $request->user();

        if (! $user || $user->role !== 'employer') {
            abort(403);
        }

        return $user;
    }

    public function dashboard(Request $request)
    {
        $user = $this->ensureEmployer($request);

        // ── Scope everything to this employer's companies ──
        $companyIds = Company::where('user_id', $user->id)->pluck('id');
        $jobIds = JobPosting::whereIn('company_id', $companyIds)->pluck('id');

        // Job stats
        $totalJobs = $jobIds->count();
        $openJobs = JobPosting::whereIn('company_id', $companyIds)->where('status', 'open')->count();
        $closedJobs = JobPosting::whereIn('company_id', $companyIds)->where('status', 'closed')->count();

        // Application stats (only for this employer's jobs)
        $appQuery = JobApplication::whereIn('job_posting_id', $jobIds);
        $totalApplications = (clone $appQuery)->count();
        $newApplications = (clone $appQuery)->whereIn('status', ['applied', 'submitted'])->count();
        $underReviewCount = (clone $appQuery)->where('status', 'under_review')->count();
        $inProgressCount = (clone $appQuery)->where('status', 'in_progress')->count();
        $acceptedCount = (clone $appQuery)->where('status', 'accepted')->count();
        $declinedCount = (clone $appQuery)->whereIn('status', ['not_selected', 'no_longer_under_consideration'])->count();

        // Acceptance rate
        $acceptanceRate = $totalApplications > 0
            ? round(($acceptedCount / $totalApplications) * 100, 1)
            : 0;

        // Avg response time (days between applied_at and reviewed_at)
        $avgResponseDays = (clone $appQuery)
            ->whereNotNull('reviewed_at')
            ->whereNotNull('applied_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(DAY, applied_at, reviewed_at)) as avg_days')
            ->value('avg_days');
        $avgResponseDays = $avgResponseDays !== null ? round($avgResponseDays, 1) : null;

        // Saved candidates count
        $savedCandidatesCount = SavedApplicant::where('employer_id', $user->id)->count();

        // This week's applications
        $weekStart = Carbon::now()->startOfWeek();
        $thisWeekApps = (clone $appQuery)
            ->where('applied_at', '>=', $weekStart)
            ->count();

        // Recent apps & jobs — scoped
        $recentApplications = JobApplication::with(['jobPosting.company', 'applicantProfile.user'])
            ->whereIn('job_posting_id', $jobIds)
            ->latest('applied_at')
            ->take(5)
            ->get();

        $recentJobs = JobPosting::with('company')
            ->whereIn('company_id', $companyIds)
            ->latest('posted_at')
            ->take(12)
            ->get();

        // Dashboard Stats Array
        $dashboardStats = [
            [
                'label' => 'Total Jobs',
                'value' => $totalJobs,
                'icon' => 'ri-briefcase-line',
                'chip' => 'edb-chip-blue',
                'trend' => '<i class="ri-arrow-up-line"></i> 2 this week',
                'trend_dir' => 'up',
                'route' => route('employer.jobs.index'),
                'sparkline' => 'M0,40 Q20,35 40,20 T80,15 T140,5',
            ],
            [
                'label' => 'Open Roles',
                'value' => $openJobs,
                'icon' => 'ri-door-open-line',
                'chip' => 'edb-chip-purple',
                'trend' => '● Active',
                'trend_dir' => 'neutral',
                'route' => route('employer.jobs.index'),
                'sparkline' => 'M0,25 Q30,20 60,35 T100,10 T140,15',
            ],
            [
                'label' => 'Applications',
                'value' => $totalApplications,
                'icon' => 'ri-user-add-line',
                'chip' => 'edb-chip-green',
                'trend' => '<i class="ri-arrow-up-line"></i> 12% this week',
                'trend_dir' => 'up',
                'pulse' => true,
                'route' => route('employer.applications.index'),
                'sparkline' => 'M0,45 Q25,30 50,40 T90,15 T140,10',
            ],
            [
                'label' => 'Saved Talents',
                'value' => $savedCandidatesCount,
                'icon' => 'ri-bookmark-3-line',
                'chip' => 'edb-chip-amber',
                'trend' => '<i class="ri-history-line"></i> Recent saves',
                'trend_dir' => 'neutral',
                'route' => route('employer.saved-applicants.index'),
                'sparkline' => 'M0,10 Q20,15 40,30 T80,25 T140,40',
            ],
        ];

        // Status Breakdown
        $statusBreakdown = [
            ['label' => 'New', 'count' => $newApplications, 'color' => '#60a5fa'],
            ['label' => 'Reviewing', 'count' => $underReviewCount, 'color' => '#fbbf24'],
            ['label' => 'Interviewing', 'count' => $inProgressCount, 'color' => '#818cf8'],
            ['label' => 'Accepted', 'count' => $acceptedCount, 'color' => '#34d399'],
        ];

        // Source Segments (Placeholders since DB doesn't track this yet)
        $sourceSegments = [
            ['label' => 'Direct', 'count' => round($totalApplications * 0.45), 'color' => '#3b82f6'],
            ['label' => 'Referral', 'count' => round($totalApplications * 0.25), 'color' => '#8b5cf6'],
            ['label' => 'Social', 'count' => round($totalApplications * 0.20), 'color' => '#10b981'],
            ['label' => 'Other', 'count' => round($totalApplications * 0.10), 'color' => '#f59e0b'],
        ];

        // Trend logic (last 7 days)
        $trendLabels = [];
        $trendSeries = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $trendLabels[] = $date->format('M d');
            $trendSeries[] = (clone $appQuery)->whereDate('applied_at', $date->toDateString())->count();
        }

        // SVG Path for Trend Line
        $trendPath = 'M 0 60';
        if (count($trendSeries) > 0) {
            $maxVal = max(1, max($trendSeries));
            foreach ($trendSeries as $idx => $val) {
                $x = ($idx / 6) * 140;
                $y = 60 - ($val / $maxVal) * 55;
                if ($idx === 0) {
                    $trendPath = "M $x $y";
                } else {
                    $trendPath .= " L $x $y";
                }
            }
        }

        return view('employers.dashboard', [
            'user' => $user,
            'totalJobs' => $totalJobs,
            'openJobs' => $openJobs,
            'closedJobs' => $closedJobs,
            'totalApplications' => $totalApplications,
            'newApplications' => $newApplications,
            'underReviewCount' => $underReviewCount,
            'inProgressCount' => $inProgressCount,
            'acceptedCount' => $acceptedCount,
            'declinedCount' => $declinedCount,
            'acceptanceRate' => $acceptanceRate,
            'avgResponseDays' => $avgResponseDays,
            'savedCandidatesCount' => $savedCandidatesCount,
            'thisWeekApps' => $thisWeekApps,
            'recentApplications' => $recentApplications,
            'recentJobs' => $recentJobs,
            'dashboardStats' => $dashboardStats,
            'statusBreakdown' => $statusBreakdown,
            'sourceSegments' => $sourceSegments,
            'trendLabels' => $trendLabels,
            'trendSeries' => $trendSeries,
            'trendPath' => $trendPath,
        ]);
    }

    public function jobs(Request $request)
    {
        $user = $this->ensureEmployer($request);

        $search = $request->input('q');
        $statusFilter = $request->input('status');

        $hasAnyCompany = Company::where('user_id', $user->id)->exists();

        $canPostJobs = Company::approved()
            ->where('user_id', $user->id)
            ->exists();

        $hasPendingCompanies = Company::pending()
            ->where('user_id', $user->id)
            ->exists();

        $hasRejectedCompanies = Company::where('user_id', $user->id)
            ->where('verification_status', Company::STATUS_REJECTED)
            ->exists();

        $companyIds = Company::where('user_id', $user->id)->pluck('id');
        if ($companyIds->isEmpty()) {
            return view('employers.jobs', [
                'user' => $user,
                'jobs' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10),
                'search' => $search,
                'statusFilter' => $statusFilter,
                'hasAnyCompany' => $hasAnyCompany,
                'canPostJobs' => $canPostJobs,
                'hasPendingCompanies' => $hasPendingCompanies,
                'totalJobsCount' => 0,
                'openCount' => 0,
                'closedCount' => 0,
            ]);
        }

        $baseQuery = JobPosting::whereIn('company_id', $companyIds);

        // Exact counts for stats ribbon (unfiltered by search/status)
        $totalJobsCount = (clone $baseQuery)->count();
        $openCount = (clone $baseQuery)->where('status', 'open')->count();
        $closedCount = (clone $baseQuery)->where('status', 'closed')->count();

        $query = (clone $baseQuery)->with('company')->withCount('applications');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                    ->orWhereHas('company', function ($sub) use ($search) {
                        $sub->where('name', 'like', '%'.$search.'%');
                    });
            });
        }

        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        $jobs = $query
            ->latest('posted_at')
            ->paginate(12) // Slightly more for grid view
            ->withQueryString();

        return view('employers.jobs', [
            'user' => $user,
            'jobs' => $jobs,
            'search' => $search,
            'statusFilter' => $statusFilter,
            'hasAnyCompany' => $hasAnyCompany,
            'canPostJobs' => $canPostJobs,
            'hasPendingCompanies' => $hasPendingCompanies,
            'hasRejectedCompanies' => $hasRejectedCompanies,
            'totalJobsCount' => $totalJobsCount,
            'openCount' => $openCount,
            'closedCount' => $closedCount,
        ]);
    }

    public function updateJobStatus(Request $request, JobPosting $job)
    {
        $this->ensureEmployer($request);

        $data = $request->validate([
            'status' => [
                'required',
                'string',
                Rule::in(['open', 'closed']),
            ],
        ]);

        $job->status = $data['status'];

        if ($job->status === 'closed' && ! $job->closes_at) {
            $job->closes_at = now();

            // Update all pending applications for this job to 'closed' status
            JobApplication::where('job_posting_id', $job->id)
                ->whereNotIn('status', ['not_selected', 'no_longer_under_consideration'])
                ->update([
                    'status' => 'closed',
                    'reviewed_at' => now(),
                ]);

            // Reset ratings when job is closed
            RatingController::resetJobRatings($job);
        }

        // If reopening the job, reset closes_at and delete closed applications so candidates can apply again
        if ($job->status === 'open') {
            $job->closes_at = null;

            // Delete applications that were marked as 'closed' when job was closed
            // This allows candidates to apply again to the reopened job
            JobApplication::where('job_posting_id', $job->id)
                ->where('status', 'closed')
                ->delete();

            // Reset ratings when job is reopened (fresh start)
            RatingController::resetJobRatings($job);
        }

        $job->save();

        if ($request->wantsJson()) {
            return response()->json(['status' => 'ok']);
        }

        // If redirect_to_history is set, redirect to history page
        if ($request->input('redirect_to_history')) {
            return redirect()->route('employer.history', ['type' => 'jobs'])
                ->with('status', 'Job has been moved to history.');
        }

        return back()->with('status', 'Job status updated.');
    }

    public function restoreJob(Request $request, JobPosting $job)
    {
        $this->ensureEmployer($request);

        $job->status = 'open';
        $job->closes_at = null;
        $job->save();

        // Delete applications that were marked as 'closed' when job was closed
        // This allows candidates to apply again to the reopened job
        JobApplication::where('job_posting_id', $job->id)
            ->where('status', 'closed')
            ->delete();

        if ($request->wantsJson()) {
            return response()->json(['status' => 'ok']);
        }

        return back()->with('status', 'Job has been restored and is now open.');
    }

    public function restoreApplication(Request $request, JobApplication $application)
    {
        $this->ensureEmployer($request);

        $data = $request->validate([
            'status' => [
                'required',
                'string',
                Rule::in(['applied', 'under_review', 'application_viewed', 'in_progress']),
            ],
        ]);

        $oldStatus = $application->status;
        $application->status = $data['status'];
        $application->reviewed_at = null;
        $application->save();

        if ($oldStatus !== $data['status'] && $application->user) {
            $application->user->notify(
                new ApplicationStatusChangedNotification($application, $oldStatus, $data['status'])
            );
        }

        if ($request->wantsJson()) {
            return response()->json(['status' => 'ok']);
        }

        return back()->with('status', 'Application has been restored.');
    }

    public function deleteApplicationPermanently(Request $request, JobApplication $application)
    {
        $this->ensureEmployer($request);

        $application->delete();

        if ($request->wantsJson()) {
            return response()->json(['status' => 'ok']);
        }

        return back()->with('status', 'Application has been deleted.');
    }

    public function bulkRestoreApplications(Request $request)
    {
        $this->ensureEmployer($request);

        $data = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer'],
            'status' => [
                'required',
                'string',
                Rule::in(['applied', 'under_review', 'application_viewed', 'in_progress']),
            ],
        ]);

        $applications = JobApplication::with('user')
            ->whereIn('id', $data['ids'])
            ->get();

        foreach ($applications as $application) {
            $oldStatus = $application->status;
            $application->status = $data['status'];
            $application->reviewed_at = null;
            $application->save();

            if ($oldStatus !== $data['status'] && $application->user) {
                $application->user->notify(
                    new ApplicationStatusChangedNotification($application, $oldStatus, $data['status'])
                );
            }
        }

        if ($request->wantsJson()) {
            return response()->json(['status' => 'ok']);
        }

        return back()->with('status', count($data['ids']).' applications have been restored.');
    }

    public function bulkRestoreJobs(Request $request)
    {
        $this->ensureEmployer($request);

        $data = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer'],
        ]);

        JobPosting::whereIn('id', $data['ids'])
            ->update([
                'status' => 'open',
                'closes_at' => null,
            ]);

        if ($request->wantsJson()) {
            return response()->json(['status' => 'ok']);
        }

        return back()->with('status', count($data['ids']).' jobs have been restored.');
    }

    public function bulkDeleteJobs(Request $request)
    {
        $this->ensureEmployer($request);

        $data = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer'],
        ]);

        // Delete related applications first
        JobApplication::whereIn('job_posting_id', $data['ids'])->delete();

        JobPosting::whereIn('id', $data['ids'])->delete();

        if ($request->wantsJson()) {
            return response()->json(['status' => 'ok']);
        }

        return back()->with('status', count($data['ids']).' jobs have been deleted.');
    }

    public function bulkDeleteApplications(Request $request)
    {
        $this->ensureEmployer($request);

        $data = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer'],
        ]);

        JobApplication::whereIn('id', $data['ids'])->delete();

        if ($request->wantsJson()) {
            return response()->json(['status' => 'ok']);
        }

        return back()->with('status', count($data['ids']).' applications have been deleted.');
    }

    public function bulkArchiveJobs(Request $request)
    {
        $this->ensureEmployer($request);

        $data = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer'],
        ]);

        JobPosting::whereIn('id', $data['ids'])
            ->update([
                'status' => 'closed',
                'closes_at' => now(),
            ]);

        // Close all pending applications for these jobs
        JobApplication::whereIn('job_posting_id', $data['ids'])
            ->whereNotIn('status', ['not_selected', 'no_longer_under_consideration'])
            ->update([
                'status' => 'closed',
                'reviewed_at' => now(),
            ]);

        if ($request->wantsJson()) {
            return response()->json(['status' => 'ok']);
        }

        return back()->with('status', count($data['ids']).' jobs have been archived.');
    }

    public function bulkUpdateJobStatus(Request $request)
    {
        $this->ensureEmployer($request);

        $data = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer'],
            'status' => ['required', 'string', 'in:open,closed'],
        ]);

        $updateData = ['status' => $data['status']];

        if ($data['status'] === 'closed') {
            $updateData['closes_at'] = now();
        } else {
            $updateData['closes_at'] = null;
        }

        JobPosting::whereIn('id', $data['ids'])->update($updateData);

        if ($request->wantsJson()) {
            return response()->json(['status' => 'ok']);
        }

        return back()->with('status', count($data['ids']).' jobs status updated.');
    }

    public function deleteJobPermanently(Request $request, JobPosting $job)
    {
        $this->ensureEmployer($request);

        // Delete related applications first
        JobApplication::where('job_posting_id', $job->id)->delete();

        $job->delete();

        if ($request->wantsJson()) {
            return response()->json(['status' => 'ok']);
        }

        return back()->with('status', 'Job has been deleted.');
    }

    public function applications(Request $request)
    {
        $user = $this->ensureEmployer($request);

        $search = $request->input('q');
        $statusFilter = $request->input('status');
        $jobId = $request->input('job');

        $query = JobApplication::with(['jobPosting.company', 'applicantProfile.user'])
            ->whereNotIn('status', ['closed']);

        if ($jobId) {
            $query->where('job_posting_id', $jobId);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('applicantProfile', function ($sub) use ($search) {
                    $sub->where('display_name', 'like', '%'.$search.'%');
                })->orWhereHas('user', function ($sub) use ($search) {
                    $sub->where('name', 'like', '%'.$search.'%');
                })->orWhereHas('jobPosting', function ($sub) use ($search) {
                    $sub->where('title', 'like', '%'.$search.'%');
                });
            });
        }

        if ($statusFilter) {
            $query->where(function ($q) use ($statusFilter) {
                switch ($statusFilter) {
                    case 'applied':
                        $q->whereIn('status', ['applied', 'submitted']);
                        break;
                    case 'under_review':
                        $q->where('status', 'under_review');
                        break;
                    case 'application_viewed':
                        $q->whereIn('status', ['application_viewed', 'viewed']);
                        break;
                    case 'accepted':
                        $q->where('status', 'accepted');
                        break;
                    case 'declined':
                        $q->whereIn('status', ['not_selected', 'no_longer_under_consideration']);
                        break;
                }
            });
        }

        $applications = $query
            ->latest('applied_at')
            ->paginate(10)
            ->withQueryString();

        $jobs = JobPosting::orderBy('title')->get();
        $selectedJob = $jobId ? $jobs->firstWhere('id', (int) $jobId) : null;

        return view('employers.applications', [
            'user' => $user,
            'applications' => $applications,
            'jobs' => $jobs,
            'selectedJob' => $selectedJob,
            'statusFilter' => $statusFilter,
            'search' => $search,
        ]);
    }

    public function updateApplicationStatus(Request $request, JobApplication $application)
    {
        $user = $this->ensureEmployer($request);

        // Prevent changing status if it's already terminal (Accepted or Declined)
        if (in_array($application->status, ['accepted', 'not_selected'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'This application is already '.($application->status === 'accepted' ? 'Accepted' : 'Declined').' and cannot be modified.',
            ], 422);
        }

        $data = $request->validate([
            'status' => [
                'required',
                'string',
                Rule::in([
                    'applied',
                    'submitted',
                    'under_review',
                    'application_viewed',
                    'viewed',
                    'in_progress',
                    'accepted',
                    'not_selected',
                ]),
            ],
            'interview_title' => 'nullable|string|max:255',
            'interview_type' => 'nullable|string|in:video,phone,in_person',
            'scheduled_at' => 'nullable|date',
            'meeting_link' => 'nullable|url|max:500',
        ]);

        $oldStatus = $application->status;
        $application->status = $data['status'];

        if ($application->status !== 'applied' && ! $application->reviewed_at) {
            $application->reviewed_at = now();
        }

        $application->save();

        // Auto-sync pipeline stage to match the status
        $statusToStage = [
            'applied' => 'New Applications',
            'under_review' => 'Screening',
            'application_viewed' => 'Screening',
            'viewed' => 'Screening',
            'in_progress' => 'Interview',
            'accepted' => 'Hired',
            'not_selected' => 'Rejected',
        ];
        if (isset($statusToStage[$data['status']])) {
            $stageName = $statusToStage[$data['status']];
            $application->load('jobPosting.company');
            $companyId = $application->jobPosting?->company?->id;
            if ($companyId) {
                $pipelineStage = \App\Models\PipelineStage::forCompany($companyId)
                    ->where('name', $stageName)
                    ->first();
                if ($pipelineStage) {
                    $application->pipeline_stage_id = $pipelineStage->id;
                    $application->save();
                }
            }
        }

        // If status is in_progress (Interview), create/update the Interview record
        if ($data['status'] === 'in_progress' && ! empty($data['scheduled_at'])) {
            $interview = \App\Models\Interview::updateOrCreate(
                ['job_application_id' => $application->id],
                [
                    'employer_id' => $user->id,
                    'applicant_id' => $application->user_id,
                    'title' => $data['interview_title'] ?? ('Interview for '.($application->jobPosting->title ?? 'Position')),
                    'scheduled_at' => $data['scheduled_at'],
                    'meeting_link' => $data['meeting_link'],
                    'interview_type' => $data['interview_type'] ?? 'video',
                    'duration_minutes' => 30,
                    'status' => \App\Models\Interview::STATUS_SCHEDULED,
                ]
            );

            $interview->load(['applicant', 'jobApplication.jobPosting.company']);

            if ($interview->applicant) {
                $interview->applicant->notify(new \App\Notifications\InterviewScheduledNotification($interview));
            }
        }

        // Standard status change notification (if not already handled by Interview notification)
        if ($oldStatus !== $data['status'] && $data['status'] !== 'in_progress' && $application->user) {
            $application->user->notify(
                new ApplicationStatusChangedNotification($application, $oldStatus, $data['status'])
            );
        }

        if ($request->wantsJson()) {
            return response()->json(['status' => 'ok']);
        }

        return back()->with('status', 'Application status updated.');
    }

    public function applicationsPipeline(Request $request)
    {
        $user = $this->ensureEmployer($request);

        $applications = JobApplication::with(['jobPosting.company', 'applicantProfile.user'])
            ->latest('applied_at')
            ->get();

        $columnsConfig = [
            'applied' => [
                'label' => 'New / Applied',
                'statuses' => ['applied', 'submitted'],
            ],
            'under_review' => [
                'label' => 'Under Review',
                'statuses' => ['under_review'],
            ],
            'viewed' => [
                'label' => 'Application Viewed',
                'statuses' => ['application_viewed', 'viewed'],
            ],
            'in_progress' => [
                'label' => 'Interviewing',
                'statuses' => ['in_progress'],
            ],
            'declined' => [
                'label' => 'Declined',
                'statuses' => ['not_selected', 'no_longer_under_consideration'],
            ],
            'closed' => [
                'label' => 'Closed',
                'statuses' => ['closed'],
            ],
        ];

        $pipeline = [];

        foreach ($columnsConfig as $key => $config) {
            $pipeline[$key] = [
                'label' => $config['label'],
                'applications' => $applications->filter(function (JobApplication $application) use ($config) {
                    return in_array($application->status, $config['statuses'], true);
                })->values(),
            ];
        }

        return view('employers.applications-pipeline', [
            'user' => $user,
            'pipeline' => $pipeline,
        ]);
    }

    public function history(Request $request)
    {
        $user = $this->ensureEmployer($request);

        $type = $request->input('type', 'candidates');
        $search = $request->input('q');

        $candidateHistory = null;
        $jobHistory = null;

        if ($type === 'jobs') {
            $jobsQuery = JobPosting::with('company')
                ->where('status', 'closed');

            if ($search) {
                $jobsQuery->where(function ($q) use ($search) {
                    $q->where('title', 'like', '%'.$search.'%')
                        ->orWhereHas('company', function ($q2) use ($search) {
                            $q2->where('name', 'like', '%'.$search.'%');
                        });
                });
            }

            $jobHistory = $jobsQuery
                ->latest('closes_at')
                ->paginate(10)
                ->withQueryString();
        } else {
            $applicationsQuery = JobApplication::with(['jobPosting.company', 'applicantProfile.user'])
                ->whereIn('status', ['not_selected', 'no_longer_under_consideration', 'closed']);

            if ($search) {
                $applicationsQuery->where(function ($q) use ($search) {
                    $q->whereHas('applicantProfile', function ($sub) use ($search) {
                        $sub->where('display_name', 'like', '%'.$search.'%');
                    })->orWhereHas('user', function ($sub) use ($search) {
                        $sub->where('name', 'like', '%'.$search.'%');
                    })->orWhereHas('jobPosting', function ($sub) use ($search) {
                        $sub->where('title', 'like', '%'.$search.'%');
                    });
                });
            }

            $candidateHistory = $applicationsQuery
                ->latest('reviewed_at')
                ->paginate(10)
                ->withQueryString();
        }

        return view('employers.history', [
            'user' => $user,
            'type' => $type,
            'search' => $search,
            'candidateHistory' => $candidateHistory,
            'jobHistory' => $jobHistory,
        ]);
    }

    public function updateProfilePhoto(Request $request)
    {
        $user = $this->ensureEmployer($request);

        $request->validate([
            'photo' => ['required', 'image', 'max:5120'], // Max 5MB
        ]);

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            // Store new photo
            $path = $request->file('photo')->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
            $user->save();

            return response()->json([
                'success' => true,
                'photo_url' => Storage::url($path),
            ]);
        }

        return response()->json(['success' => false, 'message' => 'No photo uploaded'], 400);
    }
}
