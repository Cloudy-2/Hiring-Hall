<x-app-layout>
    <x-slot name="title">Help & Support</x-slot>
    <x-slot name="url_1">{"link": "{{ route('tickets.list') }}", "text": "Help & Support"}</x-slot>
    <x-slot name="active">Support</x-slot>

    @php
        $dashboardRoute = match (auth()->user()?->role) {
            'employer' => Route::has('employer.dashboard') ? route('employer.dashboard') : url('/employer/dashboard'),
            default => Route::has('applicant.dashboard') ? route('applicant.dashboard') : url('/applicant/dashboard'),
        };
    @endphp

    <style>
        [data-theme-mode="dark"] .support-container, .dark .support-container, .dark-theme .support-container {
            background-color: rgba(255,255,255,0.02) !important;
            border: 1px solid rgba(255,255,255,0.05) !important;
        }
        [data-theme-mode="dark"] .btn-new-ticket, .dark .btn-new-ticket, .dark-theme .btn-new-ticket {
            background-color: rgba(255,255,255,0.05) !important;
            border-color: rgba(255,255,255,0.1) !important;
            color: rgba(255,255,255,0.9) !important;
        }
        [data-theme-mode="dark"] .empty-ticket-box, .dark .empty-ticket-box, .dark-theme .empty-ticket-box {
            background-color: transparent !important;
            border-color: rgba(255,255,255,0.05) !important;
        }

        /* Improved status badges for dark mode */
        .status-badge {
            font-weight: 700;
            letter-spacing: 0.025em;
            text-transform: uppercase;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.7rem;
        }
        html.dark .status-badge-open, .dark-theme .status-badge-open {
            background-color: rgba(59, 130, 246, 0.2) !important;
            color: #93c5fd !important;
            border: 1px solid rgba(59, 130, 246, 0.3);
        }
        html.dark .status-badge-pending, .dark-theme .status-badge-pending {
            background-color: rgba(99, 102, 241, 0.2) !important;
            color: #c7d2fe !important;
            border: 1px solid rgba(99, 102, 241, 0.35);
        }
        html.dark .status-badge-progress, .dark-theme .status-badge-progress {
            background-color: rgba(245, 158, 11, 0.2) !important;
            color: #fcd34d !important;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }
        html.dark .status-badge-resolved, .dark-theme .status-badge-resolved {
            background-color: rgba(16, 185, 129, 0.2) !important;
            color: #6ee7b7 !important;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        /* Hover fix for dark mode */
        .support-table tbody tr {
            transition: background-color 0.2s ease;
        }
        .support-table tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.02) !important;
        }
        html.dark .support-table tbody tr:hover, .dark-theme .support-table tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.05) !important;
        }

        /* ── Modern Minimalist Header (Interactive Board Style) ── */
        .jf-header-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 2rem;
            padding: 0.5rem 0 1.5rem 0;
            border-bottom: 2px solid #e2e8f0 !important;
            position: relative;
        }

        .jf-header-section.cd-section {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            padding: 0.75rem 1.5rem 1.5rem;
        }

        .jf-header-content { flex: 1; }

        .jf-context-row {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            margin-bottom: 0.75rem;
        }

        .jf-v-bar {
            width: 4px;
            height: 20px;
            background: #6366f1;
            border-radius: 4px;
        }

        .jf-context-label {
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6366f1;
            background: rgba(99, 102, 241, 0.1);
            padding: 2px 10px;
            border-radius: 20px;
        }

        .jf-header-title {
            font-size: 2.25rem;
            font-weight: 800;
            color: #1e293b;
            letter-spacing: -0.02em;
            margin-bottom: 0.75rem;
            line-height: 1.2;
        }

        .jf-header-desc {
            font-size: 1rem;
            color: #64748b;
            max-width: 700px;
            line-height: 1.5;
        }

        .jf-header-desc b { color: #6366f1; font-weight: 700; }

        .jf-header-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0.75rem;
            margin-bottom: 0.25rem;
            position: absolute;
            bottom: 0.85rem;
            right: 0.85rem;
        }

        :is([data-theme-mode="dark"], .dark) .jf-header-section {
            border-bottom: 2px solid #303134 !important;
            background: rgb(30, 32, 35) !important;
        }
        :is([data-theme-mode="dark"], .dark) .jf-header-section.cd-section {
            border-color: #303134 !important;
            background: rgb(30, 32, 35) !important;
        }
        :is([data-theme-mode="dark"], .dark) .jf-header-title { color: #f8fafc !important; }
        :is([data-theme-mode="dark"], .dark) .jf-header-desc { color: #94a3b8 !important; }
        :is([data-theme-mode="dark"], .dark) .jf-context-label { color: #ffffff !important; }
        :is([data-theme-mode="dark"], .dark) hr { border-top-color: rgba(255, 255, 255, 0.08) !important; }
        :is([data-theme-mode="dark"], .dark) .jf-header-actions a,
        :is([data-theme-mode="dark"], .dark) .jf-header-actions button {
            background-color: rgba(30, 41, 59, 0.8) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            color: #ffffff !important;
        }
        :is([data-theme-mode="dark"], .dark) .jf-header-actions a i,
        :is([data-theme-mode="dark"], .dark) .jf-header-actions button i {
            color: #ffffff !important;
        }

        @media (max-width: 992px) {
            .jf-header-section { flex-direction: column; align-items: flex-start; gap: 1.5rem; }
            .jf-header-title { font-size: 1.875rem; }
            .jf-header-actions {
                position: static;
                width: 100%;
                flex-wrap: wrap;
                justify-content: flex-start;
            }
        }
    </style>

    @include('applicants.partials.candidate-styles')

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        {{-- Modern Minimalist Header (Interactive Board Style) --}}
        <x-modern-header :container="false" chip="Customer Support">
            <x-slot name="titleContent"><strong>Support Tickets</strong></x-slot>
            <x-slot name="description">
                View and manage your support requests. Our dedicated team is here to assist you with any <b>Technical</b> or platform-related inquiries.
            </x-slot>
            <x-slot name="actions">
                <a href="{{ route('tickets.create') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-indigo-700 transition-all">
                    <i class="ri-add-line text-lg"></i>
                    <span>New Ticket</span>
                </a>

                <a href="{{ $dashboardRoute }}" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-white text-slate-700 font-bold hover:bg-slate-50 transition-all shadow-sm hover:shadow-md border border-slate-200 text-sm" title="Dashboard">
                    <i class="ri-dashboard-line me-2 text-indigo-500"></i> Dashboard
                </a>
            </x-slot>
        </x-modern-header>

        <div class="box support-container">
            <div class="box-body">

            @if (session('success'))
                <div class="alert alert-success mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-x-auto rounded-xl border border-defaultborder dark:border-white/10">
                @if ($tickets->isEmpty())
                    <div class="empty-ticket-box rounded-xl bg-gray-50 dark:bg-white/5 p-12 text-center">
                        <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="ri-customer-service-2-line text-3xl text-primary"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2">No tickets yet</h3>
                        <p class="text-textmuted text-sm mb-6 max-w-xs mx-auto">Have a question or need technical assistance? Our support team is here to help.</p>
                        <a href="{{ route('tickets.create') }}"
                            class="inline-flex items-center gap-2 rounded-xl bg-primary px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-primary/20 hover:opacity-90 transition-all">
                            <i class="ri-add-line"></i>
                            Create your first ticket
                        </a>
                    </div>
                @else
                    <table class="table min-w-full m-0 support-table">
                        <thead class="bg-gray-50 dark:bg-white/5 border-b border-defaultborder dark:border-white/10">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-black uppercase tracking-widest text-textmuted min-w-[200px]">Subject</th>
                                <th class="px-4 py-3 text-left text-xs font-black uppercase tracking-widest text-textmuted w-24 text-center">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-black uppercase tracking-widest text-textmuted w-36">Last Updated</th>
                                <th class="px-4 py-3 text-center text-xs font-black uppercase tracking-widest text-textmuted w-20">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-defaultborder dark:divide-white/10">
                            @foreach ($tickets as $ticket)
                                <tr class="transition-colors">
                                    <td class="px-4 py-4 max-w-0 w-full">
                                        <div class="truncate-container overflow-hidden">
                                            <a href="{{ route('tickets.show', $ticket) }}" class="font-bold text-gray-800 dark:text-white hover:text-primary dark:hover:text-primary leading-tight block overflow-hidden text-ellipsis whitespace-nowrap" title="{{ $ticket->subject }}">
                                                {{ $ticket->subject }}
                                            </a>
                                            <div class="text-[10px] text-textmuted mt-1 uppercase font-black opacity-60">#{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-center whitespace-nowrap">
                                        <span class="status-badge inline-flex
                                            @if ($ticket->status === 'pending') bg-indigo-100 text-indigo-800 status-badge-pending
                                            @elseif ($ticket->status === 'open') bg-blue-100 text-blue-800 status-badge-open
                                            @elseif ($ticket->status === 'in_progress') bg-amber-100 text-amber-800 status-badge-progress
                                            @elseif ($ticket->status === 'resolved') bg-green-100 text-green-800 status-badge-resolved
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ str_replace('_', ' ', ucfirst($ticket->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-textmuted font-medium whitespace-nowrap">
                                        {{ $ticket->updated_at->format('M j, Y') }}
                                        <div class="text-[10px] opacity-70">{{ $ticket->updated_at->format('g:i A') }}</div>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <a href="{{ route('tickets.show', $ticket) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-primary/10 text-primary hover:bg-primary hover:text-white transition-all shadow-sm" title="View Details">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
            @if ($tickets->hasPages())
                <div class="mt-6">
                    {{ $tickets->links() }}
                </div>
            @endif
        </div>
    </div>
    </div> {{-- Close max-w-7xl mx-auto --}}
</x-app-layout>

