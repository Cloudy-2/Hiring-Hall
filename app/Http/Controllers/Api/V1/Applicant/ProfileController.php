<?php

namespace App\Http\Controllers\Api\V1\Applicant;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\Applicant\UpdateProfessionalRequest;
use App\Http\Requests\Api\Applicant\UpdateProfileRequest;
use App\Http\Requests\Api\Applicant\UploadCvRequest;
use App\Http\Requests\Api\Applicant\UploadPhotoRequest;
use App\Http\Resources\Api\ApplicantProfileResource;
use App\Http\Resources\Api\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends ApiController
{
    /**
     * GET /api/v1/applicant/profile
     */
    public function show(Request $request): JsonResponse
    {
        $user = $request->user()->load('applicantProfile');

        return $this->success([
            'user' => new UserResource($user),
            'profile' => new ApplicantProfileResource($user->applicantProfile),
        ]);
    }

    /**
     * PUT /api/v1/applicant/profile
     *
     * Updates User-level personal fields (name, phone, gender, etc.)
     */
    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $user = $request->user();
        $user->update($request->validated());

        return $this->success(new UserResource($user->fresh()), 'Profile updated.');
    }

    /**
     * PUT /api/v1/applicant/profile/professional
     *
     * Updates ApplicantProfile fields (headline, skills, salary expectations, etc.)
     */
    public function updateProfessional(UpdateProfessionalRequest $request): JsonResponse
    {
        $user = $request->user();
        $profile = $user->applicantProfile;

        if (! $profile) {
            return $this->notFound('Applicant profile not found.');
        }

        $profile->update($request->validated());

        return $this->success(new ApplicantProfileResource($profile->fresh()), 'Professional profile updated.');
    }

    /**
     * POST /api/v1/applicant/profile/photo
     *
     * Uploads and stores a profile photo. Replaces any existing photo.
     */
    public function uploadPhoto(UploadPhotoRequest $request): JsonResponse
    {
        $user = $request->user();

        // Delete old photo if stored locally
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        $path = $request->file('photo')->store('profile-photos', 'public');
        $user->update(['profile_photo_path' => $path]);

        return $this->success([
            'profile_photo_url' => $user->fresh()->profile_photo_url,
        ], 'Profile photo uploaded.');
    }

    /**
     * POST /api/v1/applicant/profile/cv
     *
     * Uploads and stores a CV (PDF). Updates ApplicantProfile.cv_path.
     */
    public function uploadCv(UploadCvRequest $request): JsonResponse
    {
        $user = $request->user();
        $profile = $user->applicantProfile;

        if (! $profile) {
            return $this->notFound('Applicant profile not found.');
        }

        // Delete old CV
        if ($profile->cv_path) {
            Storage::disk('public')->delete($profile->cv_path);
        }

        $path = $request->file('cv')->store('applicant-cvs', 'public');
        $profile->update(['cv_path' => $path]);

        return $this->success([
            'cv_url' => asset('storage/'.$path),
        ], 'CV uploaded successfully.');
    }
}
