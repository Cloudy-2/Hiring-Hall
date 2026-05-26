<x-app-layout page-title="Manage Support Tickets" :breadcrumbs="[
        ['label' => 'Moderator', 'url' => route('moderator.dashboard')],
    ]" active="Support Tickets">
    <style>
        /* Dark Mode Overrides */
        [data-theme-mode="dark"] .box, .dark .box,
        [data-theme-mode="dark"] .bg-white, .dark .bg-white {
            background-color: rgb(30, 32, 34) !important;
            border-color: rgba(255, 255, 255, 0.05) !important;
        }

        [data-theme-mode="dark"] .box-header, .dark .box-header {
            border-bottom-color: rgba(255, 255, 255, 0.05) !important;
            background-color: rgb(30, 32, 34) !important;
        }

        .box-body {
            padding: 1.25rem;
            border-radius: 0.75rem;
            background-color: #fff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        }

        :is(.dark, [data-theme-mode="dark"]) .box-body {
            background-color: rgb(30, 32, 34);
            border-color: rgba(255, 255, 255, 0.05);
        }

        .moderator-ticket-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1.25rem;
        }

        .moderator-ticket-tab {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.8125rem;
            transition: all 0.2s ease;
        }

        .moderator-ticket-searchbar {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.25rem;
        }

        .moderator-ticket-search-field {
            flex: 1;
        }

        .moderator-ticket-bulk-row {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            background: #f8fafc;
            border-radius: 0.5rem;
            margin-bottom: 1.25rem;
        }

        :is(.dark, [data-theme-mode="dark"]) .moderator-ticket-bulk-row {
            background: rgba(255, 255, 255, 0.03);
        }

        .moderator-ticket-table-wrap {
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            overflow: hidden;
        }

        :is(.dark, [data-theme-mode="dark"]) .moderator-ticket-table-wrap {
            border-color: rgba(255, 255, 255, 0.05);
        }

        .moderator-ticket-table thead th {
            background: #f8fafc;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.025em;
            color: #64748b;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #e2e8f0;
        }

        :is(.dark, [data-theme-mode="dark"]) .moderator-ticket-table thead th {
            background: rgba(255, 255, 255, 0.02);
            color: #94a3b8;
            border-bottom-color: rgba(255, 255, 255, 0.05);
        }

        .moderator-ticket-table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }

        :is(.dark, [data-theme-mode="dark"]) .moderator-ticket-table tbody td {
            border-bottom-color: rgba(255, 255, 255, 0.03);
        }

        .moderator-ticket-table tbody tr:last-child td {
            border-bottom: none;
        }

        .moderator-ticket-table tbody tr:hover {
            background-color: #f8fafc;
        }

        :is(.dark, [data-theme-mode="dark"]) .moderator-ticket-table tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.01);
        }

        .moderator-ticket-status-pill {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.625rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .moderator-ticket-subject-text {
            color: #1e293b;
            text-decoration: none;
            transition: color 0.2s;
        }

        :is(.dark, [data-theme-mode="dark"]) .moderator-ticket-subject-text {
            color: #f1f5f9;
        }

        .moderator-ticket-subject-text:hover {
            color: #3b82f6;
        }

        .moderator-ticket-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2rem;
            height: 2rem;
            border-radius: 0.375rem;
            background: #f1f5f9;
            color: #64748b;
            transition: all 0.2s;
        }

        :is(.dark, [data-theme-mode="dark"]) .moderator-ticket-action {
            background: rgba(255, 255, 255, 0.05);
            color: #94a3b8;
        }

        .moderator-ticket-action:hover {
            background: #e2e8f0;
            color: #1e293b;
        }

        :is(.dark, [data-theme-mode="dark"]) .moderator-ticket-action:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #f1f5f9;
        }
    </style>

    <x-modern-header class="text-2xl" chip="Tech Support" title="Support Tickets"
        desc="View and manage all support tickets.">
    </x-modern-header>

    <div class="mx-auto mt-4 pb-6 sm:px-6 lg:px-8">
        <div class="box-body">
            @if (session('success'))
                <div class="alert alert-success mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="moderator-ticket-controls">
                {{-- Status filter tabs --}}
                <div class="moderator-ticket-tabs">
                    <a href="{{ route('moderator.tickets.index') }}"
                        class="moderator-ticket-tab {{ $status === null ? 'bg-primary text-white' : 'bg-gray-100 dark:bg-white/10 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-white/20' }}">
                        All ({{ $counts['all'] }})
                    </a>
                    <a href="{{ route('moderator.tickets.index', ['status' => 'pending']) }}"
                        class="moderator-ticket-tab {{ $status === 'pending' ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-white/10 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-white/20' }}">
                        Pending ({{ $counts['pending'] }})
                    </a>
                    <a href="{{ route('moderator.tickets.index', ['status' => 'in_progress']) }}"
                        class="moderator-ticket-tab {{ $status === 'in_progress' ? 'bg-amber-600 text-white' : 'bg-gray-100 dark:bg-white/10 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-white/20' }}">
                        In Progress ({{ $counts['in_progress'] }})
                    </a>
                    <a href="{{ route('moderator.tickets.index', ['status' => 'resolved']) }}"
                        class="moderator-ticket-tab {{ $status === 'resolved' ? 'bg-green-600 text-white' : 'bg-gray-100 dark:bg-white/10 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-white/20' }}">
                        Resolved ({{ $counts['resolved'] }})
                    </a>
                    <a href="{{ route('moderator.tickets.index', ['status' => 'closed']) }}"
                        class="moderator-ticket-tab {{ $status === 'closed' ? 'bg-gray-600 text-white' : 'bg-gray-100 dark:bg-white/10 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-white/20' }}">
                        Closed ({{ $counts['closed'] }})
                    </a>
                </div>

                <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
                    <form method="GET" action="{{ route('moderator.tickets.index') }}" class="moderator-ticket-searchbar mb-0 flex-1">
                        <input type="hidden" name="status" value="{{ $status }}">
                        <div class="moderator-ticket-search-field flex gap-2">
                            <input id="ticket-search" type="text" name="search" value="{{ $search ?? '' }}"
                                placeholder="Search by subject, message, user name or email" class="form-control">
                            <button type="submit" class="ti-btn ti-btn-primary">
                                <i class="bi bi-search"></i>
                            </button>
                            @if(!empty($search))
                                <a href="{{ route('moderator.tickets.index', array_filter(['status' => $status])) }}"
                                    class="ti-btn ti-btn-light">Reset</a>
                            @endif
                        </div>
                    </form>

                    <div class="flex items-center gap-2" id="ticket-live-controls" data-updates-url="{{ route('moderator.tickets.updates') }}" data-status="{{ $status }}" data-search="{{ $search ?? '' }}" data-since="{{ now()->toIso8601String() }}">
                        <button type="button" id="refresh-tickets-btn" class="ti-btn ti-btn-sm ti-btn-soft-primary">
                            <i class="bi bi-arrow-clockwise me-1"></i> Refresh
                        </button>
                        <span id="new-updates-badge" class="hidden inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold bg-danger/10 text-danger"></span>
                        <span id="last-checked-text" class="text-xs text-textmuted">Live monitor active</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('moderator.tickets.bulk-update-status') }}" id="bulk-status-form">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" id="bulk-status-value">
                    <input type="hidden" name="filter_status" value="{{ $status }}">
                    <input type="hidden" name="search" value="{{ $search ?? '' }}">

                    <div class="moderator-ticket-bulk-row py-3 px-4 mb-4">
                        <div class="flex items-center gap-3">
                            <label for="bulk-status-select" class="font-semibold text-sm whitespace-nowrap mb-0">Bulk Action:</label>
                            <select id="bulk-status-select"
                                class="form-control form-control-sm moderator-ticket-bulk-select" disabled>
                                <option value="">Change status to...</option>
                                <option value="pending">Pending</option>
                                <option value="in_progress">In Progress</option>
                                <option value="resolved">Resolved</option>
                                <option value="closed">Closed</option>
                            </select>
                            <button type="submit" id="bulk-apply-btn" class="ti-btn ti-btn-sm ti-btn-primary" disabled>
                                Apply
                            </button>
                            <span id="bulk-selected-count" class="text-xs text-textmuted font-medium ml-2">0 selected</span>
                        </div>
                    </div>
                </div>

                    <div class="moderator-ticket-table-wrap overflow-hidden border border-defaultborder/10 rounded-lg">
                        @if ($tickets->isEmpty())
                            <div class="p-12 text-center">
                                <div class="bg-light dark:bg-white/5 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="bi bi-life-preserver text-2xl text-textmuted"></i>
                                </div>
                                <h5 class="font-semibold mb-1">No tickets found</h5>
                                <p class="text-textmuted text-sm px-4">There are no support tickets matching your current filters.</p>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="table min-w-full moderator-ticket-table mb-0">
                                    <thead>
                                        <tr>
                                            <th class="w-10">
                                                <input type="checkbox" id="select-all-tickets" aria-label="Select all tickets"
                                                    class="form-check-input">
                                            </th>
                                            <th class="w-20">ID</th>
                                            <th class="w-52">User</th>
                                            <th>Subject</th>
                                            <th class="text-center w-24">Replies</th>
                                            <th class="w-32">Status</th>
                                            <th class="w-44">Created</th>
                                            <th class="text-right w-20">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tickets as $ticket)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="ticket_ids[]" value="{{ $ticket->id }}"
                                                        class="ticket-row-checkbox form-check-input"
                                                        aria-label="Select ticket {{ $ticket->id }}">
                                                </td>
                                                <td class="text-xs font-mono text-textmuted">
                                                    #{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</td>
                                                <td>
                                                    <div class="font-semibold text-sm">{{ $ticket->user->name ?? '—' }}</div>
                                                    <div class="text-xs text-textmuted">{{ $ticket->user->email ?? '' }}</div>
                                                </td>
                                                <td>
                                                    <a href="{{ route('moderator.tickets.show', $ticket) }}"
                                                        class="moderator-ticket-subject-text font-semibold text-sm block" title="{{ $ticket->subject }}">
                                                        {{ $ticket->subject }}
                                                    </a>
                                                </td>
                                                <td class="text-center">
                                                    <span class="text-xs bg-light dark:bg-white/10 px-2 py-1 rounded font-medium">
                                                        {{ $ticket->replies_count ?? 0 }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="moderator-ticket-status-pill
                                                                @if ($ticket->status === 'pending') bg-indigo-100 text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-300
                                                                @elseif ($ticket->status === 'open') bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300
                                                                @elseif ($ticket->status === 'in_progress') bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-300
                                                                @elseif ($ticket->status === 'resolved') bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300
                                                                @else bg-slate-100 text-slate-700 dark:bg-slate-500/20 dark:text-slate-300
                                                                @endif">
                                                        {{ str_replace('_', ' ', ucfirst($ticket->status)) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="text-sm">{{ $ticket->created_at->format('M j, Y') }}</div>
                                                    <div class="text-xs text-textmuted">{{ $ticket->created_at->format('g:i A') }}</div>
                                                </td>
                                                <td class="text-right">
                                                    <a href="{{ route('moderator.tickets.show', $ticket) }}" class="moderator-ticket-action"
                                                        title="View ticket">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if($tickets->hasPages())
                                <div class="px-4 py-3 border-top border-defaultborder/10">
                                    {{ $tickets->links() }}
                                </div>
                            @endif
                        @endif
                    </div>
                </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var refreshBtn = document.getElementById('refresh-tickets-btn');
            var liveControls = document.getElementById('ticket-live-controls');
            var badge = document.getElementById('new-updates-badge');
            var lastCheckedText = document.getElementById('last-checked-text');

            var selectAll = document.getElementById('select-all-tickets');
            var rowCheckboxes = Array.from(document.querySelectorAll('.ticket-row-checkbox'));
            var bulkSelect = document.getElementById('bulk-status-select');
            var bulkApplyBtn = document.getElementById('bulk-apply-btn');
            var bulkCount = document.getElementById('bulk-selected-count');
            var bulkStatusValue = document.getElementById('bulk-status-value');
            var bulkForm = document.getElementById('bulk-status-form');

            var since = liveControls?.dataset.since || new Date().toISOString();

            function getSelectedCount() {
                return rowCheckboxes.filter(function (cb) { return cb.checked; }).length;
            }

            function updateBulkUi() {
                var selected = getSelectedCount();
                if (bulkCount) {
                    bulkCount.textContent = selected + ' selected';
                }
                if (bulkSelect) {
                    bulkSelect.disabled = selected === 0;
                }
                if (bulkApplyBtn) {
                    bulkApplyBtn.disabled = selected === 0 || !bulkSelect.value;
                }
                if (selectAll) {
                    selectAll.checked = rowCheckboxes.length > 0 && selected === rowCheckboxes.length;
                    selectAll.indeterminate = selected > 0 && selected < rowCheckboxes.length;
                }
            }

            if (selectAll) {
                selectAll.addEventListener('change', function () {
                    rowCheckboxes.forEach(function (cb) {
                        cb.checked = selectAll.checked;
                    });
                    updateBulkUi();
                });
            }

            rowCheckboxes.forEach(function (cb) {
                cb.addEventListener('change', updateBulkUi);
            });

            if (bulkSelect) {
                bulkSelect.addEventListener('change', function () {
                    if (bulkStatusValue) {
                        bulkStatusValue.value = bulkSelect.value;
                    }
                    updateBulkUi();
                });
            }

            if (bulkForm) {
                bulkForm.addEventListener('submit', function (e) {
                    if (!bulkStatusValue?.value || getSelectedCount() === 0) {
                        e.preventDefault();
                    }
                });
            }

            async function pollUpdates() {
                if (!liveControls) return;
                var updatesUrl = liveControls.dataset.updatesUrl;
                if (!updatesUrl) return;

                var params = new URLSearchParams();
                if (liveControls.dataset.status) params.set('status', liveControls.dataset.status);
                if (liveControls.dataset.search) params.set('search', liveControls.dataset.search);
                if (since) params.set('since', since);

                try {
                    var response = await fetch(updatesUrl + '?' + params.toString(), {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    if (!response.ok) return;

                    var data = await response.json();
                    var count = Number(data.updated_count || 0);

                    if (count > 0 && badge) {
                        badge.classList.remove('hidden');
                        badge.textContent = count + ' new update' + (count > 1 ? 's' : '');
                    }

                    since = data.server_time || new Date().toISOString();
                    if (lastCheckedText) {
                        lastCheckedText.textContent = 'Last checked: ' + new Date().toLocaleTimeString();
                    }
                } catch (error) {
                    // Keep polling silently even if one request fails.
                }
            }

            if (refreshBtn) {
                refreshBtn.addEventListener('click', function () {
                    window.location.reload();
                });
            }

            updateBulkUi();
            setInterval(pollUpdates, 30000);
        });
    </script>
</x-app-layout>