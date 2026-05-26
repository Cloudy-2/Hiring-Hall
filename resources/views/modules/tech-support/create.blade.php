<x-app-layout>
    <x-slot name="title">New Ticket</x-slot>
    <x-slot name="url_1">{"link": "{{ route('tickets.list') }}", "text": "Help & Support"}</x-slot>
    <x-slot name="active">Support</x-slot>

    <style>
        .ticket-create-panel {
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 1rem;
            background: #ffffff;
        }
        html.dark .ticket-create-panel, .dark-theme .ticket-create-panel {
            background: rgba(15, 23, 42, 0.45) !important;
            border-color: rgba(148, 163, 184, 0.22) !important;
        }
        html.dark .ticket-create-panel .form-control, .dark-theme .ticket-create-panel .form-control {
            background-color: rgba(2, 6, 23, 0.5) !important;
            border-color: rgba(148, 163, 184, 0.28) !important;
            color: #e2e8f0 !important;
        }
        html.dark .ticket-create-panel .form-label, .dark-theme .ticket-create-panel .form-label {
            color: #e5e7eb !important;
        }
    </style>

    <div class="box">
        <div class="box-body ticket-create-panel">
            <div class="mb-4">
                <h6 class="font-bold text-2xl text-gray-700 dark:text-white">
                    <strong>New Support Ticket</strong>
                </h6>
                <span class="text-sm text-gray-600 dark:text-gray-300">
                    Describe your issue and we will get back to you as soon as possible.
                </span>
            </div>

            <form action="{{ route('tickets.store') }}" method="POST" class="max-w-2xl" enctype="multipart/form-data">
                @csrf
                <div class="form-group mb-4">
                    <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                    <input type="text" name="subject" id="subject" value="{{ old('subject') }}"
                        class="form-control @error('subject') border-danger @enderror" placeholder="Brief summary of your issue" required maxlength="255">
                    @error('subject')
                        <p class="text-danger text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group mb-4">
                    <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                    <textarea name="message" id="message" rows="6" required maxlength="10000"
                        class="form-control @error('message') border-danger @enderror" placeholder="Describe your issue in detail...">{{ old('message') }}</textarea>
                    @error('message')
                        <p class="text-danger text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group mb-4">
                    <label for="attachments" class="form-label">Attachments (optional)</label>
                    <input type="file" name="attachments[]" id="attachments" multiple accept=".jpg,.jpeg,.png,.gif,.webp,.pdf"
                        class="form-control @error('attachments.*') border-danger @enderror">
                    <p class="text-sm text-textmuted mt-1">Images (JPEG, PNG, GIF, WebP) or PDF. Max 5 files, 5 MB each.</p>
                    @error('attachments.*')
                        <p class="text-danger text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex flex-wrap gap-2">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-md bg-primary px-4 py-2 text-sm font-medium text-white hover:opacity-90">
                        <i class="bi bi-send"></i>
                        Submit Ticket
                    </button>
                    <a href="{{ route('tickets.list') }}" class="inline-flex items-center gap-2 rounded-md border border-slate-300 bg-white dark:bg-white/10 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-white/10">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
