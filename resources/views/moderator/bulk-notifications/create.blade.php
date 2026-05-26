<x-app-layout page-title="Create Bulk Notification">
    <x-slot name="url_1">{"link": "/moderator/dashboard", "text": "Moderator"}</x-slot>
    <x-slot name="url_2">{"link": "/moderator/bulk-notifications", "text": "Bulk Notifications"}</x-slot>
    <x-slot name="active">Create</x-slot>

    <div class="grid grid-cols-12 gap-6">
        <div class="xl:col-span-8 col-span-12">
            <div class="box border">
                <div class="box-header">
                    <div class="box-title">Create Bulk Notification</div>
                </div>
                <div class="box-body">
                    <form action="{{ route('moderator.bulk-notifications.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label">Subject <span class="text-danger">*</span></label>
                            <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror" value="{{ old('subject') }}" required placeholder="Email subject line">
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Message <span class="text-danger">*</span></label>
                            <textarea name="message" class="form-control @error('message') is-invalid @enderror" rows="8" required placeholder="Write your notification message here...">{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="form-label">Notification Type <span class="text-danger">*</span></label>
                                <select name="notification_type" class="form-control @error('notification_type') is-invalid @enderror" required>
                                    <option value="email" {{ old('notification_type') === 'email' ? 'selected' : '' }}>Email Only</option>
                                    <option value="database" {{ old('notification_type') === 'database' ? 'selected' : '' }}>In-App Only</option>
                                    <option value="both" {{ old('notification_type') === 'both' ? 'selected' : '' }}>Both Email & In-App</option>
                                </select>
                                @error('notification_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label">Target Audience <span class="text-danger">*</span></label>
                                <div class="space-y-2">
                                    <label class="flex items-center gap-2">
                                        <input type="checkbox" name="target_roles[]" value="all" class="form-check-input" {{ in_array('all', old('target_roles', [])) ? 'checked' : '' }}>
                                        <span>All Users ({{ $userCounts['all'] }})</span>
                                    </label>
                                    <label class="flex items-center gap-2">
                                        <input type="checkbox" name="target_roles[]" value="applicant" class="form-check-input" {{ in_array('applicant', old('target_roles', [])) ? 'checked' : '' }}>
                                        <span>Applicants ({{ $userCounts['applicant'] }})</span>
                                    </label>
                                    <label class="flex items-center gap-2">
                                        <input type="checkbox" name="target_roles[]" value="employer" class="form-check-input" {{ in_array('employer', old('target_roles', [])) ? 'checked' : '' }}>
                                        <span>Employers ({{ $userCounts['employer'] }})</span>
                                    </label>
                                    <label class="flex items-center gap-2">
                                        <input type="checkbox" name="target_roles[]" value="moderator" class="form-check-input" {{ in_array('moderator', old('target_roles', [])) ? 'checked' : '' }}>
                                        <span>Moderators ({{ $userCounts['moderator'] }})</span>
                                    </label>
                                    <label class="flex items-center gap-2">
                                        <input type="checkbox" name="target_roles[]" value="admin" class="form-check-input" {{ in_array('admin', old('target_roles', [])) ? 'checked' : '' }}>
                                        <span>Admins ({{ $userCounts['admin'] }})</span>
                                    </label>
                                </div>
                                @error('target_roles')
                                    <div class="text-danger text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="ti-btn ti-btn-primary">
                                <i class="ri-draft-line me-1"></i> Save & Preview
                            </button>
                            <a href="{{ route('moderator.bulk-notifications.index') }}" class="ti-btn ti-btn-light">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="xl:col-span-4 col-span-12">
            <div class="box border">
                <div class="box-header">
                    <div class="box-title">Instructions</div>
                </div>
                <div class="box-body">
                    <ul class="list-disc list-inside text-sm text-textmuted space-y-2">
                        <li>Notifications will be saved as a draft first</li>
                        <li>You can preview before sending</li>
                        <li>Email notifications are sent in the background</li>
                        <li>Select "All Users" to send to everyone</li>
                        <li>Or select specific roles to target</li>
                    </ul>
                </div>
            </div>

            <div class="box border mt-4 border-warning">
                <div class="box-header bg-warning/10">
                    <div class="box-title text-warning">Warning</div>
                </div>
                <div class="box-body">
                    <p class="text-sm text-textmuted">
                        Bulk notifications cannot be undone once sent. Please review carefully before sending to ensure the message is correct and targets the right audience.
                    </p>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
