<?php

namespace App\Http\Controllers\Moderator;

use App\Http\Controllers\Controller;
use App\Jobs\SendBulkNotificationJob;
use App\Models\BulkNotification;
use App\Models\User;
use Illuminate\Http\Request;

class BulkNotificationController extends Controller
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

        $query = BulkNotification::with('sentBy');

        if ($status === 'draft') {
            $query->draft();
        } elseif ($status === 'sent') {
            $query->sent();
        } elseif ($status === 'sending') {
            $query->sending();
        }

        $notifications = $query->latest()->paginate(15)->withQueryString();

        $counts = [
            'all' => BulkNotification::count(),
            'draft' => BulkNotification::draft()->count(),
            'sending' => BulkNotification::sending()->count(),
            'sent' => BulkNotification::sent()->count(),
        ];

        return view('moderator.bulk-notifications.index', compact('notifications', 'status', 'counts'));
    }

    public function create(Request $request)
    {
        $this->ensureModerator($request);

        $userCounts = [
            'all' => User::count(),
            'applicant' => User::where('role', 'applicant')->count(),
            'employer' => User::where('role', 'employer')->count(),
            'moderator' => User::where('role', 'moderator')->count(),
            'admin' => User::whereIn('role', ['admin', 'super_admin'])->count(),
        ];

        return view('moderator.bulk-notifications.create', compact('userCounts'));
    }

    public function store(Request $request)
    {
        $user = $this->ensureModerator($request);

        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:50000',
            'notification_type' => 'required|in:email,database,both',
            'target_roles' => 'required|array|min:1',
            'target_roles.*' => 'in:all,applicant,employer,moderator,admin',
        ]);

        $notification = BulkNotification::create([
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'notification_type' => $validated['notification_type'],
            'target_roles' => $validated['target_roles'],
            'status' => BulkNotification::STATUS_DRAFT,
            'sent_by' => $user->id,
        ]);

        return redirect()->route('moderator.bulk-notifications.preview', $notification)
            ->with('status', 'Notification created. Please review before sending.');
    }

    public function preview(Request $request, BulkNotification $bulkNotification)
    {
        $this->ensureModerator($request);

        $recipientCount = $bulkNotification->getTargetUsersQuery()->count();

        return view('moderator.bulk-notifications.preview', compact('bulkNotification', 'recipientCount'));
    }

    public function send(Request $request, BulkNotification $bulkNotification)
    {
        $this->ensureModerator($request);

        if (! $bulkNotification->isDraft()) {
            return redirect()->back()->with('error', 'This notification has already been sent or is currently sending.');
        }

        SendBulkNotificationJob::dispatch($bulkNotification);

        return redirect()->route('moderator.bulk-notifications.index')
            ->with('status', 'Notification is being sent. This may take a few minutes.');
    }

    public function show(Request $request, BulkNotification $bulkNotification)
    {
        $this->ensureModerator($request);

        $bulkNotification->load('sentBy');

        return view('moderator.bulk-notifications.show', compact('bulkNotification'));
    }

    public function destroy(Request $request, BulkNotification $bulkNotification)
    {
        $this->ensureModerator($request);

        if (! $bulkNotification->isDraft()) {
            return redirect()->back()->with('error', 'Only draft notifications can be deleted.');
        }

        $bulkNotification->delete();

        return redirect()->route('moderator.bulk-notifications.index')
            ->with('status', 'Draft notification deleted.');
    }
}
