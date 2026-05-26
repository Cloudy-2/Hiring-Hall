<?php

namespace App\Http\Controllers\Api\V1\Shared;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\Shared\ChangePasswordRequest;
use App\Http\Resources\Api\ApplicantProfileResource;
use App\Http\Resources\Api\CompanyResource;
use App\Http\Resources\Api\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MeController extends ApiController
{
    /**
     * GET /api/v1/me
     *
     * Returns the current authenticated user's profile summary.
     * Works for both applicants and employers.
     */
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();

        // Eager load the relevant profile based on role
        if ($user->role === 'applicant') {
            $user->load('applicantProfile');
        } elseif ($user->role === 'employer') {
            $user->load('company');
        }

        return $this->success([
            'user' => new UserResource($user),
            'profile' => match ($user->role) {
                'applicant' => $user->applicantProfile ? new ApplicantProfileResource($user->applicantProfile) : null,
                'employer' => $user->company ? new CompanyResource($user->company) : null,
                default => null,
            },
        ]);
    }

    /**
     * PUT /api/v1/me/password
     *
     * Changes the current user's password and revokes all other tokens.
     */
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $user = $request->user();
        $user->update(['password' => Hash::make($request->password)]);

        // Revoke all other tokens (force re-login on other devices)
        $user->tokens()->where('id', '!=', $user->currentAccessToken()->id)->delete();

        return $this->success(null, 'Password changed successfully.');
    }

    /**
     * GET /api/v1/me/notifications
     *
     * Returns the unread notification count for the current user.
     */
    public function notificationCount(Request $request): JsonResponse
    {
        $count = $request->user()->unreadNotifications()->count();

        return $this->success(['unread_count' => $count]);
    }
}
