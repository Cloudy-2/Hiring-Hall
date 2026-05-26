<?php

use App\Http\Controllers\Api\NewUpdateController;
use App\Http\Controllers\Api\V1\Applicant\ApplicationController as ApplicantApplicationController;
use App\Http\Controllers\Api\V1\Applicant\InterviewController as ApplicantInterviewController;
use App\Http\Controllers\Api\V1\Applicant\JobAlertController as ApplicantJobAlertController;
use App\Http\Controllers\Api\V1\Applicant\JobController as ApplicantJobController;
use App\Http\Controllers\Api\V1\Applicant\NotificationController as ApplicantNotificationController;
use App\Http\Controllers\Api\V1\Applicant\OnboardingController as ApplicantOnboardingController;
use App\Http\Controllers\Api\V1\Applicant\ProfileController as ApplicantProfileController;
use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Employer\ApplicationController as EmployerApplicationController;
use App\Http\Controllers\Api\V1\Employer\CompanyController;
use App\Http\Controllers\Api\V1\Employer\InterviewController as EmployerInterviewController;
use App\Http\Controllers\Api\V1\Employer\JobController as EmployerJobController;
use App\Http\Controllers\Api\V1\Employer\OnboardingController as EmployerOnboardingController;
use App\Http\Controllers\Api\V1\Employer\SavedApplicantController;
use App\Http\Controllers\Api\V1\Shared\AnnouncementController;
use App\Http\Controllers\Api\V1\Shared\DropdownController;
use App\Http\Controllers\Api\V1\Shared\MeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — Hiring Hall
|--------------------------------------------------------------------------
|
| Route groups:
|   /api/v1/auth/**          Public — registration, login, password reset
|   /api/v1/applicant/**     Protected — auth:sanctum + ability:applicant
|   /api/v1/employer/**      Protected — auth:sanctum + ability:employer
|   /api/v1/** (shared)      Protected — auth:sanctum (both roles)
|
| Legacy internal routes (new-updates) are preserved below.
|
*/

