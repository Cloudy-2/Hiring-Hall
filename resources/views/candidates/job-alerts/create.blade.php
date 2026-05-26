<x-app-layout>

    <x-slot name="url_1">{"link": "/candidate/job-alerts", "text": "Job Alerts"}</x-slot>
    <x-slot name="active">Create Job Alert</x-slot>

    <div class="grid grid-cols-12 gap-x-6">
        <div class="xxl:col-span-8 col-span-12">
            <div class="box border">
                <div class="box-header">
                    <h6 class="box-title font-semibold">Create Job Alert</h6>
                </div>
                <div class="box-body">
                    <form method="POST" action="{{ route('candidate.job-alerts.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label">Alert Name <span class="text-textmuted">(optional)</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                   placeholder="e.g. Remote VA Jobs">
                            @error('name')
                                <p class="text-danger text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Keywords <span class="text-textmuted">(optional)</span></label>
                            <input type="text" name="keywords" class="form-control" value="{{ old('keywords') }}"
                                   placeholder="e.g. virtual assistant, VA, remote">
                            <p class="text-xs text-textmuted mt-1">Comma-separated keywords to match in job title or description.</p>
                            @error('keywords')
                                <p class="text-danger text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="form-label">Location</label>
                                <select name="location" class="form-select">
                                    <option value="">Any location</option>
                                    @foreach($dropdownOptions['locations'] ?? [] as $opt)
                                        <option value="{{ $opt->value }}" @selected(old('location') == $opt->value)>{{ $opt->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Category</label>
                                <select name="category" class="form-select">
                                    <option value="">Any category</option>
                                    @foreach($dropdownOptions['categories'] ?? [] as $opt)
                                        <option value="{{ $opt->value }}" @selected(old('category') == $opt->value)>{{ $opt->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="form-label">Employment Type</label>
                                <select name="employment_type" class="form-select">
                                    <option value="">Any</option>
                                    @foreach($dropdownOptions['employment_types'] ?? [] as $opt)
                                        <option value="{{ $opt->value }}" @selected(old('employment_type') == $opt->value)>{{ $opt->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Remote Type</label>
                                <select name="remote_type" class="form-select">
                                    <option value="">Any</option>
                                    @foreach($dropdownOptions['remote_types'] ?? [] as $opt)
                                        <option value="{{ $opt->value }}" @selected(old('remote_type') == $opt->value)>{{ $opt->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="form-label">Frequency</label>
                                <select name="frequency" class="form-select" required>
                                    <option value="daily" @selected(old('frequency', 'daily') == 'daily')>Daily</option>
                                    <option value="weekly" @selected(old('frequency') == 'weekly')>Weekly</option>
                                </select>
                            </div>
                            <div class="flex items-end pb-2">
                                <div class="form-check">
                                    <input type="checkbox" name="email_enabled" value="1" class="form-check-input"
                                           id="email_enabled" @checked(old('email_enabled', true))>
                                    <label class="form-check-label" for="email_enabled">Send email notifications</label>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="ti-btn ti-btn-primary">
                                <i class="bi bi-check-lg me-1"></i> Create Alert
                            </button>
                            <a href="{{ route('candidate.job-alerts.index') }}" class="ti-btn ti-btn-outline-light">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
