<?php

namespace App\Http\Controllers\Employers;

use App\Http\Controllers\Controller;
use App\Models\ApplicantProfile;
use App\Models\SavedApplicant;
use Illuminate\Http\Request;

class SavedApplicantController extends Controller
{
    public function index()
    {
        $savedApplicants = SavedApplicant::where('employer_id', auth()->id())
            ->with(['applicantProfile.user'])
            ->latest()
            ->paginate(12);

        return view('employers.saved-candidates', compact('savedApplicants'));
    }

    public function toggle(Request $request, ApplicantProfile $applicant)
    {
        $employerId = auth()->id();

        $existing = SavedApplicant::where('employer_id', $employerId)
            ->where('applicant_profile_id', $applicant->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $saved = false;
        } else {
            SavedApplicant::create([
                'employer_id' => $employerId,
                'applicant_profile_id' => $applicant->id,
            ]);
            $saved = true;
        }

        if ($request->wantsJson()) {
            return response()->json(['saved' => $saved]);
        }

        return back()->with('success', $saved ? 'Applicant saved!' : 'Applicant removed from saved.');
    }

    public function destroy(ApplicantProfile $applicant)
    {
        SavedApplicant::where('employer_id', auth()->id())
            ->where('applicant_profile_id', $applicant->id)
            ->delete();

        return back()->with('success', 'Applicant removed from saved.');
    }

    public function bulkRemove(Request $request)
    {
        $data = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer'],
        ]);

        SavedApplicant::where('employer_id', auth()->id())
            ->whereIn('applicant_profile_id', $data['ids'])
            ->delete();

        if ($request->wantsJson()) {
            return response()->json(['status' => 'ok']);
        }

        return back()->with('success', count($data['ids']).' applicants removed from saved.');
    }
}
