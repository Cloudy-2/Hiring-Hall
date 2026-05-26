<?php

namespace App\Http\Controllers\Moderator;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\User;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    protected function ensureAdmin(Request $request)
    {
        $user = $request->user();

        if (! $user || ! in_array($user->role, ['admin', 'super_admin'])) {
            abort(403);
        }

        return $user;
    }

    public function index(Request $request)
    {
        $this->ensureAdmin($request);

        $period = $request->input('period', '30');

        $stats = $this->getOverviewStats();
        $registrationData = $this->getRegistrationTrends($period);
        $jobPostingData = $this->getJobPostingStats($period);
        $applicationData = $this->getApplicationStats();
        $companyData = $this->getCompanyVerificationStats();

        return view('moderator.reports.index', compact(
            'stats',
            'registrationData',
            'jobPostingData',
            'applicationData',
            'companyData',
            'period'
        ));
    }

    protected function getOverviewStats(): array
    {
        return [
            'total_users' => User::count(),
            'total_applicants' => User::where('role', 'applicant')->count(),
            'total_employers' => User::where('role', 'employer')->count(),
            'total_jobs' => JobPosting::count(),
            'active_jobs' => JobPosting::where('status', 'active')->count(),
            'total_applications' => JobApplication::count(),
            'total_companies' => Company::count(),
            'verified_companies' => Company::where('verification_status', 'approved')->count(),
            'pending_companies' => Company::where('verification_status', 'pending')->count(),
        ];
    }

    protected function getRegistrationTrends(int $days): array
    {
        $startDate = now()->subDays($days)->startOfDay();

        $registrations = User::where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, role, COUNT(*) as count')
            ->groupBy('date', 'role')
            ->orderBy('date')
            ->get();

        $labels = [];
        $applicants = [];
        $employers = [];

        $currentDate = $startDate->copy();
        while ($currentDate <= now()) {
            $dateStr = $currentDate->format('Y-m-d');
            $labels[] = $currentDate->format('M d');

            $dayData = $registrations->where('date', $dateStr);
            $applicants[] = $dayData->where('role', 'applicant')->sum('count');
            $employers[] = $dayData->where('role', 'employer')->sum('count');

            $currentDate->addDay();
        }

        return [
            'labels' => $labels,
            'applicants' => $applicants,
            'employers' => $employers,
        ];
    }

    protected function getJobPostingStats(int $days): array
    {
        $startDate = now()->subDays($days)->startOfDay();

        $jobs = JobPosting::where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = [];
        $counts = [];

        $currentDate = $startDate->copy();
        while ($currentDate <= now()) {
            $dateStr = $currentDate->format('Y-m-d');
            $labels[] = $currentDate->format('M d');
            $counts[] = $jobs->where('date', $dateStr)->sum('count');
            $currentDate->addDay();
        }

        $byCategory = JobPosting::selectRaw('category, COUNT(*) as count')
            ->whereNotNull('category')
            ->groupBy('category')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        return [
            'trend' => [
                'labels' => $labels,
                'counts' => $counts,
            ],
            'by_category' => $byCategory,
        ];
    }

    protected function getApplicationStats(): array
    {
        $total = JobApplication::count();
        $byStatus = JobApplication::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();

        $conversionRate = $total > 0
            ? round((($byStatus['hired'] ?? 0) / $total) * 100, 2)
            : 0;

        return [
            'total' => $total,
            'by_status' => $byStatus,
            'conversion_rate' => $conversionRate,
        ];
    }

    protected function getCompanyVerificationStats(): array
    {
        $total = Company::count();
        $byStatus = Company::selectRaw('verification_status, COUNT(*) as count')
            ->groupBy('verification_status')
            ->get()
            ->pluck('count', 'verification_status')
            ->toArray();

        $verificationRate = $total > 0
            ? round((($byStatus['approved'] ?? 0) / $total) * 100, 2)
            : 0;

        return [
            'total' => $total,
            'by_status' => $byStatus,
            'verification_rate' => $verificationRate,
        ];
    }

    public function export(Request $request)
    {
        $this->ensureAdmin($request);

        $type = $request->input('type', 'users');

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$type.'_report_'.date('Y-m-d').'.csv"',
        ];

        $callback = function () use ($type) {
            $file = fopen('php://output', 'w');

            if ($type === 'users') {
                fputcsv($file, ['ID', 'Name', 'Email', 'Role', 'Registered At']);
                User::orderBy('created_at', 'desc')->chunk(100, function ($users) use ($file) {
                    foreach ($users as $user) {
                        fputcsv($file, [
                            $user->id,
                            $user->name,
                            $user->email,
                            $user->role,
                            $user->created_at->format('Y-m-d H:i:s'),
                        ]);
                    }
                });
            } elseif ($type === 'jobs') {
                fputcsv($file, ['ID', 'Title', 'Company', 'Category', 'Status', 'Applications', 'Posted At']);
                JobPosting::with('company')->orderBy('created_at', 'desc')->chunk(100, function ($jobs) use ($file) {
                    foreach ($jobs as $job) {
                        fputcsv($file, [
                            $job->id,
                            $job->title,
                            $job->company->name ?? 'N/A',
                            $job->category,
                            $job->status,
                            $job->applications()->count(),
                            $job->created_at->format('Y-m-d H:i:s'),
                        ]);
                    }
                });
            } elseif ($type === 'companies') {
                fputcsv($file, ['ID', 'Name', 'Industry', 'Status', 'Owner', 'Registered At']);
                Company::with('user')->orderBy('created_at', 'desc')->chunk(100, function ($companies) use ($file) {
                    foreach ($companies as $company) {
                        fputcsv($file, [
                            $company->id,
                            $company->name,
                            $company->industry,
                            $company->verification_status,
                            $company->user->name ?? 'N/A',
                            $company->created_at->format('Y-m-d H:i:s'),
                        ]);
                    }
                });
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
