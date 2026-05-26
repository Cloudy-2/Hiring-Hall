{{-- Sidemenu V2 - Employer Menu --}}

@include('components.navigation.sidemenu-v2.partials.category', ['name' => 'Employer', 'icon' => 'ri-building-fill'])

@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => '/employer/dashboard',
    'icon' => 'ri-dashboard-fill',
    'label' => 'Dashboard',
    'active' => request()->is('employer/dashboard*')
])

@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => '/applicants',
    'icon' => 'ri-search-2-line',
    'label' => 'Search Applicants',
    'active' => request()->is('applicants*')
])

@include('components.navigation.sidemenu-v2.partials.category', ['name' => 'Company Management', 'icon' => 'ri-briefcase-fill'])

@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => '/employer/companies',
    'icon' => 'ri-building-fill',
    'label' => 'My Companies',
    'active' => request()->is('employer/companies*')
])

@include('components.navigation.sidemenu-v2.partials.category', ['name' => 'Job Management', 'icon' => 'ri-add-box-fill'])

@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => route('jobs.create'),
    'icon' => 'ri-add-circle-fill',
    'label' => 'Post a Job',
    'active' => request()->is('jobs/create')
])

@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => '/employer/jobs',
    'icon' => 'ri-briefcase-fill',
    'label' => 'My Job Posts',
    'active' => request()->is('employer/jobs*')
])

@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => '/employer/templates',
    'icon' => 'ri-file-list-3-fill',
    'label' => 'Job Templates',
    'active' => request()->is('employer/templates*')
])

@include('components.navigation.sidemenu-v2.partials.category', ['name' => 'Applicants', 'icon' => 'ri-team-fill'])

@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => '/employer/applications',
    'icon' => 'ri-team-fill',
    'label' => 'All Applicants',
    'active' => request()->is('employer/applications*')
])

@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => '/employer/pipeline',
    'icon' => 'ri-layout-column-fill',
    'label' => 'Hiring Pipeline',
    'active' => request()->is('employer/pipeline*')
])

@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => '/employer/interviews',
    'icon' => 'ri-calendar-check-fill',
    'label' => 'Interviews',
    'active' => request()->is('employer/interviews*')
])

@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => '/employer/history',
    'icon' => 'ri-history-fill',
    'label' => 'History',
    'active' => request()->is('employer/history*')
])

@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => '/employer/saved-candidates',
    'icon' => 'ri-bookmark-fill',
    'label' => 'Saved Applicants',
    'active' => request()->is('employer/saved-applicants*')
])

@include('components.navigation.sidemenu-v2.partials.category', ['name' => 'Account', 'icon' => 'ri-user-settings-fill'])

<!-- Hide for now until we have email templates -->
<!--
@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => '/employer/email-templates',
    'icon' => 'ri-mail-settings-fill',
    'label' => 'Email Templates',
    'active' => request()->is('employer/email-templates*')
]) -->

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

@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => '/chats/v2',
    'icon' => 'ri-chat-3-fill',
    'label' => 'Messages',
    'active' => request()->is('chats*')
])

@include('components.navigation.sidemenu-v2.partials.category', ['name' => 'Support', 'icon' => 'ri-customer-service-2-fill'])

@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => '/FAQ',
    'icon' => 'ri-question-fill',
    'label' => 'FAQ',
    'active' => request()->is('FAQ')
])

@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => '/release-notes',
    'icon' => 'bi bi-journal-text',
    'label' => 'Release Notes',
    'active' => request()->is('release-notes*')
])

@include('components.navigation.sidemenu-v2.partials.menu-item', [
    'href' => '/tickets/list',
    'icon' => 'ri-customer-service-2-fill',
    'label' => 'Help & Support',
    'active' => request()->is('tickets/list*')
])
