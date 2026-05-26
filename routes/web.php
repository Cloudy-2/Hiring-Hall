<?php

use App\Http\Controllers\Applicants\ApplicantPanelController;
use App\Http\Controllers\Applicants\JobAlertController;
use App\Http\Controllers\Jobs\JobApplicationController;
use App\Http\Controllers\Jobs\JobPostingController;
use App\Http\Controllers\Jobs\SavedJobController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Landing page - converted to Inertia
Route::get('/', [LandingController::class, 'index']);
Route::post('/newsletter', [LandingController::class, 'subscribeNewsletter'])->name('newsletter.subscribe');

// Jobs routes (public + authenticated)
Route::get('/jobs', [JobPostingController::class, 'index'])->name('jobs');
Route::get('/jobs/{job:slug}', [JobPostingController::class, 'show'])->name('jobs.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user && $user->isApplicant()) {
            return redirect()->route('candidate.dashboard');
        }

        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Applicant/Candidate panel routes
    Route::get('/candidate/dashboard', [ApplicantPanelController::class, 'dashboard'])->name('candidate.dashboard');
    Route::get('/candidate/applications', [ApplicantPanelController::class, 'applications'])->name('candidate.applications.index');
    Route::get('/candidate/applications/history', [ApplicantPanelController::class, 'applicationHistory'])->name('candidate.applications.history');
    Route::delete('/candidate/applications/history/{application}', [ApplicantPanelController::class, 'destroyHistory'])->name('candidate.applications.history.destroy');
    Route::delete('/candidate/applications/{application}', [JobApplicationController::class, 'destroy'])->name('candidate.applications.destroy');
    Route::get('/candidate/profile', [ApplicantPanelController::class, 'editProfile'])->name('candidate.profile.edit');
    Route::post('/candidate/profile', [ApplicantPanelController::class, 'updateProfile'])->name('candidate.profile.update');
    Route::get('/candidate/saved-jobs', [ApplicantPanelController::class, 'savedJobs'])->name('candidate.saved-jobs.index');
    Route::post('/candidate/saved-jobs/bulk-remove', [SavedJobController::class, 'bulkRemove'])->name('candidate.saved-jobs.bulk-remove');
    Route::post('/candidate/saved-jobs/bulk-apply', [SavedJobController::class, 'bulkApply'])->name('candidate.saved-jobs.bulk-apply');
    Route::get('/candidate/recommended-jobs', [ApplicantPanelController::class, 'recommendedJobs'])->name('candidate.recommended-jobs');
    Route::get('/candidate/interviews', [ApplicantPanelController::class, 'interviews'])->name('candidate.interviews.index');
    Route::get('/candidate/interviews/{interview}/calendar', [ApplicantPanelController::class, 'downloadCalendar'])->name('candidate.interviews.calendar');

    // Job Alerts routes
    Route::get('/candidate/job-alerts', [JobAlertController::class, 'index'])->name('candidate.job-alerts.index');
    Route::get('/candidate/job-alerts/create', [JobAlertController::class, 'create'])->name('candidate.job-alerts.create');
    Route::post('/candidate/job-alerts', [JobAlertController::class, 'store'])->name('candidate.job-alerts.store');
    Route::get('/candidate/job-alerts/{jobAlert}', [JobAlertController::class, 'show'])->name('candidate.job-alerts.show');
    Route::get('/candidate/job-alerts/{jobAlert}/edit', [JobAlertController::class, 'edit'])->name('candidate.job-alerts.edit');
    Route::put('/candidate/job-alerts/{jobAlert}', [JobAlertController::class, 'update'])->name('candidate.job-alerts.update');
    Route::delete('/candidate/job-alerts/{jobAlert}', [JobAlertController::class, 'destroy'])->name('candidate.job-alerts.destroy');

    // Job actions (authenticated users)
    Route::post('/jobs/{job:slug}/apply', [JobApplicationController::class, 'store'])->name('jobs.apply');
    Route::post('/jobs/{job:slug}/save', [SavedJobController::class, 'store'])->name('jobs.save');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
