<x-app-layout page-title="Reported Chat Messages" :breadcrumbs="[
        ['label' => 'Moderator', 'url' => route('moderator.dashboard')],
        ['label' => 'Chat Moderator', 'url' => route('chats.monitor')],
    ]"
    active="Reported messages">

    <style>
        .box-body {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.06);
            transition: box-shadow 160ms ease, border-color 160ms ease;
            margin: 24px;
            margin-top: 0;
        }

        [data-theme-mode="dark"] .box-body,
        .dark .box-body {
            background: rgb(30, 32, 35) !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
            box-shadow: none !important;
        }
    </style>

    <x-modern-header class="text-2xl" chip="Chat Moderator" title="Reported Chat Messages"
        desc='Review and resolve user reports of chat messages.'>
        <x-slot name="actions">
            <a href="{{ route('chats.monitor') }}" class="ti-btn ti-btn-sm ti-btn-soft-primary shrink-0">
                <i class="bi bi-chat-square-dots me-1"></i> Chat monitor
            </a>
        </x-slot>
    </x-modern-header>

    <div class="box-body">
        @if (session('success'))
            <div
                class="mb-4 rounded-lg bg-green-100 dark:bg-green-900/30 px-4 py-3 text-sm text-green-800 dark:text-green-200">
                {{ session('success') }}
            </div>
        @endif

        {{-- Status filter tabs --}}
        <div class="flex flex-wrap gap-2 mb-4">
            <a href="{{ route('moderator.chat-reports.index') }}"
                class="rounded-lg px-3 py-2 text-sm font-medium {{ $status === null ? 'bg-primary text-white' : 'filter-tab-inactive bg-gray-100 text-gray-700 hover:bg-gray-200 dark:text-gray-200 dark:hover:bg-gray-700' }}">
                All ({{ $counts['all'] }})
            </a>
            <a href="{{ route('moderator.chat-reports.index', ['status' => 'pending']) }}"
                class="rounded-lg px-3 py-2 text-sm font-medium {{ $status === 'pending' ? 'bg-amber-600 text-white' : 'filter-tab-inactive bg-gray-100 text-gray-700 hover:bg-gray-200 dark:text-gray-200 dark:hover:bg-gray-700' }}">
                Pending ({{ $counts['pending'] }})
            </a>
            <a href="{{ route('moderator.chat-reports.index', ['status' => 'resolved']) }}"
                class="rounded-lg px-3 py-2 text-sm font-medium {{ $status === 'resolved' ? 'bg-green-600 text-white' : 'filter-tab-inactive bg-gray-100 text-gray-700 hover:bg-gray-200 dark:text-gray-200 dark:hover:bg-gray-700' }}">
                Resolved ({{ $counts['resolved'] }})
            </a>
            <a href="{{ route('moderator.chat-reports.index', ['status' => 'dismissed']) }}"
                class="rounded-lg px-3 py-2 text-sm font-medium {{ $status === 'dismissed' ? 'bg-gray-600 text-white' : 'filter-tab-inactive bg-gray-100 text-gray-700 hover:bg-gray-200 dark:text-gray-200 dark:hover:bg-gray-700' }}">
                Dismissed ({{ $counts['dismissed'] }})
            </a>
        </div>

        <div class="overflow-x-auto">
            @if ($reports->isEmpty())
                <div
                    class="rounded-lg border border-defaultborder dark:border-defaultborder/10 bg-gray-50 dark:bg-white/5 text-center">
                    <i class="bi bi-chat-square-dots text-4xl text-gray-400 dark:text-gray-500 mb-3"></i>
                    <p class="text-gray-600 dark:text-gray-300">No reports found.</p>
                </div>
            @else
                <table class="table min-w-full">
                    <thead
                        class="reports-head border-b border-defaultborder dark:border-defaultborder/10 bg-gray-50 dark:bg-white/5">
                        <tr>
                            <th class="px-3 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Reported
                                by</th>
                            <th class="px-3 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Reason /
                                Message</th>
                            <th class="px-3 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 w-28">
                                Status</th>
                            <th class="px-3 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 w-36">
                                Reported at</th>
                            <th class="px-3 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 w-24">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reports as $report)
                            <tr
                                class="reports-row border-b border-defaultborder dark:border-defaultborder/10 hover:bg-gray-50 dark:hover:bg-white/5">
                                <td class="px-3 py-2 text-sm text-gray-700 dark:text-gray-200">
                                    {{ $report->reporter->name ?? '—' }}
                                </td>
                                <td class="px-3 py-2">
                                    <a href="{{ route('moderator.chat-reports.show', $report) }}"
                                        class="font-medium text-primary hover:underline">
                                        {{ Str::limit($report->reason ?? ($report->message ? Str::limit($report->message->body ?? '', 40) : '—'), 50) }}
                                    </a>
                                </td>
                                <td class="px-3 py-2">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                                            @if ($report->status === 'pending') bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-200
                                                            @elseif ($report->status === 'resolved') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200
                                                            @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                                                            @endif">
                                        {{ ucfirst($report->status) }}
                                    </span>
                                </td>
                                <td class="px-3 py-2 text-sm text-textmuted">{{ $report->created_at->format('M j, Y g:i A') }}
                                </td>
                                <td class="px-3 py-2">
                                    <a href="{{ route('moderator.chat-reports.show', $report) }}"
                                        class="ti-btn ti-btn-sm ti-btn-soft-primary">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="reports-pagination mt-4">
                    {{ $reports->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>