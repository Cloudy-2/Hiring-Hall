<x-app-layout page-title="Bulk Notifications">
    <x-slot name="url_1">{"link": "/moderator/dashboard", "text": "Moderator"}</x-slot>
    <x-slot name="active">Bulk Notifications</x-slot>

    <style>
        /* Dark Mode Overrides */
        [data-theme-mode="dark"] .box, .dark .box,
        [data-theme-mode="dark"] .bg-white, .dark .bg-white {
            background-color: rgba(255,255,255,0.02) !important;
            border-color: rgba(255,255,255,0.05) !important;
        }
        [data-theme-mode="dark"] .box-header, .dark .box-header {
            border-bottom-color: rgba(255,255,255,0.05) !important;
            background-color: rgba(255,255,255,0.01) !important;
        }
        [data-theme-mode="dark"] thead, .dark thead,
        [data-theme-mode="dark"] .bg-gray-50\/50, .dark .bg-gray-50\/50 {
            background-color: rgba(255,255,255,0.02) !important;
        }
        [data-theme-mode="dark"] td, .dark td,
        [data-theme-mode="dark"] th, .dark th {
            border-color: rgba(255,255,255,0.05) !important;
        }
    </style>
    </style>

    <x-modern-header chip="Communications" title="Bulk Notifications" desc="Send and track mass notifications to users.">
        <x-slot:actions>
            <a href="{{ route('moderator.bulk-notifications.create') }}" class="ti-btn ti-btn-primary">
                <i class="ri-add-line me-1"></i> New Notification
            </a>
        </x-slot:actions>
    </x-modern-header>

    <div class="grid grid-cols-12 gap-6 mx-auto pb-6 sm:px-6 lg:px-8">
        <div class="xl:col-span-12 col-span-12">
            {{-- Stats Cards --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-4">
                <a href="{{ route('moderator.bulk-notifications.index', ['status' => 'all']) }}"
                   class="bg-white dark:bg-slate-900 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-slate-800 {{ $status === 'all' ? 'border-l-4 border-l-primary' : '' }} hover:bg-primary/5 transition-colors">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">All</p>
                    <p class="mt-1 text-2xl font-extrabold text-primary">{{ $counts['all'] }}</p>
                </a>
                <a href="{{ route('moderator.bulk-notifications.index', ['status' => 'draft']) }}"
                   class="bg-white dark:bg-slate-900 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-slate-800 {{ $status === 'draft' ? 'border-l-4 border-l-secondary' : '' }} hover:bg-secondary/5 transition-colors">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">Drafts</p>
                    <p class="mt-1 text-2xl font-extrabold text-secondary">{{ $counts['draft'] }}</p>
                </a>
                <a href="{{ route('moderator.bulk-notifications.index', ['status' => 'sending']) }}"
                   class="bg-white dark:bg-slate-900 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-slate-800 {{ $status === 'sending' ? 'border-l-4 border-l-warning' : '' }} hover:bg-warning/5 transition-colors">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">Sending</p>
                    <p class="mt-1 text-2xl font-extrabold text-warning">{{ $counts['sending'] }}</p>
                </a>
                <a href="{{ route('moderator.bulk-notifications.index', ['status' => 'sent']) }}"
                   class="bg-white dark:bg-slate-900 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-slate-800 {{ $status === 'sent' ? 'border-l-4 border-l-success' : '' }} hover:bg-success/5 transition-colors">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">Sent</p>
                    <p class="mt-1 text-2xl font-extrabold text-success">{{ $counts['sent'] }}</p>
                </a>
            </div>

            <div class="box border">
                <div class="box-header flex items-center justify-between">
                    <div class="box-title">{{ ucfirst($status) }} Notifications</div>
                </div>
                <div class="box-body">
                    @if (session('status'))
                        <div class="alert alert-success mb-4">{{ session('status') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger mb-4">{{ session('error') }}</div>
                    @endif

                    @if($notifications->isEmpty())
                        <div class="text-center py-8">
                            <i class="ri-mail-send-line text-4xl text-textmuted mb-3"></i>
                            <p class="text-textmuted">No bulk notifications found.</p>
                            <a href="{{ route('moderator.bulk-notifications.create') }}" class="ti-btn ti-btn-primary mt-4">
                                Create First Notification
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table whitespace-nowrap table-bordered">
                                <thead>
                                    <tr>
                                        <th>Subject</th>
                                        <th>Target</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Recipients</th>
                                        <th>Created</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($notifications as $notification)
                                        <tr>
                                            <td>
                                                <span class="font-medium">{{ Str::limit($notification->subject, 40) }}</span>
                                            </td>
                                            <td>
                                                @if($notification->target_roles)
                                                    @foreach($notification->target_roles as $role)
                                                        <span class="badge bg-secondary/10 text-secondary text-xs">{{ ucfirst($role) }}</span>
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-primary/10 text-primary">{{ ucfirst($notification->notification_type) }}</span>
                                            </td>
                                            <td>
                                                @if($notification->status === 'draft')
                                                    <span class="badge bg-secondary/10 text-secondary">Draft</span>
                                                @elseif($notification->status === 'sending')
                                                    <span class="badge bg-warning/10 text-warning">
                                                        <i class="ri-loader-4-line animate-spin me-1"></i> Sending
                                                    </span>
                                                @elseif($notification->status === 'sent')
                                                    <span class="badge bg-success/10 text-success">Sent</span>
                                                @else
                                                    <span class="badge bg-danger/10 text-danger">Failed</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($notification->isSent())
                                                    <span class="text-success">{{ $notification->sent_count }}</span>
                                                    @if($notification->failed_count > 0)
                                                        / <span class="text-danger">{{ $notification->failed_count }} failed</span>
                                                    @endif
                                                @else
                                                    {{ $notification->recipient_count ?: '-' }}
                                                @endif
                                            </td>
                                            <td>{{ $notification->created_at->format('M d, Y') }}</td>
                                            <td class="text-center">
                                                <div class="flex items-center justify-center gap-1">
                                                    <a href="{{ route('moderator.bulk-notifications.show', $notification) }}" class="ti-btn ti-btn-sm ti-btn-info" title="View">
                                                        <i class="ri-eye-line"></i>
                                                    </a>
                                                    @if($notification->isDraft())
                                                        <a href="{{ route('moderator.bulk-notifications.preview', $notification) }}" class="ti-btn ti-btn-sm ti-btn-primary" title="Preview & Send">
                                                            <i class="ri-send-plane-line"></i>
                                                        </a>
                                                        <form action="{{ route('moderator.bulk-notifications.destroy', $notification) }}" method="POST" class="inline" onsubmit="return confirm('Delete this draft?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="ti-btn ti-btn-sm ti-btn-danger" title="Delete">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