Route::prefix('v1')->group(function () {

    // =========================================================================
    // PUBLIC — Authentication
    // =========================================================================
    Route::prefix('auth')->name('api.auth.')->group(function () {
        Route::post('register/applicant', [AuthController::class, 'registerApplicant'])->name('register.applicant');
        Route::post('register/employer', [AuthController::class, 'registerEmployer'])->name('register.employer');
        Route::post('login', [AuthController::class, 'login'])->name('login');
        Route::post('forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');
        Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('reset-password');

        // Logout requires a valid token
        Route::post('logout', [AuthController::class, 'logout'])
            ->middleware('auth:sanctum')
            ->name('logout');
    });

    // =========================================================================
    // SHARED — Authenticated (both roles)
    // =========================================================================
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('me', [MeController::class, 'show']);
        Route::put('me/password', [MeController::class, 'changePassword']);
        Route::get('me/notifications', [MeController::class, 'notificationCount']);
        Route::get('announcements', [AnnouncementController::class, 'index']);
        Route::post('announcements/read', [AnnouncementController::class, 'markRead']);
        Route::get('dropdowns/{type}', [DropdownController::class, 'index']);
    });

    // =========================================================================
    // APPLICANT — auth:sanctum + ability:applicant
    // =========================================================================
    Route::middleware(['auth:sanctum', 'ability:applicant'])
        ->prefix('applicant')
        ->name('api.applicant.')
        ->group(function () {

            // Profile
            Route::get('profile', [ApplicantProfileController::class, 'show'])->name('profile.show');
            Route::put('profile', [ApplicantProfileController::class, 'update'])->name('profile.update');
            Route::put('profile/professional', [ApplicantProfileController::class, 'updateProfessional'])->name('profile.professional');
            Route::post('profile/photo', [ApplicantProfileController::class, 'uploadPhoto'])->name('profile.photo');
            Route::post('profile/cv', [ApplicantProfileController::class, 'uploadCv'])->name('profile.cv');

            // Onboarding
            Route::get('onboarding', [ApplicantOnboardingController::class, 'show'])->name('onboarding.show');
            Route::post('onboarding', [ApplicantOnboardingController::class, 'advance'])->name('onboarding.advance');

            // Job Discovery
            // NOTE: 'saved' must be declared BEFORE '{slug}' to avoid route capture conflict
            Route::get('jobs/saved', [ApplicantJobController::class, 'saved'])->name('jobs.saved');
            Route::get('jobs/recommended', [ApplicantJobController::class, 'recommended'])->name('jobs.recommended');
            Route::get('jobs', [ApplicantJobController::class, 'index'])->name('jobs.index');
            Route::get('jobs/{slug}', [ApplicantJobController::class, 'show'])->name('jobs.show');
            Route::post('jobs/{slug}/save', [ApplicantJobController::class, 'toggleSave'])->name('jobs.save');

            // Applications
            Route::get('applications', [ApplicantApplicationController::class, 'index'])->name('applications.index');
            Route::get('applications/history', [ApplicantApplicationController::class, 'history'])->name('applications.history');
            Route::post('applications', [ApplicantApplicationController::class, 'store'])->name('applications.store');
            Route::get('applications/{id}', [ApplicantApplicationController::class, 'show'])->name('applications.show');
            Route::delete('applications/{id}', [ApplicantApplicationController::class, 'destroy'])->name('applications.destroy');

            // Job Alerts
            Route::get('job-alerts', [ApplicantJobAlertController::class, 'index'])->name('job-alerts.index');
            Route::post('job-alerts', [ApplicantJobAlertController::class, 'store'])->name('job-alerts.store');
            Route::get('job-alerts/{id}', [ApplicantJobAlertController::class, 'show'])->name('job-alerts.show');
            Route::put('job-alerts/{id}', [ApplicantJobAlertController::class, 'update'])->name('job-alerts.update');
            Route::delete('job-alerts/{id}', [ApplicantJobAlertController::class, 'destroy'])->name('job-alerts.destroy');
            Route::get('job-alerts/{id}/jobs', [ApplicantJobAlertController::class, 'jobs'])->name('job-alerts.jobs');

            // Interviews (read-only for applicants)
            Route::get('interviews', [ApplicantInterviewController::class, 'index'])->name('interviews.index');
            Route::get('interviews/{id}', [ApplicantInterviewController::class, 'show'])->name('interviews.show');

            // Notifications
            Route::get('notifications', [ApplicantNotificationController::class, 'index'])->name('notifications.index');
            Route::put('notifications/{id}/read', [ApplicantNotificationController::class, 'markRead'])->name('notifications.read');
            Route::post('notifications/read-all', [ApplicantNotificationController::class, 'markAllRead'])->name('notifications.read-all');
        });

    // =========================================================================
    // EMPLOYER — auth:sanctum + ability:employer
    // =========================================================================
    Route::middleware(['auth:sanctum', 'ability:employer'])
        ->prefix('employer')
        ->name('api.employer.')
        ->group(function () {

            // Company
            Route::get('company', [CompanyController::class, 'show'])->name('company.show');
            Route::put('company', [CompanyController::class, 'update'])->name('company.update');
            Route::post('company/logo', [CompanyController::class, 'uploadLogo'])->name('company.logo');
            Route::post('company/registration-document', [CompanyController::class, 'uploadRegistrationDocument'])->name('company.registration-document');

            // Onboarding
            Route::get('onboarding', [EmployerOnboardingController::class, 'show'])->name('onboarding.show');
            Route::post('onboarding', [EmployerOnboardingController::class, 'advance'])->name('onboarding.advance');

            // Job Postings
            Route::get('jobs', [EmployerJobController::class, 'index'])->name('jobs.index');
            Route::post('jobs', [EmployerJobController::class, 'store'])->name('jobs.store');
            Route::get('jobs/{id}', [EmployerJobController::class, 'show'])->name('jobs.show');
            Route::put('jobs/{id}', [EmployerJobController::class, 'update'])->name('jobs.update');
            Route::delete('jobs/{id}', [EmployerJobController::class, 'destroy'])->name('jobs.destroy');
            Route::post('jobs/{id}/publish', [EmployerJobController::class, 'publish'])->name('jobs.publish');
            Route::post('jobs/{id}/close', [EmployerJobController::class, 'close'])->name('jobs.close');

            // Pipeline
            Route::get('pipeline-stages', [EmployerApplicationController::class, 'pipelineStages'])->name('pipeline.stages');
            Route::get('applications', [EmployerApplicationController::class, 'index'])->name('applications.index');
            Route::get('history', [EmployerApplicationController::class, 'history'])->name('history.index');
            Route::get('jobs/{id}/applications', [EmployerApplicationController::class, 'indexForJob'])->name('jobs.applications.index');
            Route::get('applications/{id}', [EmployerApplicationController::class, 'show'])->name('applications.show');
            Route::put('applications/{id}/stage', [EmployerApplicationController::class, 'moveStage'])->name('applications.stage');
            Route::put('applications/{id}/notes', [EmployerApplicationController::class, 'updateNotes'])->name('applications.notes');

            // Interviews
            Route::get('interviews', [EmployerInterviewController::class, 'index'])->name('interviews.index');
            Route::post('interviews', [EmployerInterviewController::class, 'store'])->name('interviews.store');
            Route::get('interviews/{id}', [EmployerInterviewController::class, 'show'])->name('interviews.show');
            Route::put('interviews/{id}', [EmployerInterviewController::class, 'update'])->name('interviews.update');
            Route::delete('interviews/{id}', [EmployerInterviewController::class, 'destroy'])->name('interviews.destroy');
            Route::post('interviews/{id}/feedback', [EmployerInterviewController::class, 'feedback'])->name('interviews.feedback');

            // Saved Applicants
            Route::get('saved-applicants', [SavedApplicantController::class, 'index'])->name('saved-applicants.index');
            Route::post('saved-applicants/{applicantId}', [SavedApplicantController::class, 'toggle'])->name('saved-applicants.toggle');
        });
});

// =========================================================================
// LEGACY — New Updates (web session auth, unchanged)
// =========================================================================
Route::prefix('new-updates')->middleware('web')->group(function () {
    Route::get('/', [NewUpdateController::class, 'index']);
    Route::get('/selectors', [NewUpdateController::class, 'getSelectors']);
    Route::post('/', [NewUpdateController::class, 'store']);
    Route::put('/{id}', [NewUpdateController::class, 'update']);
    Route::delete('/{id}', [NewUpdateController::class, 'destroy']);
    Route::post('/{id}/toggle', [NewUpdateController::class, 'toggle']);
});
