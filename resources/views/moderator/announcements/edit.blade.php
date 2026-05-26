<x-app-layout page-title="Edit Announcement">
    <x-slot name="url_1">{"link": "/moderator/dashboard", "text": "Moderator"}</x-slot>
    <x-slot name="url_2">{"link": "/moderator/announcements", "text": "Announcements"}</x-slot>
    <x-slot name="active">Edit</x-slot>

    <div class="grid grid-cols-12 gap-6">
        <div class="xl:col-span-8 col-span-12">
            <div class="box border">
                <div class="box-header">
                    <div class="box-title">Edit Announcement</div>
                </div>
                <div class="box-body">
                    <form action="{{ route('moderator.announcements.update', $announcement) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $announcement->title) }}" required placeholder="Announcement title">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Content <span class="text-danger">*</span></label>
                            <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="6" required placeholder="Write your announcement content here...">{{ old('content', $announcement->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="form-label">Type <span class="text-danger">*</span></label>
                                <select name="type" class="form-control @error('type') is-invalid @enderror" required>
                                    <option value="info" {{ old('type', $announcement->type) === 'info' ? 'selected' : '' }}>Info (Blue)</option>
                                    <option value="success" {{ old('type', $announcement->type) === 'success' ? 'selected' : '' }}>Success (Green)</option>
                                    <option value="warning" {{ old('type', $announcement->type) === 'warning' ? 'selected' : '' }}>Warning (Yellow)</option>
                                    <option value="danger" {{ old('type', $announcement->type) === 'danger' ? 'selected' : '' }}>Danger (Red)</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label">Target Audience</label>
                                @php $selectedRoles = old('target_roles', $announcement->target_roles ?? ['all']); @endphp
                                <select name="target_roles[]" class="form-control @error('target_roles') is-invalid @enderror" multiple>
                                    <option value="all" {{ in_array('all', $selectedRoles) ? 'selected' : '' }}>All Users</option>
                                    <option value="applicant" {{ in_array('applicant', $selectedRoles) ? 'selected' : '' }}>Applicants Only</option>
                                    <option value="employer" {{ in_array('employer', $selectedRoles) ? 'selected' : '' }}>Employers Only</option>
                                    <option value="moderator" {{ in_array('moderator', $selectedRoles) ? 'selected' : '' }}>Moderators Only</option>
                                    <option value="admin" {{ in_array('admin', $selectedRoles) ? 'selected' : '' }}>Admins Only</option>
                                </select>
                                <small class="text-textmuted">Hold Ctrl/Cmd to select multiple</small>
                                @error('target_roles')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="form-label">Publish Date</label>
                                <input type="datetime-local" name="published_at" class="form-control @error('published_at') is-invalid @enderror" value="{{ old('published_at', $announcement->published_at?->format('Y-m-d\TH:i')) }}">
                                <small class="text-textmuted">Leave empty to publish immediately</small>
                                @error('published_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label">Expiration Date</label>
                                <input type="datetime-local" name="expires_at" class="form-control @error('expires_at') is-invalid @enderror" value="{{ old('expires_at', $announcement->expires_at?->format('Y-m-d\TH:i')) }}">
                                <small class="text-textmuted">Leave empty for no expiration</small>
                                @error('expires_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="is_pinned" value="1" class="form-check-input" {{ old('is_pinned', $announcement->is_pinned) ? 'checked' : '' }}>
                                    <span>Pin this announcement</span>
                                </label>
                                <small class="text-textmuted">Pinned announcements appear at the top</small>
                            </div>

                            <div>
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', $announcement->is_active) ? 'checked' : '' }}>
                                    <span>Active</span>
                                </label>
                                <small class="text-textmuted">Inactive announcements won't be displayed</small>
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="ti-btn ti-btn-primary">
                                <i class="ri-save-line me-1"></i> Update Announcement
                            </button>
                            <a href="{{ route('moderator.announcements.index') }}" class="ti-btn ti-btn-light">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="xl:col-span-4 col-span-12">
            <div class="box border">
                <div class="box-header">
                    <div class="box-title">Preview</div>
                </div>
                <div class="box-body">
                    <div id="preview-container">
                        <div class="alert alert-{{ $announcement->type }}">
                            <h5 class="font-semibold" id="preview-title">{{ $announcement->title }}</h5>
                            <p class="mt-2 text-sm" id="preview-content">{{ $announcement->content }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box border mt-4">
                <div class="box-header">
                    <div class="box-title">Info</div>
                </div>
                <div class="box-body">
                    <p class="text-sm"><strong>Created:</strong> {{ $announcement->created_at->format('M d, Y H:i') }}</p>
                    @if($announcement->creator)
                        <p class="text-sm"><strong>By:</strong> {{ $announcement->creator->name }}</p>
                    @endif
                    <p class="text-sm"><strong>Last Updated:</strong> {{ $announcement->updated_at->format('M d, Y H:i') }}</p>
                </div>
            </div>

            <div class="box border mt-4 border-danger">
                <div class="box-header bg-danger/10">
                    <div class="box-title text-danger">Danger Zone</div>
                </div>
                <div class="box-body">
                    <form action="{{ route('moderator.announcements.destroy', $announcement) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this announcement? This cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="ti-btn ti-btn-danger w-full">
                            <i class="ri-delete-bin-line me-1"></i> Delete Announcement
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const titleInput = document.querySelector('input[name="title"]');
            const contentInput = document.querySelector('textarea[name="content"]');
            const typeSelect = document.querySelector('select[name="type"]');
            const previewTitle = document.getElementById('preview-title');
            const previewContent = document.getElementById('preview-content');
            const previewContainer = document.getElementById('preview-container');

            function updatePreview() {
                previewTitle.textContent = titleInput.value || 'Announcement Title';
                previewContent.textContent = contentInput.value || 'Your announcement content will appear here...';

                const alertDiv = previewContainer.querySelector('.alert');
                alertDiv.className = 'alert alert-' + typeSelect.value;
            }

            titleInput.addEventListener('input', updatePreview);
            contentInput.addEventListener('input', updatePreview);
            typeSelect.addEventListener('change', updatePreview);
        });
    </script>

</x-app-layout>
