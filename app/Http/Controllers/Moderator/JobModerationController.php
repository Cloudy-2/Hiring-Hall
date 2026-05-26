<?php

namespace App\Http\Controllers\Moderator;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use Illuminate\Http\Request;

class JobModerationController extends Controller
{
    protected function ensureModerator(Request $request)
    {
        $user = $request->user();

        if (! $user || ! in_array($user->role, ['moderator', 'admin', 'super_admin'])) {
            abort(403);
        }

        return $user;
    }

    public function index(Request $request)
    {
        $this->ensureModerator($request);

        $status = $request->input('status', 'all');
        $flagged = $request->boolean('flagged', false);
        $search = $request->input('search');

        $query = JobPosting::with(['company', 'moderatedBy']);

        if ($status === 'pending') {
            $query->pendingModeration();
        } elseif ($status === 'approved') {
            $query->approvedModeration();
        } elseif ($status === 'rejected') {
            $query->rejectedModeration();
        }

        if ($flagged) {
            $query->flagged();
        }

        if (! empty($search)) {
            $term = '%'.trim($search).'%';
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', $term)
                    ->orWhereHas('company', function ($cq) use ($term) {
                        $cq->where('name', 'like', $term);
                    });
            });
        }

        $jobs = $query->latest()->paginate(15)->withQueryString();

        $counts = [
            'all' => JobPosting::count(),
            'pending' => JobPosting::pendingModeration()->count(),
            'approved' => JobPosting::approvedModeration()->count(),
            'rejected' => JobPosting::rejectedModeration()->count(),
            'flagged' => JobPosting::flagged()->count(),
        ];

        return view('moderator.jobs.index', compact('jobs', 'status', 'flagged', 'counts', 'search'));
    }

    public function show(Request $request, $id)
    {
        $this->ensureModerator($request);

        $job = JobPosting::with(['company.user', 'moderatedBy', 'applications'])->findOrFail($id);

        return view('moderator.jobs.show', compact('job'));
    }

    public function approve(Request $request, $id)
    {
        $user = $this->ensureModerator($request);

        $job = JobPosting::findOrFail($id);

        $job->update([
            'moderation_status' => JobPosting::MODERATION_APPROVED,
            'moderated_by' => $user->id,
            'moderated_at' => now(),
            'posted_at' => now(),
            'moderation_notes' => $request->input('notes'),
            'is_flagged' => false,
            'flag_reason' => null,
        ]);

        return redirect()->back()->with('status', 'Job "'.$job->title.'" has been approved.');
    }

    public function reject(Request $request, $id)
    {
        $user = $this->ensureModerator($request);

        $validated = $request->validate([
            'notes' => 'required|string|max:1000',
        ]);

        $job = JobPosting::findOrFail($id);

        $job->update([
            'moderation_status' => JobPosting::MODERATION_REJECTED,
            'moderated_by' => $user->id,
            'moderated_at' => now(),
            'moderation_notes' => $validated['notes'],
            'status' => 'archived',
        ]);

        return redirect()->back()->with('status', 'Job "'.$job->title.'" has been rejected.');
    }

    public function flag(Request $request, $id)
    {
        $this->ensureModerator($request);

        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $job = JobPosting::findOrFail($id);

        $job->update([
            'is_flagged' => true,
            'flag_reason' => $validated['reason'],
        ]);

        return redirect()->back()->with('status', 'Job "'.$job->title.'" has been flagged for review.');
    }

    public function unflag(Request $request, $id)
    {
        $this->ensureModerator($request);

        $job = JobPosting::findOrFail($id);

        $job->update([
            'is_flagged' => false,
            'flag_reason' => null,
        ]);

        return redirect()->back()->with('status', 'Flag removed from "'.$job->title.'".');
    }

    public function bulkApprove(Request $request)
    {
        $user = $this->ensureModerator($request);

        $validated = $request->validate([
            'job_ids' => 'required|array',
            'job_ids.*' => 'exists:job_postings,id',
        ]);

        JobPosting::whereIn('id', $validated['job_ids'])->update([
            'moderation_status' => JobPosting::MODERATION_APPROVED,
            'moderated_by' => $user->id,
            'moderated_at' => now(),
            'posted_at' => now(),
            'is_flagged' => false,
            'flag_reason' => null,
        ]);

        return redirect()->back()->with('status', count($validated['job_ids']).' jobs have been approved.');
    }

    public function bulkReject(Request $request)
    {
        $user = $this->ensureModerator($request);

        $validated = $request->validate([
            'job_ids' => 'required|array',
            'job_ids.*' => 'exists:job_postings,id',
            'notes' => 'required|string|max:1000',
        ]);

        JobPosting::whereIn('id', $validated['job_ids'])->update([
            'moderation_status' => JobPosting::MODERATION_REJECTED,
            'moderated_by' => $user->id,
            'moderated_at' => now(),
            'moderation_notes' => $validated['notes'],
            'status' => 'archived',
        ]);

        return redirect()->back()->with('status', count($validated['job_ids']).' jobs have been rejected.');
    }
}
