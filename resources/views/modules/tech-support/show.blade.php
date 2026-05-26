<x-app-layout
    page-title="Ticket Details"
    :breadcrumbs="[
        ['label' => 'Help & Support', 'url' => route('tickets.list')],
    ]"
    active="Ticket Details"
>
    {{-- Custom styles for a premium look --}}
    <style>
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

        .ticket-nav {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
        }

        .ticket-summary-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.55rem;
            margin-top: 0.9rem;
        }

        .ticket-summary-item {
            border: 1px solid rgba(148, 163, 184, 0.22);
            border-radius: 10px;
            background: #fff;
            padding: 0.5rem 0.65rem;
        }

        .ticket-summary-label {
            display: block;
            font-size: 0.67rem;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            font-weight: 700;
            color: #64748b;
        }

        .ticket-summary-value {
            font-size: 0.82rem;
            font-weight: 600;
            color: #0f172a;
            margin-top: 0.15rem;
            line-height: 1.3;
            word-break: break-word;
        }

        .ticket-thread-scroll {
            max-height: 48vh;
            overflow-y: auto;
            padding-right: 0.2rem;
        }

        .ticket-header {
            background: linear-gradient(to right, rgba(var(--primary-rgb), 0.05), transparent);
            border-bottom: 1px solid rgba(var(--primary-rgb), 0.1);
            margin: -1.25rem -1.25rem 1.5rem -1.25rem;
            padding: 1.5rem;
            overflow: hidden;
        }
        .message-bubble {
            position: relative;
            padding: 1rem;
            border-radius: 1.25rem;
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            overflow: hidden;
        }
        .message-bubble:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        .message-bubble-user {
            background-color: #f8fafc;
        }
        .message-bubble-staff {
            background-color: rgba(var(--primary-rgb), 0.04);
            border-color: rgba(var(--primary-rgb), 0.1);
        }
        
        /* Dark mode overrides */
        html.dark .message-bubble-user, .dark-theme .message-bubble-user {
            background-color: rgba(255, 255, 255, 0.03);
            border-color: rgba(255, 255, 255, 0.08);
            color: #e2e8f0;
        }
        html.dark .message-bubble-staff, .dark-theme .message-bubble-staff {
            background-color: rgba(var(--primary-rgb), 0.1);
            border-color: rgba(var(--primary-rgb), 0.2);
            color: #e2e8f0;
        }
        html.dark .ticket-header, .dark-theme .ticket-header {
            background: linear-gradient(to right, rgba(var(--primary-rgb), 0.15), transparent);
            border-bottom-color: rgba(255, 255, 255, 0.08);
        }
        html.dark .ticket-summary-item, .dark-theme .ticket-summary-item {
            background: rgba(30, 41, 59, 0.62);
            border-color: rgba(148, 163, 184, 0.24);
        }
        html.dark .ticket-summary-label, .dark-theme .ticket-summary-label {
            color: #94a3b8;
        }
        html.dark .ticket-summary-value, .dark-theme .ticket-summary-value {
            color: #e2e8f0;
        }
        html.dark .ticket-form-panel, .dark-theme .ticket-form-panel {
            background-color: rgba(255, 255, 255, 0.03) !important;
            border-color: rgba(255, 255, 255, 0.12) !important;
        }
        html.dark .ticket-input, .dark-theme .ticket-input {
            background-color: rgba(2, 6, 23, 0.5) !important;
            border-color: rgba(255, 255, 255, 0.14) !important;
            color: #e5e7eb !important;
        }
        html.dark .ticket-status-pending, .dark-theme .ticket-status-pending {
            background-color: rgba(99, 102, 241, 0.2) !important;
            color: #c7d2fe !important;
            border-color: rgba(99, 102, 241, 0.35) !important;
        }
        
        /* Alert fixing */
        .premium-alert-success {
            background-color: #ecfdf5;
            border: 1px solid #10b98126;
            color: #065f46;
        }
        html.dark .premium-alert-success, .dark-theme .premium-alert-success {
            background-color: rgba(16, 185, 129, 0.1);
            border-color: rgba(16, 185, 129, 0.2);
            color: #34d399;
        }

        @media (max-width: 900px) {
            .ticket-summary-grid {
                grid-template-columns: 1fr 1fr;
            }
            .ticket-thread-scroll {
                max-height: 44vh;
            }
        }

        @media (max-width: 640px) {
            .ticket-summary-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="box overflow-hidden">
        <div class="box-body !p-5 overflow-hidden">
            <div class="ticket-nav">
                <a href="{{ route('tickets.list') }}" class="ti-btn ti-btn-sm ti-btn-light">
                    <i class="ri-arrow-left-line me-1"></i> Back to tickets
                </a>
            </div>

            <div class="ticket-header overflow-hidden">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 overflow-hidden">
                    <div class="overflow-hidden w-full">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wider
                                @if ($ticket->status === 'pending') bg-indigo-500/10 text-indigo-600 border border-indigo-500/20 ticket-status-pending
                                @elseif ($ticket->status === 'open') bg-blue-500/10 text-blue-600 border border-blue-500/20
                                @elseif ($ticket->status === 'in_progress') bg-amber-500/10 text-amber-600 border border-amber-500/20
                                @elseif ($ticket->status === 'resolved') bg-emerald-500/10 text-emerald-600 border border-emerald-500/20
                                @else bg-gray-500/10 text-gray-600 border border-gray-500/20
                                @endif">
                                {{ str_replace('_', ' ', ucfirst($ticket->status)) }}
                            </span>
                            <span class="text-xs font-medium text-textmuted">ID: #{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <h1 class="font-bold text-xl md:text-2xl text-gray-900 dark:text-white leading-tight break-all" style="white-space: normal !important; word-break: break-all !important;">
                            {{ $ticket->subject }}
                        </h1>
                    </div>
                </div>

                @php
                    $attachmentCount = $ticket->attachments->count() + $ticket->replies->sum(fn($r) => $r->attachments->count());
                @endphp

                <div class="ticket-summary-grid">
                    <div class="ticket-summary-item">
                        <span class="ticket-summary-label">Created</span>
                        <span class="ticket-summary-value">{{ $ticket->created_at->format('M j, Y g:i A') }}</span>
                    </div>
                    <div class="ticket-summary-item">
                        <span class="ticket-summary-label">Last Updated</span>
                        <span class="ticket-summary-value">{{ $ticket->updated_at->format('M j, Y g:i A') }}</span>
                    </div>
                    <div class="ticket-summary-item">
                        <span class="ticket-summary-label">Replies</span>
                        <span class="ticket-summary-value">{{ $ticket->replies->count() }}</span>
                    </div>
                    <div class="ticket-summary-item">
                        <span class="ticket-summary-label">Attachments</span>
                        <span class="ticket-summary-value">{{ $attachmentCount }}</span>
                    </div>
                    <div class="ticket-summary-item">
                        <span class="ticket-summary-label">Requester</span>
                        <span class="ticket-summary-value">{{ $ticket->user->name ?? 'You' }}</span>
                    </div>
                    <div class="ticket-summary-item">
                        <span class="ticket-summary-label">Status</span>
                        <span class="ticket-summary-value">{{ str_replace('_', ' ', ucfirst($ticket->status)) }}</span>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success mb-6 flex items-center gap-3 shadow-sm">
                    <i class="ri-checkbox-circle-fill text-xl"></i>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            {{-- Initial message bubbles --}}
            <div class="space-y-4 ticket-thread-scroll">
                <div class="message-bubble message-bubble-user">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold">
                            {{ substr($ticket->user->name ?? 'U', 0, 1) }}
                        </div>
                        <div>
                            <div class="font-bold text-gray-900 dark:text-white text-sm">{{ $ticket->user->name ?? 'You' }}</div>
                            <div class="text-[11px] text-textmuted uppercase tracking-widest font-semibold opacity-70">
                                <i class="ri-time-line"></i> {{ $ticket->created_at->format('M j, Y g:i A') }}
                            </div>
                        </div>
                    </div>
                    <div class="text-gray-800 dark:text-gray-200 whitespace-pre-wrap leading-relaxed break-all" style="overflow-wrap: anywhere; word-break: break-all !important; white-space: pre-wrap !important;">{{ $ticket->message }}</div>
                    
                    @if ($ticket->attachments->isNotEmpty())
                        <div class="mt-5 pt-4 border-t border-dashed border-gray-200 dark:border-white/10">
                            <p class="text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-3">Attachments</p>
                            <div class="flex flex-wrap gap-3">
                                @foreach ($ticket->attachments as $att)
                                    @php
                                        $attExt = strtolower(pathinfo($att->name ?? '', PATHINFO_EXTENSION));
                                        $attPreviewType = $att->isImage() ? 'image' : (($att->mime_type === 'application/pdf' || $attExt === 'pdf') ? 'pdf' : 'download');
                                    @endphp
                                    @if ($attPreviewType !== 'download')
                                        <div class="group relative">
                                            <button onclick="openAttachmentPreview({{ $att->id }}, '{{ $att->name }}', '{{ $attPreviewType }}')" class="ticket-attachment-preview-btn" title="Open preview in modal" style="border: none; background: none; cursor: pointer; padding: 0;">
                                                <div class="ticket-attachment-preview-card {{ $attPreviewType === 'image' ? 'is-image' : 'is-pdf' }}">
                                                    @if ($attPreviewType === 'image')
                                                        <img src="{{ route('attachment.preview', $att->id) }}" alt="{{ $att->name }}" class="w-full h-20 object-cover rounded-md" loading="lazy">
                                                    @else
                                                        <i class="ri-file-pdf-line"></i>
                                                    @endif
                                                    <div class="ticket-attachment-preview-title">{{ $att->name }}</div>
                                                    <div class="ticket-attachment-preview-meta">{{ $attPreviewType === 'image' ? 'Image Preview' : 'PDF Preview' }}</div>
                                                </div>
                                            </button>
                                            <a href="{{ route('attachment.download', $att->id) }}" class="absolute bottom-1 right-1 bg-black/60 text-white p-1 rounded-md opacity-0 group-hover:opacity-100 transition-opacity" aria-label="Download attachment">
                                                <i class="ri-download-2-line"></i>
                                            </a>
                                        </div>
                                    @else
                                        <a href="{{ route('attachment.download', $att->id) }}" 
                                            class="inline-flex items-center gap-2 rounded-lg border border-gray-200 dark:border-white/10 px-3 py-2 text-xs font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5 shadow-sm">
                                            <i class="ri-file-text-line text-lg text-primary"></i> 
                                            <div class="text-left leading-tight">
                                                <div class="truncate max-w-[120px]">{{ $att->name }}</div>
                                                <div class="text-[10px] opacity-60">{{ format_size($att->size) }}</div>
                                            </div>
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Replies --}}
                @foreach ($ticket->replies as $reply)
                    <div class="message-bubble {{ $reply->is_staff ? 'message-bubble-staff' : 'message-bubble-user' }}">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-full {{ $reply->is_staff ? 'bg-primary text-white' : 'bg-primary/10 text-primary' }} flex items-center justify-center font-bold shadow-sm">
                                @if($reply->is_staff)
                                    <i class="ri-shield-user-fill"></i>
                                @else
                                    {{ substr($reply->user->name ?? 'U', 0, 1) }}
                                @endif
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-gray-900 dark:text-white text-sm">{{ $reply->user->name ?? 'User' }}</span>
                                    @if ($reply->is_staff)
                                        <span class="bg-primary text-white text-[9px] font-black px-1.5 py-0.5 rounded uppercase tracking-tighter">Staff</span>
                                    @endif
                                </div>
                                <div class="text-[11px] text-textmuted uppercase tracking-widest font-semibold opacity-70">
                                    <i class="ri-time-line"></i> {{ $reply->created_at->format('M j, Y g:i A') }}
                                </div>
                            </div>
                        </div>
                        <div class="text-gray-800 dark:text-gray-200 whitespace-pre-wrap leading-relaxed break-all" style="overflow-wrap: anywhere; word-break: break-all !important; white-space: pre-wrap !important;">{{ $reply->message }}</div>
                        
                        @if ($reply->attachments->isNotEmpty())
                            <div class="mt-5 pt-4 border-t border-dashed border-gray-200 dark:border-white/10">
                                <p class="text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-3">Attachments</p>
                                <div class="flex flex-wrap gap-3">
                                    @foreach ($reply->attachments as $att)
                                        @php
                                            $attExt = strtolower(pathinfo($att->name ?? '', PATHINFO_EXTENSION));
                                            $attPreviewType = $att->isImage() ? 'image' : (($att->mime_type === 'application/pdf' || $attExt === 'pdf') ? 'pdf' : 'download');
                                        @endphp
                                        @if ($attPreviewType !== 'download')
                                            <div class="group relative">
                                                <button onclick="openAttachmentPreview({{ $att->id }}, '{{ $att->name }}', '{{ $attPreviewType }}')" class="ticket-attachment-preview-btn" title="Open preview in modal" style="border: none; background: none; cursor: pointer; padding: 0;">
                                                    <div class="ticket-attachment-preview-card {{ $attPreviewType === 'image' ? 'is-image' : 'is-pdf' }}">
                                                        @if ($attPreviewType === 'image')
                                                            <img src="{{ route('attachment.preview', $att->id) }}" alt="{{ $att->name }}" class="w-full h-20 object-cover rounded-md" loading="lazy">
                                                        @else
                                                            <i class="ri-file-pdf-line"></i>
                                                        @endif
                                                        <div class="ticket-attachment-preview-title">{{ $att->name }}</div>
                                                        <div class="ticket-attachment-preview-meta">{{ $attPreviewType === 'image' ? 'Image Preview' : 'PDF Preview' }}</div>
                                                    </div>
                                                </button>
                                                <a href="{{ route('attachment.download', $att->id) }}" class="absolute bottom-1 right-1 bg-black/60 text-white p-1 rounded-md opacity-0 group-hover:opacity-100 transition-opacity" aria-label="Download attachment">
                                                    <i class="ri-download-2-line"></i>
                                                </a>
                                            </div>
                                        @else
                                            <a href="{{ route('attachment.download', $att->id) }}" 
                                                class="inline-flex items-center gap-2 rounded-lg border border-gray-200 dark:border-white/10 px-3 py-2 text-xs font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5 shadow-sm">
                                                <i class="ri-file-text-line text-lg text-primary"></i> 
                                                <div class="text-left leading-tight">
                                                    <div class="truncate max-w-[120px]">{{ $att->name }}</div>
                                                    <div class="text-[10px] opacity-60">{{ format_size($att->size) }}</div>
                                                </div>
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Reply form --}}
            @if (in_array($ticket->status, ['pending', 'open', 'in_progress']))
                <div class="ticket-form-panel mt-12 p-6 rounded-2xl bg-gray-50 dark:bg-white/5 border border-dashed border-gray-200 dark:border-white/10">
                    <form action="{{ route('tickets.reply', $ticket) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label for="reply-message" class="block text-sm font-bold text-gray-700 dark:text-white mb-2">Add a reply</label>
                            <textarea name="message" id="reply-message" rows="5" required maxlength="10000"
                                class="ticket-input w-full rounded-xl border-gray-200 dark:border-white/10 dark:bg-black/20 dark:text-white focus:ring-primary focus:border-primary px-4 py-3" 
                                placeholder="Type your reply here...">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="text-danger text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label for="reply-attachments" class="block text-sm font-bold text-gray-700 dark:text-white mb-2">Attachments (optional)</label>
                                <input type="file" name="attachments[]" id="reply-attachments" multiple accept=".jpg,.jpeg,.png,.gif,.webp,.pdf"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                                <p class="text-[10px] text-textmuted mt-2 font-medium opacity-60 uppercase tracking-tighter">Images or PDF. Max 5 files, 5 MB each.</p>
                                @error('attachments.*')
                                    <p class="text-danger text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-primary px-6 py-3 text-sm font-bold text-white shadow-lg shadow-primary/20 hover:scale-[1.02] transition-transform">
                            <i class="ri-send-plane-fill"></i>
                            Send Reply
                        </button>
                    </form>
                </div>
            @else
                <div class="mt-8 p-6 rounded-2xl bg-amber-500/5 border border-amber-500/20 text-center">
                    <i class="ri-lock-line text-2xl text-amber-500 mb-2 block"></i>
                    <p class="text-sm font-bold text-amber-600 dark:text-amber-400">This ticket is {{ $ticket->status }} and locked.</p>
                    <p class="text-xs text-amber-600/70 dark:text-amber-400/60 mt-1">Please open a new ticket if you need further assistance.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Attachment Preview Modal --}}
    <div id="ticket-attachment-viewer" class="ticket-attachment-viewer">
        <div class="ticket-attachment-viewer-panel">
            <div class="ticket-attachment-viewer-head">
                <h3 class="ticket-attachment-viewer-title" id="attachmentTitle">Attachment Preview</h3>
                <div class="ticket-attachment-viewer-actions">
                    <a id="attachmentDownloadBtn" href="#" class="ticket-attachment-download-link" download>Download</a>
                    <button id="closeAttachmentBtn" class="ticket-attachment-viewer-close" type="button" aria-label="Close preview">
                        <i class="ri-close-line text-lg"></i>
                    </button>
                </div>
            </div>
            <div class="ticket-attachment-viewer-body">
                <img id="attachmentImage" class="ticket-attachment-viewer-image" alt="Preview" />
                <iframe id="attachmentFrame" class="ticket-attachment-viewer-frame"></iframe>
                <div class="ticket-attachment-viewer-loading" id="attachmentLoading">
                    <span>Loading preview...</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openAttachmentPreview(id, name, type) {
            const viewer = document.getElementById('ticket-attachment-viewer');
            const image = document.getElementById('attachmentImage');
            const frame = document.getElementById('attachmentFrame');
            const loading = document.getElementById('attachmentLoading');
            const title = document.getElementById('attachmentTitle');
            const downloadBtn = document.getElementById('attachmentDownloadBtn');
            const previewUrl = "{{ route('attachment.preview', ['id' => 'PLACEHOLDER']) }}".replace('PLACEHOLDER', id);
            const downloadUrl = "{{ route('attachment.download', ['id' => 'PLACEHOLDER']) }}".replace('PLACEHOLDER', id);

            // Set title and download link
            title.textContent = name;
            downloadBtn.href = downloadUrl;

            // Reset viewer
            viewer.classList.remove('is-image', 'is-pdf');
            image.src = '';
            frame.src = '';

            // Show loading state
            loading.style.display = 'flex';
            viewer.classList.add('is-open');

            // Load appropriately based on type
            if (type === 'image') {
                viewer.classList.add('is-image');
                image.onload = () => loading.style.display = 'none';
                image.onerror = () => {
                    loading.textContent = 'Failed to load image';
                    loading.style.display = 'flex';
                };
                image.src = previewUrl;
            } else if (type === 'pdf') {
                viewer.classList.add('is-pdf');
                frame.src = previewUrl;
                frame.onload = () => loading.style.display = 'none';
                frame.onerror = () => {
                    loading.textContent = 'Failed to load PDF';
                    loading.style.display = 'flex';
                };
            }
        }

        // Close button handler
        document.getElementById('closeAttachmentBtn')?.addEventListener('click', function() {
            document.getElementById('ticket-attachment-viewer').classList.remove('is-open');
        });

        // Close on background click
        document.getElementById('ticket-attachment-viewer')?.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('is-open');
            }
        });

        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.getElementById('ticket-attachment-viewer').classList.remove('is-open');
            }
        });
    </script>

</x-app-layout>

@php
    function format_size($bytes) {
        if ($bytes >= 1048576) return number_format($bytes / 1048576, 1) . ' MB';
        if ($bytes >= 1024) return number_format($bytes / 1024, 1) . ' KB';
        return $bytes . ' B';
    }
@endphp

