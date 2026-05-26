<x-app-layout
    page-title="Report Details"
    :breadcrumbs="[
        ['label' => 'Moderator', 'url' => route('moderator.dashboard')],
        ['label' => 'Chat Moderator', 'url' => route('chats.monitor')],
        ['label' => 'Reported messages', 'url' => route('moderator.chat-reports.index')],
    ]"
    active="Report details"
>
    <style>
        [data-theme-mode="dark"] .report-detail .ti-btn-soft-primary:hover,
        [data-theme-mode="dark"] .report-detail .ti-btn-soft-secondary:hover,
        .dark .report-detail .ti-btn-soft-primary:hover,
        .dark .report-detail .ti-btn-soft-secondary:hover {
            background-color: rgba(55,65,81,0.9) !important;
            color: #f3f4f6 !important;
            border-color: rgba(255,255,255,0.14) !important;
        }
    </style>

    <div class="box report-detail">
        <div class="box-body">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                <div>
                    <h6 class="font-bold text-2xl text-gray-700 dark:text-white">
                        Report #{{ $report->id }}
                    </h6>
                    <div class="flex flex-wrap items-center gap-2 mt-1 text-sm text-textmuted">
                        <span>Reported {{ $report->created_at->format('M j, Y g:i A') }}</span>
                        <span>·</span>
                        <span class="font-medium text-gray-700 dark:text-gray-200">By: {{ $report->reporter->name ?? '—' }}</span>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    @if ($report->conversation_id)
                        <a href="{{ route('conversations.show', $report->conversation_id) }}" class="ti-btn ti-btn-soft-primary" target="_blank" rel="noopener noreferrer">
                            <i class="bi bi-chat-square-dots"></i> Open in Chat Moderator
                        </a>
                    @endif
                    @if ($report->status === 'pending')
                        <form action="{{ route('moderator.chat-reports.resolve', $report) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="ti-btn ti-btn-success">
                                <i class="bi bi-check-circle"></i> Mark resolved
                            </button>
                        </form>
                        <form action="{{ route('moderator.chat-reports.dismiss', $report) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="ti-btn ti-btn-soft-secondary">
                                <i class="bi bi-x-circle"></i> Dismiss
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            @if (session('success'))
                <div class="mb-4 rounded-lg bg-green-100 dark:bg-green-900/30 px-4 py-3 text-sm text-green-800 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @endif

            <div class="space-y-4">
                <div class="rounded-lg border border-defaultborder dark:border-defaultborder/10 bg-gray-50 dark:bg-white/5 p-4">
                    <h6 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Report reason</h6>
                    <p class="text-gray-800 dark:text-gray-200 whitespace-pre-wrap">{{ $report->reason ?? '—' }}</p>
                </div>

                <div class="rounded-lg border border-defaultborder dark:border-defaultborder/10 bg-gray-50 dark:bg-white/5 p-4">
                    <h6 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Reported message</h6>
                    @if ($report->message)
                        <div class="flex items-center gap-2 text-sm text-textmuted mb-2">
                            <span class="font-medium text-gray-700 dark:text-gray-200">{{ $report->message->user->name ?? 'Unknown' }}</span>
                            <span>{{ $report->message->created_at?->format('M j, Y g:i A') ?? '—' }}</span>
                            @if ($report->message->trashed())
                                <span class="rounded bg-red-100 dark:bg-red-900/30 px-1.5 py-0.5 text-xs text-red-700 dark:text-red-200">Deleted</span>
                            @endif
                        </div>
                        <div class="text-gray-800 dark:text-gray-200 whitespace-pre-wrap">{{ $report->message->body ?? $report->context['message_body'] ?? '—' }}</div>
                    @else
                        <p class="text-textmuted">Message no longer available.</p>
                        @if(!empty($report->context['message_body']))
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Snapshot: {{ $report->context['message_body'] }}</p>
                        @endif
                    @endif
                </div>

                @if ($report->status !== 'pending' && $report->resolver)
                    <div class="rounded-lg border border-defaultborder dark:border-defaultborder/10 p-4 text-sm text-textmuted">
                        {{ ucfirst($report->status) }} by {{ $report->resolver->name ?? '—' }} on {{ $report->resolved_at?->format('M j, Y g:i A') ?? '—' }}.
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
