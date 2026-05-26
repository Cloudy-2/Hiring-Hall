<x-app-layout page-title="Edit Version">
    <x-slot name="url_1">{"link": "/moderator/dashboard", "text": "Moderator"}</x-slot>
    <x-slot name="url_2">{"link": "/moderator/release-notes", "text": "Version Management"}</x-slot>
    <x-slot name="active">Edit Version Details</x-slot>

    <style>
        .form-container-card {
            background: #fff;
            border-radius: 1.25rem;
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.01);
            overflow: hidden;
            margin-bottom: 2rem;
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
        
        .side-info-card {
            background: #fff;
            border-radius: 1rem;
            border: 1px solid #e2e8f0;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .side-info-item {
            display: flex;
            flex-direction: column;
            margin-bottom: 1rem;
        }
        .side-info-item:last-child { margin-bottom: 0; }
        .side-info-label {
            font-size: 0.625rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #94a3b8;
            margin-bottom: 0.25rem;
        }
        .side-info-value {
            font-size: 0.8125rem;
            font-weight: 600;
            color: #334155;
        }

        html.dark .form-container-card,
        html.dark .side-info-card { background: rgba(30, 41, 59, 0.5); border-color: rgba(255,255,255,0.05); }
        html.dark .form-header { background: rgba(15, 23, 42, 0.3); border-color: rgba(255,255,255,0.05); }
        html.dark .form-header h2 { color: #f1f5f9; }
        html.dark .refined-label { color: #94a3b8; }
        html.dark .refined-input { background: rgba(15, 23, 42, 0.5) !important; border-color: rgba(255,255,255,0.1) !important; color: #f1f5f9 !important; }
        html.dark .refined-input::placeholder { color: #64748b !important; }
        html.dark .checkbox-group { background: rgba(15, 23, 42, 0.2); border-color: rgba(255,255,255,0.05); }
        html.dark .checkbox-title { color: #f1f5f9; }
        html.dark .checkbox-hint { color: #64748b; }
        html.dark .side-info-label { color: #64748b; }
        html.dark .side-info-value { color: #cbd5e1; }
        html.dark .side-info-card.border-danger\/20 { border-color: rgba(248, 113, 113, 0.2) !important; background: rgba(248, 113, 113, 0.05) !important; }
    </style>

    <div class="grid grid-cols-12 gap-8 py-8">
        <div class="xl:col-span-8 col-span-12">
            <div class="form-container-card">
                <div class="form-header">
                    <div class="form-header-bar">
                        <h2>Edit Version Details</h2>
                        <a href="{{ route('moderator.release-notes.index') }}" class="ti-btn ti-btn-light px-4 !py-2 rounded-xl font-bold uppercase tracking-wide text-xs">
                            <i class="ri-arrow-left-line me-1"></i>
                            Back to Version Management
                        </a>
                    </div>
                </div>
                <div class="form-body">
                    <form action="{{ route('moderator.release-notes.update', $releaseNote) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div>
                                <label class="refined-label">Version Number</label>
                                <div class="relative">
                                    <span class="absolute top-1/2 -translate-y-1/2 text-slate-400 font-mono text-sm pointer-events-none" style="left: 28px;">v</span>
                                    <input type="text" name="version" class="form-control refined-input @error('version') is-invalid @enderror" style="padding-left: 42px !important;" value="{{ old('version', $releaseNote->version) }}" placeholder="1.0.0">
                                </div>
                                @error('version')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div>
                                <label class="refined-label">Deployment Date <span class="text-danger">*</span></label>
                                <input type="date" name="released_at" class="form-control refined-input @error('released_at') is-invalid @enderror" value="{{ old('released_at', $releaseNote->released_at->format('Y-m-d')) }}" required>
                                @error('released_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-8">
                            <label class="refined-label">Version Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control refined-input @error('title') is-invalid @enderror" value="{{ old('title', $releaseNote->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-8">
                            <label class="refined-label">Version Content (Markdown Supported) <span class="text-danger">*</span></label>
                            <textarea name="body" class="form-control refined-input @error('body') is-invalid @enderror" style="height: 500px !important; font-size: 1rem !important;" required>{{ old('body', $releaseNote->body) }}</textarea>
                            @error('body')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                            <div class="checkbox-group">
                                <label class="custom-checkbox-label">
                                    <input type="hidden" name="is_published" value="0">
                                    <input type="checkbox" name="is_published" value="1" class="form-check-input !w-5 !h-5 !mt-0.5 rounded-md" {{ old('is_published', $releaseNote->is_published) ? 'checked' : '' }}>
                                    <div class="checkbox-description">
                                        <span class="checkbox-title">Publicly Visible</span>
                                        <span class="checkbox-hint">Published versions are visible to all system users.</span>
                                    </div>
                                </label>
                            </div>
                            <div class="checkbox-group border-primary/20 bg-primary/5">
                                <label class="custom-checkbox-label">
                                    <input type="hidden" name="set_as_system_version" value="0">
                                    <input type="checkbox" name="set_as_system_version" value="1" class="form-check-input !w-5 !h-5 !mt-0.5 rounded-md" {{ old('set_as_system_version', ($releaseNote->version === $appVersion)) ? 'checked' : '' }}>
                                    <div class="checkbox-description">
                                        <span class="checkbox-title text-primary">Promote to Production</span>
                                        <span class="checkbox-hint">Overwrite the current system version with this version.</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                            <a href="{{ route('moderator.release-notes.index') }}" class="ti-btn ti-btn-light px-8 !py-2.5 rounded-xl font-bold uppercase tracking-wide text-xs">
                                Discard Changes
                            </a>
                            <button type="submit" class="ti-btn ti-btn-primary px-8 !py-2.5 rounded-xl font-bold uppercase tracking-wide text-xs">
                                Update Version Details
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="xl:col-span-4 col-span-12">
            <div class="side-info-card">
                <h3 class="side-info-label mb-4">Metadata Info</h3>
                <div class="side-info-item">
                    <span class="side-info-label">Created At</span>
                    <span class="side-info-value">{{ $releaseNote->created_at->format('M d, Y • H:i') }}</span>
                </div>
                @if($releaseNote->creator)
                    <div class="side-info-item">
                        <span class="side-info-label">Author</span>
                        <span class="side-info-value">{{ $releaseNote->creator->name }}</span>
                    </div>
                @endif
                <div class="side-info-item">
                    <span class="side-info-label">Last Modified</span>
                    <span class="side-info-value">{{ $releaseNote->updated_at->format('M d, Y • H:i') }}</span>
                    <span class="text-[10px] text-slate-400 mt-0.5">{{ $releaseNote->updated_at->diffForHumans() }}</span>
                </div>
            </div>

            <div class="side-info-card border-danger/20 bg-danger/5">
                <h3 class="side-info-label text-danger mb-4">Danger Zone</h3>
                <p class="text-xs text-slate-500 mb-4">Once deleted, this version update cannot be recovered. This will also remove it from the public timeline.</p>
                <form action="{{ route('moderator.release-notes.destroy', $releaseNote) }}" method="POST" onsubmit="return confirmDelete(this)">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="ti-btn ti-btn-danger w-full !py-2.5 rounded-xl font-bold uppercase tracking-wide text-xs">
                        <i class="ri-delete-bin-line me-2"></i>
                        Delete Version
                    </button>
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

        function confirmDelete(form) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Once deleted, this version update cannot be recovered and will be removed from the public timeline.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, delete version!',
                cancelButtonText: 'Cancel',
                customClass: {
                    popup: 'rounded-2xl border-0',
                    confirmButton: 'ti-btn ti-btn-danger px-6 py-2.5 rounded-xl font-bold uppercase text-xs',
                    cancelButton: 'ti-btn ti-btn-light px-6 py-2.5 rounded-xl font-bold uppercase text-xs'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
            return false;
        }
    </script>
</x-app-layout>
