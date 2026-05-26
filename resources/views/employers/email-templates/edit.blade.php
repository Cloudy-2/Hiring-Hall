<x-app-layout>

    <x-slot name="pageTitle">Edit Email Template</x-slot>
    <x-slot name="url_1">{"link": "/employer/dashboard", "text": "Dashboard"}</x-slot>
    <x-slot name="url_2">{"link": "/employer/email-templates", "text": "Email Templates"}</x-slot>
    <x-slot name="active">Edit Template</x-slot>

    @include('employers.partials.employer-styles')

    <div class="grid grid-cols-12 gap-x-5 gap-y-4">

        {{-- ═══ Page Hero ═══ --}}
        <div class="col-span-12">
            <div class="cd-page-hero">
                <div>
                    <h1 class="cd-page-hero-title"><i class="ri-mail-settings-line me-2"></i>Edit Email Template</h1>
                    <p class="cd-page-hero-sub">Update your email template</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('employer.email-templates.index') }}" class="cd-hero-btn cd-hero-btn-ghost"><i class="ri-arrow-left-line"></i> Back to Templates</a>
                </div>
            </div>
        </div>

        {{-- ═══ Form ═══ --}}
        <div class="col-span-12 lg:col-span-8">
            <div class="cd-section">
                <form method="POST" action="{{ route('employer.email-templates.update', $emailTemplate) }}">
                    @csrf
                    @method('PUT')

                    @if($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="list-disc ms-4">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Template Info --}}
                    <div class="cd-section-head">
                        <span class="cd-section-label"><i class="ri-information-line"></i> Template Information</span>
                    </div>

                    <div class="grid grid-cols-12 gap-4 mb-6">
                        <div class="col-span-12 md:col-span-6">
                            <label class="form-label">Template Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $emailTemplate->name) }}" placeholder="e.g., Interview Invitation" required>
                        </div>
                        <div class="col-span-12 md:col-span-6">
                            <label class="form-label">Template Type <span class="text-danger">*</span></label>
                            <select name="type" class="form-select" required>
                                @foreach($types as $value => $label)
                                    <option value="{{ $value }}" @selected(old('type', $emailTemplate->type) == $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-12 gap-4 mb-6">
                        <div class="col-span-12 md:col-span-6">
                            <label class="form-label">Company (Optional)</label>
                            <select name="company_id" class="form-select">
                                <option value="">Personal Template</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" @selected(old('company_id', $emailTemplate->company_id) == $company->id)>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-12 md:col-span-6">
                            <label class="form-label flex items-center gap-2 mt-6">
                                <input type="checkbox" name="is_default" value="1" class="form-checkbox" {{ old('is_default', $emailTemplate->is_default) ? 'checked' : '' }}>
                                <span>Set as default for this type</span>
                            </label>
                        </div>
                    </div>

                    {{-- Email Content --}}
                    <div class="cd-section-head mt-8">
                        <span class="cd-section-label"><i class="ri-mail-line"></i> Email Content</span>
                    </div>

                    <div class="grid grid-cols-12 gap-4 mb-6">
                        <div class="col-span-12">
                            <label class="form-label">Subject <span class="text-danger">*</span></label>
                            <input type="text" name="subject" class="form-control" value="{{ old('subject', $emailTemplate->subject) }}" placeholder="e.g., Interview Invitation for {job_title} at {company_name}" required>
                            <p class="text-xs text-gray-500 mt-1">Use placeholders like {`applicant_name`} to personalize</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-12 gap-4 mb-6">
                        <div class="col-span-12">
                            <label class="form-label">Body <span class="text-danger">*</span></label>
                            <textarea name="body" class="form-control font-mono text-sm" rows="12" required>{{ old('body', $emailTemplate->body) }}</textarea>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="flex justify-end gap-3 mt-8 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('employer.email-templates.index') }}" class="cd-btn cd-btn-outline">Cancel</a>
                        <button type="submit" class="cd-btn cd-btn-primary"><i class="ri-save-line me-1"></i> Update Template</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Placeholders Sidebar --}}
        <div class="col-span-12 lg:col-span-4">
            <div class="cd-section sticky top-4">
                <div class="cd-section-head">
                    <span class="cd-section-label"><i class="ri-code-line"></i> Available Placeholders</span>
                </div>
                <p class="text-sm text-gray-500 mb-4">Click a placeholder to copy it to your clipboard.</p>
                <div class="space-y-2">
                    @foreach($placeholders as $placeholder => $description)
                        <button type="button" class="placeholder-btn w-full text-left p-2 rounded-lg border border-gray-200 hover:border-indigo-300 hover:bg-indigo-50 dark:border-gray-700 dark:hover:border-indigo-500 dark:hover:bg-indigo-900/20 transition-colors" data-placeholder="{{ $placeholder }}">
                            <code class="text-xs font-mono text-indigo-600 dark:text-indigo-400">{{ $placeholder }}</code>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $description }}</p>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.placeholder-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const placeholder = btn.dataset.placeholder;
                navigator.clipboard.writeText(placeholder).then(function() {
                    const originalText = btn.querySelector('code').textContent;
                    btn.querySelector('code').textContent = 'Copied!';
                    setTimeout(function() {
                        btn.querySelector('code').textContent = originalText;
                    }, 1000);
                });
            });
        });
    });
    </script>

</x-app-layout>
