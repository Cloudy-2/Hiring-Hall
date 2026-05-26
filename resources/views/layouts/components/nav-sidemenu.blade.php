{{-- <aside class="app-sidebar" id="sidebar">

    <!-- Start::main-sidebar-header -->
    <div class="main-sidebar-header">
        <a href="index.html" class="header-logo">
            <img src="../assets/images/brand-logos/desktop-logo.png" alt="logo" class="desktop-logo">
            <img src="../assets/images/brand-logos/toggle-dark.png" alt="logo" class="toggle-dark">
            <img src="../assets/images/brand-logos/desktop-dark.png" alt="logo" class="desktop-dark">
            <img src="../assets/images/brand-logos/toggle-logo.png" alt="logo" class="toggle-logo">
            <img src="../assets/images/brand-logos/toggle-white.png" alt="logo" class="toggle-white">
            <img src="../assets/images/brand-logos/desktop-white.png" alt="logo" class="desktop-white">
        </a>
    </div>
    <!-- End::main-sidebar-header -->

    <!-- Start::main-sidebar -->
    <div class="main-sidebar" id="sidebar-scroll">

        <!-- Start::nav -->
        <nav class="main-menu-container nav nav-pills flex-col sub-open h-full">
            <div class="slide-left" id="slide-left">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path>
                </svg>
            </div>
            <ul class="main-menu flex flex-col gap-1 flex-grow">
                <!-- Start::slide__category -->
                <li class="slide__category"><span class="category-name">Main</span></li>
                <!-- End::slide__category -->

                <!-- Start::slide -->
                </li>

                <li class="slide {{ request()->is('jobs') ? 'active' : '' }}">
                    <a href="/jobs" class="side-menu__item {{ request()->is('jobs') ? 'active-menu' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            class="w-6 h-6 side-menu__icon !text-white">
                            <path
                                d="M20 3H4C3.44772 3 3 3.44772 3 4V20C3 20.5523 3.44772 21 4 21H20C20.5523 21 21 20.5523 21 20V4C21 3.44772 20.5523 3 20 3ZM5 19V5H19V19H5ZM8 8V16H10V11.4142L15.2929 16.7071L16.7071 15.2929L11.4142 10H16V8H8Z">
                            </path>
                        </svg>
                        <span class="side-menu__label">Search Jobs</span>
                    </a>
                </li>
                <li class="slide {{ request()->is('applicants') ? 'active' : '' }}">
                    <a href="/applicants"
                        class="side-menu__item {{ request()->is('applicants') ? 'active-menu' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            class="w-6 h-6 side-menu__icon !text-white">
                            <path
                                d="M20 3H4C3.44772 3 3 3.44772 3 4V20C3 20.5523 3.44772 21 4 21H20C20.5523 21 21 20.5523 21 20V4C21 3.44772 20.5523 3 20 3ZM5 19V5H19V19H5ZM8 8V16H10V11.4142L15.2929 16.7071L16.7071 15.2929L11.4142 10H16V8H8Z">
                            </path>
                        </svg>
                        <span class="side-menu__label">Search Applicants</span>
                    </a>
                </li>

                <li class="slide__category"><span class="category-name">Applications</span></li>
                <li class="slide {{ request()->is('x') ? 'active' : '' }}">
                    <a href="#" class="side-menu__item {{ request()->is('x') ? 'active-menu' : '' }}">
                        <span class="bi bi-chat-square-dots side-menu__icon"></span>
                        <span class="side-menu__label">Messages</span>
                    </a>
                </li>
                <li class="slide {{ request()->is('y') ? 'active' : '' }}">
                    <a href="#" class="side-menu__item {{ request()->is('y') ? 'active-menu' : '' }}">
                        <span class="bi bi-chat-dots side-menu__icon"></span>
                        <span class="side-menu__label">Global Chat</span>
                    </a>
                </li>
                <!-- End::slide -->

                <!-- End::slide -->



            </ul>
            <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24"
                    height="24" viewBox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path>
                </svg></div>

            @if ($user)
            <div class="mt-4 px-4 py-3 border-t border-white/10 text-white flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <div class="text-sm font-semibold truncate">{{ $user->name }}</div>
                    <div class="text-xs text-white/70 truncate">{{ $user->email }}</div>
                </div>
                <a href="/user/profile"
                    class="w-9 h-9 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 transition-colors"
                    title="Profile settings">
                    <i class="bi bi-gear text-lg"></i>
                </a>
            </div>
            @endif
        </nav>
        <!-- End::nav -->

    </div>
    <!-- End::main-sidebar -->

</aside> --}}

@php

    // session()->get('manage_portal_id');
    // session()->get('manage_portal_email');

    use App\Models\Chats\Conversation;
    use Illuminate\Support\Facades\Auth;

    $user = Auth::user();
    $clientId = null;

    $id = $clientId;

@endphp

