<x-app-layout>

    <x-slot name="pageTitle">Job Templates</x-slot>
    <x-slot name="url_1">{"link": "/employer/dashboard", "text": "Dashboard"}</x-slot>
    <x-slot name="active">Job Templates</x-slot>

    @include('employers.partials.employer-styles')

    <x-modern-header chip="Job Templates" title="Manage Your Job Templates"
        desc='Save and reuse job posting templates.'>
        <x-slot name="actions">
            <a href="{{ route('employer.templates.create') }}" class="cd-btn cd-btn-primary">
                <i class="ri-add-line"></i>
                <span>Create Template</span>
            </a>
            <a href="{{ route('employer.dashboard') }}" class="cd-btn cd-btn-secondary">
                <i class="ri-dashboard-line"></i>
                <span>Dashboard</span>
            </a>
        </x-slot>
    </x-modern-header>

    <div class="max-w-7xl mx-auto pb-6 sm:px-6 lg:px-8">
        {{-- ═══ Templates List ═══ --}}
        <div class="col-span-12" id="wt-templates-list">
            <div class="cd-section">
                <div class="cd-section-head">
                    <span class="cd-section-label"><i class="ri-file-list-3-fill"></i> Your Templates</span>
                    <span class="text-xs text-gray-400">{{ $templates->total() }} total</span>
                </div>

                @if (session('status'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            if (window.Swal) Swal.fire({ icon: 'success', title: 'Success', text: @json(session('status')), timer: 2500, showConfirmButton: false });
                        });
                    </script>
                @endif

                @if($templates->isEmpty())
                    <div class="cd-empty">
                        <i class="ri-file-list-3-line"></i>
                        <p>You haven't created any job templates yet.</p>
                        <p class="text-sm text-gray-500 mt-2">Templates help you quickly create job posts with pre-filled
                            information.</p>
                        <a href="{{ route('employer.templates.create') }}" class="cd-btn cd-btn-primary mt-4">Create Your
                            First Template</a>
                    </div>
                @else
                    <div class="grid grid-cols-12 gap-6">
                        @foreach($templates as $template)
                            @php
                                $templateName = $template->name;
                                $avatarBgs = ['#6366f1','#0ea5e9','#ec4899','#f59e0b','#10b981','#8b5cf6','#06b6d4','#3b82f6'];
                                $avatarBg  = $avatarBgs[abs(crc32($templateName)) % count($avatarBgs)];
                                $initial   = strtoupper(substr($templateName, 0, 1));
                            @endphp
                            <div class="xl:col-span-4 md:col-span-6 col-span-12" 
                                 style="animation-delay: {{ $loop->index * 0.05 }}s">
                                <div class="cd-job-card h-full">
                                    <div class="cd-job-card-body p-6">
                                        {{-- Top Row: Avatar & Primary Info --}}
                                        <div class="cd-job-card-top !mb-5">
                                            <div style="display:flex;align-items:center;gap:1.15rem;min-width:0;flex:1">
                                                <div class="cd-job-avatar" style="background: {{ $avatarBg }}; box-shadow: 0 10px 20px {{ $avatarBg }}33; width: 56px; height: 56px; border-radius: 18px;">
                                                    <i class="ri-file-list-3-line" style="font-size: 1.5rem"></i>
                                                </div>
                                                <div style="min-width:0;flex:1">
                                                    <h3 class="text-base font-bold text-gray-900 dark:text-white truncate" title="{{ $templateName }}">
                                                        {{ $templateName }}
                                                    </h3>
                                                    @if($template->is_default)
                                                        <span class="inline-flex items-center text-[9px] font-bold text-amber-500 uppercase tracking-widest mt-0.5">
                                                            <i class="ri-star-fill me-1"></i> Default
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Title/Role Highlight --}}
                                        <div class="mb-5">
                                            <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1.5">Target Role</p>
                                            <p class="text-base font-bold text-gray-800 dark:text-gray-200">{{ $template->title ?? 'Unspecified Role' }}</p>
                                        </div>

                                        {{-- Modular Info Chips --}}
                                        <div class="cd-info-chip-group">
                                            @if($template->employment_type)
                                                <div class="cd-info-chip">
                                                    <i class="ri-briefcase-line"></i>
                                                    {{ Str::headline($template->employment_type) }}
                                                </div>
                                            @endif
                                            @if($template->location)
                                                <div class="cd-info-chip">
                                                    <i class="ri-map-pin-line"></i>
                                                    {{ Str::headline($template->location) }}
                                                </div>
                                            @endif
                                            @if($template->salary_min || $template->salary_max)
                                                <div class="cd-info-chip success">
                                                    <i class="ri-money-dollar-circle-line"></i>
                                                    @if($template->salary_min && $template->salary_max)
                                                        {{ number_format($template->salary_min/1000, 1) }}k-{{ number_format($template->salary_max/1000,1) }}k
                                                    @else
                                                        {{ number_format(($template->salary_min ?? $template->salary_max)/1000, 1) }}k+
                                                    @endif
                                                </div>
                                            @endif
                                            @if($template->category)
                                                <div class="cd-info-chip">
                                                    <i class="ri-price-tag-3-line"></i>
                                                    {{ $template->category }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Action Footer --}}
                                    <div class="cd-job-card-footer !pt-4 !border-dashed border-gray-100 dark:border-gray-800 p-6">
                                        <a href="{{ route('jobs.create', ['template' => $template->id]) }}"
                                            class="cd-view-role-btn">
                                            <i class="ri-flashlight-line"></i>
                                            <span>Use Template</span>
                                        </a>
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('employer.templates.edit', $template) }}"
                                                class="cd-more-btn" title="Edit Template">
                                                <i class="ri-edit-line"></i>
                                            </a>
                                            <button type="button" class="cd-more-btn !text-red-500 hover:!bg-red-50 hover:!border-red-200 template-delete-btn"
                                                data-template-id="{{ $template->id }}" 
                                                data-template-name="{{ $templateName }}"
                                                title="Delete Template">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>


                    <div class="cd-pagination mt-4">{{ $templates->links() }}</div>
                @endif
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let focusReturnEl = null;

            function rememberFocus(el) {
                focusReturnEl = el instanceof HTMLElement ? el : document.activeElement;
            }

            function restoreFocus() {
                if (focusReturnEl && typeof focusReturnEl.focus === 'function') {
                    setTimeout(function () { focusReturnEl.focus(); }, 0);
                }
            }

            document.querySelectorAll('.template-delete-btn').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    rememberFocus(btn);
                    const templateId = btn.dataset.templateId;
                    const templateName = btn.dataset.templateName;

                    Swal.fire({
                        icon: 'warning',
                        title: 'Delete Template?',
                        text: 'Are you sure you want to delete "' + templateName + '"? This cannot be undone.',
                        showCancelButton: true,
                        confirmButtonText: 'Delete',
                        confirmButtonColor: '#dc2626'
                    }).then(function (result) {
                        if (!result.isConfirmed) {
                            restoreFocus();
                            return;
                        }

                        fetch('/employer/templates/' + templateId, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        })
                            .then(res => res.json())
                            .then(() => {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => window.location.reload());
                            })
                            .catch(() => {
                                Swal.fire({ icon: 'error', title: 'Delete failed' });
                                restoreFocus();
                            });
                    });
                });
            });
        });
    </script>

</x-app-layout>