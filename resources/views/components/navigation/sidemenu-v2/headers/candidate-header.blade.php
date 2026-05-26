{{-- Sidemenu V2 - Candidate (Header Version) --}}

<li class="relative group">
    <a href="javascript:void(0);"
        class="flex items-center gap-1.5 font-medium text-[14px] px-3 py-2 rounded-md transition-colors text-defaulttextcolor dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-primary dark:hover:text-primary cursor-pointer">
        <span>Main</span>
        <i class="fe fe-chevron-down text-[11px] opacity-70"></i>
    </a>
    <ul
        class="absolute left-0 top-full mt-1 min-w-[200px] bg-white dark:bg-bodybg rounded-lg shadow-lg border border-gray-100 dark:border-white/10 py-1.5 z-[100] header-dropdown-menu">
        <li>
            <a href="/applicant/dashboard"
                class="block px-4 py-2 text-[14px] {{ request()->is('applicant/dashboard*') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                Dashboard
            </a>
        </li>
        <li>
            <a href="/jobs"
                class="block px-4 py-2 text-[14px] {{ request()->is('jobs*') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                Search Jobs
            </a>
        </li>
        <li>
            <a href="/applicant/recommended-jobs"
                class="block px-4 py-2 text-[14px] {{ request()->is('applicant/recommended-jobs*') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                Recommended Jobs
            </a>
        </li>
    </ul>
</li>

<li class="relative group">
    <a href="javascript:void(0);"
        class="flex items-center gap-1.5 font-medium text-[14px] px-3 py-2 rounded-md transition-colors text-defaulttextcolor dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-primary dark:hover:text-primary cursor-pointer">
        <span>Management</span>
        <i class="fe fe-chevron-down text-[11px] opacity-70"></i>
    </a>
    <ul
        class="absolute left-0 top-full mt-1 min-w-[200px] bg-white dark:bg-bodybg rounded-lg shadow-lg border border-gray-100 dark:border-white/10 py-1.5 z-[100] header-dropdown-menu">
        <li>
            <a href="/applicant/applications"
                class="block px-4 py-2 text-[14px] {{ request()->is('applicant/applications') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                My Applications
            </a>
        </li>
        <li>
            <a href="/applicant/applications/history"
                class="block px-4 py-2 text-[14px] {{ request()->is('applicant/applications/history*') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                App History
            </a>
        </li>
        @php
            $upcomingInterviewCount = auth()->check() ? \App\Models\Interview::where('applicant_id', auth()->id())->upcoming()->count() : 0;
        @endphp
        <li>
            <a href="/applicant/interviews"
                class="flex justify-between items-center px-4 py-2 text-[14px] {{ request()->is('applicant/interviews*') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                <span>Interviews</span>
                @if($upcomingInterviewCount > 0)
                    <span
                        class="bg-indigo-500 text-white text-[10px] px-1.5 py-0.5 rounded-full">{{ $upcomingInterviewCount }}</span>
                @endif
            </a>
        </li>
        <li>
            <a href="/applicant/job-alerts"
                class="block px-4 py-2 text-[14px] {{ request()->is('applicant/job-alerts*') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                Job Alerts
            </a>
        </li>
        <li>
            <a href="/applicant/saved-jobs"
                class="block px-4 py-2 text-[14px] {{ request()->is('applicant/saved-jobs*') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                Saved Jobs
            </a>
        </li>
    </ul>
</li>

<li class="relative group">
    <a href="javascript:void(0);"
        class="flex items-center gap-1.5 font-medium text-[14px] px-3 py-2 rounded-md transition-colors text-defaulttextcolor hover:bg-gray-50 dark:hover:bg-white/5 hover:text-primary cursor-pointer">
        <span>Account</span>
        <i class="fe fe-chevron-down text-[11px] opacity-70"></i>
    </a>
    <ul
        class="absolute left-0 top-full mt-1 min-w-[200px] bg-white dark:bg-bodybg rounded-lg shadow-lg border border-gray-100 dark:border-white/10 py-1.5 z-[100] header-dropdown-menu">
        <li>
            <a href="/applicant/profile"
                class="block px-4 py-2 text-[14px] {{ request()->is('applicant/profile*') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                My Profile
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
                        class="bg-red-500 text-white text-[10px] px-1.5 py-0.5 rounded-full">{{ $announcementCount }}</span>
                @endif
            </a>
        </li>
        <li>
            <a href="/chats/v2"
                class="block px-4 py-2 text-[14px] {{ request()->is('chats*') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                Messages
            </a>
        </li>
    </ul>
</li>

<li class="relative group">
    <a href="javascript:void(0);"
        class="flex items-center gap-1.5 font-medium text-[14px] px-3 py-2 rounded-md transition-colors text-defaulttextcolor hover:bg-gray-50 dark:hover:bg-white/5 hover:text-primary cursor-pointer">
        <span>Tools</span>
        <i class="fe fe-chevron-down text-[11px] opacity-70"></i>
    </a>
    <ul
        class="absolute left-0 top-full mt-1 min-w-[200px] bg-white dark:bg-bodybg rounded-lg shadow-lg border border-gray-100 dark:border-white/10 py-1.5 z-[100] header-dropdown-menu">
        <li>
            <a href="{{ route('employers.index') }}"
                class="block px-4 py-2 text-[14px] {{ request()->is('employers*', 'drive/file-manager*') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                Search Employers
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
            <a href="/release-notes"
                class="block px-4 py-2 text-[14px] {{ request()->is('release-notes*') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                Release Notes
            </a>
        </li>
        <li>
            <a href="/tickets/list"
                class="block px-4 py-2 text-[14px] {{ request()->is('tickets*') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                Help &amp; Support
            </a>
        </li>
    </ul>
</li>