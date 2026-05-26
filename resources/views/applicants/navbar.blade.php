<nav class="mb-4">
    <div class="box border">
        <div class="box-body py-3">
            <ul class="flex flex-wrap gap-2 text-[13px]">
                {{-- Dashboard --}}
                @php $isDashboard = request()->is('candidate/dashboard'); @endphp
                <li>
                    <a href="/applicant/dashboard"
                       class="ti-btn ti-btn-sm rounded-full {{ $isDashboard ? 'bg-primary text-white' : 'ti-btn-outline-light' }}">
                        <i class="ri-dashboard-line me-1"></i>
                        Dashboard
                    </a>
                </li>

                {{-- Search Jobs --}}
                @php $isJobs = request()->routeIs('jobs') || request()->routeIs('jobs.show'); @endphp
                <li>
                    <a href="{{ route('jobs') }}"
                       class="ti-btn ti-btn-sm rounded-full {{ $isJobs ? 'bg-primary text-white' : 'ti-btn-outline-light' }}">
                        <i class="ri-briefcase-search-line me-1"></i>
                        Search Jobs
                    </a>
                </li>

                {{-- My Applications --}}
                @php $isApplications = request()->is('candidate/applications*'); @endphp
                <li>
                    <a href="/applicant/applications"
                       class="ti-btn ti-btn-sm rounded-full {{ $isApplications ? 'bg-primary text-white' : 'ti-btn-outline-light' }}">
                        <i class="ri-file-list-3-line me-1"></i>
                        My Applications
                    </a>
                </li>

                {{-- My Profile --}}
                @php $isProfile = request()->is('candidate/profile'); @endphp
                <li>
                    <a href="/applicant/profile"
                       class="ti-btn ti-btn-sm rounded-full {{ $isProfile ? 'bg-primary text-white' : 'ti-btn-outline-light' }}">
                        <i class="ri-user-line me-1"></i>
                        My Profile
                    </a>
                </li>

                {{-- Recommended & Saved Jobs --}}
                @php $isRecommended = request()->is('candidate/recommended-jobs*', 'candidate/saved-jobs*'); @endphp
                <li>
                    <a href="/applicant/recommended-jobs"
                       class="ti-btn ti-btn-sm rounded-full {{ $isRecommended ? 'bg-primary text-white' : 'ti-btn-outline-light' }}">
                        <i class="ri-bookmark-line me-1"></i>
                        Recommended & Saved Jobs
                    </a>
                </li>

                {{-- Messages / Chat --}}
                @php $isMessages = request()->is('chats*'); @endphp
                <li>
                    <a href="/chats/v2"
                       class="ti-btn ti-btn-sm rounded-full {{ $isMessages ? 'bg-primary text-white' : 'ti-btn-outline-light' }}">
                        <i class="ri-chat-1-line me-1"></i>
                        Messages
                    </a>
                </li>

                {{-- Settings / Account --}}
                @php $isSettings = request()->is('user/profile'); @endphp
                <li>
                    <a href="/user/profile"
                       class="ti-btn ti-btn-sm rounded-full {{ $isSettings ? 'bg-primary text-white' : 'ti-btn-outline-light' }}">
                        <i class="ri-settings-3-line me-1"></i>
                        Settings
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
