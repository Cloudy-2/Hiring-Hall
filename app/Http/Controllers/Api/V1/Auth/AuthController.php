<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\Onboarding\EmployerOnboard;
use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\Auth\ForgotPasswordRequest;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterApplicantRequest;
use App\Http\Requests\Api\Auth\RegisterEmployerRequest;
use App\Http\Requests\Api\Auth\ResetPasswordRequest;
use App\Http\Resources\Api\UserResource;
use App\Models\ApplicantProfile;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends ApiController
{
    /**
     * POST /api/v1/auth/register/applicant
     *
     * Registers a new applicant, seeds their ApplicantProfile,
     * and sends an email verification link.
     * Does NOT issue a token — the user must verify email first.
     */
    public function registerApplicant(RegisterApplicantRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'applicant',
        ]);

        // Seed an empty applicant profile
        ApplicantProfile::create([
            'user_id' => $user->id,
            'display_name' => $user->name,
            'onboarding_step' => 1,
        ]);

        // Send verification email
        $user->sendEmailVerificationNotification();

        return $this->success(
            ['email' => $user->email],
            'Registration successful. Please check your email to verify your account.',
            201
        );
    }

    /**
     * POST /api/v1/auth/register/employer
     *
     * Registers a new employer and optionally seeds a Company record.
     * Sends an email verification link. No token issued until verified.
     */
    public function registerEmployer(RegisterEmployerRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'employer',
        ]);

        // Seed a company if a name was provided
        if ($request->filled('company_name')) {
            // Generate slug using EmployerOnboard action's logic
            $action = new EmployerOnboard;
            $company = Company::create([
                'user_id' => $user->id,
                'name' => $request->company_name,
                'slug' => $this->generateSlug($request->company_name, $user->id),
                'onboarding_step' => 1,
                'verification_status' => Company::STATUS_PENDING,
            ]);
        }

        $user->sendEmailVerificationNotification();

        return $this->success(
            ['email' => $user->email],
            'Registration successful. Please check your email to verify your account.',
            201
        );
    }

    /**
     * Generate a unique slug for a company
     */
    private function generateSlug(string $name, int $userId): string
    {
        $slugBase = \Illuminate\Support\Str::slug($name) ?: 'company-'.$userId;
        $slug = $slugBase;
        $suffix = 2;

        while (Company::where('slug', $slug)->exists()) {
            $slug = $slugBase.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }

    /**
     * POST /api/v1/auth/login
     *
     * Authenticates the user and issues a Sanctum token scoped to their role.
     * Rejects unverified email addresses with a 403.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Enforce email verification
        if (! $user->hasVerifiedEmail()) {
            return $this->error(
                'Your email address is not verified. Please check your inbox.',
                403
            );
        }

        // Map role -> Sanctum token ability
        $abilityMap = [
            'applicant' => 'applicant',
            'employer' => 'employer',
            'admin' => 'applicant,employer',
            'super_admin' => 'applicant,employer',
            'moderator' => 'applicant,employer',
        ];

        $abilities = isset($abilityMap[$user->role])
            ? explode(',', $abilityMap[$user->role])
            : [$user->role];

        // Revoke existing tokens with the same device name (avoid duplicates)
        $deviceName = $request->device_name ?? 'mobile';
        $user->tokens()->where('name', $deviceName)->delete();

        $token = $user->createToken($deviceName, $abilities)->plainTextToken;

        return $this->success([
            'token' => $token,
            'token_type' => 'Bearer',
            'abilities' => $abilities,
            'user' => new UserResource($user),
        ], 'Login successful.');
    }

    /**
     * POST /api/v1/auth/logout
     *
     * Revokes the current Sanctum token.
     */
    public function logout(): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->currentAccessToken()->delete();

        return $this->success(null, 'Logged out successfully.');
    }

    /**
     * POST /api/v1/auth/forgot-password
     *
     * Sends a password reset link to the given email address.
     */
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $status = Password::sendResetLink($request->only('email'));

        if ($status !== Password::RESET_LINK_SENT) {
            return $this->error(__($status), 400);
        }

        return $this->success(null, __($status));
    }

    /**
     * POST /api/v1/auth/reset-password
     *
     * Resets the user's password given a valid token.
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill(['password' => Hash::make($password)])->save();
                $user->tokens()->delete(); // Revoke all tokens on password reset
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            return $this->error(__($status), 400);
        }

        return $this->success(null, __($status));
    }
}
