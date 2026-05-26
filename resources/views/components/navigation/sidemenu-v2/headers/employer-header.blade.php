{{-- Sidemenu V2 - Employer (Header Version) --}}

<li class="relative group">
    <a href="javascript:void(0);"
        class="flex items-center gap-1.5 font-medium text-[14px] px-3 py-2 rounded-md transition-colors text-defaulttextcolor dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-primary dark:hover:text-primary cursor-pointer">
        <span>Employer</span>
        <i class="fe fe-chevron-down text-[11px] opacity-70"></i>
    </a>
    <ul
        class="absolute left-0 top-full mt-1 min-w-[200px] bg-white dark:bg-bodybg rounded-lg shadow-lg border border-gray-100 dark:border-white/10 py-1.5 z-[100] header-dropdown-menu">
        <li>
            <a href="/employer/dashboard"
                class="block px-4 py-2 text-[14px] {{ request()->is('employer/dashboard*') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                Dashboard
            </a>
        </li>
        <li>
            <a href="/applicants"
                class="block px-4 py-2 text-[14px] {{ request()->is('applicants*') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                Search Applicants
            </a>
        </li>
    </ul>
</li>

<li class="relative group">
    <a href="javascript:void(0);"
        class="flex items-center gap-1.5 font-medium text-[14px] px-3 py-2 rounded-md transition-colors text-defaulttextcolor dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-primary dark:hover:text-primary cursor-pointer">
        <span>Company</span>
        <i class="fe fe-chevron-down text-[11px] opacity-70"></i>
    </a>
    <ul
        class="absolute left-0 top-full mt-1 min-w-[200px] bg-white dark:bg-bodybg rounded-lg shadow-lg border border-gray-100 dark:border-white/10 py-1.5 z-[100] header-dropdown-menu">
        <li>
            <a href="/employer/companies"
                class="block px-4 py-2 text-[14px] {{ request()->is('employer/companies*') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                My Companies
            </a>
        </li>
    </ul>
</li>

<li class="relative group">
    <a href="javascript:void(0);"
        class="flex items-center gap-1.5 font-medium text-[14px] px-3 py-2 rounded-md transition-colors text-defaulttextcolor dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-primary dark:hover:text-primary cursor-pointer">
        <span>Jobs</span>
        <i class="fe fe-chevron-down text-[11px] opacity-70"></i>
    </a>
    <ul
        class="absolute left-0 top-full mt-1 min-w-[200px] bg-white dark:bg-bodybg rounded-lg shadow-lg border border-gray-100 dark:border-white/10 py-1.5 z-[100] header-dropdown-menu">
        <li>
            <a href="{{ route('jobs.create') }}"
                class="block px-4 py-2 text-[14px] {{ request()->is('jobs/create') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                Post a Job
            </a>
        </li>
        <li>
            <a href="/employer/jobs"
                class="block px-4 py-2 text-[14px] {{ request()->is('employer/jobs*') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                My Job Posts
            </a>
        </li>
        <li>
            <a href="/employer/templates"
                class="block px-4 py-2 text-[14px] {{ request()->is('employer/templates*') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                Job Templates
            </a>
        </li>
    </ul>
</li>

<li class="relative group">
    <a href="javascript:void(0);"
        class="flex items-center gap-1.5 font-medium text-[14px] px-3 py-2 rounded-md transition-colors text-defaulttextcolor dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-primary dark:hover:text-primary cursor-pointer">
        <span>Applicants</span>
        <i class="fe fe-chevron-down text-[11px] opacity-70"></i>
    </a>
    <ul
        class="absolute left-0 top-full mt-1 min-w-[200px] bg-white dark:bg-bodybg rounded-lg shadow-lg border border-gray-100 dark:border-white/10 py-1.5 z-[100] header-dropdown-menu">
        <li>
            <a href="/employer/applications"
                class="block px-4 py-2 text-[14px] {{ request()->is('employer/applications*') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                All Applicants
            </a>
        </li>
        <li>
            <a href="/employer/pipeline"
                class="block px-4 py-2 text-[14px] {{ request()->is('employer/pipeline*') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                Hiring Pipeline
            </a>
        </li>
        <li>
            <a href="/employer/interviews"
                class="block px-4 py-2 text-[14px] {{ request()->is('employer/interviews*') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                Interviews
            </a>
        </li>
        <li>
            <a href="/employer/history"
                class="block px-4 py-2 text-[14px] {{ request()->is('employer/history*') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                History
            </a>
        </li>
        <li>
            <a href="/employer/saved-candidates"
                class="block px-4 py-2 text-[14px] {{ request()->is('employer/saved-candidates*') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                Saved Applicants
            </a>
        </li>
    </ul>
</li>

<li class="relative group">
    <a href="javascript:void(0);"
        class="flex items-center gap-1.5 font-medium text-[14px] px-3 py-2 rounded-md transition-colors text-defaulttextcolor dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-primary dark:hover:text-primary cursor-pointer">
        <span>Account</span>
        <i class="fe fe-chevron-down text-[11px] opacity-70"></i>
    </a>
    <ul
        class="absolute left-0 top-full mt-1 min-w-[200px] bg-white dark:bg-bodybg rounded-lg shadow-lg border border-gray-100 dark:border-white/10 py-1.5 z-[100] header-dropdown-menu">
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
        class="flex items-center gap-1.5 font-medium text-[14px] px-3 py-2 rounded-md transition-colors text-defaulttextcolor dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-primary dark:hover:text-primary cursor-pointer">
        <span>Support</span>
        <i class="fe fe-chevron-down text-[11px] opacity-70"></i>
    </a>
    <ul
        class="absolute left-0 top-full mt-1 min-w-[200px] bg-white dark:bg-bodybg rounded-lg shadow-lg border border-gray-100 dark:border-white/10 py-1.5 z-[100] header-dropdown-menu">
        <li>
            <a href="/FAQ"
                class="block px-4 py-2 text-[14px] {{ request()->is('FAQ') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                FAQ
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
                class="block px-4 py-2 text-[14px] {{ request()->is('tickets/list*') ? 'text-primary font-medium bg-primary/5' : 'text-gray-600 dark:text-gray-300 hover:text-primary hover:bg-gray-50 dark:hover:bg-white/5' }} transition-colors">
                Help & Support
            </a>
        </li>
    </ul>
</li>