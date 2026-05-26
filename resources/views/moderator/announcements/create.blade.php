<x-app-layout page-title="Create Announcement">
    <x-slot name="url_1">{"link": "/moderator/dashboard", "text": "Moderator"}</x-slot>
    <x-slot name="url_2">{"link": "/moderator/announcements", "text": "Announcements"}</x-slot>
    <x-slot name="active">Create</x-slot>

    <div class="grid grid-cols-12 gap-6">
        <div class="xl:col-span-8 col-span-12">
            <div class="box border">
                <div class="box-header">
                    <div class="box-title">Create New Announcement</div>
                </div>
                <div class="box-body">
                    <form action="{{ route('moderator.announcements.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required placeholder="Announcement title">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Content <span class="text-danger">*</span></label>
                            <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="6" required placeholder="Write your announcement content here...">{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="form-label">Type <span class="text-danger">*</span></label>
                                <select name="type" class="form-control @error('type') is-invalid @enderror" required>
                                    <option value="info" {{ old('type') === 'info' ? 'selected' : '' }}>Info (Blue)</option>
                                    <option value="success" {{ old('type') === 'success' ? 'selected' : '' }}>Success (Green)</option>
                                    <option value="warning" {{ old('type') === 'warning' ? 'selected' : '' }}>Warning (Yellow)</option>
                                    <option value="danger" {{ old('type') === 'danger' ? 'selected' : '' }}>Danger (Red)</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label">Target Audience</label>
                                <select name="target_roles[]" class="form-control @error('target_roles') is-invalid @enderror" multiple>
                                    <option value="all" selected>All Users</option>
                                    <option value="applicant">Applicants Only</option>
                                    <option value="employer">Employers Only</option>
                                    <option value="moderator">Moderators Only</option>
                                    <option value="admin">Admins Only</option>
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
                                <input type="datetime-local" name="published_at" class="form-control @error('published_at') is-invalid @enderror" value="{{ old('published_at') }}">
                                <small class="text-textmuted">Leave empty to publish immediately</small>
                                @error('published_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label">Expiration Date</label>
                                <input type="datetime-local" name="expires_at" class="form-control @error('expires_at') is-invalid @enderror" value="{{ old('expires_at') }}">
                                <small class="text-textmuted">Leave empty for no expiration</small>
                                @error('expires_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="is_pinned" value="1" class="form-check-input" {{ old('is_pinned') ? 'checked' : '' }}>
                                <span>Pin this announcement</span>
                            </label>
                            <small class="text-textmuted">Pinned announcements appear at the top</small>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="ti-btn ti-btn-primary">
                                <i class="ri-megaphone-line me-1"></i> Create Announcement
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
                        <div class="alert alert-info">
                            <h5 class="font-semibold" id="preview-title">Announcement Title</h5>
                            <p class="mt-2 text-sm" id="preview-content">Your announcement content will appear here...</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box border mt-4">
                <div class="box-header">
                    <div class="box-title">Tips</div>
                </div>
                <div class="box-body">
                    <ul class="list-disc list-inside text-sm text-textmuted space-y-2">
                        <li>Keep titles short and descriptive</li>
                        <li>Use "Danger" type for urgent announcements</li>
                        <li>Pin important announcements to keep them at the top</li>
                        <li>Set expiration dates for time-limited announcements</li>
                        <li>Target specific user roles when the message isn't for everyone</li>
                    </ul>
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
