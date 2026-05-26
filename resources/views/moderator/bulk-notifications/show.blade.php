<x-app-layout page-title="Notification Details">
    <x-slot name="url_1">{"link": "/moderator/dashboard", "text": "Moderator"}</x-slot>
    <x-slot name="url_2">{"link": "/moderator/bulk-notifications", "text": "Bulk Notifications"}</x-slot>
    <x-slot name="active">Details</x-slot>

    <x-modern-header chip="Communications" title="Notification Details"
        desc="View comprehensive information about this notification.">
    </x-modern-header>

    <div class="grid grid-cols-12 gap-6 mx-auto pb-6 sm:px-6 lg:px-8">
        <div class="xl:col-span-8 col-span-12">
            <div class="box border">
                <div class="box-header flex items-center justify-between">
                    <div class="box-title">Notification Details</div>
                    <div>
                        @if($bulkNotification->status === 'draft')
                            <span class="badge bg-secondary/10 text-secondary">Draft</span>
                        @elseif($bulkNotification->status === 'sending')
                            <span class="badge bg-warning/10 text-warning">
                                <i class="ri-loader-4-line animate-spin me-1"></i> Sending
                            </span>
                        @elseif($bulkNotification->status === 'sent')
                            <span class="badge bg-success/10 text-success">Sent</span>
                        @else
                            <span class="badge bg-danger/10 text-danger">Failed</span>
                        @endif
                    </div>
                </div>
                <div class="box-body">
                    <div class="mb-4">
                        <label class="text-xs text-textmuted">Subject</label>
                        <p class="text-lg font-semibold">{{ $bulkNotification->subject }}</p>
                    </div>

                    <div class="mb-6">
                        <label class="text-xs text-textmuted">Message</label>
                        <div class="bg-light dark:bg-slate-800 p-4 rounded-lg mt-2">
                            {!! nl2br(e($bulkNotification->message)) !!}
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div>
                            <label class="text-xs text-textmuted">Type</label>
                            <p class="font-medium">{{ ucfirst($bulkNotification->notification_type) }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-textmuted">Target</label>
                            <div class="flex flex-wrap gap-1 mt-1">
                                @foreach($bulkNotification->target_roles ?? ['all'] as $role)
                                    <span class="badge bg-primary/10 text-primary text-xs">{{ ucfirst($role) }}</span>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <label class="text-xs text-textmuted">Created</label>
                            <p class="font-medium">{{ $bulkNotification->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        @if($bulkNotification->sent_at)
                            <div>
                                <label class="text-xs text-textmuted">Sent At</label>
                                <p class="font-medium">{{ $bulkNotification->sent_at->format('M d, Y H:i') }}</p>
                            </div>
                        @endif
                    </div>

                    @if($bulkNotification->isSent())
                        <div class="grid grid-cols-3 gap-4 p-4 bg-light dark:bg-slate-800 rounded-lg">
                            <div class="text-center">
                                <p class="text-2xl font-bold text-primary">
                                    {{ number_format($bulkNotification->recipient_count) }}</p>
                                <p class="text-xs text-textmuted">Total Recipients</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-bold text-success">
                                    {{ number_format($bulkNotification->sent_count) }}</p>
                                <p class="text-xs text-textmuted">Sent</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-bold text-danger">
                                    {{ number_format($bulkNotification->failed_count) }}</p>
                                <p class="text-xs text-textmuted">Failed</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="xl:col-span-4 col-span-12">
            <div class="box border">
                <div class="box-header">
                    <div class="box-title">Sender Info</div>
                </div>
                <div class="box-body">
                    @if($bulkNotification->sentBy)
                        <p class="text-sm"><strong>Sent By:</strong> {{ $bulkNotification->sentBy->name }}</p>
                        <p class="text-sm text-textmuted">{{ $bulkNotification->sentBy->email }}</p>
                    @else
                        <p class="text-textmuted">Unknown sender</p>
                    @endif
                </div>
            </div>

            @if($bulkNotification->isDraft())
                <div class="box border mt-4">
                    <div class="box-header">
                        <div class="box-title">Actions</div>
                    </div>
                    <div class="box-body space-y-2">
                        <a href="{{ route('moderator.bulk-notifications.preview', $bulkNotification) }}"
                            class="ti-btn ti-btn-primary w-full">
                            <i class="ri-send-plane-line me-1"></i> Preview & Send
                        </a>
                        <form action="{{ route('moderator.bulk-notifications.destroy', $bulkNotification) }}" method="POST"
                            onsubmit="return confirm('Delete this draft?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="ti-btn ti-btn-danger w-full">
                                <i class="ri-delete-bin-line me-1"></i> Delete Draft
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>

</x-app-layout>