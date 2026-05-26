{{-- Sidemenu V2 - Premium Applicant Menu --}}

@include('components.navigation.sidemenu-v2.partials.category', ['name' => 'Main', 'icon' => 'ri-home-4-fill'])

@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => '/applicant/dashboard',
    'icon' => 'ri-dashboard-3-line',
    'label' => 'Dashboard',
    'active' => request()->is('applicant/dashboard')
])

@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => '/applicant/recommended-jobs',
    'icon' => 'ri-magic-line',
    'label' => 'Recommended Jobs',
    'active' => request()->is('applicant/recommended-jobs*', 'applicant/saved-jobs*')
])

@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => '/jobs',
    'icon' => 'ri-search-2-line',
    'label' => 'Search Jobs',
    'active' => request()->is('jobs*')
])

@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => route('employers.index'),
    'icon' => 'ri-building-4-line',
    'label' => 'Search Employers',
    'active' => request()->is('employers*')
])

@include('components.navigation.sidemenu-v2.partials.category', ['name' => 'Management', 'icon' => 'ri-briefcase-fill'])

@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => '/applicant/applications',
    'icon' => 'ri-file-list-3-line',
    'label' => 'My Applications',
    'active' => request()->is('applicant/applications')
])

@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => '/applicant/applications/history',
    'icon' => 'ri-history-line',
    'label' => 'History',
    'active' => request()->is('applicant/applications/history')
])

@php
    $upcomingInterviewCount = auth()->check() ? \App\Models\Interview::where('applicant_id', auth()->id())->upcoming()->count() : 0;
@endphp

@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => '/applicant/interviews',
    'icon' => 'ri-calendar-event-line',
    'label' => 'Interviews',
    'active' => request()->is('applicant/interviews*'),
    'badge' => $upcomingInterviewCount > 0 ? $upcomingInterviewCount : null,
    'badgeColor' => 'indigo'
])

@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => '/applicant/job-alerts',
    'icon' => 'ri-notification-3-line',
    'label' => 'Job Alerts',
    'active' => request()->is('applicant/job-alerts*')
])

@include('components.navigation.sidemenu-v2.partials.category', ['name' => 'Account', 'icon' => 'ri-user-settings-fill'])

@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => '/applicant/profile',
    'icon' => 'ri-user-settings-line',
    'label' => 'My Profile',
    'active' => request()->is('applicant/profile*')
])

@php
    $announcementCount = \App\Models\Announcement::getUnreadCountForUser(auth()->user());
@endphp

@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => '/announcements',
    'icon' => 'ri-megaphone-line',
    'label' => 'Announcements',
    'active' => request()->is('announcements*'),
    'badge' => $announcementCount > 0 ? $announcementCount : null,
    'badgeColor' => 'red'
])

@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => '/chats/v2',
    'icon' => 'ri-message-3-line',
    'label' => 'Messages',
    'active' => request()->is('chats*')
])

@include('components.navigation.sidemenu-v2.partials.category', ['name' => 'Tools', 'icon' => 'ri-tools-fill'])

@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => route('filemanager.index'),
    'icon' => 'ri-folder-open-line',
    'label' => 'My Documents',
    'active' => request()->is('drive/file-manager*')
])

@include('components.navigation.sidemenu-v2.partials.category', ['name' => 'Support', 'icon' => 'ri-customer-service-2-fill'])

@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => '/FAQ',
    'icon' => 'ri-customer-service-2-line',
    'label' => "FAQ's",
    'active' => request()->is('FAQ')
])

@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => '/release-notes',
    'icon' => 'ri-file-list-3-line',
    'label' => 'Release Notes',
    'active' => request()->is('release-notes*')
])

@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => '/tickets/list',
    'icon' => 'ri-add-circle-line',
    'label' => 'Help & Support',
    'active' => request()->is('tickets*')
])


<!-- @include('components.navigation.sidemenu-v2.partials.menu-item', [
    'label' => 'Support',
    'icon' => 'ri-customer-service-2-line',
    'active' => request()->is('FAQ', 'release-notes*', 'tickets/list*'),
    'submenu' => [
        [
            'href' => '/FAQ',
            'label' => "FAQ's",
            'active' => request()->is('FAQ')
        ],
        [
            'href' => '/release-notes',
            'label' => 'Release Notes',
            'active' => request()->is('release-notes*')
        ],
        [
            'href' => '/tickets/list',
            'label' => 'Help Desk',
            'active' => request()->is('tickets/list*')
        ]
    ]
]) -->
