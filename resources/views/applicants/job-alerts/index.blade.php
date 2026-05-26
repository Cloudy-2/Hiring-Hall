<x-app-layout>

    <x-slot name="url_1">{"link": "/applicant/dashboard", "text": "Dashboard"}</x-slot>
    <x-slot name="title">Job Alerts</x-slot>
    <x-slot name="active">Job Alerts</x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @include('applicants.partials.candidate-styles')
        {{-- Modern Minimalist Header (Interactive Board Style) --}}
        <x-modern-header :container="false" chip="Job Notifications">
            <x-slot name="titleContent"><strong>Job Alerts</strong></x-slot>
            <x-slot name="description">
                Get notified when new jobs match your criteria. Stay ahead in your <b>Career Journey</b> with real-time updates tailored to your preferences.
            </x-slot>
            <x-slot name="actions">
                <button type="button" class="inline-flex items-center px-5 py-2.5 rounded-xl bg-white dark:bg-slate-800 text-slate-700 dark:text-white font-bold hover:bg-slate-50 dark:hover:bg-slate-700 transition-all shadow-sm hover:shadow-md border border-slate-200 dark:border-slate-700 text-sm" id="btnOpenCreateModal">
                    <i class="ri-add-line me-2 text-indigo-500"></i> Create Alert
                </button>
                <a href="{{ route('applicant.dashboard') }}" class="inline-flex items-center px-5 py-2.5 rounded-xl bg-white dark:bg-slate-800 text-slate-700 dark:text-white font-bold hover:bg-slate-50 dark:hover:bg-slate-700 transition-all shadow-sm hover:shadow-md border border-slate-200 dark:border-slate-700 text-sm" title="Dashboard">
                    <i class="ri-dashboard-line me-2 text-indigo-500"></i> Dashboard
                </a>
            </x-slot>
        </x-modern-header>

        <div class="grid grid-cols-12 gap-x-6">
            <div class="xxl:col-span-12 col-span-12">
                <div class="box">
                    <div class="box-body">

                    @if (session('status'))
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                Swal.fire({
                                    icon: 'success',
                                    title: @json(session('status')),
                                    showConfirmButton: false,
                                    timer: 3500,
                                    timerProgressBar: true,
                                });
                            });
                        </script>
                    @endif

                    @if($alerts->isEmpty())
                        {{-- Premium Consistent Empty State --}}
                        <div class="jf-empty-state w-full">
                            <div class="jf-empty-icon shadow-sm">
                                <i class="ri-notification-badge-line"></i>
                            </div>
                            <div class="text-center max-w-md mx-auto">
                                <h2 class="text-2xl font-black text-slate-800 dark:text-white mb-2">No job alerts yet</h2>
                                <p class="text-slate-500 dark:text-slate-400 mb-8 leading-relaxed font-medium">
                                    Create an alert to receive instant email notifications when new jobs match your unique skills and preferences.
                                </p>
                                <button type="button" class="cd-btn cd-btn-primary py-3 px-8 shadow-xl" id="btnOpenCreateModalEmpty">
                                    <i class="ri-add-line"></i> Create Your First Alert
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="grid grid-cols-1 gap-3">
                            @foreach($alerts as $alert)
                                @php
                                    $keywordsArr = $alert->keywords ? array_filter(explode(',', $alert->keywords)) : [];
                                    $maxTags = 2;
                                    $tagCount = count($keywordsArr);
                                    $displayTags = array_slice($keywordsArr, 0, $maxTags);
                                    $remainingCount = $tagCount - $maxTags;
                                @endphp
                                <div class="jf-alert-row {{ $alert->is_active ? 'active-state' : 'paused-state' }} group bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl p-5 mb-4 transition-all cursor-pointer"
                                     onclick="if(!event.target.closest('.jf-stop-propagation')) { jQuery('#edit-btn-{{ $alert->id }}').click(); }">
                                    <div class="flex flex-col md:flex-row md:items-center gap-5">
                                        {{-- Left: Icon --}}
                                        <div class="jf-alert-icon-sq flex-shrink-0">
                                            <i class="ri-notification-3-line"></i>
                                        </div>

                                        {{-- Center: Content --}}
                                        <div class="flex-1 min-w-0">
                                            <div class="flex flex-wrap items-center gap-3 mb-3">
                                                <h3 class="font-bold text-slate-900 dark:text-white text-lg tracking-tight mb-0">
                                                    {{ $alert->name ?: 'Job Alert' }}
                                                </h3>

                                                {{-- Status Badge (Replaces Toggle) --}}
                                                <div class="jf-status-badge {{ $alert->is_active ? 'jf-status-active' : 'jf-status-paused' }}">
                                                    {{-- Note: Icon and Pulse handled via CSS --}}
                                                    {{ $alert->is_active ? 'Active' : 'Paused' }}
                                                </div>
                                            </div>

                                            {{-- Tags & Metadata Grid --}}
                                            <div class="jf-metadata-grid">
                                                {{-- User Keywords --}}
                                                @if(count($keywordsArr) > 0)
                                                    <div class="flex flex-wrap gap-1.5">
                                                        @foreach($displayTags as $tag)
                                                            <span class="jf-pill">
                                                                <i class="ri-hashtag text-[10px] me-1 opacity-50"></i> {{ trim($tag) }}
                                                            </span>
                                                        @endforeach
                                                        @if($remainingCount > 0)
                                                            <span class="jf-pill opacity-60">+{{ $remainingCount }}</span>
                                                        @endif
                                                    </div>
                                                @endif

                                                {{-- Category --}}
                                                @if($alert->category)
                                                    <span class="jf-pill bg-indigo-50/50 text-indigo-600 border-indigo-100 dark:bg-indigo-500/10 dark:text-indigo-400 dark:border-indigo-500/20">
                                                        <i class="ri-folder-line text-[10px] me-1"></i> {{ Str::headline($alert->category) }}
                                                    </span>
                                                @endif

                                                {{-- Frequency --}}
                                                <div class="jf-pill bg-emerald-50/50 text-emerald-600 border-emerald-100 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20">
                                                    <i class="ri-refresh-line me-1"></i>
                                                    {{ Str::headline($alert->frequency) }}
                                                </div>

                                                {{-- Last Sent --}}
                                                <div class="jf-pill bg-amber-50/50 text-amber-600 border-amber-100 dark:bg-amber-500/10 dark:text-amber-400 dark:border-amber-500/20">
                                                    <i class="ri-history-line me-1"></i>
                                                    <b>
                                                        @if($alert->last_sent_at)
                                                            {{ $alert->last_sent_at->diffForHumans() }}
                                                        @else
                                                            Not sent yet
                                                        @endif
                                                    </b>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Right Actions --}}
                                        <div class="flex items-center gap-3 jf-stop-propagation flex-shrink-0">
                                            <button type="button" id="edit-btn-{{ $alert->id }}"
                                                class="btn-edit-alert jf-primary-action"
                                                data-id="{{ $alert->id }}" data-name="{{ $alert->name }}"
                                                data-keywords="{{ $alert->keywords }}" data-location="{{ $alert->location }}"
                                                data-category="{{ $alert->category }}"
                                                data-employment-type="{{ $alert->employment_type }}"
                                                data-remote-type="{{ $alert->remote_type }}"
                                                data-frequency="{{ $alert->frequency }}"
                                                data-email-enabled="{{ $alert->email_enabled ? '1' : '0' }}"
                                                data-is-active="{{ $alert->is_active ? '1' : '0' }}"
                                                data-update-url="{{ route('applicant.job-alerts.update', $alert) }}">
                                                <i class="ri-edit-line"></i> Edit Alert
                                            </button>

                                            <div class="hs-dropdown ti-dropdown [--placement:bottom-right]">
                                                <button id="alert-more-{{ $alert->id }}" type="button"
                                                    class="hs-dropdown-toggle ti-dropdown-toggle w-10 h-10 flex items-center justify-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all focus:outline-none border border-slate-100 dark:border-slate-800">
                                                    <i class="ri-more-2-line text-xl"></i>
                                                </button>
                                                <div class="hs-dropdown-menu ti-dropdown-menu hidden min-w-[12rem] bg-white dark:bg-slate-800 shadow-xl border border-slate-100 dark:border-slate-700 rounded-xl overflow-hidden" aria-labelledby="alert-more-{{ $alert->id }}">
                                                    <div class="p-1.5 text-start">
                                                        {{-- Toggle Quick Action --}}
                                                        <button type="button"
                                                            class="jf-toggle-status-btn w-full flex items-center gap-2 px-3 py-2 text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 rounded-lg transition-all"
                                                            data-id="{{ $alert->id }}"
                                                            data-is-active="{{ $alert->is_active ? '1' : '0' }}"
                                                            data-update-url="{{ route('applicant.job-alerts.update', $alert) }}"
                                                            data-name="{{ $alert->name }}"
                                                            data-keywords="{{ $alert->keywords }}"
                                                            data-location="{{ $alert->location }}"
                                                            data-category="{{ $alert->category }}"
                                                            data-employment-type="{{ $alert->employment_type }}"
                                                            data-remote-type="{{ $alert->remote_type }}"
                                                            data-frequency="{{ $alert->frequency }}"
                                                            data-email-enabled="{{ $alert->email_enabled ? '1' : '0' }}">
                                                            <i class="{{ $alert->is_active ? 'ri-pause-circle-line' : 'ri-play-circle-line' }} text-lg"></i>
                                                            {{ $alert->is_active ? 'Pause Alert' : 'Resume Alert' }}
                                                        </button>

                                                        <div class="my-1 border-t border-slate-100 dark:border-slate-700"></div>

                                                        <form id="delete-form-{{ $alert->id }}" method="POST"
                                                            action="{{ route('applicant.job-alerts.destroy', $alert) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button"
                                                                class="btn-delete-alert w-full flex items-center gap-2 px-3 py-2 text-xs font-bold text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-500/10 rounded-lg transition-all"
                                                                data-form-id="delete-form-{{ $alert->id }}"
                                                                data-name="{{ $alert->name ?: 'Job Alert' }}">
                                                                <i class="ri-trash-line text-lg"></i> Delete Alert
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            {{ $alerts->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- Edit Job Alert Modal --}}
    {{-- ============================================================ --}}
    <div id="editAlertModal" class="hs-overlay hidden ti-modal">
        <div class="hs-overlay-open:mt-7 ti-modal-box mt-0 ease-out lg:!max-w-3xl lg:w-full m-3 lg:!mx-auto">
            <div class="ti-modal-content rounded-2xl shadow-2xl border-none">
                <div class="ti-modal-header p-6 border-b border-slate-50">
                    <div class="flex items-center gap-4">
                        <div class="jf-modal-icon">
                            <i class="ri-edit-box-line"></i>
                        </div>
                        <div>
                            <h6 class="modal-title font-black text-slate-800 text-lg" id="editAlertModalLabel">Edit Job Alert</h6>
                            <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-0.5">Alert Management</p>
                        </div>
                    </div>
                    <button type="button" class="hs-dropdown-toggle ti-modal-close-btn p-2 hover:bg-slate-100 rounded-lg transition-all"
                        data-hs-overlay="#editAlertModal">
                        <span class="sr-only">Close</span>
                        <i class="ri-close-line text-xl text-slate-400"></i>
                    </button>
                </div>
                <form id="editAlertForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="ti-modal-body p-6 space-y-6 max-h-[70vh] overflow-y-auto">

                        {{-- Section: Identification --}}
                        <div>
                            <div class="jf-form-section-label">Identity & Context</div>
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="form-label font-bold text-slate-700 mb-1.5 flex items-center gap-2">
                                        <i class="ri-text-spacing text-indigo-500"></i> Alert Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="name" id="edit_name" class="form-control rounded-xl py-2.5 border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium"
                                        placeholder="e.g. Remote VA Jobs" required>
                                </div>

                                <div>
                                    <label class="form-label font-bold text-slate-700 mb-1.5 flex items-center gap-2">
                                        <i class="ri-price-tag-3-line text-indigo-500"></i> Keywords <span class="text-slate-400 font-normal">(optional)</span>
                                    </label>
                                    <select name="keywords[]" id="edit_keywords" class="form-control" multiple="multiple">
                                        @php
                                            $suggestions = ['Virtual Assistant', 'Data Entry', 'Customer Service', 'Social Media', 'Graphic Design', 'Web Developer', 'SEO', 'Admin', 'Remote', 'Content Writer'];
                                        @endphp
                                        @foreach($suggestions as $s)
                                            <option value="{{ $s }}">{{ $s }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-[11px] text-slate-400 mt-2 font-medium">Type keywords and press Enter or select from suggestions.</p>
                                </div>
                            </div>
                        </div>

                        {{-- Section: Filters --}}
                        <div>
                            <div class="jf-form-section-label">Target Filters</div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="form-label font-bold text-slate-700 mb-1.5 flex items-center gap-2">
                                        <i class="ri-map-pin-2-line text-slate-400"></i> Location
                                    </label>
                                    <select name="location" id="edit_location" class="form-select rounded-xl py-2.5 border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium">
                                        <option value="">Any location</option>
                                        @foreach($alerts->first() ? \App\Models\DropdownOption::getOptionsCollection(\App\Models\DropdownOption::TYPE_LOCATION) : collect() as $opt)
                                            <option value="{{ $opt->value }}">{{ $opt->label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label font-bold text-slate-700 mb-1.5 flex items-center gap-2">
                                        <i class="ri-folders-line text-slate-400"></i> Category
                                    </label>
                                    <select name="category" id="edit_category" class="form-select rounded-xl py-2.5 border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium">
                                        <option value="">Any category</option>
                                        @foreach(\App\Models\DropdownOption::getOptionsCollection(\App\Models\DropdownOption::TYPE_JOB_CATEGORY) as $opt)
                                            <option value="{{ $opt->value }}">{{ $opt->label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label font-bold text-slate-700 mb-1.5 flex items-center gap-2">
                                        <i class="ri-briefcase-line text-slate-400"></i> Employment
                                    </label>
                                    <select name="employment_type" id="edit_employment_type" class="form-select rounded-xl py-2.5 border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium">
                                        <option value="">Any</option>
                                        @foreach(\App\Models\DropdownOption::getOptionsCollection(\App\Models\DropdownOption::TYPE_EMPLOYMENT_TYPE) as $opt)
                                            <option value="{{ $opt->value }}">{{ $opt->label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label font-bold text-slate-700 mb-1.5 flex items-center gap-2">
                                        <i class="ri-remote-control-line text-slate-400"></i> Remote
                                    </label>
                                    <select name="remote_type" id="edit_remote_type" class="form-select rounded-xl py-2.5 border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium">
                                        <option value="">Any</option>
                                        @foreach(\App\Models\DropdownOption::getOptionsCollection(\App\Models\DropdownOption::TYPE_REMOTE_TYPE) as $opt)
                                            <option value="{{ $opt->value }}">{{ $opt->label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Section: Settings --}}
                        <div>
                            <div class="jf-form-section-label">Preferences</div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="form-label font-bold text-slate-700 mb-1.5 flex items-center gap-2">
                                        <i class="ri-refresh-line text-slate-400"></i> Frequency
                                    </label>
                                    <select name="frequency" id="edit_frequency" class="form-select rounded-xl py-2.5 border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium" required>
                                        <option value="daily">Daily</option>
                                        <option value="weekly">Weekly</option>
                                    </select>
                                </div>
                                <div class="flex flex-col gap-3 justify-center md:pt-4">
                                    <div class="flex items-center gap-3">
                                        <input type="checkbox" name="email_enabled" value="1" class="form-check-input w-5 h-5 rounded-md border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                            id="edit_email_enabled">
                                        <label class="text-sm font-bold text-slate-700 cursor-pointer" for="edit_email_enabled">Send email notifications</label>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <input type="checkbox" name="is_active" value="1" class="form-check-input w-5 h-5 rounded-md border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                            id="edit_is_active">
                                        <label class="text-sm font-bold text-slate-700 cursor-pointer" for="edit_is_active">Alert is active</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="ti-modal-footer p-6 border-t border-slate-50 flex items-center justify-end gap-3">
                        <button type="button" class="jf-btn-secondary px-6 py-2.5 rounded-xl font-bold transition-all text-sm"
                            data-hs-overlay="#editAlertModal">Cancel</button>
                        <button type="submit" class="jf-btn-primary px-8 py-2.5 rounded-xl text-white font-black shadow-lg transition-all text-sm jf-btn-pulse">
                            <i class="ri-save-line me-1"></i> Update Alert
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- Scripts --}}
    {{-- ============================================================ --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* ── Page Background ── */
        :is([data-theme-mode="dark"], .dark, [data-bs-theme="dark"], html.dark) body {
            background: #1f1f1f !important;
        }

        .select2-container .select2-selection--multiple {
            min-height: 48px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 4px 8px;
            transition: all 0.2s ease;
        }
        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            border: none;
            color: #fff;
            border-radius: 8px;
            padding: 4px 12px;
            font-size: 0.75rem;
            font-weight: 700;
            margin-top: 4px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: rgba(255, 255, 255, 0.8);
            margin-right: 8px;
            border: none;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: #fff;
            background: transparent;
        }

        /* Modal Header Enhancements */
        .jf-modal-icon {
            width: 40px;
            height: 40px;
            background: rgba(99, 102, 241, 0.1);
            color: #6366f1;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }
        .jf-form-section-label {
            font-size: 0.65rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #94a3b8;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .jf-form-section-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #f1f5f9;
        }

        /* Success Pulse */
        @keyframes jf-pulse-indigo {
            0% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.4); }
            70% { box-shadow: 0 0 0 12px rgba(79, 70, 229, 0); }
            100% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0); }
        }
        .jf-btn-pulse { animation: jf-pulse-indigo 2s infinite; }

        /* Scrollable Modal Body */
        .ti-modal-body {
            max-height: 70vh;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #e2e8f0 transparent;
        }

        /* Select2 Dropdown Position Fix */
        .select2-container--open {
            z-index: 10001 !important;
        }

        /* Explicit Button Styles (Fix Visibility) */
        .jf-btn-primary {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%) !important;
            color: #ffffff !important;
            border: none !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .jf-btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.4);
        }

        .jf-btn-secondary {
            background: #f8fafc !important;
            color: #64748b !important;
            border: 1px solid #e2e8f0 !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .jf-btn-secondary:hover {
            background: #f1f5f9 !important;
            color: #1e293b !important;
        }

        .jf-header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 0.25rem;
            justify-content: flex-end;
            position: absolute;
            bottom: 0.85rem;
            right: 0.85rem;
        }

        :is([data-theme-mode="dark"], .dark) .jf-meta-item {
            color: #94a3b8 !important;
        }

        /* ── Elite SaaS Overall Table Overhaul (Interactive) ── */
        .jf-alert-row {
            background: #ffffff;
            border: 1px solid var(--cd-border);
            border-radius: 20px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            display: block;
        }

        .jf-alert-row:hover {
            transform: translateY(-6px) scale(1.01);
            border-color: #6366f1 !important;
            box-shadow: 0 25px 50px -12px rgba(99, 102, 241, 0.15);
            background: linear-gradient(135deg, #ffffff 0%, #f9faff 100%) !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-alert-row:hover {
            background: linear-gradient(135deg, #1e293b 0%, #1e1b4b 100%) !important;
            border-color: #818cf8 !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .jf-alert-icon-sq {
            width: 54px;
            height: 54px;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: #ffffff;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.3);
            transition: all 0.4s;
            border: 1px solid rgba(255,255,255,0.1);
        }

        .jf-alert-row:hover .jf-alert-icon-sq {
            transform: scale(1.1) rotate(-8deg);
            box-shadow: 0 15px 30px -5px rgba(99, 102, 241, 0.4);
        }

        .jf-alert-row:hover .jf-alert-icon-sq i {
            animation: bell-wiggle 0.5s ease infinite;
        }

        @keyframes bell-wiggle {
            0%, 100% { transform: rotate(0); }
            25% { transform: rotate(15deg); }
            75% { transform: rotate(-15deg); }
        }

        /* Status Badge Enhancements (Premium SaaS) */
        .jf-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 30px;
            font-size: 0.65rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 4px 14px;
        }

        .jf-status-active {
            background: rgba(16, 185, 129, 0.08);
            color: #059669;
            border: 1px solid rgba(16, 185, 129, 0.15);
            position: relative;
            padding-left: 1.65rem !important;
        }

        .jf-status-paused {
            background: rgba(100, 116, 139, 0.08);
            color: #64748b;
            border: 1px solid rgba(100, 116, 139, 0.15);
            position: relative;
            padding-left: 1.25rem !important;
        }

        .jf-status-active::after {
            content: '';
            position: absolute;
            left: 10px; top: 50%; width: 7px; height: 7px;
            background: #10b981;
            border-radius: 50%;
            transform: translateY(-50%);
            box-shadow: 0 0 0 rgba(16, 185, 129, 0.4);
            animation: status-pulse 2s infinite;
        }

        .jf-status-paused::before {
            content: '';
            position: absolute;
            left: 10px; top: 50%; width: 6px; height: 6px;
            background: #94a3b8;
            border-radius: 50%;
            transform: translateY(-50%);
            opacity: 0.6;
        }

        @keyframes status-pulse {
            0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
            100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        /* Grid info */
        .jf-metadata-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
            gap: 10px;
            margin-top: 1rem;
        }

        .jf-pill {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 5px 12px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 700;
            color: #64748b;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s;
        }

        .jf-alert-row:hover .jf-pill {
            background: #ffffff;
            border-color: #6366f1;
            color: #6366f1;
            transform: scale(1.02);
        }

        :is([data-theme-mode="dark"], .dark) .jf-pill {
            background: rgba(255,255,255,0.03);
            border-color: rgba(255,255,255,0.08);
            color: #94a3b8;
        }

        .jf-meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.75rem;
            color: var(--cd-text-muted);
            font-weight: 600;
        }
        .jf-meta-item i { font-size: 0.9rem; }

        /* ── Elite SaaS Action Buttons ── */
        .jf-primary-action {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%) !important;
            color: #ffffff !important;
            border: none !important;
            border-radius: 14px;
            font-size: 0.8rem;
            font-weight: 800;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.25);
            cursor: pointer;
        }

        .jf-primary-action:hover {
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.35);
            filter: brightness(1.1);
        }

        .jf-primary-action i { font-size: 1.1rem; }

        .ti-dropdown-toggle {
            width: 42px;
            height: 42px;
            border-radius: 14px !important;
            background: #f8fafc !important;
            border: 1px solid #e2e8f0 !important;
            color: #64748b !important;
            transition: all 0.3s !important;
        }

        .jf-alert-row:hover .ti-dropdown-toggle {
            background: #ffffff !important;
            border-color: #6366f1 !important;
            color: #6366f1 !important;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.1);
        }

        :is([data-theme-mode="dark"], .dark) .ti-dropdown-toggle {
            background: rgba(255,255,255,0.05) !important;
            border-color: rgba(255,255,255,0.1) !important;
            color: #94a3b8 !important;
        }

        :is([data-theme-mode="dark"], .dark) hr { border-top-color: rgba(255, 255, 255, 0.08) !important; }

        :is([data-theme-mode="dark"], .dark) .jf-header-section { border-bottom-color: rgba(255,255,255,0.08) !important; background: rgb(30, 32, 35) !important; }
        :is([data-theme-mode="dark"], .dark) .jf-header-title { color: #f8fafc !important; }
        :is([data-theme-mode="dark"], .dark) .jf-header-desc { color: #94a3b8 !important; }
        :is([data-theme-mode="dark"], .dark) .jf-context-label { color: #ffffff !important; }

        :is([data-theme-mode="dark"], .dark) .jf-alert-row {
            background: #202124 !important;
            border-color: #303134 !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-alert-row:hover {
            background: #26272b !important;
            border-color: #818cf8 !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-status-badge {
            background: rgba(99, 102, 241, 0.1) !important;
            color: #a5b4fc !important;
            border-color: rgba(99, 102, 241, 0.2) !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-status-active {
            background: rgba(16, 185, 129, 0.1) !important;
            color: #10b981 !important;
            border-color: rgba(16, 185, 129, 0.2) !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-status-paused {
            background: rgba(148, 163, 184, 0.1) !important;
            color: #cbd5e1 !important;
            border-color: rgba(148, 163, 184, 0.2) !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-alert-icon-sq {
            background: #2b2c30 !important;
            border-color: #303134 !important;
            color: #cbd5e1 !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-alert-row:hover .jf-alert-icon-sq {
            background: rgba(99, 102, 241, 0.15) !important;
            color: #818cf8 !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-pill {
            background: rgba(148, 163, 184, 0.1) !important;
            color: #cbd5e1 !important;
            border-color: rgba(148, 163, 184, 0.2) !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-pill:hover {
            border-color: #6366f1 !important;
            color: #818cf8 !important;
            background: rgba(99, 102, 241, 0.1) !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-meta-item {
            color: #94a3b8 !important;
        }

        @media (max-width: 992px) {
            .jf-header-section { flex-direction: column; align-items: flex-start; gap: 1rem; position: relative !important; padding-bottom: 1.5rem !important; }
            .jf-header-title { font-size: 1.875rem; }
            .jf-header-actions {
                position: static !important;
                width: 100% !important;
                display: flex !important;
                flex-direction: row !important;
                gap: 0.75rem !important;
                margin-top: 1rem !important;
                justify-content: center !important;
            }
            .jf-header-actions button, .jf-header-actions a {
                flex: 1 !important;
                justify-content: center !important;
                white-space: nowrap !important;
                font-size: 0.75rem !important;
                padding-left: 0.5rem !important;
                padding-right: 0.5rem !important;
            }
        }

        /* ── Extra Tight Mobile Optimization ── */
        @media (max-width: 540px) {
            .jf-header-actions { gap: 0.5rem !important; }
            .jf-header-actions button, .jf-header-actions a { padding: 0.75rem 0.25rem !important; border-radius: 12px !important; }
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // ── Select2 for keywords inside modal ─────────────────────────
            if (jQuery('#edit_keywords').length > 0) {
                jQuery('#edit_keywords').select2({
                    tags: true,
                    placeholder: "e.g. virtual assistant, VA, remote",
                    allowClear: true,
                    width: '100%',
                    dropdownParent: jQuery('#editAlertModal').find('.ti-modal-content')
                });
            }

            // Reinforce Select2 clickability within Headless UI / Preline
            jQuery(document).on('mousedown', '.select2-container--open', function(e) {
                e.stopPropagation();
            });

            jQuery(document).on('select2:open', function() {
                setTimeout(function() {
                    document.querySelector('.select2-search__field')?.focus();
                }, 50);
            });

            // ── Open Edit Modal & populate fields ─────────────────────────
            document.querySelectorAll('.btn-edit-alert').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var data = {
                        id: btn.dataset.id,
                        name: btn.dataset.name,
                        keywords: btn.dataset.keywords,
                        location: btn.dataset.location,
                        category: btn.dataset.category,
                        employmentType: btn.dataset.employmentType,
                        remoteType: btn.dataset.remoteType,
                        frequency: btn.dataset.frequency,
                        emailEnabled: btn.dataset.emailEnabled,
                        isActive: btn.dataset.isActive,
                        updateUrl: btn.dataset.updateUrl,
                    };

                    // Set form action
                    document.getElementById('editAlertForm').action = data.updateUrl;

                    // Simple fields
                    document.getElementById('edit_name').value = data.name;
                    document.getElementById('edit_location').value = data.location;
                    document.getElementById('edit_category').value = data.category;
                    document.getElementById('edit_employment_type').value = data.employmentType;
                    document.getElementById('edit_remote_type').value = data.remoteType;
                    document.getElementById('edit_frequency').value = data.frequency;

                    // Checkboxes
                    document.getElementById('edit_email_enabled').checked = data.emailEnabled === '1';
                    document.getElementById('edit_is_active').checked = data.isActive === '1';

                    // Keywords via Select2 — clear then set
                    var $kw = jQuery('#edit_keywords');
                    $kw.val(null).trigger('change'); // clear
                    if (data.keywords && data.keywords.trim() !== '') {
                        var kwArray = data.keywords.split(',').map(s => s.trim()).filter(Boolean);
                        kwArray.forEach(function (kw) {
                            // Add option if it doesn't exist yet
                            if ($kw.find('option[value="' + kw + '"]').length === 0) {
                                var opt = new Option(kw, kw, true, true);
                                $kw.append(opt);
                            } else {
                                var vals = $kw.val() || [];
                                vals.push(kw);
                                $kw.val(vals);
                            }
                        });
                        $kw.trigger('change');
                    }

                    // Open modal via Headless UI overlay
                    HSOverlay.open(document.getElementById('editAlertModal'));
                });
            });

            // ── SweetAlert2 Delete Confirmation ──────────────────────────
            document.querySelectorAll('.btn-delete-alert').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var formId = btn.dataset.formId;
                    var alertName = btn.dataset.name;

                    Swal.fire({
                        title: 'Delete Alert?',
                        html: 'Are you sure you want to delete <strong>' + alertName + '</strong>?<br><span class="text-sm text-gray-500">This action cannot be undone.</span>',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: '<i class="bi bi-trash me-1"></i> Yes, Delete',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true,
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            document.getElementById(formId).submit();
                        }
                    });
                });
            });

            // ── Select2 Focus Trap Bypass ──────────────────────────────
            // This prevents the modal from stealing focus back from Select2
            jQuery(document).on('focusin', function (e) {
                if (jQuery(e.target).closest(".select2-container").length ||
                    jQuery(e.target).closest(".select2-search__field").length ||
                    jQuery(e.target).closest(".select2-dropdown").length) {
                    return;
                }
            });

            // ── AJAX Toggle for Alert Status (Buttons & Badges) ─────────────────────
            jQuery(document).on('click', '.jf-toggle-status-btn', function() {
                var $btn = jQuery(this);
                var currentlyActive = $btn.data('is-active') == '1';
                var nextStatus = currentlyActive ? 0 : 1;

                var data = {
                    _token: '{{ csrf_token() }}',
                    _method: 'PUT',
                    is_active: nextStatus,
                    name: $btn.data('name'),
                    keywords: $btn.data('keywords'),
                    location: $btn.data('location'),
                    category: $btn.data('category'),
                    employment_type: $btn.data('employment-type'),
                    remote_type: $btn.data('remote-type'),
                    frequency: $btn.data('frequency'),
                    email_enabled: $btn.data('email-enabled')
                };

                // Show loading state
                var originalHtml = $btn.html();
                $btn.html('<i class="ri-loader-4-line animate-spin text-lg"></i> Updating...');
                $btn.prop('disabled', true);

                jQuery.ajax({
                    url: $btn.data('update-url'),
                    type: 'POST',
                    data: data,
                    success: function() {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: nextStatus ? 'Alert Resumed' : 'Alert Paused',
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            window.location.reload(); // Simple way to update UI for now
                        });
                    },
                    error: function() {
                        $btn.html(originalHtml);
                        $btn.prop('disabled', false);
                        Swal.fire({
                            icon: 'error',
                            title: 'Update Failed',
                            text: 'We couldn\'t update the alert status. Please try again.'
                        });
                    }
                });
            });
        });
    </script>

    {{-- ============================================================ --}}
    {{-- Create Job Alert Modal --}}
    {{-- ============================================================ --}}
    <div id="createAlertModal" class="hs-overlay hidden ti-modal">
        <div class="hs-overlay-open:mt-7 ti-modal-box mt-0 ease-out lg:!max-w-3xl lg:w-full m-3 lg:!mx-auto">
            <div class="ti-modal-content rounded-2xl shadow-2xl border-none">
                <div class="ti-modal-header p-6 border-b border-slate-50">
                    <div class="flex items-center gap-4">
                        <div class="jf-modal-icon">
                            <i class="ri-notification-3-line"></i>
                        </div>
                        <div>
                            <h6 class="modal-title font-black text-slate-800 text-lg">Create Job Alert</h6>
                            <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-0.5">Stay Informed</p>
                        </div>
                    </div>
                    <button type="button" class="hs-dropdown-toggle ti-modal-close-btn p-2 hover:bg-slate-100 rounded-lg transition-all"
                        data-hs-overlay="#createAlertModal">
                        <span class="sr-only">Close</span>
                        <i class="ri-close-line text-xl text-slate-400"></i>
                    </button>
                </div>
                <form method="POST" action="{{ route('applicant.job-alerts.store') }}">
                    @csrf
                    <div class="ti-modal-body p-6 space-y-6 max-h-[70vh] overflow-y-auto">

                        {{-- Section: Identification --}}
                        <div>
                            <div class="jf-form-section-label">Identity & Context</div>
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="form-label font-bold text-slate-700 mb-1.5 flex items-center gap-2">
                                        <i class="ri-text-spacing text-indigo-500"></i> Alert Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="name" id="create_name" class="form-control rounded-xl py-2.5 border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium"
                                        value="{{ old('name') }}" placeholder="e.g. Remote VA Jobs" required>
                                    @error('name')
                                        <p class="text-danger text-sm mt-1 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="form-label font-bold text-slate-700 mb-1.5 flex items-center gap-2">
                                        <i class="ri-price-tag-3-line text-indigo-500"></i> Keywords <span class="text-slate-400 font-normal">(optional)</span>
                                    </label>
                                    <select name="keywords[]" id="create_keywords" class="form-control" multiple="multiple">
                                        @php
                                            $oldKw = old('keywords', []);
                                            $oldKwArray = is_array($oldKw) ? $oldKw : array_map('trim', explode(',', $oldKw));
                                            $suggestions = ['Virtual Assistant', 'Data Entry', 'Customer Service', 'Social Media', 'Graphic Design', 'Web Developer', 'SEO', 'Admin', 'Remote', 'Content Writer'];
                                        @endphp
                                        @foreach($suggestions as $s)
                                            @if(!in_array($s, $oldKwArray))
                                                <option value="{{ $s }}">{{ $s }}</option>
                                            @endif
                                        @endforeach
                                        @foreach($oldKwArray as $kw)
                                            @if(!empty($kw))
                                                <option value="{{ $kw }}" selected>{{ $kw }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <p class="text-[11px] text-slate-400 mt-2 font-medium">Type keywords and press Enter or select from suggestions.</p>
                                    @error('keywords')
                                        <p class="text-danger text-sm mt-1 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Section: Filters --}}
                        <div>
                            <div class="jf-form-section-label">Target Filters</div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="form-label font-bold text-slate-700 mb-1.5 flex items-center gap-2">
                                        <i class="ri-map-pin-2-line text-slate-400"></i> Location
                                    </label>
                                    <select name="location" id="create_location" class="form-select rounded-xl py-2.5 border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium">
                                        <option value="">Any location</option>
                                        @foreach($dropdownOptions['locations'] ?? [] as $opt)
                                            <option value="{{ $opt->value }}" @selected(old('location') == $opt->value)>
                                                {{ $opt->label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label font-bold text-slate-700 mb-1.5 flex items-center gap-2">
                                        <i class="ri-folders-line text-slate-400"></i> Category
                                    </label>
                                    <select name="category" id="create_category" class="form-select rounded-xl py-2.5 border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium">
                                        <option value="">Any category</option>
                                        @foreach($dropdownOptions['categories'] ?? [] as $opt)
                                            <option value="{{ $opt->value }}" @selected(old('category') == $opt->value)>
                                                {{ $opt->label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label font-bold text-slate-700 mb-1.5 flex items-center gap-2">
                                        <i class="ri-briefcase-line text-slate-400"></i> Employment
                                    </label>
                                    <select name="employment_type" id="create_employment_type" class="form-select rounded-xl py-2.5 border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium">
                                        <option value="">Any</option>
                                        @foreach($dropdownOptions['employment_types'] ?? [] as $opt)
                                            <option value="{{ $opt->value }}" @selected(old('employment_type') == $opt->value)>
                                                {{ $opt->label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label font-bold text-slate-700 mb-1.5 flex items-center gap-2">
                                        <i class="ri-remote-control-line text-slate-400"></i> Remote
                                    </label>
                                    <select name="remote_type" id="create_remote_type" class="form-select rounded-xl py-2.5 border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium">
                                        <option value="">Any</option>
                                        @foreach($dropdownOptions['remote_types'] ?? [] as $opt)
                                            <option value="{{ $opt->value }}" @selected(old('remote_type') == $opt->value)>
                                                {{ $opt->label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Section: Settings --}}
                        <div>
                            <div class="jf-form-section-label">Preferences</div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="form-label font-bold text-slate-700 mb-1.5 flex items-center gap-2">
                                        <i class="ri-refresh-line text-slate-400"></i> Frequency
                                    </label>
                                    <select name="frequency" id="create_frequency" class="form-select rounded-xl py-2.5 border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium" required>
                                        <option value="daily" @selected(old('frequency', 'daily') == 'daily')>Daily</option>
                                        <option value="weekly" @selected(old('frequency') == 'weekly')>Weekly</option>
                                    </select>
                                </div>
                                <div class="flex items-center md:pt-4">
                                    <div class="flex items-center gap-3">
                                        <input type="checkbox" name="email_enabled" value="1" class="form-check-input w-5 h-5 rounded-md border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                            id="create_email_enabled" @checked(old('email_enabled', true))>
                                        <label class="text-sm font-bold text-slate-700 cursor-pointer" for="create_email_enabled">Send email notifications</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="ti-modal-footer p-6 border-t border-slate-50 flex items-center justify-end gap-3">
                        <button type="button" class="jf-btn-secondary px-6 py-2.5 rounded-xl font-bold transition-all text-sm"
                            data-hs-overlay="#createAlertModal">Cancel</button>
                        <button type="submit" class="jf-btn-primary px-8 py-2.5 rounded-xl text-white font-black shadow-lg transition-all text-sm jf-btn-pulse">
                            <i class="ri-notification-badge-line me-1"></i> Create Alert
                        </button>
                    </div>
                </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Select2 for create modal keywords
            if (jQuery('#create_keywords').length > 0) {
                jQuery('#create_keywords').select2({
                    tags: true,
                    placeholder: "e.g. virtual assistant, VA, remote",
                    allowClear: true,
                    width: '100%',
                    dropdownParent: jQuery('#createAlertModal').find('.ti-modal-content')
                });
            }

            // Open create modal from both buttons
            ['btnOpenCreateModal', 'btnOpenCreateModalEmpty'].forEach(function (id) {
                var btn = document.getElementById(id);
                if (btn) {
                    btn.addEventListener('click', function () {
                        HSOverlay.open(document.getElementById('createAlertModal'));
                    });
                }
            });

            @if($errors->any())
                // Re-open create modal on validation errors
                HSOverlay.open(document.getElementById('createAlertModal'));
            @endif
        });
    </script>

    </div> {{-- Close max-w-7xl mx-auto --}}

</x-app-layout>
