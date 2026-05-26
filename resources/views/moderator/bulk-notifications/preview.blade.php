<x-app-layout page-title="Preview Notification">
    <x-slot name="url_1">{"link": "/moderator/dashboard", "text": "Moderator"}</x-slot>
    <x-slot name="url_2">{"link": "/moderator/bulk-notifications", "text": "Bulk Notifications"}</x-slot>
    <x-slot name="active">Preview</x-slot>

    <x-modern-header chip="Communications" title="Preview Notification" desc="Review notification details before sending.">
    </x-modern-header>

    <div class="grid grid-cols-12 gap-6 mx-auto pb-6 sm:px-6 lg:px-8">
        <div class="xl:col-span-8 col-span-12">
            <div class="box border">
                <div class="box-header">
                    <div class="box-title">Preview Notification</div>
                </div>
                <div class="box-body">
                    @if (session('status'))
                        <div class="alert alert-success mb-4">{{ session('status') }}</div>
                    @endif

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

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="text-xs text-textmuted">Type</label>
                            <p class="font-medium">{{ ucfirst($bulkNotification->notification_type) }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-textmuted">Target Audience</label>
                            <div class="flex flex-wrap gap-1 mt-1">
                                @foreach($bulkNotification->target_roles ?? ['all'] as $role)
                                    <span class="badge bg-primary/10 text-primary">{{ ucfirst($role) }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info mb-6">
                        <div class="flex items-center gap-2">
                            <i class="ri-user-line text-xl"></i>
                            <span>This notification will be sent to <strong>{{ number_format($recipientCount) }}</strong> users.</span>
                        </div>
                    </div>

                    <div class="border-t pt-4 flex gap-2">
                        <form action="{{ route('moderator.bulk-notifications.send', $bulkNotification) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to send this notification to {{ number_format($recipientCount) }} users? This cannot be undone.')">
                            @csrf
                            <button type="submit" class="ti-btn ti-btn-success">
                                <i class="ri-send-plane-fill me-1"></i> Send Now
                            </button>
                        </form>
                        <a href="{{ route('moderator.bulk-notifications.index') }}" class="ti-btn ti-btn-light">
                            Save as Draft
                        </a>
                        <form action="{{ route('moderator.bulk-notifications.destroy', $bulkNotification) }}" method="POST" class="inline" onsubmit="return confirm('Delete this draft?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="ti-btn ti-btn-danger">
                                <i class="ri-delete-bin-line me-1"></i> Discard
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="xl:col-span-4 col-span-12">
            <div class="box border border-warning">
                <div class="box-header bg-warning/10">
                    <div class="box-title text-warning">
                        <i class="ri-error-warning-line me-1"></i> Before You Send
                    </div>
                </div>
                <div class="box-body">
                    <ul class="list-disc list-inside text-sm space-y-2">
                        <li>Double-check the subject line</li>
                        <li>Review the message for errors</li>
                        <li>Confirm the target audience is correct</li>
                        <li>Notifications cannot be recalled</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
