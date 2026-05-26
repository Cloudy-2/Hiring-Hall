<?php

namespace App\Http\Controllers\Employers;

use App\Http\Controllers\Controller;
use App\Models\Interview;
use App\Models\JobApplication;
use App\Notifications\InterviewScheduledNotification;
use Illuminate\Http\Request;

class InterviewController extends Controller
{
    protected function ensureEmployer(Request $request)
    {
        $user = $request->user();

        if (! $user || $user->role !== 'employer') {
            abort(403);
        }

        return $user;
    }

    public function index(Request $request)
    {
        $user = $this->ensureEmployer($request);

        $filter = $request->get('filter', 'upcoming');

        $query = Interview::forEmployer($user->id)
            ->with(['applicant', 'jobApplication.jobPosting']);

        if ($filter === 'upcoming') {
            $query->upcoming()->orderBy('scheduled_at');
        } elseif ($filter === 'past') {
            $query->where('scheduled_at', '<', now())->orderBy('scheduled_at', 'desc');
        } elseif ($filter === 'today') {
            $query->today()->orderBy('scheduled_at');
        } else {
            $query->orderBy('scheduled_at', 'desc');
        }

        $interviews = $query->paginate(15);

        $todayCount = Interview::forEmployer($user->id)->today()->count();
        $upcomingCount = Interview::forEmployer($user->id)->upcoming()->count();

        $calendarEvents = Interview::forEmployer($user->id)
            ->where('scheduled_at', '>=', now()->startOfMonth()->subMonth())
            ->where('scheduled_at', '<=', now()->endOfMonth()->addMonth())
            ->get()
            ->map(function ($interview) {
                return [
                    'id' => $interview->id,
                    'title' => $interview->title,
                    'start' => $interview->scheduled_at->toIso8601String(),
                    'end' => $interview->getEndTime()->toIso8601String(),
                    'color' => $this->getStatusColor($interview->status),
                    'extendedProps' => [
                        'applicant' => $interview->applicant?->name,
                        'type' => $interview->getTypeLabel(),
                        'status' => $interview->status,
                    ],
                ];
            });

        return view('employers.interviews.index', compact('interviews', 'filter', 'todayCount', 'upcomingCount', 'calendarEvents'));
    }

    public function create(Request $request)
    {
        $user = $this->ensureEmployer($request);

        $applicationId = $request->get('application_id');
        $application = null;

        if ($applicationId) {
            $application = JobApplication::with(['applicantProfile.user', 'jobPosting'])
                ->whereHas('jobPosting.company', fn ($q) => $q->where('user_id', $user->id))
                ->find($applicationId);
        }

        $types = Interview::TYPES;

        return view('employers.interviews.create', compact('application', 'types'));
    }

    public function store(Request $request)
    {
        $user = $this->ensureEmployer($request);

        $validated = $request->validate([
            'job_application_id' => 'required|exists:job_applications,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'interview_type' => 'required|string|in:'.implode(',', array_keys(Interview::TYPES)),
            'scheduled_at' => 'required|date|after:now',
            'duration_minutes' => 'required|integer|min:15|max:480',
            'location' => 'nullable|string|max:255',
            'meeting_link' => 'nullable|url|max:500',
            'notes' => 'nullable|string',
        ]);

        $application = JobApplication::with(['applicantProfile.user', 'jobPosting.company'])
            ->findOrFail($validated['job_application_id']);

        if ($application->jobPosting->company->user_id !== $user->id) {
            abort(403);
        }

        $interview = Interview::create([
            'job_application_id' => $validated['job_application_id'],
            'employer_id' => $user->id,
            'applicant_id' => $application->user_id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'interview_type' => $validated['interview_type'],
            'scheduled_at' => $validated['scheduled_at'],
            'duration_minutes' => $validated['duration_minutes'],
            'location' => $validated['location'],
            'meeting_link' => $validated['meeting_link'],
            'notes' => $validated['notes'],
            'status' => Interview::STATUS_SCHEDULED,
        ]);

        $applicantUser = $application->applicantProfile?->user ?? $application->user;
        if ($applicantUser) {
            $applicantUser->notify(new InterviewScheduledNotification($interview));
        }

        return redirect()->route('employer.interviews.index')
            ->with('status', 'Interview scheduled successfully! The applicant has been notified.');
    }

