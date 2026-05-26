<x-app-layout
    page-title="Ticket Details"
    :breadcrumbs="[
        ['label' => 'Moderator', 'url' => route('moderator.dashboard')],
        ['label' => 'Support Tickets', 'url' => route('moderator.tickets.index')],
    ]"
    active="Ticket Details"
>
    <style>
        html.dark .moderator-ticket-form .form-control, .dark-theme .moderator-ticket-form .form-control {
            background-color: rgba(2, 6, 23, 0.55) !important;
            border-color: rgba(148, 163, 184, 0.28) !important;
            color: #e2e8f0 !important;
        }
        html.dark .moderator-ticket-form .form-label, .dark-theme .moderator-ticket-form .form-label {
            color: #e5e7eb !important;
        }

        .ticket-attachment-preview-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.75rem;
            border: 1px solid rgba(226, 232, 240, 1);
            background: #fff;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.06);
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }

        .ticket-attachment-preview-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 18px rgba(15, 23, 42, 0.08);
        }

        .ticket-attachment-preview-card {
            width: 160px;
            min-height: 112px;
            padding: 0.8rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 0.4rem;
            background: linear-gradient(180deg, #ffffff, #f8fafc);
        }

        .ticket-attachment-preview-card i {
            font-size: 1.9rem;
            color: #2563eb;
        }

        .ticket-attachment-preview-card.is-image i {
            color: #0ea5e9;
        }

        .ticket-attachment-preview-card.is-pdf i {
            color: #dc2626;
        }

        .ticket-attachment-preview-title {
            max-width: 100%;
            font-size: 0.8rem;
            font-weight: 700;
            color: #0f172a;
            text-align: center;
            line-height: 1.3;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .ticket-attachment-preview-meta {
            font-size: 0.68rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            font-weight: 700;
        }

        .ticket-attachment-viewer {
            position: fixed;
            inset: 0;
            z-index: 100;
            display: none;
            align-items: center;
            justify-content: center;
            background: rgba(2, 6, 23, 0.9);
            padding: 1rem;
        }

        .ticket-attachment-viewer.is-open {
            display: flex;
        }

        .ticket-attachment-viewer-panel {
            width: min(100%, 1100px);
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
        }

        .ticket-attachment-viewer-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.8rem;
        }

        .ticket-attachment-viewer-title {
            color: #fff;
            font-size: 0.95rem;
            font-weight: 700;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .ticket-attachment-viewer-actions {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .ticket-attachment-viewer-body {
            border-radius: 1rem;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.35);
            min-height: 65vh;
            position: relative;
        }

        .ticket-attachment-viewer-image {
            width: 100%;
            max-height: 86vh;
            object-fit: contain;
            display: none;
            background: #fff;
        }

        .ticket-attachment-viewer-frame {
            width: 100%;
            height: 86vh;
            border: 0;
            display: none;
            background: #fff;
        }

        .ticket-attachment-viewer-loading {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 65vh;
            color: #475569;
            font-size: 0.9rem;
            font-weight: 600;
            background: linear-gradient(180deg, #ffffff, #f8fafc);
        }

        .ticket-attachment-viewer.is-image .ticket-attachment-viewer-image {
            display: block;
        }

        .ticket-attachment-viewer.is-pdf .ticket-attachment-viewer-frame {
            display: block;
        }

        .ticket-attachment-viewer-close {
            border-radius: 999px;
            width: 2.5rem;
            height: 2.5rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.12);
            color: #fff;
            transition: background-color 0.18s ease, transform 0.18s ease;
        }

        .ticket-attachment-viewer-close:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-1px);
        }

        .ticket-attachment-download-link {
            color: #bfdbfe;
            font-size: 0.8rem;
            font-weight: 700;
            text-decoration: none;
        }

        .ticket-attachment-download-link:hover {
            color: #fff;
            text-decoration: underline;
        }

        html.dark .ticket-attachment-preview-btn,
        .dark-theme .ticket-attachment-preview-btn {
            border-color: rgba(148, 163, 184, 0.25);
            background: rgba(15, 23, 42, 0.55);
        }

        html.dark .ticket-attachment-preview-card,
        .dark-theme .ticket-attachment-preview-card {
            background: linear-gradient(180deg, rgba(15, 23, 42, 0.9), rgba(30, 41, 59, 0.75));
        }

        html.dark .ticket-attachment-preview-title,
        .dark-theme .ticket-attachment-preview-title {
            color: #e2e8f0;
        }

        html.dark .ticket-attachment-preview-meta,
        .dark-theme .ticket-attachment-preview-meta {
            color: #94a3b8;
        }

        html.dark .ticket-attachment-viewer-body,
        .dark-theme .ticket-attachment-viewer-body,
        html.dark .ticket-attachment-viewer-image,
        .dark-theme .ticket-attachment-viewer-image,
        html.dark .ticket-attachment-viewer-frame,
        .dark-theme .ticket-attachment-viewer-frame {
            background: #0f172a;
        }

        html.dark .ticket-attachment-viewer-loading,
        .dark-theme .ticket-attachment-viewer-loading {
            background: linear-gradient(180deg, #0f172a, #111827);
            color: #cbd5e1;
        }

        @media (max-width: 640px) {
            .ticket-attachment-viewer-panel {
                max-height: 92vh;
            }

            .ticket-attachment-viewer-body,
            .ticket-attachment-viewer-frame,
            .ticket-attachment-viewer-image {
                min-height: 62vh;
            }
        }

        .moderator-ticket-nav {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 0.6rem;
            margin-bottom: 0.9rem;
        }

        .moderator-ticket-nav-links {
            display: inline-flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.5rem;
        }

        .moderator-ticket-header {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            padding: 1rem;
            margin-bottom: 0.9rem;
        }

        .moderator-ticket-meta-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.6rem;
            margin-top: 0.8rem;
        }

        .moderator-ticket-meta-item {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.6rem 0.75rem;
            background: #ffffff;
        }

        .moderator-ticket-meta-label {
            display: block;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 0.2rem;
        }

        .moderator-ticket-meta-value {
            font-size: 0.85rem;
            font-weight: 600;
            color: #0f172a;
            line-height: 1.3;
            word-break: break-word;
        }

        .moderator-ticket-thread {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            background: #ffffff;
            padding: 0.8rem;
            margin-bottom: 1rem;
            max-height: 50vh;
            overflow-y: auto;
        }

        .moderator-ticket-entry {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 0.68rem;
            background: #ffffff;
            margin-bottom: 0.55rem;
        }

        .moderator-ticket-entry:last-child {
            margin-bottom: 0;
        }

        .moderator-ticket-entry.staff {
            border-color: rgba(37, 99, 235, 0.28);
            background: rgba(239, 246, 255, 0.8);
        }

        .moderator-ticket-entry.requester {
            background: #f8fafc;
        }

        .moderator-ticket-entry-head {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.45rem;
            margin-bottom: 0.35rem;
        }

        .moderator-ticket-entry .whitespace-pre-wrap {
            font-size: 0.9rem;
            line-height: 1.45;
        }

        .moderator-ticket-entry .ticket-image-preview img {
            max-height: 84px !important;
            max-width: 140px !important;
        }

        .moderator-status-switch {
            display: flex;
            align-items: flex-start;
            gap: 0.55rem;
            flex-direction: column;
        }

        .moderator-status-options {
            display: flex;
            flex-wrap: wrap;
            gap: 0.4rem;
        }

        .moderator-status-chip {
            border: 1px solid #cbd5e1;
            background: #ffffff;
            color: #334155;
            border-radius: 999px;
            padding: 0.35rem 0.72rem;
            font-size: 0.74rem;
            font-weight: 700;
            letter-spacing: 0.03em;
            line-height: 1;
            transition: all 0.18s ease;
        }

        .moderator-status-chip:hover {
            border-color: #93c5fd;
            background: #eff6ff;
            color: #1d4ed8;
        }

        .moderator-status-chip.active.pending { background: #eef2ff; border-color: #c7d2fe; color: #4338ca; }
        .moderator-status-chip.active.in_progress { background: #fffbeb; border-color: #fde68a; color: #b45309; }
        .moderator-status-chip.active.resolved { background: #ecfdf5; border-color: #a7f3d0; color: #047857; }
        .moderator-status-chip.active.closed { background: #f1f5f9; border-color: #cbd5e1; color: #334155; }

        .moderator-ticket-role-badge {
            border-radius: 999px;
            padding: 0.12rem 0.45rem;
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            border: 1px solid transparent;
        }

        .moderator-ticket-role-badge.staff {
            background: rgba(37, 99, 235, 0.14);
            color: #1d4ed8;
            border-color: rgba(37, 99, 235, 0.22);
        }

        .moderator-ticket-role-badge.requester {
            background: rgba(15, 23, 42, 0.08);
            color: #334155;
            border-color: rgba(15, 23, 42, 0.12);
        }

        .moderator-ticket-reply-box {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            background: #ffffff;
            padding: 1rem;
        }

        :is([data-theme-mode="dark"], .dark) .moderator-ticket-header,
        :is([data-theme-mode="dark"], .dark) .moderator-ticket-thread,
        :is([data-theme-mode="dark"], .dark) .moderator-ticket-reply-box {
            border-color: rgba(148, 163, 184, 0.24);
            background: rgba(15, 23, 42, 0.55);
        }

        :is([data-theme-mode="dark"], .dark) .moderator-ticket-meta-item,
        :is([data-theme-mode="dark"], .dark) .moderator-ticket-entry {
            border-color: rgba(148, 163, 184, 0.24);
            background: rgba(30, 41, 59, 0.6);
        }

        :is([data-theme-mode="dark"], .dark) .moderator-ticket-entry.requester {
            background: rgba(30, 41, 59, 0.48);
        }

        :is([data-theme-mode="dark"], .dark) .moderator-ticket-entry.staff {
            background: rgba(30, 58, 138, 0.22);
            border-color: rgba(96, 165, 250, 0.3);
        }

        :is([data-theme-mode="dark"], .dark) .moderator-ticket-meta-label {
            color: #94a3b8;
        }

        :is([data-theme-mode="dark"], .dark) .moderator-ticket-meta-value {
            color: #e2e8f0;
        }

        :is([data-theme-mode="dark"], .dark) .moderator-ticket-role-badge.requester {
            background: rgba(148, 163, 184, 0.18);
            color: #cbd5e1;
            border-color: rgba(148, 163, 184, 0.25);
        }

        :is([data-theme-mode="dark"], .dark) .moderator-status-chip {
            border-color: rgba(148, 163, 184, 0.34);
            background: rgba(30, 41, 59, 0.55);
            color: #cbd5e1;
        }

        :is([data-theme-mode="dark"], .dark) .moderator-status-chip:hover {
            background: rgba(59, 130, 246, 0.18);
            border-color: rgba(96, 165, 250, 0.42);
            color: #bfdbfe;
        }

        :is([data-theme-mode="dark"], .dark) .moderator-status-chip.active.pending { background: rgba(99, 102, 241, 0.25); border-color: rgba(165, 180, 252, 0.45); color: #c7d2fe; }
        :is([data-theme-mode="dark"], .dark) .moderator-status-chip.active.in_progress { background: rgba(245, 158, 11, 0.2); border-color: rgba(252, 211, 77, 0.45); color: #fcd34d; }
        :is([data-theme-mode="dark"], .dark) .moderator-status-chip.active.resolved { background: rgba(16, 185, 129, 0.2); border-color: rgba(110, 231, 183, 0.45); color: #6ee7b7; }
        :is([data-theme-mode="dark"], .dark) .moderator-status-chip.active.closed { background: rgba(148, 163, 184, 0.2); border-color: rgba(148, 163, 184, 0.45); color: #cbd5e1; }

        @media (max-width: 640px) {
            .moderator-ticket-meta-grid {
                grid-template-columns: 1fr;
            }

            .moderator-ticket-thread {
                max-height: 45vh;
            }
        }
    </style>

    <div class="box">
        <div class="box-body">
            <div class="moderator-ticket-nav">
                <div class="moderator-ticket-nav-links">
                    <a href="{{ route('moderator.tickets.index') }}" class="ti-btn ti-btn-sm ti-btn-light">
                        <i class="bi bi-arrow-left me-1"></i> Back to tickets
                    </a>
                </div>
            </div>

            <div class="moderator-ticket-header">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                <div>
                    <h6 class="font-bold text-2xl text-gray-700 dark:text-white">
                        {{ $ticket->subject }}
                    </h6>
                    <div class="flex flex-wrap items-center gap-2 mt-1 text-sm text-textmuted">
                        <span class="font-medium text-gray-700 dark:text-gray-200">From: {{ $ticket->user->name ?? '—' }}</span>
                        <span>·</span>
                        <span>Created {{ $ticket->created_at->format('M j, Y g:i A') }}</span>
                    </div>
                </div>
                {{-- Change status (staff) --}}
                <form action="{{ route('moderator.tickets.update-status', $ticket) }}" method="POST" class="moderator-status-switch" aria-label="Ticket status quick actions">
                    @csrf
                    @method('PATCH')
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Status</span>
                    <div class="moderator-status-options">
                        <button type="submit" name="status" value="pending" class="moderator-status-chip pending {{ $ticket->status === 'pending' ? 'active' : '' }}">Pending</button>
                        <button type="submit" name="status" value="in_progress" class="moderator-status-chip in_progress {{ $ticket->status === 'in_progress' ? 'active' : '' }}">In Progress</button>
                        <button type="submit" name="status" value="resolved" class="moderator-status-chip resolved {{ $ticket->status === 'resolved' ? 'active' : '' }}">Resolved</button>
                        <button type="submit" name="status" value="closed" class="moderator-status-chip closed {{ $ticket->status === 'closed' ? 'active' : '' }}">Closed</button>
                    </div>
                </form>
            </div>

            @php
                $allAttachmentsCount = $ticket->attachments->count() + $ticket->replies->sum(fn($r) => $r->attachments->count());
            @endphp

            <div class="moderator-ticket-meta-grid">
                <div class="moderator-ticket-meta-item">
                    <span class="moderator-ticket-meta-label">Ticket ID</span>
                    <span class="moderator-ticket-meta-value">#{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="moderator-ticket-meta-item">
                    <span class="moderator-ticket-meta-label">Requester Email</span>
                    <span class="moderator-ticket-meta-value">{{ $ticket->user->email ?? 'N/A' }}</span>
                </div>
                <div class="moderator-ticket-meta-item">
                    <span class="moderator-ticket-meta-label">Total Replies</span>
                    <span class="moderator-ticket-meta-value">{{ $ticket->replies->count() }}</span>
                </div>
                <div class="moderator-ticket-meta-item">
                    <span class="moderator-ticket-meta-label">Total Attachments</span>
                    <span class="moderator-ticket-meta-value">{{ $allAttachmentsCount }}</span>
                </div>
                <div class="moderator-ticket-meta-item">
                    <span class="moderator-ticket-meta-label">Last Updated</span>
                    <span class="moderator-ticket-meta-value">{{ $ticket->updated_at->format('M j, Y g:i A') }}</span>
                </div>
                <div class="moderator-ticket-meta-item">
                    <span class="moderator-ticket-meta-label">Current Status</span>
                    <span class="moderator-ticket-meta-value">{{ str_replace('_', ' ', ucfirst($ticket->status)) }}</span>
                </div>
            </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 rounded-lg bg-danger/10 border border-danger/30 px-4 py-3 text-sm text-danger">
                    <p class="font-medium mb-1">Please fix the following errors:</p>
                    <ul class="list-disc ps-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="moderator-ticket-thread">
                <h6 class="font-bold text-base text-gray-700 dark:text-gray-100 mb-3">Conversation</h6>

            {{-- Initial message --}}
            <div class="moderator-ticket-entry requester">
                <div class="moderator-ticket-entry-head text-sm text-textmuted">
                    <span class="font-medium text-gray-700 dark:text-gray-200">{{ $ticket->user->name ?? 'User' }}</span>
                    <span class="moderator-ticket-role-badge requester">Requester</span>
                    <span>{{ $ticket->created_at->format('M j, Y g:i A') }}</span>
                </div>
                <div class="text-gray-800 dark:text-gray-200 whitespace-pre-wrap">{{ $ticket->message }}</div>
                @if ($ticket->attachments->isNotEmpty())
                    <div class="mt-3 pt-3 border-t border-defaultborder dark:border-defaultborder/10">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Attachments</p>
                        <div class="flex flex-wrap gap-3">
                            @foreach ($ticket->attachments as $att)
                                @php
                                    $attExt = strtolower(pathinfo($att->name ?? '', PATHINFO_EXTENSION));
                                    $attPreviewType = $att->isImage() ? 'image' : (($att->mime_type === 'application/pdf' || $attExt === 'pdf') ? 'pdf' : 'download');
                                @endphp
                                        @if ($attPreviewType !== 'download')
                                        <div class="flex flex-col items-start">
                                            <button type="button" class="ticket-attachment-preview-btn" onclick="openAttachmentPreview({{ $att->id }}, '{{ $att->name }}', '{{ $attPreviewType }}')" style="border: none; background: none; cursor: pointer; padding: 0;">
                                                <div class="ticket-attachment-preview-card {{ $attPreviewType === 'image' ? 'is-image' : 'is-pdf' }}">
                                                    <i class="{{ $attPreviewType === 'image' ? 'ri-image-2-line' : 'ri-file-pdf-line' }}"></i>
                                                    <div class="ticket-attachment-preview-title">{{ $att->name }}</div>
                                                    <div class="ticket-attachment-preview-meta">{{ $attPreviewType === 'image' ? 'Image Preview' : 'PDF Preview' }}</div>
                                                </div>
                                            </button>
                                            <a href="{{ route('attachment.download', $att->id) }}" download="{{ $att->name }}" class="text-xs text-primary hover:underline mt-1 truncate max-w-[200px] block">Download</a>
                                        </div>
                                    @else
                                    <a href="{{ route('attachment.download', $att->id) }}" download="{{ $att->name }}" class="inline-flex items-center gap-1 rounded border border-defaultborder dark:border-defaultborder/10 px-2 py-1.5 text-sm text-primary hover:bg-gray-100 dark:hover:bg-white/10">
                                        <i class="bi bi-file-earmark-arrow-down"></i> {{ $att->name }}
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Replies --}}
            @foreach ($ticket->replies as $reply)
                <div class="moderator-ticket-entry {{ $reply->is_staff ? 'staff' : 'requester' }}">
                    <div class="moderator-ticket-entry-head text-sm text-textmuted">
                        <span class="font-medium text-gray-700 dark:text-gray-200">{{ $reply->user->name ?? 'User' }}</span>
                        @if ($reply->is_staff)
                            <span class="moderator-ticket-role-badge staff">Support</span>
                        @else
                            <span class="moderator-ticket-role-badge requester">Requester</span>
                        @endif
                        <span>{{ $reply->created_at->format('M j, Y g:i A') }}</span>
                    </div>
                    <div class="text-gray-800 dark:text-gray-200 whitespace-pre-wrap">{{ $reply->message }}</div>
                    @if ($reply->attachments->isNotEmpty())
                        <div class="mt-3 pt-3 border-t border-defaultborder dark:border-defaultborder/10">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Attachments</p>
                            <div class="flex flex-wrap gap-3">
                                @foreach ($reply->attachments as $att)
                                            @php
                                                $attExt = strtolower(pathinfo($att->name ?? '', PATHINFO_EXTENSION));
                                                $attPreviewType = $att->isImage() ? 'image' : (($att->mime_type === 'application/pdf' || $attExt === 'pdf') ? 'pdf' : 'download');
                                            @endphp
                                            @if ($attPreviewType !== 'download')
                                        <div class="flex flex-col items-start">
                                                    <button type="button" class="ticket-attachment-preview-btn" onclick="openAttachmentPreview({{ $att->id }}, '{{ $att->name }}', '{{ $attPreviewType }}')" style="border: none; background: none; cursor: pointer; padding: 0;">
                                                        <div class="ticket-attachment-preview-card {{ $attPreviewType === 'image' ? 'is-image' : 'is-pdf' }}">
                                                            <i class="{{ $attPreviewType === 'image' ? 'ri-image-2-line' : 'ri-file-pdf-line' }}"></i>
                                                            <div class="ticket-attachment-preview-title">{{ $att->name }}</div>
                                                            <div class="ticket-attachment-preview-meta">{{ $attPreviewType === 'image' ? 'Image Preview' : 'PDF Preview' }}</div>
                                                        </div>
                                            </button>
                                            <a href="{{ route('attachment.download', $att->id) }}" download="{{ $att->name }}" class="text-xs text-primary hover:underline mt-1 truncate max-w-[200px] block">Download</a>
                                        </div>
                                    @else
                                        <a href="{{ route('attachment.download', $att->id) }}" download="{{ $att->name }}" class="inline-flex items-center gap-1 rounded border border-defaultborder dark:border-defaultborder/10 px-2 py-1.5 text-sm text-primary hover:bg-gray-100 dark:hover:bg-white/10">
                                            <i class="bi bi-file-earmark-arrow-down"></i> {{ $att->name }}
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
            </div>

            {{-- Staff reply form --}}
            <form action="{{ route('moderator.tickets.reply', $ticket) }}" method="POST" enctype="multipart/form-data" class="moderator-ticket-form moderator-ticket-reply-box">
                @csrf
                <h6 class="font-bold text-base text-gray-700 dark:text-gray-100 mb-3">Reply as staff</h6>
                <label for="staff-reply-message" class="form-label">Message</label>
                <textarea name="message" id="staff-reply-message" rows="4" required maxlength="10000"
                    class="form-control @error('message') border-danger @enderror" placeholder="Type your reply as support staff...">{{ old('message') }}</textarea>
                @error('message')
                    <p class="text-danger text-sm mt-1">{{ $message }}</p>
                @enderror

                <div class="mt-3">
                    <label for="staff-reply-attachments" class="form-label">Attachments (optional)</label>
                    <input
                        type="file"
                        name="attachments[]"
                        id="staff-reply-attachments"
                        multiple
                        accept=".jpg,.jpeg,.png,.gif,.webp,.pdf"
                        class="form-control"
                    >
                    <p class="text-xs text-textmuted mt-1">Images or PDF, up to 5 files, max 5 MB each.</p>
                    @error('attachments')
                        <p class="text-danger text-sm mt-1">{{ $message }}</p>
                    @enderror
                    @error('attachments.*')
                        <p class="text-danger text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="mt-2 inline-flex items-center gap-2 rounded-md bg-primary px-4 py-2 text-sm font-medium text-white hover:opacity-90">
                    <i class="bi bi-reply"></i>
                    Send reply (as staff)
                </button>
            </form>
        </div>
    </div>

    {{-- Attachment preview modal --}}
    <div id="ticket-attachment-preview-modal" class="ticket-attachment-viewer" role="dialog" aria-modal="true">
        <div class="ticket-attachment-viewer-panel">
            <div class="ticket-attachment-viewer-head">
                <div class="ticket-attachment-viewer-title" id="ticket-attachment-preview-caption"></div>
                <div class="ticket-attachment-viewer-actions">
                    <a id="ticket-attachment-preview-download" href="#" class="ticket-attachment-download-link" download>
                        <i class="ri-download-2-line me-1"></i>Download
                    </a>
                    <button type="button" id="ticket-attachment-preview-close" class="ticket-attachment-viewer-close" aria-label="Close">
                        <i class="ri-close-line"></i>
                    </button>
                </div>
            </div>
            <div class="ticket-attachment-viewer-body">
                <div id="ticket-attachment-viewer-loading" class="ticket-attachment-viewer-loading">Loading preview...</div>
                <img id="ticket-attachment-preview-img" src="" alt="" class="ticket-attachment-viewer-image">
                <iframe id="ticket-attachment-preview-frame" class="ticket-attachment-viewer-frame" title="Attachment preview"></iframe>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var modal = document.getElementById('ticket-attachment-preview-modal');
            var img = document.getElementById('ticket-attachment-preview-img');
            var frame = document.getElementById('ticket-attachment-preview-frame');
            var caption = document.getElementById('ticket-attachment-preview-caption');
            var downloadLink = document.getElementById('ticket-attachment-preview-download');
            var closeBtn = document.getElementById('ticket-attachment-preview-close');
            var loading = document.getElementById('ticket-attachment-viewer-loading');
            var objectUrl = null;

            function revokeObjectUrl() {
                if (objectUrl) {
                    URL.revokeObjectURL(objectUrl);
                    objectUrl = null;
                }
            }

            window.openAttachmentPreview = async function(id, name, type) {
                const previewUrl = "{{ route('attachment.preview', ['id' => 'PLACEHOLDER']) }}".replace('PLACEHOLDER', id);
                const downloadUrl = "{{ route('attachment.download', ['id' => 'PLACEHOLDER']) }}".replace('PLACEHOLDER', id);
                
                caption.textContent = name || 'Attachment preview';
                downloadLink.href = downloadUrl;
                loading.style.display = 'flex';
                loading.textContent = 'Loading preview...';
                revokeObjectUrl();
                img.removeAttribute('src');
                frame.removeAttribute('src');

                try {
                    const response = await fetch(previewUrl, { credentials: 'same-origin' });
                    if (!response.ok) {
                        throw new Error('Preview failed');
                    }

                    const blob = await response.blob();
                    objectUrl = URL.createObjectURL(blob);

                    if (type === 'pdf') {
                        modal.classList.add('is-pdf');
                        modal.classList.remove('is-image');
                        img.removeAttribute('src');
                        frame.src = objectUrl;
                    } else {
                        modal.classList.add('is-image');
                        modal.classList.remove('is-pdf');
                        frame.removeAttribute('src');
                        img.src = objectUrl;
                        img.alt = name || 'Attachment preview';
                    }
                } catch (error) {
                    modal.classList.add('is-open');
                    document.body.style.overflow = 'hidden';
                    loading.textContent = 'Preview unavailable. Use Download to open the file.';
                    loading.style.display = 'flex';
                    return;
                }

                loading.style.display = 'none';
                modal.classList.add('is-open');
                document.body.style.overflow = 'hidden';
            };

            function closePreview() {
                modal.classList.remove('is-open', 'is-image', 'is-pdf');
                img.removeAttribute('src');
                frame.removeAttribute('src');
                loading.style.display = 'flex';
                revokeObjectUrl();
                document.body.style.overflow = '';
            }

            closeBtn.addEventListener('click', closePreview);
            modal.addEventListener('click', function (e) {
                if (e.target === modal) closePreview();
            });
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closePreview();
            });
        });
    </script>
</x-app-layout>