{{-- @if(auth()->check() && method_exists(auth()->user(), 'getTheme') && auth()->user()->getTheme() == 'theme-hillbc')
<style>
    html body .app-sidebar,
    html body .app-sidebar .main-sidebar-header,
    html body .app-sidebar .main-sidebar {
        background: linear-gradient(180deg, #000428 0%, #004e92 100%) !important;
        border-color: rgba(255, 255, 255, 0.08) !important;
    }

    html body .app-sidebar .slide__category .category-name {
        color: rgba(255, 255, 255, 0.7) !important;
    }

    html body .app-sidebar .side-menu__item,
    html body .app-sidebar .side-menu__item .side-menu__label,
    html body .app-sidebar .side-menu__item .side-menu__icon {
        color: #FFFFFF !important;
    }

    html body .app-sidebar .side-menu__item:hover {
        background: rgba(255, 255, 255, 0.12) !important;
        color: #FFFF00 !important;
    }

    html body .app-sidebar .side-menu__item:hover .side-menu__label,
    html body .app-sidebar .side-menu__item:hover .side-menu__icon {
        color: #FFFF00 !important;
    }

    html body .app-sidebar .side-menu__item>div.absolute {
        background: #FFFF00 !important;
    }

    html body .app-sidebar .side-menu__item.active-menu {
        background: rgba(255, 255, 0, 0.2) !important;
        color: #FFFF00 !important;
    }

    html body .app-sidebar .side-menu__item.active-menu .side-menu__label,
    html body .app-sidebar .side-menu__item.active-menu .side-menu__icon {
        color: #FFFF00 !important;
    }
</style>
@elseif(auth()->check() && method_exists(auth()->user(), 'getTheme') && auth()->user()->getTheme() ==
'theme-brass-monkey')
<style>
    html body .app-sidebar,
    html body .app-sidebar .main-sidebar-header,
    html body .app-sidebar .main-sidebar {
        background: #5D4037 !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
    }

    html body .app-sidebar .slide__category .category-name {
        color: rgba(255, 255, 255, 0.7) !important;
    }

    html body .app-sidebar .side-menu__item,
    html body .app-sidebar .side-menu__item .side-menu__label,
    html body .app-sidebar .side-menu__item .side-menu__icon {
        color: #FFFFFF !important;
    }

    html body .app-sidebar .side-menu__item:hover {
        background: rgba(255, 215, 0, 0.2) !important;
        color: #FFD700 !important;
    }

    html body .app-sidebar .side-menu__item:hover .side-menu__label,
    html body .app-sidebar .side-menu__item:hover .side-menu__icon {
        color: #FFD700 !important;
    }

    html body .app-sidebar .side-menu__item>div.absolute {
        background: #FFD700 !important;
    }

    html body .app-sidebar .side-menu__item.active-menu {
        background: rgba(255, 215, 0, 0.2) !important;
        color: #FFD700 !important;
    }

    html body .app-sidebar .side-menu__item.active-menu .side-menu__label,
    html body .app-sidebar .side-menu__item.active-menu .side-menu__icon {
        color: #FFD700 !important;
    }
</style>
@endif --}}

<aside class="app-sidebar" id="sidebar">

    <!-- Start::main-sidebar-header -->
    <div class="main-sidebar-header relative">
        <a href="/dashboard" class="header-logo">
            <img src="/assets/raw/logo.png" alt="logo" class="desktop-logo">
            <img src="/assets/raw/logo.png" alt="logo" class="toggle-dark">
            <img src="/assets/raw/logo.png" alt="logo" class="desktop-dark">
            <img src="{{ asset('assets/logo.png') }}" alt="logo" class="toggle-logo" style="height: 50px; width: 50px;">
            <img src="/assets/raw/new.png" alt="logo" class="toggle-white">
            <img src="/assets/raw/logo.png" alt="logo" class="desktop-white">
        </a>
        <a href="javascript:;"
            class="sidemenu-toggle-inner absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 flex items-center justify-center text-white/70 hover:text-white hover:bg-white/10 rounded-lg transition-colors z-50 hidden"
            onclick="document.querySelector('.app-header .sidemenu-toggle').click();">
            <i class="ri-menu-fold-line text-lg"></i>
        </a>
    </div>
    <!-- End::main-sidebar-header -->

    <!-- Start::main-sidebar -->
    <div class="main-sidebar" id="sidebar-scroll">

        <!-- Start::nav -->
        <nav class="main-menu-container nav nav-pills flex-col sub-open h-full">
            <div class="slide-left" id="slide-left">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path>
                </svg>
            </div>
            <ul class="main-menu flex flex-col gap-1 flex-grow">

                @if ($user && $user->role === 'applicant')

                    <li class="slide__category"><span class="category-name">Applicant</span></li>

                    {{-- Dashboard --}}
                    <li class="slide" id="dashboard-menu">
                        <a href="/applicant/dashboard"
                            class="side-menu__item relative transition-all duration-300 group !hover:bg-[#f58331] hover:text-white hover:rounded-x-md hover:px-3 hover:pl-4 hover:mx-1 {{ request()->is('applicant/dashboard') ? 'active-menu' : '' }}">
                            <div
                                class="absolute left-0 top-0 h-full w-1.5 bg-[#FFBC58] rounded-r-md opacity-0 group-hover:opacity-100 transition-all duration-300">
                            </div>
                            <i class="w-6 h-4 side-menu__icon bi bi-globe" style="color: #fff !important"></i>
                            <span class="side-menu__label text-white" style="color: #fff !important">Dashboard</span>
                        </a>
                    </li>

                    {{-- Search Jobs --}}
                    <li class="slide">
                        <a href="/jobs"
                            class="side-menu__item relative transition-all duration-300 group !hover:bg-[#f58331] hover:text-white hover:rounded-x-md hover:px-3 hover:pl-4 hover:mx-1 {{ request()->is('jobs*') ? 'active-menu' : '' }}">
                            <div
                                class="absolute left-0 top-0 h-full w-1.5 bg-[#FFBC58] rounded-r-md opacity-0 group-hover:opacity-100 transition-all duration-300">
                            </div>
                            <i class="w-6 h-4 side-menu__icon bi bi-search" style="color: #fff !important"></i>
                            <span class="side-menu__label" style="color: #fff !important">Search Jobs</span>
                        </a>
                    </li>

                    {{-- Release Notes --}}
                    <li class="slide">
                        <a href="/release-notes"
                            class="side-menu__item relative transition-all duration-300 group !hover:bg-[#f58331] hover:text-white hover:rounded-x-md hover:px-3 hover:pl-4 hover:mx-1 {{ request()->is('release-notes*') ? 'active-menu' : '' }}">
                            <div
                                class="absolute left-0 top-0 h-full w-1.5 bg-[#FFBC58] rounded-r-md opacity-0 group-hover:opacity-100 transition-all duration-300">
                            </div>
                            <i class="w-6 h-4 side-menu__icon bi bi-stars" style="color: #fff !important"></i>
                            <span class="side-menu__label" style="color: #fff !important">Release Notes</span>
                        </a>
                    </li>

                    {{-- Separator: Applications --}}
                    <li class="slide__category mt-4"><span class="category-name">Applications</span></li>

                    {{-- My Applications --}}
                    <li class="slide">
                        <a href="/applicant/applications"
                            class="side-menu__item relative transition-all duration-300 group !hover:bg-[#f58331] hover:text-white hover:rounded-x-md hover:px-3 hover:pl-4 hover:mx-1 {{ request()->is('applicant/applications') ? 'active-menu' : '' }}">
                            <div
                                class="absolute left-0 top-0 h-full w-1.5 bg-[#FFBC58] rounded-r-md opacity-0 group-hover:opacity-100 transition-all duration-300">
                            </div>
                            <i class="w-6 h-4 side-menu__icon bi bi-list-check" style="color: #fff !important"></i>
                            <span class="side-menu__label" style="color: #fff !important">My Applications</span>
                        </a>
                    </li>

                    {{-- Application History --}}
                    <li class="slide">
                        <a href="/applicant/applications/history"
                            class="side-menu__item relative transition-all duration-300 group !hover:bg-[#f58331] hover:text-white hover:rounded-x-md hover:px-3 hover:pl-4 hover:mx-1 {{ request()->is('applicant/applications/history*') ? 'active-menu' : '' }}">
                            <div
                                class="absolute left-0 top-0 h-full w-1.5 bg-[#FFBC58] rounded-r-md opacity-0 group-hover:opacity-100 transition-all duration-300">
                            </div>
                            <i class="w-6 h-4 side-menu__icon bi bi-clock-history" style="color: #fff !important"></i>
                            <span class="side-menu__label" style="color: #fff !important">Application History</span>
                        </a>
                    </li>

                    {{-- Saved Jobs --}}
                    <li class="slide">
                        <a href="/applicant/saved-jobs"
                            class="side-menu__item relative transition-all duration-300 group !hover:bg-[#f58331] hover:text-white hover:rounded-x-md hover:px-3 hover:pl-4 hover:mx-1 {{ request()->is('applicant/saved-jobs*') ? 'active-menu' : '' }}">
                            <div
                                class="absolute left-0 top-0 h-full w-1.5 bg-[#FFBC58] rounded-r-md opacity-0 group-hover:opacity-100 transition-all duration-300">
                            </div>
                            <i class="w-6 h-4 side-menu__icon bi bi-bookmark" style="color: #fff !important"></i>
                            <span class="side-menu__label" style="color: #fff !important">Saved Jobs</span>
                        </a>
                    </li>

                    {{-- Separator: Account --}}
                    <li class="slide__category mt-4"><span class="category-name">Account</span></li>

                    {{-- My Profile --}}
                    <li class="slide">
                        <a href="/applicant/profile"
                            class="side-menu__item relative transition-all duration-300 group !hover:bg-[#f58331] hover:text-white hover:rounded-x-md hover:px-3 hover:pl-4 hover:mx-1 {{ request()->is('applicant/profile*') ? 'active-menu' : '' }}">
                            <div
                                class="absolute left-0 top-0 h-full w-1.5 bg-[#FFBC58] rounded-r-md opacity-0 group-hover:opacity-100 transition-all duration-300">
                            </div>
                            <i class="w-6 h-4 side-menu__icon bi bi-person" style="color: #fff !important"></i>
                            <span class="side-menu__label" style="color: #fff !important">My Profile</span>
                        </a>
                    </li>

                    {{-- Messages --}}
                    <li class="slide" id="dashboard-menu">
                        <a href="/chats/v2"
                            class="side-menu__item relative transition-all duration-300 group !hover:bg-[#f58331] hover:text-white hover:rounded-x-md hover:px-3 hover:pl-4 hover:mx-1 {{ request()->is('chats*') ? 'active-menu' : '' }}">
                            <div
                                class="absolute left-0 top-0 h-full w-1.5 bg-[#FFBC58] rounded-r-md opacity-0 group-hover:opacity-100 transition-all duration-300">
                            </div>
                            <style>
                                .icon-badge {
                                    position: absolute;
                                    top: -6px;
                                    left: 28px;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    font-size: 10px;
                                    color: #fff;
                                    background-color: #dc2626;
                                    border-radius: 9999px;
                                    animation: pulse-glow 1.6s infinite;
                                    z-index: 10;
                                }

                                @keyframes pulse-glow {
                                    0% {
                                        box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.55);
                                    }

                                    70% {
                                        box-shadow: 0 0 0 8px rgba(239, 68, 68, 0);
                                    }

                                    100% {
                                        box-shadow: 0 0 0 0 rgba(239, 68, 68, 0);
                                    }
                                }
                            </style>
                            @php

                                $userId = Auth::id();
                                $countUnread = 0;

                            @endphp
                            <i class="w-6 h-4 side-menu__icon bi bi-chat-dots" style="color: #fff !important"></i>
                            <span class="side-menu__label" style="color: #fff !important">Messages</span>
                            <span class="ml-auto flex h-5 w-5 side-menu__icon " style="display: block">
                                @if ($countUnread > 0)
                                    <span class="mx-2 translate-middle badge !rounded-full bg-danger icon-badge"
                                        style="display: block">
                                        {{ $countUnread > 10 ? '9+' : $countUnread }}
                                    </span>
                                @endif
                            </span>
                        </a>
                    </li>

                    <li class="slide__category mt-4"><span class="category-name">Support</span></li>

                    <li class="slide">
                        <a href="/FAQ"
                            class="side-menu__item relative transition-all duration-300 group !hover:bg-[#f58331] hover:text-white hover:rounded-x-md hover:px-3 hover:pl-4 hover:mx-1 {{ request()->is('FAQ') ? 'active-menu' : '' }}">
                            <div
                                class="absolute left-0 top-0 h-full w-1.5 bg-[#FFBC58] rounded-r-md opacity-0 group-hover:opacity-100 transition-all duration-300">
                            </div>
                            <i class="w-6 h-4 side-menu__icon bi bi-question-circle" style="color: #fff !important"></i>
                            <span class="side-menu__label" style="color: #fff !important">FAQ</span>
                        </a>
                    </li>

                    <li class="slide">
                        <a href="/tickets/list"
                            class="side-menu__item relative transition-all duration-300 group !hover:bg-[#f58331] hover:text-white hover:rounded-x-md hover:px-3 hover:pl-4 hover:mx-1 {{ request()->is('tickets*') ? 'active-menu' : '' }}">
                            <div
                                class="absolute left-0 top-0 h-full w-1.5 bg-[#FFBC58] rounded-r-md opacity-0 group-hover:opacity-100 transition-all duration-300">
                            </div>
                            <i class="w-6 h-4 side-menu__icon bi bi-plus-circle" style="color: #fff !important"></i>
                            <span class="side-menu__label" style="color: #fff !important">Help &amp; Support</span>
                        </a>
                    </li>

                @elseif ($user && $user->role === 'employer')

                    <li class="slide__category"><span class="category-name">Employer</span></li>

                    {{-- Dashboard --}}
                    <li class="slide" id="dashboard-menu">
                        <a href="/employer/dashboard"
                            class="side-menu__item relative transition-all duration-300 group !hover:bg-[#f58331] hover:text-white hover:rounded-x-md hover:px-3 hover:pl-4 hover:mx-1 {{ request()->is('employer/dashboard*') ? 'active-menu' : '' }}">
                            <div
                                class="absolute left-0 top-0 h-full w-1.5 bg-[#FFBC58] rounded-r-md opacity-0 group-hover:opacity-100 transition-all duration-300">
                            </div>
                            <i class="w-6 h-4 side-menu__icon bi bi-globe" style="color: #fff !important"></i>
                            <span class="side-menu__label text-white" style="color: #fff !important">Dashboard</span>
                        </a>
                    </li>

                    {{-- Search Applicants --}}
                    <li class="slide">
                        <a href="/applicants"
                            class="side-menu__item relative transition-all duration-300 group !hover:bg-[#f58331] hover:text-white hover:rounded-x-md hover:px-3 hover:pl-4 hover:mx-1 {{ request()->is('applicants*') ? 'active-menu' : '' }}">
                            <div
                                class="absolute left-0 top-0 h-full w-1.5 bg-[#FFBC58] rounded-r-md opacity-0 group-hover:opacity-100 transition-all duration-300">
                            </div>
                            <i class="w-6 h-4 side-menu__icon bi bi-search" style="color: #fff !important"></i>
                            <span class="side-menu__label" style="color: #fff !important">Search Applicants</span>
                        </a>
                    </li>

                    {{-- Release Notes --}}
                    <li class="slide">
                        <a href="/release-notes"
                            class="side-menu__item relative transition-all duration-300 group !hover:bg-[#f58331] hover:text-white hover:rounded-x-md hover:px-3 hover:pl-4 hover:mx-1 {{ request()->is('release-notes*') ? 'active-menu' : '' }}">
                            <div
                                class="absolute left-0 top-0 h-full w-1.5 bg-[#FFBC58] rounded-r-md opacity-0 group-hover:opacity-100 transition-all duration-300">
                            </div>
                            <i class="w-6 h-4 side-menu__icon bi bi-stars" style="color: #fff !important"></i>
                            <span class="side-menu__label" style="color: #fff !important">Release Notes</span>
                        </a>
                    </li>

                    {{-- Separator: Job Management --}}
                    <li class="slide__category mt-4"><span class="category-name">Job Management</span></li>

                    {{-- Register a Company --}}
                    <li class="slide">
                        <a href="/employer/companies/create"
                            class="side-menu__item relative transition-all duration-300 group !hover:bg-[#f58331] hover:text-white hover:rounded-x-md hover:px-3 hover:pl-4 hover:mx-1 {{ request()->is('employer/companies*') ? 'active-menu' : '' }}">
                            <div
                                class="absolute left-0 top-0 h-full w-1.5 bg-[#FFBC58] rounded-r-md opacity-0 group-hover:opacity-100 transition-all duration-300">
                            </div>
                            <i class="w-6 h-4 side-menu__icon bi bi-building" style="color: #fff !important"></i>
                            <span class="side-menu__label" style="color: #fff !important">Register a Company</span>
                        </a>
                    </li>

                    {{-- Post a Job --}}
                    <li class="slide">
                        <a href="{{ route('jobs.create') }}"
                            class="side-menu__item relative transition-all duration-300 group !hover:bg-[#f58331] hover:text-white hover:rounded-x-md hover:px-3 hover:pl-4 hover:mx-1 {{ request()->is('jobs/create') ? 'active-menu' : '' }}">
                            <div
                                class="absolute left-0 top-0 h-full w-1.5 bg-[#FFBC58] rounded-r-md opacity-0 group-hover:opacity-100 transition-all duration-300">
                            </div>
                            <i class="w-6 h-4 side-menu__icon bi bi-plus-circle" style="color: #fff !important"></i>
                            <span class="side-menu__label" style="color: #fff !important">Post a Job</span>
                        </a>
                    </li>

                    {{-- My Job Posts --}}
                    <li class="slide">
                        <a href="/employer/jobs"
                            class="side-menu__item relative transition-all duration-300 group !hover:bg-[#f58331] hover:text-white hover:rounded-x-md hover:px-3 hover:pl-4 hover:mx-1 {{ request()->is('employer/jobs*') ? 'active-menu' : '' }}">
                            <div
                                class="absolute left-0 top-0 h-full w-1.5 bg-[#FFBC58] rounded-r-md opacity-0 group-hover:opacity-100 transition-all duration-300">
                            </div>
                            <i class="w-6 h-4 side-menu__icon bi bi-briefcase" style="color: #fff !important"></i>
                            <span class="side-menu__label" style="color: #fff !important">My Job Posts</span>
                        </a>
                    </li>

                    {{-- Separator: Applicants --}}
                    <li class="slide__category mt-4"><span class="category-name">Applicants</span></li>

                    {{-- Applicants --}}
                    <li class="slide">
                        <a href="/employer/applications"
                            class="side-menu__item relative transition-all duration-300 group !hover:bg-[#f58331] hover:text-white hover:rounded-x-md hover:px-3 hover:pl-4 hover:mx-1 {{ request()->is('employer/applications*') ? 'active-menu' : '' }}">
                            <div
                                class="absolute left-0 top-0 h-full w-1.5 bg-[#FFBC58] rounded-r-md opacity-0 group-hover:opacity-100 transition-all duration-300">
                            </div>
                            <i class="w-6 h-4 side-menu__icon bi bi-people" style="color: #fff !important"></i>
                            <span class="side-menu__label" style="color: #fff !important">All Applicants</span>
                        </a>
                    </li>

                    {{-- History --}}
                    <li class="slide">
                        <a href="/employer/history"
                            class="side-menu__item relative transition-all duration-300 group !hover:bg-[#f58331] hover:text-white hover:rounded-x-md hover:px-3 hover:pl-4 hover:mx-1 {{ request()->is('employer/history*') ? 'active-menu' : '' }}">
                            <div
                                class="absolute left-0 top-0 h-full w-1.5 bg-[#FFBC58] rounded-r-md opacity-0 group-hover:opacity-100 transition-all duration-300">
                            </div>
                            <i class="w-6 h-4 side-menu__icon bi bi-clock-history" style="color: #fff !important"></i>
                            <span class="side-menu__label" style="color: #fff !important">History</span>
                        </a>
                    </li>

                    {{-- Saved Applicants --}}
                    <li class="slide">
                        <a href="/employer/saved-applicants"
                            class="side-menu__item relative transition-all duration-300 group !hover:bg-[#f58331] hover:text-white hover:rounded-x-md hover:px-3 hover:pl-4 hover:mx-1 {{ request()->is('employer/saved-applicants*') ? 'active-menu' : '' }}">
                            <div
                                class="absolute left-0 top-0 h-full w-1.5 bg-[#FFBC58] rounded-r-md opacity-0 group-hover:opacity-100 transition-all duration-300">
                            </div>
                            <i class="w-6 h-4 side-menu__icon ri-user-heart-line" style="color: #fff !important"></i>
                            <span class="side-menu__label" style="color: #fff !important">Saved Applicants</span>
                        </a>
                    </li>

                    {{-- Separator: Account --}}
                    <li class="slide__category mt-4"><span class="category-name">Account</span></li>

                    {{-- Messages --}}
                    <li class="slide" id="dashboard-menu">
                        <a href="/chats/v2"
                            class="side-menu__item relative transition-all duration-300 group !hover:bg-[#f58331] hover:text-white hover:rounded-x-md hover:px-3 hover:pl-4 hover:mx-1 {{ request()->is('chats*') ? 'active-menu' : '' }}">
                            <div
                                class="absolute left-0 top-0 h-full w-1.5 bg-[#FFBC58] rounded-r-md opacity-0 group-hover:opacity-100 transition-all duration-300">
                            </div>
                            <i class="w-6 h-4 side-menu__icon bi bi-chat-dots" style="color: #fff !important"></i>
                            <span class="side-menu__label" style="color: #fff !important">Messages</span>
                        </a>
                    </li>

                    {{-- Separator: Support --}}
                    <li class="slide__category mt-4"><span class="category-name">Support</span></li>

                    {{-- FAQ --}}
                    <li class="slide">
                        <a href="/FAQ"
                            class="side-menu__item relative transition-all duration-300 group !hover:bg-[#f58331] hover:text-white hover:rounded-x-md hover:px-3 hover:pl-4 hover:mx-1 {{ request()->is('FAQ') ? 'active-menu' : '' }}">
                            <div
                                class="absolute left-0 top-0 h-full w-1.5 bg-[#FFBC58] rounded-r-md opacity-0 group-hover:opacity-100 transition-all duration-300">
                            </div>
                            <i class="w-6 h-4 side-menu__icon bi bi-question-circle" style="color: #fff !important"></i>
                            <span class="side-menu__label" style="color: #fff !important">FAQ</span>
                        </a>
                    </li>

                    {{-- Help --}}
                    <li class="slide">
                        <a href="/tickets/list"
                            class="side-menu__item relative transition-all duration-300 group !hover:bg-[#f58331] hover:text-white hover:rounded-x-md hover:px-3 hover:pl-4 hover:mx-1 {{ request()->is('tickets/list*') ? 'active-menu' : '' }}">
                            <div
                                class="absolute left-0 top-0 h-full w-1.5 bg-[#FFBC58] rounded-r-md opacity-0 group-hover:opacity-100 transition-all duration-300">
                            </div>
                            <i class="w-6 h-4 side-menu__icon bi bi-life-preserver" style="color: #fff !important"></i>
                            <span class="side-menu__label" style="color: #fff !important">Help &amp; Support</span>
                        </a>
                    </li>

                @else

                    <li class="slide__category"><span class="category-name">Dashboard</span></li>
                    <li class="slide" id="dashboard-menu">
                        <a href="/dashboard"
                            class="side-menu__item relative transition-all duration-300 group !hover:bg-[#f58331] hover:text-white hover:rounded-x-md hover:px-3 hover:pl-4 hover:mx-1 {{ request()->is('dashboard') ? 'active-menu' : '' }}">
                            <div
                                class="absolute left-0 top-0 h-full w-1.5 bg-[#FFBC58] rounded-r-md opacity-0 group-hover:opacity-100 transition-all duration-300">
                            </div>
                            <i class="w-6 h-4 side-menu__icon bi bi-globe" style="color: #fff !important"></i>
                            <span class="side-menu__label text-white" style="color: #fff !important">Dashboard</span>
                        </a>
                    </li>
                    @if(!in_array(Auth::user()->role ?? '', ['applicant', 'employer']))
                        <li class="slide__category"><span class="category-name">Moderator Tools</span></li>
                        <li class="slide" id="dashboard-menu">
                            <a href="/chats/monitor"
                                class="side-menu__item relative transition-all duration-300 group !hover:bg-[#f58331] hover:text-white hover:rounded-x-md hover:px-3 hover:pl-4 hover:mx-1 {{ request()->is('chats/monitor*') ? 'active-menu' : '' }}">
                                <div
                                    class="absolute left-0 top-0 h-full w-1.5 bg-[#FFBC58] rounded-r-md opacity-0 group-hover:opacity-100 transition-all duration-300">
                                </div>
                                <i class="w-6 h-4 side-menu__icon bi bi-chat-square-dots" style="color: #fff !important"></i>
                                <span class="side-menu__label" style="color: #fff !important">Chat Moderator</span>
                            </a>
                        </li>

                        {{-- Manage Users --}}
                        <li class="slide">
                            <a href="/moderator/users"
                                class="side-menu__item relative transition-all duration-300 group !hover:bg-[#f58331] hover:text-white hover:rounded-x-md hover:px-3 hover:pl-4 hover:mx-1 {{ request()->is('moderator/users*') ? 'active-menu' : '' }}">
                                <div
                                    class="absolute left-0 top-0 h-full w-1.5 bg-[#FFBC58] rounded-r-md opacity-0 group-hover:opacity-100 transition-all duration-300">
                                </div>
                                <i class="w-6 h-4 side-menu__icon bi bi-people" style="color: #fff !important"></i>
                                <span class="side-menu__label" style="color: #fff !important">Manage Users</span>
                            </a>
                        </li>

                        {{-- Registered Companies --}}
                        <li class="slide">
                            <a href="/moderator/companies"
                                class="side-menu__item relative transition-all duration-300 group !hover:bg-[#f58331] hover:text-white hover:rounded-x-md hover:px-3 hover:pl-4 hover:mx-1 {{ request()->is('moderator/companies*') ? 'active-menu' : '' }}">
                                <div
                                    class="absolute left-0 top-0 h-full w-1.5 bg-[#FFBC58] rounded-r-md opacity-0 group-hover:opacity-100 transition-all duration-300">
                                </div>
                                <i class="w-6 h-4 side-menu__icon bi bi-building-check" style="color: #fff !important"></i>
                                <span class="side-menu__label" style="color: #fff !important">Registered Companies</span>
                            </a>
                        </li>

                        {{-- Dropdown Options Management --}}
                        <li class="slide">
                            <a href="/moderator/job-form-options"
                                class="side-menu__item relative transition-all duration-300 group !hover:bg-[#f58331] hover:text-white hover:rounded-x-md hover:px-3 hover:pl-4 hover:mx-1 {{ request()->is('moderator/job-form-options*') ? 'active-menu' : '' }}">
                                <div
                                    class="absolute left-0 top-0 h-full w-1.5 bg-[#FFBC58] rounded-r-md opacity-0 group-hover:opacity-100 transition-all duration-300">
                                </div>
                                <i class="w-6 h-4 side-menu__icon bi bi-briefcase" style="color: #fff !important"></i>
                                <span class="side-menu__label" style="color: #fff !important">Job Form Options</span>
                            </a>
                        </li>
                        <li class="slide">
                            <a href="/moderator/applicant-profile-options"
                                class="side-menu__item relative transition-all duration-300 group !hover:bg-[#f58331] hover:text-white hover:rounded-x-md hover:px-3 hover:pl-4 hover:mx-1 {{ request()->is('moderator/applicant-profile-options*') ? 'active-menu' : '' }}">
                                <div
                                    class="absolute left-0 top-0 h-full w-1.5 bg-[#FFBC58] rounded-r-md opacity-0 group-hover:opacity-100 transition-all duration-300">
                                </div>
                                <i class="w-6 h-4 side-menu__icon bi bi-person-badge" style="color: #fff !important"></i>
                                <span class="side-menu__label" style="color: #fff !important">Applicant Profiles</span>
                            </a>
                        </li>

                        {{-- Release History Management --}}
                        <li class="slide">
                            <a href="/moderator/release-notes"
                                class="side-menu__item relative transition-all duration-300 group !hover:bg-[#f58331] hover:text-white hover:rounded-x-md hover:px-3 hover:pl-4 hover:mx-1 {{ request()->is('moderator/release-notes*') ? 'active-menu' : '' }}">
                                <div
                                    class="absolute left-0 top-0 h-full w-1.5 bg-[#FFBC58] rounded-r-md opacity-0 group-hover:opacity-100 transition-all duration-300">
                                </div>
                                <i class="w-6 h-4 side-menu__icon bi bi-megaphone" style="color: #fff !important"></i>
                                <span class="side-menu__label" style="color: #fff !important">Release History</span>
                            </a>
                        </li>
                    @endif

                    <li class="slide__category"><span class="category-name">Application Menu</span></li>
                    <li class="slide" id="dashboard-menu">
                        <a href="/chats/v2"
                            class="side-menu__item relative transition-all duration-300 group !hover:bg-[#f58331] hover:text-white hover:rounded-x-md hover:px-3 hover:pl-4 hover:mx-1">
                            <div
                                class="absolute left-0 top-0 h-full w-1.5 bg-[#FFBC58] rounded-r-md opacity-0 group-hover:opacity-100 transition-all duration-300">
                            </div>
                            <style>
                                .icon-badge {
                                    position: absolute;
                                    top: -6px;
                                    left: 28px;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    font-size: 10px;
                                    color: #fff;
                                    background-color: #dc2626;
                                    /* red-600 */
                                    border-radius: 9999px;
                                    animation: pulse-glow 1.6s infinite;
                                    z-index: 10;
                                }

                                @keyframes pulse-glow {
                                    0% {
                                        box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.55);
                                    }

                                    70% {
                                        box-shadow: 0 0 0 8px rgba(239, 68, 68, 0);
                                    }

                                    100% {
                                        box-shadow: 0 0 0 0 rgba(239, 68, 68, 0);
                                    }
                                }
                            </style>
                            @php

                                $userId = Auth::id();
                                $countUnread = 0;

                            @endphp
                            <i class="w-6 h-4 side-menu__icon bi bi-chat-dots" style="color: #fff !important"></i>
                            <span class="side-menu__label" style="color: #fff !important">Messages</span>
                            <span class="ml-auto flex h-5 w-5 side-menu__icon " style="display: block">
                                @if ($countUnread > 0)
                                    <span class="mx-2 translate-middle badge !rounded-full bg-danger icon-badge"
                                        style="display: block">
                                        {{ $countUnread > 10 ? '9+' : $countUnread }}
                                    </span>
                                @endif
                            </span>
                        </a>
                    </li>

                    @if(!in_array(Auth::user()->role ?? '', ['applicant', 'employer']))
                        <li class="slide">
                            <a href="/file-manager/list"
                                class="side-menu__item relative transition-all duration-300 group !hover:bg-[#f58331] hover:text-white hover:rounded-x-md hover:px-3 hover:pl-4 hover:mx-1 {{ request()->is('file-manager/list*') ? 'active-menu' : '' }}">
                                <div
                                    class="absolute left-0 top-0 h-full w-1.5 bg-[#FFBC58] rounded-r-md opacity-0 group-hover:opacity-100 transition-all duration-300">
                                </div>
                                <i class="w-6 h-4 side-menu__icon bi bi-folder-symlink" style="color: #fff !important"></i>
                                <span class="side-menu__label" style="color: #fff !important">
                                    File Manager
                                </span>
                            </a>
                        </li>
                    @endif
                    <li class="slide">
                        <a href="/relationship/list"
                            class="side-menu__item relative transition-all duration-300 group !hover:bg-[#f58331] hover:text-white hover:rounded-x-md hover:px-3 hover:pl-4 hover:mx-1 {{ request()->is('relationship/list*') ? 'active-menu' : '' }}">
                            <div
                                class="absolute left-0 top-0 h-full w-1.5 bg-[#FFBC58] rounded-r-md opacity-0 group-hover:opacity-100 transition-all duration-300">
                            </div>
                            <i class="w-6 h-4 side-menu__icon bi bi-person-square" style="color: #fff !important"></i>
                            <span class="side-menu__label" style="color: #fff !important">
                                Relationship
                            </span>
                        </a>
                    </li>

                    <li class="slide__category"><span class="category-name">FAQ's & Support</span></li>
                    <li class="slide" id="feedback-hub-menu">
                        <a href="/moderator/tickets"
                            class="side-menu__item relative transition-all duration-300 group !hover:bg-[#f58331] hover:text-white hover:rounded-x-md hover:px-3 hover:pl-4 hover:mx-1 {{ request()->is('moderator/tickets*') ? 'active-menu' : '' }}">
                            <div
                                class="absolute left-0 top-0 h-full w-1.5 bg-[#FFBC58] rounded-r-md opacity-0 group-hover:opacity-100 transition-all duration-300">
                            </div>
                            <i class="w-6 h-4 side-menu__icon bi bi-send" style="color: #fff !important"></i>
                            <span class="side-menu__label" style="color: #fff !important">Manage Support Tickets</span>
                        </a>
                    </li>
                    <li class="slide" id="faq-menu">
                        <a href="/FAQ"
                            class="side-menu__item relative transition-all duration-300 group !hover:bg-[#f58331] hover:text-white hover:rounded-x-md hover:px-3 hover:pl-4 hover:mx-1 {{ request()->is('FAQ') ? 'active-menu' : '' }}">
                            <div
                                class="absolute left-0 top-0 h-full w-1.5 bg-[#FFBC58] rounded-r-md opacity-0 group-hover:opacity-100 transition-all duration-300">
                            </div>
                            <i class="w-6 h-4 side-menu__icon bi bi-question-circle" style="color: #fff !important"></i>
                            <span class="side-menu__label" style="color: #fff !important">FAQ's</span>
                        </a>
                    </li>

                @endif

                @if ($user)
                    <li class="slide mt-auto" id="sidebar-user-settings">
                        <a href="/user/profile"
                            class="side-menu__item relative transition-all duration-300 group !hover:bg-[#f58331] hover:text-white hover:rounded-x-md hover:px-3 hover:pl-4 hover:mx-1 flex items-center gap-3">
                            <div
                                class="absolute left-0 top-0 h-full w-1.5 bg-[#FFBC58] rounded-r-md opacity-0 group-hover:opacity-100 transition-all duration-300">
                            </div>
                            <i class="w-6 h-4 side-menu__icon bi bi-gear" style="color: #fff !important"></i>
                            <div class="flex-1 min-w-0">
                                <span class="block text-sm font-semibold truncate">{{ $user->name }}</span>
                                <span class="block text-xs text-white/70 truncate">{{ $user->email }}</span>
                            </div>
                        </a>
                    </li>
                @endif

            </ul>
            <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24"
                    height="24" viewBox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path>
                </svg></div>

            <!-- End::slide -->

            <!-- Start::slide -->

            <!-- End::slide -->
        </nav>
        <!-- End::nav -->

    </div>
    <!-- End::main-sidebar -->

