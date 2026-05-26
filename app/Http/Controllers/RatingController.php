<?php

namespace App\Http\Controllers;

use App\Models\ApplicantProfile;
use App\Models\Company;
use App\Models\JobPosting;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    /**
     * Store or update a rating for a job posting
     */
    public function rateJob(Request $request, JobPosting $job)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $userId = Auth::id();

        // Create or update the rating
        $rating = Rating::updateOrCreate(
            [
                'user_id' => $userId,
                'rateable_type' => JobPosting::class,
                'rateable_id' => $job->id,
            ],
            [
                'rating' => $request->rating,
            ]
        );

        // Recalculate average rating
        $this->updateJobRating($job);

        return response()->json([
            'success' => true,
            'rating' => $rating->rating,
            'average_rating' => $job->fresh()->rating,
            'rating_count' => $job->fresh()->rating_count,
        ]);
    }

    /**
     * Store or update a rating for an applicant profile
     */
    public function rateCandidate(Request $request, ApplicantProfile $applicant)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $userId = Auth::id();

        $rating = Rating::updateOrCreate(
            [
                'user_id' => $userId,
                'rateable_type' => ApplicantProfile::class,
                'rateable_id' => $applicant->id,
            ],
            [
                'rating' => $request->rating,
            ]
        );

        $this->updateCandidateRating($applicant);

        return response()->json([
            'success' => true,
            'rating' => $rating->rating,
            'average_rating' => $applicant->fresh()->rating,
            'rating_count' => $applicant->fresh()->rating_count,
        ]);
    }

    /**
     * Get user's rating for a job
     */
    public function getJobRating(JobPosting $job)
    {
        $userId = Auth::id();
        $rating = Rating::where('user_id', $userId)
            ->where('rateable_type', JobPosting::class)
            ->where('rateable_id', $job->id)
            ->first();

        return response()->json([
            'user_rating' => $rating?->rating,
            'average_rating' => $job->rating,
            'rating_count' => $job->rating_count,
        ]);
    }

    /**
     * Get user's rating for an applicant profile
     */
    public function getCandidateRating(ApplicantProfile $applicant)
    {
        $userId = Auth::id();
        $rating = Rating::where('user_id', $userId)
            ->where('rateable_type', ApplicantProfile::class)
            ->where('rateable_id', $applicant->id)
            ->first();

        return response()->json([
            'user_rating' => $rating?->rating,
            'average_rating' => $applicant->rating,
            'rating_count' => $applicant->rating_count,
        ]);
    }

    /**
     * Store or update a rating for a company
     */
    public function rateCompany(Request $request, Company $company)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $userId = Auth::id();

        $rating = Rating::updateOrCreate(
            [
                'user_id' => $userId,
                'rateable_type' => Company::class,
                'rateable_id' => $company->id,
            ],
            [
                'rating' => $request->rating,
            ]
        );

        $this->updateCompanyRating($company);

        return response()->json([
            'success' => true,
            'rating' => $rating->rating,
            'average_rating' => $company->fresh()->rating,
            'rating_count' => $company->fresh()->rating_count,
        ]);
    }

    /**
     * Get user's rating for a company
     */
    public function getCompanyRating(Company $company)
    {
        $userId = Auth::id();
        $rating = Rating::where('user_id', $userId)
            ->where('rateable_type', Company::class)
            ->where('rateable_id', $company->id)
            ->first();

        return response()->json([
            'user_rating' => $rating?->rating,
            'average_rating' => $company->rating,
            'rating_count' => $company->rating_count,
        ]);
    }

    /**
     * Update job's average rating
     */
    private function updateJobRating(JobPosting $job)
    {
        $ratings = Rating::where('rateable_type', JobPosting::class)
            ->where('rateable_id', $job->id)
            ->get();

        if ($ratings->count() > 0) {
            $job->rating = round($ratings->avg('rating'), 2);
            $job->rating_count = $ratings->count();
        } else {
            $job->rating = null;
            $job->rating_count = 0;
        }
        $job->save();
    }

    /**
     * Update candidate's average rating
     */
    private function updateCandidateRating(ApplicantProfile $candidate)
    {
        $ratings = Rating::where('rateable_type', ApplicantProfile::class)
            ->where('rateable_id', $candidate->id)
            ->get();

        if ($ratings->count() > 0) {
            $candidate->rating = round($ratings->avg('rating'), 2);
            $candidate->rating_count = $ratings->count();
        } else {
            $candidate->rating = null;
            $candidate->rating_count = 0;
        }
        $candidate->save();
    }

    /**
     * Update company's average rating
     */
    private function updateCompanyRating(Company $company)
    {
        $ratings = Rating::where('rateable_type', Company::class)
            ->where('rateable_id', $company->id)
            ->get();

        if ($ratings->count() > 0) {
            $company->rating = round($ratings->avg('rating'), 2);
            $company->rating_count = $ratings->count();
        } else {
            $company->rating = null;
            $company->rating_count = 0;
        }
        $company->save();
    }

    /**
     * Reset ratings for a job (called when job is closed)
     */
    public static function resetJobRatings(JobPosting $job)
    {
        Rating::where('rateable_type', JobPosting::class)
            ->where('rateable_id', $job->id)
            ->delete();

        $job->rating = null;
        $job->rating_count = 0;
        $job->save();
    }
}
