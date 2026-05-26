<?php

namespace App\Http\Controllers;

use App\Models\ApplicantProfile;
use App\Models\JobApplication;
use App\Models\JobPosting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $stats = [
            'totalJobs' => JobPosting::where('status', 'open')->count(),
            'totalApplicants' => ApplicantProfile::count(),
            'totalHires' => JobApplication::whereIn('status', ['hired', 'offered'])->count(),
        ];

        return \Inertia\Inertia::render('Landing/Index', [
            'stats' => $stats,
            'canLogin' => \Illuminate\Support\Facades\Route::has('login'),
            'canRegister' => \Illuminate\Support\Facades\Route::has('register'),
        ]);
    }

    public function subscribeNewsletter(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Optional: store in DB or send to external service later
        return redirect()->back()->with('newsletter', __('Thanks for subscribing! We will keep you updated.'));
    }
}
