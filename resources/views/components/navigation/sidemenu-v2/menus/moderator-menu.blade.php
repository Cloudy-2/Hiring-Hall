{{-- Sidemenu V2 - Moderator/Admin Menu --}}

@include('components.navigation.sidemenu-v2.partials.category', ['name' => 'Dashboard', 'icon' => 'ri-dashboard-fill'])

@php
    $dashboardRoute = '/moderator/dashboard';
    $dashboardLabel = 'Dashboard';
    if (auth()->user() && in_array(auth()->user()->role, ['admin', 'super_admin'])) {
        $dashboardRoute = '/admin/dashboard';
        $dashboardLabel = 'Admin Dashboard';
    }
@endphp

@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => $dashboardRoute,
    'icon' => 'bi bi-globe',
    'label' => $dashboardLabel,
    'active' => request()->is('dashboard') || request()->is('admin/dashboard') || request()->is('moderator/dashboard')
])

@php
    $announcementCount = \App\Models\Announcement::getUnreadCountForUser(auth()->user());
@endphp

@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => '/announcements',
    'icon' => 'ri-megaphone-fill',
    'label' => 'Announcements',
    'active' => request()->is('announcements*'),
    'badge' => $announcementCount > 0 ? $announcementCount : null,
    'badgeColor' => 'primary'
])

@include('components.navigation.sidemenu-v2.partials.category', ['name' => 'Moderator Tools', 'icon' => 'ri-hammer-fill'])

@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => '/chats/monitor',
    'icon' => 'bi bi-chat-square-dots',
    'label' => 'Chat Moderator',
    'active' => request()->is('chats/monitor*')
])

@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => route('moderator.chat-reports.index'),
    'icon' => 'bi bi-flag',
    'label' => 'Reported messages',
    'active' => request()->is('moderator/chat-reports*')
])

@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => '/moderator/users',
    'icon' => 'bi bi-people',
    'label' => 'Manage Users',
    'active' => request()->is('moderator/users*')
])

@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => '/moderator/job-form-options',
    'icon' => 'bi bi-briefcase',
    'label' => 'Job Form Options',
    'active' => request()->is('moderator/job-form-options*')
])

@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => '/moderator/applicant-profile-options',
    'icon' => 'bi bi-person-badge',
    'label' => 'Applicant Form Options',
    'active' => request()->is('moderator/applicant-profile-options*')
])

@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => route('moderator.companies.index'),
    'icon' => 'bi bi-building-check',
    'label' => 'Company Verification',
    'active' => request()->is('moderator/companies*')
])

@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => route('moderator.jobs.index'),
    'icon' => 'bi bi-briefcase-fill',
    'label' => 'Job Moderation',
    'active' => request()->is('moderator/jobs*')
])

@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => route('moderator.applicants.index'),
    'icon' => 'bi bi-person-check',
    'label' => 'Applicant Verification',
    'active' => request()->is('moderator/applicants*')
])
@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => route('moderator.ratings.index'),
    'icon' => 'bi bi-star-half',
    'label' => 'Rating Management',
    'active' => request()->is('moderator/ratings*')
])

@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => route('moderator.tickets.index'),
    'icon' => 'bi bi-life-preserver',
    'label' => 'Manage Support Tickets',
    'active' => request()->is('moderator/tickets*')
])

{{-- Admin Only: Create Moderators/Employees --}}
@if(auth()->user() && in_array(auth()->user()->role, ['admin', 'super_admin']))
    @include('components.navigation.sidemenu-v2.partials.category', ['name' => 'Admin Tools', 'icon' => 'ri-admin-fill'])

    @include('components.navigation.sidemenu-v2.partials.menu-item', [
        'href' => '/admin/staff',
        'icon' => 'bi bi-person-plus',
        'label' => 'Manage Staff',
        'active' => request()->is('admin/staff*')
    ])

    <!-- @include('components.navigation.sidemenu-v2.partials.menu-item', [
        'href' => route('admin.settings.index'),
        'icon' => 'bi bi-gear',
        'label' => 'Settings',
        'active' => request()->is('admin/settings*')
    ]) -->

    @include('components.navigation.sidemenu-v2.partials.category', ['name' => 'Reports & Logs', 'icon' => 'ri-bar-chart-box-fill'])

    @include('components.navigation.sidemenu-v2.partials.menu-item', [
        'href' => route('moderator.reports.index'),
        'icon' => 'bi bi-bar-chart-line',
        'label' => 'Reports & Analytics',
        'active' => request()->is('admin/reports*')
    ])

    @include('components.navigation.sidemenu-v2.partials.menu-item', [
        'href' => route('moderator.activity-logs.index'),
        'icon' => 'bi bi-clock-history',
        'label' => 'Activity Logs',
        'active' => request()->is('admin/activity-logs*')
    ])

    @include('components.navigation.sidemenu-v2.partials.menu-item', [
        'href' => route('admin.impersonation-logs.index'),
        'icon' => 'bi bi-person-badge',
        'label' => 'Impersonation Logs',
        'active' => request()->is('admin/impersonation-logs*')
    ])
@endif

{{-- Super Admin Only: Create Admin Accounts --}}
@if(auth()->user() && auth()->user()->role === 'super_admin')
    @include('components.navigation.sidemenu-v2.partials.menu-item', [
        'href' => '/admin/administrators',
        'icon' => 'bi bi-shield-lock',
        'label' => 'Manage Admins',
        'active' => request()->is('admin/administrators*')
    ])
@endif

@include('components.navigation.sidemenu-v2.partials.category', ['name' => 'Content & Communications', 'icon' => 'ri-message-3-fill'])

@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => route('moderator.announcements.index'),
    'icon' => 'bi bi-megaphone',
    'label' => 'Create Announcement',
    'active' => request()->is('moderator/announcements*')
])
@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => route('moderator.faqs.index'),
    'icon' => 'bi bi-question-circle',
    'label' => 'FAQ Management',
    'active' => request()->is('moderator/faqs*')
])

@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => route('moderator.bulk-notifications.index'),
    'icon' => 'bi bi-envelope-paper',
    'label' => 'Bulk Notifications',
    'active' => request()->is('moderator/bulk-notifications*')
])

@include('components.navigation.sidemenu-v2.partials.category', ['name' => 'Application Menu', 'icon' => 'ri-apps-2-fill'])

@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => '/chats/v2',
    'icon' => 'bi bi-chat-dots',
    'label' => 'Messages',
    'active' => request()->is('chats*') && !request()->is('chats/monitor*')
])

@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => '/drive/file-manager',
    'icon' => 'bi bi-folder-symlink',
    'label' => 'File Manager',
    'active' => request()->is('drive/file-manager*')
])

@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => '/relationship/list',
    'icon' => 'bi bi-person-square',
    'label' => 'Relationship',
    'active' => request()->is('relationship/list*')
])

@include('components.navigation.sidemenu-v2.partials.category', ['name' => "FAQ's & Support", 'icon' => 'ri-customer-service-2-fill'])

@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => '/FAQ',
    'icon' => 'bi bi-question-circle',
    'label' => "FAQ's",
    'active' => request()->is('FAQ')
])

@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => route('moderator.release-notes.index'),
    'icon' => 'bi bi-journal-text',
    'label' => 'Version Management',
    'active' => request()->is('moderator/release-notes*')
])
