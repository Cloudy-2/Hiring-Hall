<x-app-layout>
    <x-slot name="title">Edit Job Alert</x-slot>

    <x-slot name="url_1">{"link": "/applicant/job-alerts", "text": "Job Alerts"}</x-slot>
    <x-slot name="active">Edit Job Alert</x-slot>

    <div class="grid grid-cols-12 gap-x-6">
        <div class="xxl:col-span-8 col-span-12">
            <div class="box border">
                <div class="box-header">
                    <h6 class="box-title font-semibold">Edit Job Alert</h6>
                </div>
                <div class="box-body">
                    <form method="POST" action="{{ route('applicant.job-alerts.update', $alert) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label">Alert Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $alert->name) }}"
                                placeholder="e.g. Remote VA Jobs" required>
                            @error('name')
                                <p class="text-danger text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Keywords <span class="text-textmuted">(optional)</span></label>
                            <select name="keywords[]" class="form-control" id="keywords-select" multiple="multiple">
                                @php
                                    // Parse old keywords string to array
                                    $oldKeywords = old('keywords', $alert->keywords);
                                    $oldKeywordsArray = [];
                                    if (is_array($oldKeywords)) {
                                        $oldKeywordsArray = $oldKeywords;
                                    } elseif ($oldKeywords) {
                                        $oldKeywordsArray = array_map('trim', explode(',', $oldKeywords));
                                    }

                                    // Popular suggestions
                                    $suggestions = ['Virtual Assistant', 'Data Entry', 'Customer Service', 'Social Media', 'Graphic Design', 'Web Developer', 'SEO', 'Admin', 'Remote', 'Content Writer'];
                                @endphp
                                @foreach($suggestions as $s)
                                    @if(!in_array($s, $oldKeywordsArray))
                                        <option value="{{ $s }}">{{ $s }}</option>
                                    @endif
                                @endforeach

                                @foreach($oldKeywordsArray as $kw)
                                    @if(!empty($kw))
                                        <option value="{{ $kw }}" selected>{{ $kw }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <p class="text-xs text-textmuted mt-1">Type keywords and press Enter. You can also select
                                from the suggestions.</p>
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
                                        <option value="{{ $opt->value }}" @selected(old('location', $alert->location) == $opt->value)>{{ $opt->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Category</label>
                                <select name="category" class="form-select">
                                    <option value="">Any category</option>
                                    @foreach($dropdownOptions['categories'] ?? [] as $opt)
                                        <option value="{{ $opt->value }}" @selected(old('category', $alert->category) == $opt->value)>{{ $opt->label }}</option>
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
                                        <option value="{{ $opt->value }}" @selected(old('employment_type', $alert->employment_type) == $opt->value)>{{ $opt->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Remote Type</label>
                                <select name="remote_type" class="form-select">
                                    <option value="">Any</option>
                                    @foreach($dropdownOptions['remote_types'] ?? [] as $opt)
                                        <option value="{{ $opt->value }}" @selected(old('remote_type', $alert->remote_type) == $opt->value)>{{ $opt->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="form-label">Frequency</label>
                                <select name="frequency" class="form-select" required>
                                    <option value="daily" @selected(old('frequency', $alert->frequency) == 'daily')>Daily
                                    </option>
                                    <option value="weekly" @selected(old('frequency', $alert->frequency) == 'weekly')>
                                        Weekly</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <div class="form-check">
                                    <input type="checkbox" name="email_enabled" value="1" class="form-check-input"
                                        id="email_enabled" @checked(old('email_enabled', $alert->email_enabled))>
                                    <label class="form-check-label" for="email_enabled">Send email notifications</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" name="is_active" value="1" class="form-check-input"
                                        id="is_active" @checked(old('is_active', $alert->is_active))>
                                    <label class="form-check-label" for="is_active">Alert is active</label>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="ti-btn ti-btn-primary">
                                <i class="bi bi-check-lg me-1"></i> Update Alert
                            </button>
                            <a href="{{ route('applicant.job-alerts.index') }}"
                                class="ti-btn ti-btn-outline-light">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Select2 Cdn and Initialization -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container .select2-selection--multiple {
            min-height: 42px;
            border-color: #e5e7eb;
            border-radius: 0.375rem;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #0ea5e9;
            border-color: #0284c7;
            color: #fff;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #fff;
            margin-right: 5px;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (jQuery('#keywords-select').length > 0) {
                jQuery('#keywords-select').select2({
                    tags: true,
                    placeholder: "e.g. virtual assistant, VA, remote",
                    allowClear: true,
                    width: '100%',
                    dropdownParent: jQuery('#keywords-select').parent()
                });
            }
        });
    </script>
</x-app-layout>