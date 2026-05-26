<x-app-layout>
    <x-slot name="url_1">{"link": "/dashboard", "text": "Dashboard"}</x-slot>
    <x-slot name="active">Notifications</x-slot>
    <x-slot name="pageTitle">Notifications</x-slot>

    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            <div class="box border">
                <div class="box-header flex items-center justify-between flex-wrap gap-3">
                    <h6 class="box-title font-semibold">All Notifications</h6>
                    <div class="flex gap-2">
                        <form action="{{ route('notifications.readAll') }}" method="POST">
                            @csrf
                            <button type="submit" class="ti-btn ti-btn-sm ti-btn-outline-primary"><i class="ri-check-double-line me-1"></i>Mark All Read</button>
                        </form>
                    </div>
                </div>

                {{-- Toolbar: Filters + Search --}}
                <div class="px-5 py-3 border-b border-defaultborder/40 flex items-center justify-between gap-3 flex-wrap">
                    {{-- Status filter tabs --}}
                    <div class="flex items-center gap-2 flex-wrap">
                        <a href="{{ route('notifications.index', array_filter(['q' => $search ?? null])) }}"
                           class="ti-btn ti-btn-sm {{ ($status ?? 'all') === 'all' ? 'ti-btn-primary' : 'ti-btn-outline-light' }}">
                            All
                        </a>
                        <a href="{{ route('notifications.index', array_filter(['status' => 'unread', 'q' => $search ?? null])) }}"
                           class="ti-btn ti-btn-sm {{ ($status ?? '') === 'unread' ? 'ti-btn-primary' : 'ti-btn-outline-light' }}">
                            <i class="ri-mail-unread-line me-1"></i>Unread
                            <span class="badge bg-danger text-white ms-1">{{ $unreadCount }}</span>
                        </a>
                        <a href="{{ route('notifications.index', array_filter(['status' => 'read', 'q' => $search ?? null]  )) }}"
                           class="ti-btn ti-btn-sm {{ ($status ?? '') === 'read' ? 'ti-btn-primary' : 'ti-btn-outline-light' }}">
                            <i class="ri-mail-open-line me-1"></i>Read
                        </a>
                    </div>

                    {{-- Search --}}
                    <form action="{{ route('notifications.index') }}" method="GET" class="flex items-center gap-2">
                        @if(($status ?? 'all') !== 'all')
                            <input type="hidden" name="status" value="{{ $status }}">
                        @endif
                        <div class="relative">
                            <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Search notifications..."
                                class="ti-form-input !py-1.5 !ps-8 !text-xs rounded-md" style="min-width:200px">
                        </div>
                        <button type="submit" class="ti-btn ti-btn-sm ti-btn-primary"><i class="ri-search-line"></i></button>
                        @if($search ?? null)
                            <a href="{{ route('notifications.index', ($status ?? 'all') !== 'all' ? ['status' => $status] : []) }}"
                               class="ti-btn ti-btn-sm ti-btn-outline-light" title="Clear search"><i class="ri-close-line"></i></a>
                        @endif
                    </form>
                </div>

                <div class="box-body">
                    @if($notifications->isEmpty())
                        <div class="text-center py-12">
                            <span class="avatar avatar-xl avatar-rounded bg-secondary/10 !text-secondary mb-3 inline-flex">
                                <i class="ri-notification-off-line text-2xl"></i>
                            </span>
                            <p class="text-textmuted">
                                @if(($status ?? 'all') === 'unread')
                                    No unread notifications
                                @elseif(($search ?? null))
                                    No notifications matching "{{ $search }}"
                                @else
                                    No notifications yet
                                @endif
                            </p>
                        </div>
                    @else
                        <ul class="space-y-2" id="notifications-list">
                            @foreach($notifications as $notification)
                                <li class="border border-defaultborder/60 dark:border-defaultborder/20 rounded-lg p-3 {{ !$notification->read_at ? 'bg-primary/5' : '' }} transition-all" id="page-notif-{{ $notification->id }}">
                                    <div class="flex items-center gap-3">
                                        {{-- Notification content --}}
                                        <a href="{{ $notification->data['action_url'] ?? $notification->data['url'] ?? '#' }}" class="flex items-center gap-3 flex-1 min-w-0">
                                            <span class="avatar avatar-md avatar-rounded bg-primary/10 text-primary flex-shrink-0">
                                                <i class="ri-notification-2-line text-lg"></i>
                                            </span>
                                            <div class="flex-1 min-w-0">
                                                <p class="font-medium mb-0.5 text-sm">
                                                    {{ $notification->data['title'] ?? $notification->data['subject'] ?? 'Notification' }}
                                                </p>
                                                <p class="text-textmuted text-xs mb-0.5">
                                                    {{ $notification->data['message'] ?? $notification->data['message_preview'] ?? $notification->data['subject'] ?? '' }}
                                                </p>
                                                <p class="text-[11px] text-textmuted">{{ $notification->created_at->diffForHumans() }}</p>
                                            </div>
                                            @if(!$notification->read_at)
                                                <span class="w-2 h-2 bg-primary rounded-full flex-shrink-0"></span>
                                            @endif
                                        </a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        <div class="mt-4">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
