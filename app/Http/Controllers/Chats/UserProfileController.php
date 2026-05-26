<?php

namespace App\Http\Controllers\Chats;

use App\Http\Controllers\Controller;
use App\Models\ApplicantProfile;
use App\Models\Chats\PresenceStatus;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function show(User $user)
    {
        $currentUser = auth()->user();

        if (! $currentUser || ! in_array($currentUser->role, ['moderator', 'admin', 'super_admin', 'employer'])) {
            abort(403, 'You do not have permission to view this profile.');
        }

        $status = PresenceStatus::isOnline($user->id) ? 'online' : 'offline';
        $presenceRecord = PresenceStatus::where('user_id', $user->id)->first();
        if ($presenceRecord && $presenceRecord->status) {
            $status = $presenceRecord->status;
        }

        $avatar = $user->profile_photo_path
            ? asset('storage/'.ltrim($user->profile_photo_path, '/'))
            : 'https://api.dicebear.com/7.x/avataaars/svg?seed='.urlencode($user->name ?? 'User').'&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981';

        // Load applicant profile if the user is an applicant
        $applicantProfile = null;
        if (empty($user->role) || strtolower($user->role) === 'applicant' || $user->role === 'candidate') {
            $applicantProfile = ApplicantProfile::firstOrCreate(
                ['user_id' => $user->id],
                ['display_name' => $user->name]
            );
        }

        // Load company profile if the user is an employer
        $employerProfile = null;
        if (strtolower($user->role) === 'employer') {
            $employerProfile = Company::firstOrCreate(
                ['user_id' => $user->id],
                ['name' => $user->name, 'verification_status' => Company::STATUS_PENDING]
            );
        }

        return view('modules.users.show', [
            'user' => $user,
            'avatar' => $avatar,
            'status' => $status,
            'pageTitle' => $user->name,
            'active' => $user->name,
            'applicantProfile' => $applicantProfile,
            'employerProfile' => $employerProfile,
        ]);
    }

    public function profileCard(Request $request, User $user)
    {
        $currentUser = $request->user();

        $status = PresenceStatus::isOnline($user->id) ? 'online' : 'offline';
        $presenceRecord = PresenceStatus::where('user_id', $user->id)->first();
        if ($presenceRecord && $presenceRecord->status) {
            $status = $presenceRecord->status;
        }

        $avatar = $user->profile_photo_path
            ? asset('storage/'.ltrim($user->profile_photo_path, '/'))
            : 'https://api.dicebear.com/7.x/avataaars/svg?seed='.urlencode($user->name ?? 'User').'&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981';

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $currentUser->isModerator() ? $user->email : null,
            'avatar' => $avatar,
            'status' => $status,
            'system_role' => $user->role ?? 'applicant',
            'created_at' => $user->created_at?->toIso8601String(),
            'about' => null,
        ]);
    }
}
