{{-- Sidemenu V2 - Moderator/Admin (Header Version) --}}

<li class="relative group">
    <a href="javascript:void(0);"
        class="flex items-center gap-1.5 font-medium text-[14px] px-3 py-2 rounded-md transition-colors text-defaulttextcolor dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-primary dark:hover:text-primary cursor-pointer">
        <span>Dashboard</span>
        <i class="fe fe-chevron-down text-[11px] opacity-70"></i>
    </a>
    <ul
        class="absolute left-0 top-full mt-1 min-w-[200px] bg-white dark:bg-bodybg rounded-lg shadow-lg border border-gray-100 dark:border-white/10 py-1.5 z-[100] header-dropdown-menu">
        @php
            $dashboardRoute = '/moderator/dashboard';
            $dashboardLabel = 'Dashboard';
            if (auth()->user() && in_array(auth()->user()->role, ['admin', 'super_admin'])) {
                $dashboardRoute = '/admin/dashboard';
                $dashboardLabel = 'Admin Dashboard';
            }
        @endphp
        <li>
            <a href="{{ $dashboardRoute }}"
                class="block px-4 py-2 text-[14px] {{ request()->is('dashboard') || request()->is('admin/dashboard') || request()->is('moderator/dashboard') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                {{ $dashboardLabel }}
            </a>
        </li>
        @php
            $announcementCount = \App\Models\Announcement::getUnreadCountForUser(auth()->user());
        @endphp
        <li>
            <a href="/announcements"
                class="flex justify-between items-center px-4 py-2 text-[14px] {{ request()->is('announcements*') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                <span>Announcements</span>
                @if($announcementCount > 0)
                    <span
                        class="bg-primary text-white text-[10px] px-1.5 py-0.5 rounded-full">{{ $announcementCount }}</span>
                @endif
            </a>
        </li>
    </ul>
</li>

<li class="relative group">
    <a href="javascript:void(0);"
        class="flex items-center gap-1.5 font-medium text-[14px] px-3 py-2 rounded-md transition-colors text-defaulttextcolor dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-primary dark:hover:text-primary cursor-pointer">
        <span>Moderator Tools</span>
        <i class="fe fe-chevron-down text-[11px] opacity-70"></i>
    </a>
    <ul
        class="absolute left-0 top-full mt-1 min-w-[240px] bg-white dark:bg-bodybg rounded-lg shadow-lg border border-gray-100 dark:border-white/10 py-1.5 z-[100] header-dropdown-menu">
        <li>
            <a href="/chats/monitor"
                class="block px-4 py-2 text-[14px] {{ request()->is('chats/monitor*') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                Chat Moderator
            </a>
        </li>
        <li>
            <a href="{{ route('moderator.chat-reports.index') }}"
                class="block px-4 py-2 text-[14px] {{ request()->is('moderator/chat-reports*') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                Reported messages
            </a>
        </li>
        <li>
            <a href="/moderator/users"
                class="block px-4 py-2 text-[14px] {{ request()->is('moderator/users*') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                Manage Users
            </a>
        </li>
        <li>
            <a href="/moderator/job-form-options"
                class="block px-4 py-2 text-[14px] {{ request()->is('moderator/job-form-options*') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                Job Form Options
            </a>
        </li>
        <li>
            <a href="/moderator/applicant-profile-options"
                class="block px-4 py-2 text-[14px] {{ request()->is('moderator/applicant-profile-options*') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                Applicant Form Options
            </a>
        </li>
        <li>
            <a href="{{ route('moderator.companies.index') }}"
                class="block px-4 py-2 text-[14px] {{ request()->is('moderator/companies*') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                Company Verification
            </a>
        </li>
        <li>
            <a href="{{ route('moderator.jobs.index') }}"
                class="block px-4 py-2 text-[14px] {{ request()->is('moderator/jobs*') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                Job Moderation
            </a>
        </li>
        <li>
            <a href="{{ route('moderator.applicants.index') }}"
                class="block px-4 py-2 text-[14px] {{ request()->is('moderator/applicants*') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                Applicant Verification
            </a>
        </li>
        <li>
            <a href="{{ route('moderator.ratings.index') }}"
                class="block px-4 py-2 text-[14px] {{ request()->is('moderator/ratings*') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                Rating Management
            </a>
        </li>
        <li>
            <a href="{{ route('moderator.tickets.index') }}"
                class="block px-4 py-2 text-[14px] {{ request()->is('moderator/tickets*') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                Manage Support Tickets
            </a>
        </li>
    </ul>
</li>