    public function show(Request $request, Interview $interview)
    {
        $user = $this->ensureEmployer($request);

        if ($interview->employer_id !== $user->id) {
            abort(403);
        }

        $interview->load(['applicant', 'jobApplication.jobPosting', 'jobApplication.applicantProfile']);

        return view('employers.interviews.show', compact('interview'));
    }

    public function edit(Request $request, Interview $interview)
    {
        $user = $this->ensureEmployer($request);

        if ($interview->employer_id !== $user->id) {
            abort(403);
        }

        $interview->load(['applicant', 'jobApplication.jobPosting']);
        $types = Interview::TYPES;
        $statuses = Interview::STATUSES;

        return view('employers.interviews.edit', compact('interview', 'types', 'statuses'));
    }

    public function update(Request $request, Interview $interview)
    {
        $user = $this->ensureEmployer($request);

        if ($interview->employer_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'interview_type' => 'required|string|in:'.implode(',', array_keys(Interview::TYPES)),
            'scheduled_at' => 'required|date',
            'duration_minutes' => 'required|integer|min:15|max:480',
            'location' => 'nullable|string|max:255',
            'meeting_link' => 'nullable|url|max:500',
            'status' => 'required|string|in:'.implode(',', array_keys(Interview::STATUSES)),
            'notes' => 'nullable|string',
            'feedback' => 'nullable|string',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        $wasRescheduled = $interview->scheduled_at->ne($validated['scheduled_at']);

        $interview->update($validated);

        if ($wasRescheduled && $interview->status === Interview::STATUS_SCHEDULED) {
            $interview->update(['status' => Interview::STATUS_RESCHEDULED]);
            $interview->applicant?->notify(new InterviewScheduledNotification($interview, true));
        }

        return redirect()->route('employer.interviews.index')
            ->with('status', 'Interview updated successfully!');
    }

    public function destroy(Request $request, Interview $interview)
    {
        $user = $this->ensureEmployer($request);

        if ($interview->employer_id !== $user->id) {
            abort(403);
        }

        $interview->delete();

        return response()->json(['success' => true]);
    }

    public function cancel(Request $request, Interview $interview)
    {
        $user = $this->ensureEmployer($request);

        if ($interview->employer_id !== $user->id) {
            abort(403);
        }

        $interview->update(['status' => Interview::STATUS_CANCELLED]);

        return response()->json(['success' => true]);
    }

    public function notify(Request $request, Interview $interview)
    {
        $user = $this->ensureEmployer($request);

        if ($interview->employer_id !== $user->id) {
            abort(403);
        }

        if ($interview->status !== Interview::STATUS_SCHEDULED) {
            return response()->json(['error' => 'Cannot send reminder for non-scheduled interviews'], 400);
        }

        $interview->load(['jobApplication.jobPosting.company', 'applicant']);

        if ($interview->applicant) {
            $interview->applicant->notify(new \App\Notifications\InterviewReminderNotification($interview));
        }

        return response()->json([
            'success' => true,
            'message' => 'Interview reminder sent to '.$interview->applicant?->name ?? 'applicant',
        ]);
    }

    protected function getStatusColor(string $status): string
    {
        return match ($status) {
            Interview::STATUS_SCHEDULED => '#3b82f6',
            Interview::STATUS_COMPLETED => '#22c55e',
            Interview::STATUS_CANCELLED => '#ef4444',
            Interview::STATUS_RESCHEDULED => '#f59e0b',
            Interview::STATUS_NO_SHOW => '#6b7280',
            default => '#6b7280',
        };
    }
}
