<x-app-layout page-title="Announcements">
    <x-slot name="url_1">{"link": "/moderator/dashboard", "text": "Moderator"}</x-slot>
    <x-slot name="active">Announcements</x-slot>

    <style>
        .ann-stat-card {
            background: #fff;
            border: 1px solid rgba(226, 232, 240, 0.9);
            border-radius: 0.75rem;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        }

        .ann-table thead th {
            background: #f8fafc;
            color: #64748b;
            border-color: #e2e8f0;
        }

        .ann-table tbody td {
            color: #0f172a;
            border-color: #eef2f7;
            background: transparent;
        }

        .ann-table tbody tr {
            background: #fff;
        }

        .ann-table tbody tr:hover {
            background: #f8fafc;
        }

        /* Dark Mode Overrides */
        [data-theme-mode="dark"] .ann-stat-card,
        .dark .ann-stat-card,
        html.dark .ann-stat-card,
        [data-theme-mode="dark"] .box,
        .dark .box,
        html.dark .box {
            background-color: rgba(15, 23, 42, 0.68) !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
            box-shadow: none !important;
        }

        [data-theme-mode="dark"] .box-header,
        .dark .box-header,
        html.dark .box-header {
            border-bottom-color: rgba(255, 255, 255, 0.06) !important;
            background-color: rgba(15, 23, 42, 0.38) !important;
        }

        [data-theme-mode="dark"] .box-title,
        .dark .box-title,
        html.dark .box-title {
            color: #f8fafc !important;
        }

        [data-theme-mode="dark"] .ann-table thead th,
        .dark .ann-table thead th,
        html.dark .ann-table thead th {
            background-color: rgba(15, 23, 42, 0.92) !important;
            color: #94a3b8 !important;
            border-color: rgba(255, 255, 255, 0.06) !important;
        }

        [data-theme-mode="dark"] .ann-table tbody tr,
        .dark .ann-table tbody tr,
        html.dark .ann-table tbody tr {
            background: rgba(2, 6, 23, 0.34) !important;
        }

        [data-theme-mode="dark"] .ann-table tbody tr:hover,
        .dark .ann-table tbody tr:hover,
        html.dark .ann-table tbody tr:hover {
            background: rgba(148, 163, 184, 0.1) !important;
        }

        [data-theme-mode="dark"] .ann-table tbody td,
        .dark .ann-table tbody td,
        html.dark .ann-table tbody td,
        [data-theme-mode="dark"] .ann-table tbody td span,
        .dark .ann-table tbody td span,
        html.dark .ann-table tbody td span,
        [data-theme-mode="dark"] .ann-table tbody td div,
        .dark .ann-table tbody td div,
        html.dark .ann-table tbody td div {
            color: #e2e8f0 !important;
            border-color: rgba(255, 255, 255, 0.06) !important;
        }

        [data-theme-mode="dark"] .text-textmuted,
        .dark .text-textmuted,
        html.dark .text-textmuted {
            color: #94a3b8 !important;
        }
    </style>
    </style>

    <x-modern-header chip="Communications" title="Announcements" desc="Manage and publish platform-wide announcements.">
        <x-slot:actions>
            <a href="{{ route('moderator.announcements.create') }}" class="ti-btn ti-btn-primary">
                <i class="ri-add-line me-1"></i> Create Announcement
            </a>
        </x-slot:actions>
    </x-modern-header>

    <div class="grid grid-cols-12 gap-6 mx-auto pb-6 sm:px-6 lg:px-8">
        <div class="xl:col-span-12 col-span-12">
            {{-- Stats Cards --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-4">
                <a href="{{ route('moderator.announcements.index', ['status' => 'all']) }}"
                    class="ann-stat-card p-4 {{ $status === 'all' ? 'border-l-4 border-l-primary' : '' }} hover:bg-primary/5 transition-colors">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">All</p>
                    <p class="mt-1 text-2xl font-extrabold text-primary">{{ $counts['all'] }}</p>
                </a>
                <a href="{{ route('moderator.announcements.index', ['status' => 'active']) }}"
                    class="ann-stat-card p-4 {{ $status === 'active' ? 'border-l-4 border-l-success' : '' }} hover:bg-success/5 transition-colors">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">Active</p>
                    <p class="mt-1 text-2xl font-extrabold text-success">{{ $counts['active'] }}</p>
                </a>
                <a href="{{ route('moderator.announcements.index', ['status' => 'scheduled']) }}"
                    class="ann-stat-card p-4 {{ $status === 'scheduled' ? 'border-l-4 border-l-info' : '' }} hover:bg-info/5 transition-colors">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">Scheduled</p>
                    <p class="mt-1 text-2xl font-extrabold text-info">{{ $counts['scheduled'] }}</p>
                </a>
                <a href="{{ route('moderator.announcements.index', ['status' => 'expired']) }}"
                    class="ann-stat-card p-4 {{ $status === 'expired' ? 'border-l-4 border-l-secondary' : '' }} hover:bg-secondary/5 transition-colors">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">Expired/Inactive</p>
                    <p class="mt-1 text-2xl font-extrabold text-secondary">{{ $counts['expired'] }}</p>
                </a>
            </div>

            <div class="box border">
                <div class="box-header flex items-center justify-between">
                    <div class="box-title">{{ ucfirst($status) }} Announcements</div>
                </div>
                <div class="box-body">
                    @if (session('status'))
                        <div class="alert alert-success mb-4">{{ session('status') }}</div>
                    @endif

                    @if($announcements->isEmpty())
                        <div class="text-center py-8">
                            <i class="ri-megaphone-line text-4xl text-textmuted mb-3"></i>
                            <p class="text-textmuted">No announcements found.</p>
                            <a href="{{ route('moderator.announcements.create') }}" class="ti-btn ti-btn-primary mt-4">
                                Create First Announcement
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table whitespace-nowrap table-bordered ann-table">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Type</th>
                                        <th>Target</th>
                                        <th>Status</th>
                                        <th>Published</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($announcements as $announcement)
                                        <tr class="{{ $announcement->is_pinned ? 'bg-primary/5' : '' }}">
                                            <td>
                                                <div class="flex items-center gap-2">
                                                    @if($announcement->is_pinned)
                                                        <i class="ri-pushpin-fill text-primary" title="Pinned"></i>
                                                    @endif
                                                    <span class="font-medium">{{ $announcement->title }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $typeColors = [
                                                        'info' => 'primary',
                                                        'warning' => 'warning',
                                                        'success' => 'success',
                                                        'danger' => 'danger',
                                                    ];
                                                    $color = $typeColors[$announcement->type] ?? 'secondary';
                                                @endphp
                                                <span class="badge bg-{{ $color }}/10 text-{{ $color }}">
                                                    {{ ucfirst($announcement->type) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($announcement->target_roles)
                                                    @foreach($announcement->target_roles as $role)
                                                        <span
                                                            class="badge bg-secondary/10 text-secondary text-xs">{{ ucfirst($role) }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="badge bg-secondary/10 text-secondary text-xs">All</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($announcement->isPublished())
                                                    <span class="badge bg-success/10 text-success">Active</span>
                                                @elseif($announcement->published_at && $announcement->published_at->isFuture())
                                                    <span class="badge bg-info/10 text-info">Scheduled</span>
                                                @elseif($announcement->expires_at && $announcement->expires_at->isPast())
                                                    <span class="badge bg-secondary/10 text-secondary">Expired</span>
                                                @else
                                                    <span class="badge bg-secondary/10 text-secondary">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $announcement->published_at?->format('M d, Y') ?? 'Immediately' }}
                                                @if($announcement->expires_at)
                                                    <br><span class="text-xs text-textmuted">Expires:
                                                        {{ $announcement->expires_at->format('M d, Y') }}</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="flex items-center justify-center gap-1">
                                                    <a href="{{ route('moderator.announcements.edit', $announcement) }}"
                                                        class="ti-btn ti-btn-sm ti-btn-info" title="Edit">
                                                        <i class="ri-edit-line"></i>
                                                    </a>
                                                    <form
                                                        action="{{ route('moderator.announcements.toggle-pin', $announcement) }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit"
                                                            class="ti-btn ti-btn-sm {{ $announcement->is_pinned ? 'ti-btn-primary' : 'ti-btn-secondary' }}"
                                                            title="{{ $announcement->is_pinned ? 'Unpin' : 'Pin' }}">
                                                            <i
                                                                class="ri-pushpin-{{ $announcement->is_pinned ? 'fill' : 'line' }}"></i>
                                                        </button>
                                                    </form>
                                                    <form
                                                        action="{{ route('moderator.announcements.toggle-active', $announcement) }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit"
                                                            class="ti-btn ti-btn-sm {{ $announcement->is_active ? 'ti-btn-success' : 'ti-btn-warning' }}"
                                                            title="{{ $announcement->is_active ? 'Deactivate' : 'Activate' }}">
                                                            <i
                                                                class="ri-{{ $announcement->is_active ? 'eye-line' : 'eye-off-line' }}"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('moderator.announcements.destroy', $announcement) }}"
                                                        method="POST" class="inline"
                                                        onsubmit="return confirm('Are you sure you want to delete this announcement?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="ti-btn ti-btn-sm ti-btn-danger"
                                                            title="Delete">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $announcements->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</x-app-layout>