@if(auth()->user() && in_array(auth()->user()->role, ['admin', 'super_admin']))
    <li class="relative group">
        <a href="javascript:void(0);"
            class="flex items-center gap-1.5 font-medium text-[14px] px-3 py-2 rounded-md transition-colors text-defaulttextcolor dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-primary dark:hover:text-primary cursor-pointer">
            <span>Admin Tools</span>
            <i class="fe fe-chevron-down text-[11px] opacity-70"></i>
        </a>
        <ul
            class="absolute left-0 top-full mt-1 min-w-[200px] bg-white dark:bg-bodybg rounded-lg shadow-lg border border-gray-100 dark:border-white/10 py-1.5 z-[100] header-dropdown-menu">
            <li>
                <a href="/admin/staff"
                    class="block px-4 py-2 text-[14px] {{ request()->is('admin/staff*') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                    Manage Staff
                </a>
            </li>
            @if(auth()->user() && auth()->user()->role === 'super_admin')
                <li>
                    <a href="/admin/administrators"
                        class="block px-4 py-2 text-[14px] {{ request()->is('admin/administrators*') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                        Manage Admins
                    </a>
                </li>
            @endif
        </ul>
    </li>

    <li class="relative group">
        <a href="javascript:void(0);"
            class="flex items-center gap-1.5 font-medium text-[14px] px-3 py-2 rounded-md transition-colors text-defaulttextcolor hover:bg-gray-50 dark:hover:bg-white/5 hover:text-primary cursor-pointer">
            <span>Reports & Logs</span>
            <i class="fe fe-chevron-down text-[11px] opacity-70"></i>
        </a>
        <ul
            class="absolute left-0 top-full mt-1 min-w-[220px] bg-white dark:bg-bodybg rounded-lg shadow-lg border border-gray-100 dark:border-white/10 py-1.5 z-[100] header-dropdown-menu">
            <li>
                <a href="{{ route('moderator.reports.index') }}"
                    class="block px-4 py-2 text-[14px] {{ request()->is('admin/reports*') || request()->is('moderator/reports*') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                    Reports & Analytics
                </a>
            </li>
            <li>
                <a href="{{ route('moderator.activity-logs.index') }}"
                    class="block px-4 py-2 text-[14px] {{ request()->is('admin/activity-logs*') || request()->is('moderator/activity-logs*') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                    Activity Logs
                </a>
            </li>
            <li>
                <a href="{{ route('admin.impersonation-logs.index') }}"
                    class="block px-4 py-2 text-[14px] {{ request()->is('admin/impersonation-logs*') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                    Impersonation Logs
                </a>
            </li>
        </ul>
    </li>
@endif

<li class="relative group">
    <a href="javascript:void(0);"
        class="flex items-center gap-1.5 font-medium text-[14px] px-3 py-2 rounded-md transition-colors text-defaulttextcolor hover:bg-gray-50 dark:hover:bg-white/5 hover:text-primary cursor-pointer">
        <span>Content & Comm</span>
        <i class="fe fe-chevron-down text-[11px] opacity-70"></i>
    </a>
    <ul
        class="absolute left-0 top-full mt-1 min-w-[220px] bg-white dark:bg-bodybg rounded-lg shadow-lg border border-gray-100 dark:border-white/10 py-1.5 z-[100] header-dropdown-menu">
        <li>
            <a href="{{ route('moderator.announcements.index') }}"
                class="block px-4 py-2 text-[14px] {{ request()->is('moderator/announcements*') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                Announcements
            </a>
        </li>
        <li>
            <a href="{{ route('moderator.faqs.index') }}"
                class="block px-4 py-2 text-[14px] {{ request()->is('moderator/faqs*') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                FAQ Management
            </a>
        </li>
        <li>
            <a href="{{ route('moderator.bulk-notifications.index') }}"
                class="block px-4 py-2 text-[14px] {{ request()->is('moderator/bulk-notifications*') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                Bulk Notifications
            </a>
        </li>
    </ul>
</li>

<li class="relative group">
    <a href="javascript:void(0);"
        class="flex items-center gap-1.5 font-medium text-[14px] px-3 py-2 rounded-md transition-colors text-defaulttextcolor hover:bg-gray-50 dark:hover:bg-white/5 hover:text-primary cursor-pointer">
        <span>App Menu</span>
        <i class="fe fe-chevron-down text-[11px] opacity-70"></i>
    </a>
    <ul
        class="absolute left-0 top-full mt-1 min-w-[200px] bg-white dark:bg-bodybg rounded-lg shadow-lg border border-gray-100 dark:border-white/10 py-1.5 z-[100] header-dropdown-menu">
        <li>
            <a href="/chats/v2"
                class="block px-4 py-2 text-[14px] {{ request()->is('chats*') && !request()->is('chats/monitor*') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                Messages
            </a>
        </li>
        <li>
            <a href="/drive/file-manager"
                class="block px-4 py-2 text-[14px] {{ request()->is('drive/file-manager*') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                File Manager
            </a>
        </li>
        <li>
            <a href="/relationship/list"
                class="block px-4 py-2 text-[14px] {{ request()->is('relationship/list*') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                Relationship
            </a>
        </li>
    </ul>
</li>

<li class="relative group">
    <a href="javascript:void(0);"
        class="flex items-center gap-1.5 font-medium text-[14px] px-3 py-2 rounded-md transition-colors text-defaulttextcolor hover:bg-gray-50 dark:hover:bg-white/5 hover:text-primary cursor-pointer">
        <span>Support</span>
        <i class="fe fe-chevron-down text-[11px] opacity-70"></i>
    </a>
    <ul
        class="absolute left-0 top-full mt-1 min-w-[200px] bg-white dark:bg-bodybg rounded-lg shadow-lg border border-gray-100 dark:border-white/10 py-1.5 z-[100] header-dropdown-menu">
        <li>
            <a href="/FAQ"
                class="block px-4 py-2 text-[14px] {{ request()->is('FAQ') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                FAQ's
            </a>
        </li>
        <li>
            <a href="{{ route('moderator.release-notes.index') }}"
                class="block px-4 py-2 text-[14px] {{ request()->is('moderator/release-notes*') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                Version Management
            </a>
        </li>
    </ul>
</li>