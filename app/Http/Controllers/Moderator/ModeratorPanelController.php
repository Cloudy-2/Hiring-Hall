<?php

namespace App\Http\Controllers\Moderator;

use App\Http\Controllers\Controller;
use App\Models\Chats\Conversation;
use App\Models\Chats\DiscussionTopic;
use App\Models\Chats\Message;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ModeratorPanelController extends Controller
{
    protected function ensureModerator(Request $request)
    {
        $user = $request->user();

        if (! $user || ! in_array($user->role, ['moderator', 'admin', 'super_admin'])) {
            abort(403);
        }

        return $user;
    }

    protected function ensureAdmin(Request $request)
    {
        $user = $request->user();

        if (! $user || ! in_array($user->role, ['admin', 'super_admin'])) {
            abort(403);
        }

        return $user;
    }

    protected function ensureSuperAdmin(Request $request)
    {
        $user = $request->user();

        if (! $user || $user->role !== 'super_admin') {
            abort(403);
        }

        return $user;
    }

    /**
     * Moderator Dashboard
     */
    public function dashboard(Request $request)
    {
        $user = $this->ensureModerator($request);

        // Get stats for the dashboard
        $stats = [
            'total_users' => User::count(),
            'total_applicants' => User::where('role', 'applicant')->count(),
            'total_employers' => User::where('role', 'employer')->count(),
            'total_jobs' => JobPosting::count(),
            'active_jobs' => JobPosting::where('status', 'active')->count(),
            'total_applications' => JobApplication::count(),
            'total_conversations' => Conversation::count(),
            'total_messages' => Message::count(),
            'total_release_notes' => \App\Models\ReleaseNote::count(),
        ];

        // Paginated data
        $recentUsers = User::latest()->paginate(5, ['*'], 'users_page');
        $recentJobs = JobPosting::with('company')->latest()->paginate(10, ['*'], 'jobs_page');

        $recentMessages = Message::with(['user', 'conversation.topics'])
            ->latest()
            ->take(100)
            ->get()
            ->groupBy('conversation_id')
            ->take(10)
            ->map(function ($messages) {
                $topicMessageIds = DB::table('chat_discussion_topic_messages')
                    ->whereIn('message_id', $messages->pluck('id'))
                    ->get()
                    ->groupBy('topic_id');

                $messagesByTopic = [];
                $generalMessages = collect();

                foreach ($messages as $message) {
                    $foundInTopic = false;
                    foreach ($topicMessageIds as $topicId => $topicMsgs) {
                        if ($topicMsgs->pluck('message_id')->contains($message->id)) {
                            if (! isset($messagesByTopic[$topicId])) {
                                $messagesByTopic[$topicId] = collect();
                            }
                            $messagesByTopic[$topicId]->push($message);
                            $foundInTopic = true;
                            break;
                        }
                    }
                    if (! $foundInTopic) {
                        $generalMessages->push($message);
                    }
                }

                $topics = DiscussionTopic::whereIn('id', array_keys($messagesByTopic))->get()->keyBy('id');

                return [
                    'messages' => $messages,
                    'general' => $generalMessages,
                    'byTopic' => $messagesByTopic,
                    'topics' => $topics,
                ];
            });

        return view('moderator.dashboard', compact('stats', 'recentUsers', 'recentJobs', 'recentMessages'));
    }

    /**
     * Admin Dashboard (for admin and super_admin roles)
     */
    public function adminDashboard(Request $request)
    {
        $user = $this->ensureAdmin($request);

        // Get stats for the admin dashboard
        $stats = [
            'total_users' => User::count(),
            'total_applicants' => User::where('role', 'applicant')->count(),
            'total_employers' => User::where('role', 'employer')->count(),
            'total_moderators' => User::where('role', 'moderator')->count(),
            'total_admins' => User::whereIn('role', ['admin', 'super_admin'])->count(),
            'total_staff' => User::whereIn('role', ['moderator', 'admin', 'super_admin'])->count(),
            'pending_companies' => \App\Models\Company::where('verification_status', 'pending')->count(),
            'total_jobs' => JobPosting::count(),
            'active_jobs' => JobPosting::where('status', 'active')->count(),
            'total_applications' => JobApplication::count(),
            'total_conversations' => Conversation::count(),
            'total_messages' => Message::count(),
            'total_release_notes' => \App\Models\ReleaseNote::count(),
        ];

        // Paginated data for admin dashboard
        $recentUsers = User::latest()->paginate(10, ['*'], 'users_page');
        $recentJobs = JobPosting::with('company')->latest()->paginate(10, ['*'], 'jobs_page');

        $recentMessages = Message::with(['user', 'conversation.topics'])
            ->latest()
            ->take(100)
            ->get()
            ->groupBy('conversation_id')
            ->take(10)
            ->map(function ($messages) {
                return [
                    'messages' => $messages,
                    'general' => $messages,
                    'byTopic' => [],
                    'topics' => collect(),
                ];
            });

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentJobs', 'recentMessages'));
    }

    public function users(Request $request)
    {
        $this->ensureModerator($request);

        $query = User::whereIn('role', ['employer', 'applicant', 'moderator']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }

        // Filter by verification status
        if ($request->filled('verification') && $request->verification !== 'all') {
            if ($request->verification === 'verified') {
                $query->whereNotNull('email_verified_at');
            } elseif ($request->verification === 'unverified') {
                $query->whereNull('email_verified_at');
            }
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        // Calculate role counts for KPI cards
        $stats = [
            'total' => User::whereIn('role', ['employer', 'applicant', 'moderator'])->count(),
            'applicant' => User::where('role', 'applicant')->count(),
            'employer' => User::where('role', 'employer')->count(),
            'moderator' => User::where('role', 'moderator')->count(),
            'verified' => User::whereIn('role', ['employer', 'applicant', 'moderator'])->whereNotNull('email_verified_at')->count(),
            'unverified' => User::whereIn('role', ['employer', 'applicant', 'moderator'])->whereNull('email_verified_at')->count(),
        ];

        // Return partial HTML for AJAX infinite scroll requests
        if ($request->ajax()) {
            $listHtml = '';
            $galleryHtml = '';

            foreach ($users as $user) {
                $listHtml .= view('modules.users.partials.list-item', compact('user'))->render();
                $galleryHtml .= view('modules.users.partials.gallery-item', compact('user'))->render();
            }

            return response()->json([
                'list_html' => $listHtml,
                'gallery_html' => $galleryHtml,
                'has_more' => $users->hasMorePages(),
                'next_page' => $users->currentPage() + 1,
                'total' => $users->total(),
            ]);
        }

        return view('modules.users.index', [
            'users' => $users,
            'stats' => $stats,
            'pageTitle' => 'Manage Users',
            'active' => 'Manage Users',
        ]);
    }

    public function updateUserRole(Request $request, User $user)
    {
        $this->ensureModerator($request);

        // Prevent changing own role
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'You cannot change your own role.');
        }

        $request->validate([
            'role' => 'required|in:applicant,employer,moderator',
        ]);

        $oldRole = $user->role;
        $user->role = $request->role;
        $user->save();

        return back()->with('success', "User role changed from {$oldRole} to {$request->role} successfully.");
    }

    /**
     * Staff management page (admin can create moderators/employees)
     */
    public function staff(Request $request)
    {
        $this->ensureAdmin($request);

        $query = User::whereIn('role', ['moderator', 'employer', 'applicant']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        return view('admin.staff', [
            'users' => $users,
            'pageTitle' => 'Staff Management',
            'active' => 'Staff Management',
        ]);
    }

    /**
     * Create a new staff member (moderator or employee)
     */
    public function createStaff(Request $request)
    {
        $this->ensureAdmin($request);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:moderator,employer,applicant',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'email_verified_at' => now(),
        ]);

        return back()->with('success', "Staff member {$user->name} created successfully as {$request->role}.");
    }

    /**
     * Administrator management page (super_admin can create admin accounts)
     */
    public function administrators(Request $request)
    {
        $this->ensureSuperAdmin($request);

        $query = User::whereIn('role', ['admin', 'super_admin']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        return view('admin.administrators', [
            'users' => $users,
            'pageTitle' => 'Administrator Management',
            'active' => 'Administrator Management',
        ]);
    }

    /**
     * Create a new administrator
     */
    public function createAdministrator(Request $request)
    {
        $this->ensureSuperAdmin($request);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'email_verified_at' => now(),
        ]);

        return back()->with('success', "Administrator {$user->name} created successfully.");
    }

    /**
     * Toggle email verification status for a user
     */
    public function toggleEmailVerification(Request $request, User $user)
    {
        if (in_array($user->role, ['admin', 'super_admin'])) {
            $this->ensureSuperAdmin($request);
        } else {
            $this->ensureAdmin($request);
        }

        if ($user->email_verified_at) {
            $user->email_verified_at = null;
            $user->save();

            return back()->with('success', "Email verification removed for {$user->name}.");
        } else {
            $user->email_verified_at = now();
            $user->save();

            return back()->with('success', "Email verified for {$user->name}.");
        }
    }
}
