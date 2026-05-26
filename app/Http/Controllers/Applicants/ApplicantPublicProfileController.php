<?php

namespace App\Http\Controllers\Applicants;

use App\Http\Controllers\Controller;
use App\Models\ApplicantProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApplicantPublicProfileController extends Controller
{
    public function show(Request $request)
    {
        // Accept both 'applicant' and 'id' query parameters for backwards compatibility
        $id = $request->query('applicant') ?? $request->query('id');

        abort_unless($id, 404, 'Applicant ID is required.');

        $profile = ApplicantProfile::with('user')->findOrFail($id);

        // Check if applicant is verified - only verified applicants can be viewed by employers
        abort_if(! $profile->isVerified(), 403, 'You do not have permission to view this applicant profile. This applicant has not been verified yet.');

        // Get related profiles (other applicants, excluding current profile and same user)
        // Get unique profiles by selecting only one per user_id - ONLY VERIFIED APPLICANTS
        $relatedProfiles = ApplicantProfile::with('user')
            ->where('id', '!=', $profile->id)
            ->where('user_id', '!=', $profile->user_id)
            ->whereNotNull('display_name')
            ->where('display_name', '!=', '')
            ->where('verification_status', ApplicantProfile::STATUS_VERIFIED)
            ->whereIn('id', function ($query) use ($profile) {
                $query->selectRaw('MIN(id)')
                    ->from('applicant_profiles')
                    ->where('id', '!=', $profile->id)
                    ->where('user_id', '!=', $profile->user_id)
                    ->whereNotNull('display_name')
                    ->where('display_name', '!=', '')
                    ->where('verification_status', ApplicantProfile::STATUS_VERIFIED)
                    ->groupBy('user_id');
            })
            ->inRandomOrder()
            ->take(10)
            ->get();

        return view('modules.jobs.candidates-details', [
            'profile' => $profile,
            'user' => $profile->user,
            'relatedProfiles' => $relatedProfiles,
        ]);
    }

    public function downloadCV(Request $request, $applicant)
    {
        $profile = ApplicantProfile::with('user')->findOrFail($applicant);

        // Check if applicant is verified - only verified applicants' CVs can be downloaded
        abort_if(! $profile->isVerified(), 403, 'You do not have permission to download this applicant\'s CV. This applicant has not been verified yet.');

        if (empty($profile->cv_path)) {
            abort(404, 'CV not found');
        }

        // Check if file exists in storage
        if (! Storage::disk('public')->exists($profile->cv_path)) {
            abort(404, 'CV file not found');
        }

        // Get the file extension
        $extension = pathinfo($profile->cv_path, PATHINFO_EXTENSION);

        // Create a clean filename for download
        $displayName = $profile->display_name ?? ($profile->user->name ?? 'Applicant');
        $cleanName = preg_replace('/[^a-zA-Z0-9\s\-_]/', '', $displayName);
        $downloadName = $cleanName.'_CV.'.$extension;

        return Storage::disk('public')->download($profile->cv_path, $downloadName);
    }
}