</aside>

<script>
    (function () {
        const html = document.documentElement;
        const SIDEMENU_STATE_KEY = 'sidemenu-v2-state';

        const getSavedSidemenuState = () => {
            try {
                return localStorage.getItem(SIDEMENU_STATE_KEY);
            } catch (e) {
                return null;
            }
        };

        const getCloseState = () => {
            const currentStyle = html.getAttribute('data-vertical-style');
            if (currentStyle === 'overlay') {
                return 'icon-overlay-close';
            }
            if (currentStyle === 'closed') {
                return 'close-menu-close';
            }
            if (currentStyle === 'detached') {
                return 'detached-close';
            }
            if (currentStyle === 'icontext') {
                return 'icon-text-close';
            }

            return 'close';
        };

        const applySavedSidemenuState = () => {
            const savedState = getSavedSidemenuState();
            const vStyle = html.getAttribute('data-vertical-style');

            // If vertical style was saved in localStorage but not yet on html tag (and no database override)
            if (!vStyle || vStyle === 'default') {
                const savedVStyle = localStorage.getItem('xyntraverticalstyles');
                if (savedVStyle && savedVStyle !== 'default') {
                    html.setAttribute('data-vertical-style', savedVStyle);
                }
            }

            if (savedState === 'closed') {
                html.setAttribute('data-toggled', getCloseState());
                return true;
            }

            if (savedState === 'open') {
                html.removeAttribute('data-toggled');
                return true;
            }

            // Fallback: if style is closed/icontext/overlay and no open/closed state is saved, 
            // ensure it's closed by default for these specific styles.
            const currentStyle = html.getAttribute('data-vertical-style');
            if (['closed', 'icontext', 'overlay', 'detached'].includes(currentStyle) && !savedState) {
                html.setAttribute('data-toggled', getCloseState());
            }

            return false;
        };

        // Ensure we don't clear user preferences on every load
        // Removed: localStorage.removeItem('xyntraverticalstyles');
        // Removed: localStorage.removeItem('xyntranavstyles');

        const layoutSettings = window.databaseLayoutSettings || null;

        // Ensure base classes exist but do not source theme from localStorage.
        if (!html.classList.contains('light') && !html.classList.contains('dark')) {
            html.classList.add('light');
        }

        if (!html.hasAttribute('data-nav-layout')) {
            html.setAttribute('data-nav-layout', layoutSettings?.nav_layout || 'vertical');
        }

        if (!html.hasAttribute('data-header-styles')) {
            html.setAttribute('data-header-styles', layoutSettings?.header_style || 'light');
        }

        if (!html.hasAttribute('data-menu-styles')) {
            html.setAttribute('data-menu-styles', layoutSettings?.menu_style || 'dark');
        }

        if (!html.hasAttribute('loader')) {
            html.setAttribute('loader', layoutSettings?.loader || 'enable');
        }

        if (!html.hasAttribute('data-vertical-style')) {
            html.setAttribute('data-vertical-style', layoutSettings?.vertical_style || 'default');
        }

        if (!html.hasAttribute('data-width')) {
            html.setAttribute('data-width', layoutSettings?.width || 'fullwidth');
        }

        if (!html.hasAttribute('data-header-position')) {
            html.setAttribute('data-header-position', layoutSettings?.header_position || 'fixed');
        }

        if (!html.hasAttribute('data-menu-position')) {
            html.setAttribute('data-menu-position', layoutSettings?.menu_position || 'fixed');
        }

        if (!html.hasAttribute('data-page-style')) {
            const pageStyle = layoutSettings?.page_style || 'regular';
            if (pageStyle !== 'regular') {
                html.setAttribute('data-page-style', pageStyle);
            }
        }

        if (!html.hasAttribute('data-nav-style')) {
            html.setAttribute('data-nav-style', layoutSettings?.nav_style || 'menu-click');
        }

        applySavedSidemenuState();
    })();

    // Function to set sidebar collapsed state and add hover listeners
    // Function to set sidebar state
    function initSidebar() {
        const html = document.documentElement;
        const SIDEMENU_STATE_KEY = 'sidemenu-v2-state';
        const sidebar = document.querySelector('.app-sidebar');
        const vStyle = html.getAttribute('data-vertical-style');
        let savedState = null;

        try {
            savedState = localStorage.getItem(SIDEMENU_STATE_KEY);
        } catch (e) {
            savedState = null;
        }

        const hasSavedPreference = savedState === 'closed' || savedState === 'open';

        if (window.innerWidth >= 992) {
            // For overlay and closed modes, ensure it's closed by default on load unless saved as open
            if (['overlay', 'closed', 'icontext', 'detached'].includes(vStyle) && savedState !== 'open') {
                html.setAttribute('data-toggled', getCloseState());
            } else if (savedState === 'open') {
                html.removeAttribute('data-toggled');
            } else if ((!vStyle || vStyle === 'default') && !hasSavedPreference) {
                html.removeAttribute('data-toggled');
            }
        } else {
            // On mobile, only force closed if not explicitly saved as open
            if (savedState !== 'open') {
                html.setAttribute('data-toggled', 'close');
            } else {
                html.removeAttribute('data-toggled');
            }
        }

        // Keep collapsed state stable
        if (sidebar) {
            html.removeAttribute('data-icon-overlay');
        }
    }

    // Set initialization status
    html.setAttribute('data-sidemenu-initialized', 'true');

    // Initialize after DOM is ready
    document.addEventListener('DOMContentLoaded', function () {
        initSidebar();
    });

    // Also reinitialize after window load (in case main.js overrides)
    window.addEventListener('load', function () {
        setTimeout(initSidebar, 100);
    });
</script>