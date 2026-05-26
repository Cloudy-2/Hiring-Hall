<x-app-layout>
    <x-slot name="title">Moderator Dashboard</x-slot>
    <x-slot name="active">Dashboard</x-slot>
    @include('employers.partials.employer-styles')

    <style>
        .kpi-grid {
            margin-bottom: 2rem;
        }

        .kpi-card {
            background: #fff;
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 1.5rem;
            padding: 1rem;
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.01);
            display: flex;
            align-items: center;
            gap: 1rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            text-decoration: none !important;
            cursor: pointer;
        }

        @media (min-width: 768px) {
            .kpi-card {
                padding: 1.75rem;
                gap: 1.5rem;
            }
        }

        .kpi-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
            border-color: #4f46e5;
        }

        .kpi-icon-wrapper {
            width: 3rem;
            height: 3rem;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            transition: all 0.4s ease;
            position: relative;
            z-index: 1;
            flex-shrink: 0;
        }

        @media (min-width: 768px) {
            .kpi-icon-wrapper {
                width: 4rem;
                height: 4rem;
                border-radius: 1.25rem;
                font-size: 1.75rem;
            }
        }

        /* Nav Icon Indicator */
        .kpi-nav-icon {
            opacity: 0;
            transform: translateX(-10px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            color: #94a3b8;
            font-size: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: auto;
        }

        .kpi-card:hover .kpi-nav-icon {
            opacity: 1;
            transform: translateX(0);
            color: #4f46e5;
        }

        /* Specific card color themes with gradients */
        .kpi-card-users .kpi-icon-wrapper {
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.15) 0%, rgba(79, 70, 229, 0.05) 100%);
            color: #4f46e5;
        }

        .kpi-card-applicants .kpi-icon-wrapper {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(16, 185, 129, 0.05) 100%);
            color: #10b981;
        }

        .kpi-card-employers .kpi-icon-wrapper {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(245, 158, 11, 0.05) 100%);
            color: #f59e0b;
        }

        .kpi-card-jobs .kpi-icon-wrapper {
            background: linear-gradient(135deg, rgba(14, 165, 233, 0.15) 0%, rgba(14, 165, 233, 0.05) 100%);
            color: #0ea5e9;
        }

        .kpi-card-total-jobs .kpi-icon-wrapper {
            background: linear-gradient(135deg, rgba(100, 116, 139, 0.15) 0%, rgba(100, 116, 139, 0.05) 100%);
            color: #64748b;
        }

        .kpi-card-applications .kpi-icon-wrapper {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.15) 0%, rgba(239, 68, 68, 0.05) 100%);
            color: #ef4444;
        }

        .kpi-card-conversations .kpi-icon-wrapper {
            background: linear-gradient(135deg, rgba(139, 92, 246, 0.15) 0%, rgba(139, 92, 246, 0.05) 100%);
            color: #8b5cf6;
        }

        .kpi-card-messages .kpi-icon-wrapper {
            background: linear-gradient(135deg, rgba(20, 184, 166, 0.15) 0%, rgba(20, 184, 166, 0.05) 100%);
            color: #14b8a6;
        }

        .kpi-card:hover .kpi-icon-wrapper {
            transform: scale(1.1) rotate(5deg);
        }

        .kpi-info {
            flex: 1;
            z-index: 1;
        }

        .kpi-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #64748b;
            letter-spacing: 0.025em;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }

        .kpi-value {
            display: block;
            font-size: 1.5rem;
            font-weight: 800;
            color: #1e293b;
            line-height: 1;
            letter-spacing: -0.02em;
        }

        @media (min-width: 768px) {
            .kpi-value {
                font-size: 2rem;
            }
        }

        /* Glass Decoration Effect */
        .kpi-card::after {
            content: '';
            position: absolute;
            top: -20%;
            right: -10%;
            width: 12rem;
            height: 12rem;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.4) 0%, rgba(255, 255, 255, 0) 100%);
            border-radius: 50%;
            transform: rotate(-15deg);
            pointer-events: none;
            transition: all 0.6s ease;
        }

        .kpi-card:hover::after {
            transform: scale(1.5) rotate(0deg);
            opacity: 0.6;
        }

        .kpi-card:hover::after {
            opacity: 0.15;
        }

        /* ── Dark mode overrides ── */
        [data-theme-mode="dark"] .kpi-card,
        .dark .kpi-card {
            background: rgba(255, 255, 255, 0.04);
            border-color: rgba(255, 255, 255, 0.08);
        }

        [data-theme-mode="dark"] .kpi-card:hover,
        .dark .kpi-card:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: #6366f1;
        }

        [data-theme-mode="dark"] .kpi-label,
        .dark .kpi-label {
            color: #94a3b8;
        }

        [data-theme-mode="dark"] .kpi-value,
        .dark .kpi-value {
            color: #f8fafc;
        }

        [data-theme-mode="dark"] .kpi-card::after,
        .dark .kpi-card::after {
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 70%);
        }

        [data-theme-mode="dark"] .box,
        .dark .box {
            background: rgba(255, 255, 255, 0.02);
            border-color: rgba(255, 255, 255, 0.08);
        }

        [data-theme-mode="dark"] .box-header,
        .dark .box-header {
            border-bottom-color: rgba(255, 255, 255, 0.06);
        }

        [data-theme-mode="dark"] .hover\:bg-light:hover,
        .dark .hover\:bg-light:hover {
            background-color: rgba(255, 255, 255, 0.05) !important;
        }

        /* ── Pagination Buttons Dark Mode Fix ── */
        [data-theme-mode="dark"] nav[role="navigation"] a,
        [data-theme-mode="dark"] nav[role="navigation"] span,
        .dark nav[role="navigation"] a,
        .dark nav[role="navigation"] span {
            background-color: rgba(255, 255, 255, 0.05) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            color: #d1d5db !important;
        }

        [data-theme-mode="dark"] nav[role="navigation"] a:hover,
        .dark nav[role="navigation"] a:hover {
            background-color: rgba(255, 255, 255, 0.1) !important;
            color: #fff !important;
        }
    </style>

    <x-modern-header title="Moderator Dashboard" desc="Monitor system activity, manage users, and moderate job postings.">
        <x-slot:div>
            <div class="flex gap-2 flex-wrap mb-4">
                <a href="{{ route('chats.monitor') }}" class="ti-btn ti-btn-sm ti-btn-primary"><i class="ri-chat-3-line"></i> Chat Moderator</a>
                <a href="{{ route('moderator.chat-reports.index') }}" class="ti-btn ti-btn-sm ti-btn-soft-warning"><i class="ri-flag-line"></i> Reported messages</a>
                <a href="{{ route('chats.manage.global-feed') }}" class="ti-btn ti-btn-sm ti-btn-soft-info"><i class="ri-broadcast-line"></i> Global feed</a>
                <a href="{{ route('moderator.users.index') }}" class="ti-btn ti-btn-sm ti-btn-soft-success"><i class="ri-user-settings-line"></i> Manage Users</a>
                <a href="{{ route('moderator.job-form-options') }}" class="ti-btn ti-btn-sm ti-btn-soft-secondary"><i class="ri-list-settings-line"></i> Settings</a>
            </div>
        </x-slot:div>

        <x-slot:footer>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mt-2">
                <!-- Total Users -->
                <a href="{{ route('moderator.users.index') }}" class="kpi-card kpi-card-users">
                    <div class="kpi-icon-wrapper">
                        <i class="ri-user-line"></i>
                    </div>
                    <div class="kpi-info">
                        <span class="kpi-label">Total Users</span>
                        <span class="kpi-value text-xl">{{ number_format($stats['total_users']) }}</span>
                    </div>
                </a>

                <!-- Applicants -->
                <a href="{{ route('moderator.users.index', ['role' => 'applicant']) }}"
                    class="kpi-card kpi-card-applicants">
                    <div class="kpi-icon-wrapper">
                        <i class="ri-user-search-line"></i>
                    </div>
                    <div class="kpi-info">
                        <span class="kpi-label">Applicants</span>
                        <span class="kpi-value text-xl">{{ number_format($stats['total_applicants']) }}</span>
                    </div>
                </a>

                <!-- Employers -->
                <a href="{{ route('moderator.users.index', ['role' => 'employer']) }}"
                    class="kpi-card kpi-card-employers">
                    <div class="kpi-icon-wrapper">
                        <i class="ri-building-line"></i>
                    </div>
                    <div class="kpi-info">
                        <span class="kpi-label">Employers</span>
                        <span class="kpi-value text-xl">{{ number_format($stats['total_employers']) }}</span>
                    </div>
                </a>

                <!-- Active Jobs -->
                <a href="{{ route('moderator.jobs.index', ['status' => 'active']) }}" class="kpi-card kpi-card-jobs">
                    <div class="kpi-icon-wrapper">
                        <i class="ri-briefcase-line"></i>
                    </div>
                    <div class="kpi-info">
                        <span class="kpi-label">Active Jobs</span>
                        <span class="kpi-value text-xl">{{ number_format($stats['active_jobs']) }}</span>
                    </div>
                </a>

                <!-- Total Jobs -->
                <a href="{{ route('moderator.jobs.index') }}" class="kpi-card kpi-card-total-jobs">
                    <div class="kpi-icon-wrapper">
                        <i class="ri-file-list-3-line"></i>
                    </div>
                    <div class="kpi-info">
                        <span class="kpi-label">Total Jobs</span>
                        <span class="kpi-value text-xl">{{ number_format($stats['total_jobs']) }}</span>
                    </div>
                </a>

                <!-- Applications -->
                <a href="{{ route('moderator.jobs.index') }}" class="kpi-card kpi-card-applications"
                    title="View associated jobs">
                    <div class="kpi-icon-wrapper">
                        <i class="ri-file-user-line"></i>
                    </div>
                    <div class="kpi-info">
                        <span class="kpi-label">Applications</span>
                        <span class="kpi-value text-xl">{{ number_format($stats['total_applications']) }}</span>
                    </div>
                </a>

                <!-- Conversations -->
                <a href="{{ route('chats.monitor') }}" class="kpi-card kpi-card-conversations">
                    <div class="kpi-icon-wrapper">
                        <i class="ri-chat-3-line"></i>
                    </div>
                    <div class="kpi-info">
                        <span class="kpi-label">Conversations</span>
                        <span class="kpi-value text-xl">{{ number_format($stats['total_conversations']) }}</span>
                    </div>
                </a>

                <!-- Messages -->
                <a href="{{ route('chats.monitor') }}" class="kpi-card kpi-card-messages">
                    <div class="kpi-icon-wrapper">
                        <i class="ri-message-2-line"></i>
                    </div>
                    <div class="kpi-info">
                        <span class="kpi-label">Messages</span>
                        <span class="kpi-value text-xl">{{ number_format($stats['total_messages']) }}</span>
                    </div>
                </a>
            </div>
        </x-slot:footer>
    </x-modern-header>

    <div class="px-4 mx-auto mt-6 pb-6 sm:px-6 lg:px-8">
        <div class="grid grid-cols-12 gap-4 lg:gap-6">

        <!-- Quick Actions -->
        <div class="col-span-12 lg:col-span-4">
            <div class="box border h-full">
                <div class="box-header">
                    <div class="box-title">Quick Actions</div>
                </div>
                <div class="box-body">
                    <div class="space-y-2">
                        <a href="{{ route('chats.monitor') }}"
                            class="flex items-center gap-3 p-3 rounded-lg hover:bg-light transition group">
                            <div
                                class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center group-hover:bg-primary group-hover:text-white transition">
                                <i class="ri-chat-3-line"></i>
                            </div>
                            <div>
                                <p class="font-medium">Chat Moderator</p>
                                <p class="text-xs text-textmuted">Monitor, reports, global feed</p>
                            </div>
                        </a>
                        <a href="{{ route('moderator.chat-reports.index') }}"
                            class="flex items-center gap-3 p-3 rounded-lg hover:bg-light transition group">
                            <div
                                class="w-10 h-10 rounded-lg bg-warning/10 flex items-center justify-center group-hover:bg-warning group-hover:text-white transition">
                                <i class="ri-flag-line"></i>
                            </div>
                            <div>
                                <p class="font-medium">Reported messages</p>
                                <p class="text-xs text-textmuted">Review and resolve reports</p>
                            </div>
                        </a>
                        <a href="{{ route('chats.manage.global-feed') }}"
                            class="flex items-center gap-3 p-3 rounded-lg hover:bg-light transition group">
                            <div
                                class="w-10 h-10 rounded-lg bg-info/10 flex items-center justify-center group-hover:bg-info group-hover:text-white transition">
                                <i class="ri-broadcast-line"></i>
                            </div>
                            <div>
                                <p class="font-medium">Global feed</p>
                                <p class="text-xs text-textmuted">Live message feed</p>
                            </div>
                        </a>
                        <a href="{{ route('moderator.job-form-options') }}"
                            class="flex items-center gap-3 p-3 rounded-lg hover:bg-light transition group">
                            <div
                                class="w-10 h-10 rounded-lg bg-success/10 flex items-center justify-center group-hover:bg-success group-hover:text-white transition">
                                <i class="ri-list-settings-line"></i>
                            </div>
                            <div>
                                <p class="font-medium">Job Form Options</p>
                                <p class="text-xs text-textmuted">Manage dropdown options</p>
                            </div>
                        </a>
                        <a href="{{ route('moderator.applicant-profile-options') }}"
                            class="flex items-center gap-3 p-3 rounded-lg hover:bg-light transition group">
                            <div
                                class="w-10 h-10 rounded-lg bg-warning/10 flex items-center justify-center group-hover:bg-warning group-hover:text-white transition">
                                <i class="ri-user-settings-line"></i>
                            </div>
                            <div>
                                <p class="font-medium">Profile Options</p>
                                <p class="text-xs text-textmuted">Manage applicant profile options</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Users -->
        <div class="col-span-12 lg:col-span-4">
            <div class="box border h-full">
                <div class="box-header">
                    <div class="box-title">Recent Users</div>
                </div>
                <div class="box-body flex flex-col">
                    <div class="space-y-3 flex-1">
                        @forelse($recentUsers as $recentUser)
                            <div class="flex items-center gap-3">
                                <img src="{{ $recentUser->profile_photo_url }}" alt="{{ $recentUser->name }}"
                                    class="w-10 h-10 rounded-full object-cover">
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium truncate">{{ $recentUser->name }}</p>
                                    <p class="text-xs text-textmuted">{{ ucfirst($recentUser->role) }} •
                                        {{ $recentUser->created_at->diffForHumans() }}</p>
                                </div>
                                <span
                                    class="px-2 py-1 text-xs rounded-full {{ $recentUser->role === 'applicant' ? 'bg-success/10 text-success' : ($recentUser->role === 'employer' ? 'bg-warning/10 text-warning' : 'bg-primary/10 text-primary') }}">
                                    {{ $recentUser->role === 'applicant' ? 'Applicant' : ucfirst($recentUser->role) }}
                                </span>
                            </div>
                        @empty
                            <p class="text-textmuted text-sm text-center py-4">No users yet</p>
                        @endforelse
                    </div>
                    @if($recentUsers->hasPages())
                        <div class="mt-4 pt-3 border-t border-defaultborder">
                            {{ $recentUsers->links('pagination::simple-tailwind') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Jobs -->
        <div class="col-span-12 lg:col-span-4">
            <div class="box border h-full">
                <div class="box-header">
                    <div class="box-title">Recent Jobs</div>
                </div>
                <div class="box-body flex flex-col">
                    <div class="space-y-3 flex-1">
                        @forelse($recentJobs as $job)
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-light flex items-center justify-center">
                                    <i class="ri-briefcase-line text-primary"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium truncate">{{ $job->title }}</p>
                                    <p class="text-xs text-textmuted">{{ $job->company->name ?? 'Unknown' }} •
                                        {{ $job->created_at->diffForHumans() }}</p>
                                </div>
                                <span
                                    class="px-2 py-1 text-xs rounded-full {{ $job->status === 'active' ? 'bg-success/10 text-success' : 'bg-secondary/10 text-secondary' }}">
                                    {{ ucfirst($job->status) }}
                                </span>
                            </div>
                        @empty
                            <p class="text-textmuted text-sm text-center py-4">No jobs yet</p>
                        @endforelse
                    </div>
                    @if($recentJobs->hasPages())
                        <div class="mt-4 pt-3 border-t border-defaultborder">
                            {{ $recentJobs->links('pagination::simple-tailwind') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Messages -->
        <div class="col-span-12">
            <div class="box border">
                <div class="box-header flex items-center justify-between">
                    <div class="box-title">Recent Chat Messages</div>
                    <a href="{{ route('chats.monitor') }}" class="ti-btn ti-btn-sm ti-btn-primary">
                        <i class="ri-chat-3-line me-1"></i> Open Chat Monitor
                    </a>
                </div>
                <div class="box-body">
                    <div class="space-y-3">
                        @forelse($recentMessages as $conversationId => $data)
                            @php
                                $messages = $data['messages'];
                                $conversation = $messages->first()->conversation;
                                $uniqueUsers = $messages->pluck('user')->filter()->unique('id')->take(3);
                                $userNames = $uniqueUsers->pluck('name')->join(', ');
                                $totalUsers = $messages->pluck('user')->filter()->unique('id')->count();
                                if ($totalUsers > 3) {
                                    $userNames .= ' +' . ($totalUsers - 3);
                                }
                            @endphp
                            <div class="border rounded-lg dark:border-defaultborder/10">
                                <button type="button" onclick="toggleAccordion('messages-{{ $conversationId }}')"
                                    class="w-full flex items-center justify-between gap-3 p-4 text-start rounded-lg transition hover:bg-light/50 select-none">
                                    <div class="flex items-center gap-3 flex-1 min-w-0">
                                        <img src="{{ $conversation->photo ? asset('storage/' . ltrim($conversation->photo, '/')) : 'https://api.dicebear.com/7.x/shapes/svg?seed=' . urlencode($conversation->name ?? 'Chat-' . $conversationId) . '&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981' }}"
                                            alt="{{ $conversation->name }}" class="w-10 h-10 rounded-full object-cover">
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium truncate text-sm sm:text-base">
                                                {{ $conversation->name ?? $userNames ?: 'Direct Message' }}</p>
                                            <p class="text-[10px] sm:text-xs text-textmuted">{{ $messages->count() }} messages • Last:
                                                {{ $messages->first()->created_at->diffForHumans() }}</p>
                                        </div>
                                        <div class="flex items-center -space-x-1.5">
                                            @foreach($uniqueUsers->take(3) as $chatUser)
                                                <img src="{{ $chatUser->profile_photo_path ? asset('storage/' . ltrim($chatUser->profile_photo_path, '/')) : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode($chatUser->name ?? 'User') . '&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981' }}"
                                                    alt="{{ $chatUser->name }}"
                                                    class="w-5 h-5 rounded-full border border-white dark:border-bodybg object-cover">
                                            @endforeach
                                            @if($totalUsers > 3)
                                                <span
                                                    class="w-5 h-5 rounded-full bg-primary/20 text-primary text-[10px] font-medium flex items-center justify-center border border-white dark:border-bodybg">+{{ $totalUsers - 3 }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <span class="badge bg-primary/10 text-primary">{{ $messages->count() }}</span>
                                    <svg id="icon-messages-{{ $conversationId }}" class="w-4 h-4 transition-transform"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div id="messages-{{ $conversationId }}" class="hidden">
                                    <div class="p-4 pt-0 space-y-3">
                                        @if($data['general']->count() > 0)
                                            <div class="border rounded-lg dark:border-defaultborder/10 bg-light/30">
                                                <button type="button" onclick="toggleAccordion('general-{{ $conversationId }}')"
                                                    class="w-full flex items-center justify-between gap-3 p-3 text-start rounded-lg transition hover:bg-light/50 select-none">
                                                    <div class="flex items-center gap-2">
                                                        <i class="ri-chat-1-line text-textmuted"></i>
                                                        <span class="font-medium text-sm">General</span>
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <span
                                                            class="badge bg-secondary/10 text-secondary text-xs">{{ $data['general']->count() }}</span>
                                                        <svg id="icon-general-{{ $conversationId }}"
                                                            class="w-3 h-3 transition-transform"
                                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M19 9l-7 7-7-7" />
                                                        </svg>
                                                    </div>
                                                </button>
                                                <div id="general-{{ $conversationId }}" class="hidden">
                                                    <div class="p-3 pt-0 space-y-2 max-h-48 overflow-y-auto">
                                                        @foreach($data['general']->take(10) as $message)
                                                            <div
                                                                class="flex items-start gap-2 p-2 rounded-lg hover:bg-light/50 transition">
                                                                <img src="{{ $message->user && $message->user->profile_photo_path ? asset('storage/' . ltrim($message->user->profile_photo_path, '/')) : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode($message->user->name ?? 'User') . '&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981' }}"
                                                                    alt="" class="w-6 h-6 rounded-full object-cover mt-0.5">
                                                                <div class="flex-1 min-w-0">
                                                                    <div class="flex items-center gap-2">
                                                                        <span
                                                                            class="font-medium text-sm">{{ $message->user->name ?? 'Unknown' }}</span>
                                                                        <span
                                                                            class="text-xs text-textmuted">{{ $message->created_at->diffForHumans() }}</span>
                                                                    </div>
                                                                    <p class="text-sm text-textmuted">
                                                                        {{ Str::limit($message->body, 100) ?: '[Attachment]' }}</p>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                        @if($data['general']->count() > 10)
                                                            <p class="text-xs text-textmuted text-center py-2">+
                                                                {{ $data['general']->count() - 10 }} more messages</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @foreach($data['byTopic'] as $topicId => $topicMessages)
                                            @php $topic = $data['topics'][$topicId] ?? null; @endphp
                                            @if($topic)
                                                <div class="border rounded-lg dark:border-defaultborder/10 bg-light/30">
                                                    <button type="button"
                                                        onclick="toggleAccordion('topic-{{ $conversationId }}-{{ $topicId }}')"
                                                        class="w-full flex items-center justify-between gap-3 p-3 text-start rounded-lg transition hover:bg-light/50 select-none">
                                                        <div class="flex items-center gap-2">
                                                            <i class="ri-hashtag text-primary"></i>
                                                            <span class="font-medium text-sm">{{ $topic->name }}</span>
                                                        </div>
                                                        <div class="flex items-center gap-2">
                                                            <span
                                                                class="badge bg-primary/10 text-primary text-xs">{{ $topicMessages->count() }}</span>
                                                            <svg id="icon-topic-{{ $conversationId }}-{{ $topicId }}"
                                                                class="w-3 h-3 transition-transform"
                                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M19 9l-7 7-7-7" />
                                                            </svg>
                                                        </div>
                                                    </button>
                                                    <div id="topic-{{ $conversationId }}-{{ $topicId }}" class="hidden">
                                                        <div class="p-3 pt-0 space-y-2 max-h-48 overflow-y-auto">
                                                            @foreach($topicMessages->take(10) as $message)
                                                                <div
                                                                    class="flex items-start gap-2 p-2 rounded-lg hover:bg-light/50 transition">
                                                                    <img src="{{ $message->user && $message->user->profile_photo_path ? asset('storage/' . ltrim($message->user->profile_photo_path, '/')) : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode($message->user->name ?? 'User') . '&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981' }}"
                                                                        alt="" class="w-6 h-6 rounded-full object-cover mt-0.5">
                                                                    <div class="flex-1 min-w-0">
                                                                        <div class="flex items-center gap-2">
                                                                            <span
                                                                                class="font-medium text-sm">{{ $message->user->name ?? 'Unknown' }}</span>
                                                                            <span
                                                                                class="text-xs text-textmuted">{{ $message->created_at->diffForHumans() }}</span>
                                                                        </div>
                                                                        <p class="text-sm text-textmuted">
                                                                            {{ Str::limit($message->body, 100) ?: '[Attachment]' }}</p>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                            @if($topicMessages->count() > 10)
                                                                <p class="text-xs text-textmuted text-center py-2">+
                                                                    {{ $topicMessages->count() - 10 }} more messages</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-textmuted text-sm text-center py-4">No messages yet</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <script>
            function toggleAccordion(id) {
                const content = document.getElementById(id);
                const icon = document.getElementById('icon-' + id);
                if (content.classList.contains('hidden')) {
                    content.classList.remove('hidden');
                    icon.classList.add('rotate-180');
                } else {
                    content.classList.add('hidden');
                    icon.classList.remove('rotate-180');
                }
            }
        </script>
    </div>

</x-app-layout>
