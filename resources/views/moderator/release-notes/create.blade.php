<x-app-layout page-title="Create Version">
    <x-slot name="url_1">{"link": "/moderator/dashboard", "text": "Moderator"}</x-slot>
    <x-slot name="url_2">{"link": "/moderator/release-notes", "text": "Version Management"}</x-slot>
    <x-slot name="active">Create New Version</x-slot>

    <style>
        .form-container-card {
            background: #fff;
            border-radius: 1.25rem;
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.01);
            overflow: hidden;
        }
        .form-header {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #f1f5f9;
            background: #f8fafc;
        }
        .form-header h2 {
            font-size: 1.125rem;
            font-weight: 800;
            color: #1e293b;
            margin: 0;
        }
        .form-header-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .form-body {
            padding: 2rem;
        }
        .refined-label {
            display: block;
            text-transform: uppercase;
            font-size: 0.65rem;
            font-weight: 800;
            letter-spacing: 0.05em;
            color: #64748b;
            margin-top: 2rem;
            margin-bottom: 0.75rem;
        }
        .refined-input {
            border-radius: 0.75rem !important;
            padding: 0.75rem 1.625rem !important; /* Increased padding by 10px */
            border-color: #e2e8f0 !important;
            font-size: 0.875rem !important;
            transition: all 0.2s;
        }
        .refined-input:focus {
            border-color: #4f46e5 !important;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1) !important;
        }
        .checkbox-group {
            background: #f8fafc;
            padding: 1.25rem;
            border-radius: 1rem;
            border: 1px solid #f1f5f9;
            margin-bottom: 1.5rem;
        }
        .custom-checkbox-label {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            cursor: pointer;
            user-select: none;
        }
        .checkbox-description {
            display: flex;
            flex-direction: column;
        }
        .checkbox-title {
            font-size: 0.875rem;
            font-weight: 700;
            color: #1e293b;
        }
        .checkbox-hint {
            font-size: 0.75rem;
            color: #64748b;
            margin-top: 0.25rem;
        }
        
        html.dark .form-container-card { background: rgba(30, 41, 59, 0.5); border-color: rgba(255,255,255,0.05); }
        html.dark .form-header { background: rgba(15, 23, 42, 0.3); border-color: rgba(255,255,255,0.05); }
        html.dark .form-header h2 { color: #f1f5f9; }
        html.dark .refined-label { color: #94a3b8; }
        html.dark .refined-input { background: rgba(15, 23, 42, 0.5) !important; border-color: rgba(255,255,255,0.1) !important; color: #f1f5f9 !important; }
        html.dark .refined-input::placeholder { color: #64748b !important; }
        html.dark .checkbox-group { background: rgba(15, 23, 42, 0.2); border-color: rgba(255,255,255,0.05); }
        html.dark .checkbox-title { color: #f1f5f9; }
        html.dark .checkbox-hint { color: #64748b; }
    </style>

    <div class="max-w-4xl mx-auto py-8">
        <div class="form-container-card">
            <div class="form-header">
                <div class="form-header-bar">
                    <h2>Create New Platform Version</h2>
                    <a href="{{ route('moderator.release-notes.index') }}" class="ti-btn ti-btn-light px-4 !py-2 rounded-xl font-bold uppercase tracking-wide text-xs">
                        <i class="ri-arrow-left-line me-1"></i>
                        Back to Version Management
                    </a>
                </div>
            </div>
            <div class="form-body">
                <form action="{{ route('moderator.release-notes.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="refined-label">Version Number</label>
                            <div class="relative">
                                <span class="absolute top-1/2 -translate-y-1/2 text-slate-400 font-mono text-sm pointer-events-none" style="left: 28px;">v</span>
                                <input type="text" name="version" class="form-control refined-input @error('version') is-invalid @enderror" style="padding-left: 42px !important;" value="{{ old('version') }}" placeholder="1.0.0">
                            </div>
                            <p class="checkbox-hint mt-2">Semantic versioning (Major.Minor.Patch) is recommended.</p>
                            @error('version')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label class="refined-label">Deployment Date <span class="text-danger">*</span></label>
                            <input type="date" name="released_at" class="form-control refined-input @error('released_at') is-invalid @enderror" value="{{ old('released_at', now()->format('Y-m-d')) }}" required>
                            @error('released_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-8">
                        <label class="refined-label">Version Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control refined-input @error('title') is-invalid @enderror" value="{{ old('title') }}" required placeholder="e.g. Major UI Overhaul & Performance Fixes">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-8">
                        <label class="refined-label">Version Content (Markdown Supported) <span class="text-danger">*</span></label>
                        <textarea name="body" class="form-control refined-input @error('body') is-invalid @enderror" style="height: 500px !important; font-size: 1rem !important;" required placeholder="Outline the key features, improvements, and bug fixes included in this version...">{{ old('body') }}</textarea>
                        @error('body')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                        <div class="checkbox-group">
                            <label class="custom-checkbox-label">
                                <input type="hidden" name="is_published" value="0">
                                <input type="checkbox" name="is_published" value="1" class="form-check-input !w-5 !h-5 !mt-0.5 rounded-md" {{ old('is_published', false) ? 'checked' : '' }}>
                                <div class="checkbox-description">
                                    <span class="checkbox-title">Publicly Visible</span>
                                    <span class="checkbox-hint">Make this version update visible to all users immediately.</span>
                                </div>
                            </label>
                        </div>
                        <div class="checkbox-group border-primary/20 bg-primary/5">
                            <label class="custom-checkbox-label">
                                <input type="hidden" name="set_as_system_version" value="0">
                                <input type="checkbox" name="set_as_system_version" value="1" class="form-check-input !w-5 !h-5 !mt-0.5 rounded-md" {{ old('set_as_system_version', false) ? 'checked' : '' }}>
                                <div class="checkbox-description">
                                    <span class="checkbox-title text-primary">Promote to Production</span>
                                    <span class="checkbox-hint">Set the provided version as the active platform version.</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                        <a href="{{ route('moderator.release-notes.index') }}" class="ti-btn ti-btn-light px-8 !py-2.5 rounded-xl font-bold uppercase tracking-wide text-xs">
                            Cancel
                        </a>
                        <button type="submit" class="ti-btn ti-btn-primary px-8 !py-2.5 rounded-xl font-bold uppercase tracking-wide text-xs">
                            Confirm & Save Version
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const publicCheckbox = document.querySelector('input[name="is_published"]');
            const systemCheckbox = document.querySelector('input[name="set_as_system_version"]');
            
            if (systemCheckbox && publicCheckbox) {
                systemCheckbox.addEventListener('change', function() {
                    if (this.checked) {
                        publicCheckbox.checked = true;
                    }
                });
            }
        });
    </script>
</x-app-layout>
