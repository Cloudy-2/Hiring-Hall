<x-app-layout>

    <x-slot name="pageTitle">Saved Applicants</x-slot>
    <x-slot name="url_1">{"link": "/employer/dashboard", "text": "Dashboard"}</x-slot>
    <x-slot name="active">Saved Applicants</x-slot>

    @include('employers.partials.employer-styles')

    @php
        $logoBgs = ['#4f46e5','#0d9488','#dc2626','#7c3aed','#ea580c','#0284c7','#db2777'];
        $availabilityColors = [
            'full_time'  => ['class' => 'cd-status-success'],
            'part_time'  => ['class' => 'cd-status-warning'],
            'freelance'  => ['class' => 'cd-status-info'],
            'contract'   => ['class' => 'cd-status-gray'],
            'internship' => ['class' => 'cd-status-info'],
        ];
    @endphp

    <x-modern-header chip="Saved Applicants" title="Manage Your Saved Applicants" desc='Manage your saved applicants'>
        <x-slot name="actions">
            <a href="{{ route('applicants') }}" class="cd-hero-btn cd-hero-btn-primary"><i class="ri-user-search-line"></i> Browse Applicants</a>
            <a href="{{ route('employer.dashboard') }}" class="cd-hero-btn cd-hero-btn-ghost"><i class="ri-dashboard-line"></i> Dashboard</a>
        </x-slot>
    </x-modern-header>      

    <div class="grid grid-cols-12 gap-x-5 gap-y-4 mx-auto pb-6 sm:px-6 lg:px-8">

        {{-- ═══ Saved Applicants Grid ═══ --}}
        <div class="col-span-12" id="wt-saved-grid">
            <div class="cd-section">
                <div class="cd-section-head">
                    <span class="cd-section-label"><i class="ri-bookmark-fill"></i> Saved Applicants</span>
                    <span class="text-xs text-gray-400">{{ $savedApplicants->total() }} saved</span>
                </div>

                @if($savedApplicants->isEmpty())
                    <div class="cd-empty">
                        <i class="ri-bookmark-line"></i>
                        <p>You haven't saved any applicants yet.</p>
                        <a href="{{ route('applicants') }}" class="cd-btn cd-btn-primary">Browse Applicants</a>
                    </div>
                @else
                    <div class="grid grid-cols-12 gap-6">
                        @foreach($savedApplicants as $idx => $saved)
                            @php 
                                $profile = $saved->applicantProfile; 
                                if(!$profile) continue;
                                $displayName = $profile->display_name ?? $profile->user?->name ?? 'Applicant';
                                $jobTitle = $profile->job_title ?? $profile->title ?? $profile->headline ?? 'Professional';
                                $avatarBgs = ['#6366f1','#0ea5e9','#ec4899','#f59e0b','#10b981','#8b5cf6','#06b6d4','#3b82f6'];
                                $avatarBg  = $avatarBgs[abs(crc32($displayName)) % count($avatarBgs)];
                                $initial   = strtoupper(substr($displayName, 0, 1));
                            @endphp
                            <div class="xl:col-span-4 md:col-span-6 col-span-12"
                                 style="animation-delay: {{ $loop->index * 0.05 }}s">
                                <div class="cd-job-card h-full flex flex-col">
                                    <div class="cd-job-card-body p-6 pb-2">
                                        {{-- Top row: avatar + unsave --}}
                                        <div class="cd-job-card-top !mb-5">
                                            <div style="display:flex;align-items:center;gap:1.15rem;min-width:0;flex:1">
                                                @if($profile->user?->profile_photo_url)
                                                    <div class="relative">
                                                        <img src="{{ $profile->user->profile_photo_url }}" alt="{{ $displayName }}"
                                                            class="h-14 w-14 flex-shrink-0 rounded-2xl object-cover shadow-lg border-2 border-white dark:border-slate-800">
                                                        <div class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full bg-emerald-500 border-2 border-white dark:border-slate-800 shadow-sm" title="Available"></div>
                                                    </div>
                                                @else
                                                    <div class="cd-job-avatar" style="background: {{ $avatarBg }}; box-shadow: 0 10px 20px {{ $avatarBg }}33; width: 56px; height: 56px; border-radius: 18px;">
                                                        {{ $initial }}
                                                    </div>
                                                @endif
                                                <div style="min-width:0;flex:1">
                                                    <h3 class="text-base font-bold text-gray-900 dark:text-white truncate" title="{{ $displayName }}">
                                                        {{ $displayName }}
                                                    </h3>
                                                    <div class="text-xs font-semibold text-primary/80 dark:text-primary-light/80 line-clamp-1">{{ $jobTitle }}</div>
                                                </div>
                                            </div>
                                            
                                            {{-- Unsave Toggle --}}
                                            <button type="button"
                                                data-profile-id="{{ $profile->id }}"
                                                data-url="{{ route('employer.saved-applicants.destroy', $profile) }}"
                                                class="unsave-btn cd-more-btn !border-red-100 !bg-red-50 !text-red-500 hover:!bg-red-500 hover:!text-white transition-all shadow-sm"
                                                title="Remove from saved">
                                                <i class="ri-bookmark-fill"></i>
                                            </button>
                                        </div>

                                        {{-- Rating Section --}}
                                        @if(($profile->rating_count ?? 0) > 0)
                                            <div class="mb-4 flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-amber-50/50 dark:bg-amber-500/5 border border-amber-100/50 dark:border-amber-500/10 w-fit">
                                                <span class="flex items-center text-amber-500 gap-0.5">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="ri-star-{{ $i <= floor($profile->rating ?? 0) ? 'fill' : 'line' }}" style="font-size:0.8rem"></i>
                                                    @endfor
                                                </span>
                                                <span class="text-[10px] font-bold text-amber-700 dark:text-amber-400">({{ $profile->rating_count }})</span>
                                            </div>
                                        @endif

                                        {{-- Meta Row --}}
                                        <div class="cd-job-meta !mb-4">
                                            @if($profile->location)
                                                <span><i class="ri-map-pin-line"></i> {{ $profile->location }}</span>
                                            @endif
                                            @if($profile->years_experience !== null)
                                                <span><i class="ri-briefcase-line"></i> {{ $profile->years_experience }} yr{{ $profile->years_experience != 1 ? 's' : '' }} exp</span>
                                            @endif
                                            <span><i class="ri-calendar-line"></i> Saved {{ $saved->created_at?->diffForHumans() }}</span>
                                        </div>

                                        {{-- Availability & About --}}
                                        <div class="flex flex-col gap-3">
                                            @if($profile->availability)
                                                <div class="cd-info-chip success !rounded-lg !py-1 !px-2.5 w-fit">
                                                    <i class="ri-time-line"></i>
                                                    {{ Str::headline(str_replace('_', ' ', $profile->availability)) }}
                                                </div>
                                            @endif
                                            
                                            @if($profile->about)
                                                <p class="text-[0.8rem] text-gray-500 dark:text-gray-400 line-clamp-2 leading-relaxed">
                                                    {{ $profile->about }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Footer --}}
                                    <div class="cd-job-card-footer mt-auto p-6 pt-2">
                                        <a href="{{ route('applicants.details', ['applicant' => $profile->id]) }}" class="cd-view-role-btn">
                                            <span>View Profile</span> <i class="ri-arrow-right-line"></i>
                                        </a>
                                        @if($profile->cv_path)
                                            <a href="{{ route('applicants.download-cv', $profile) }}" class="cd-more-btn" title="Download CV">
                                                <i class="ri-file-text-line"></i>
                                            </a>
                                        @else
                                            <button type="button" class="cd-more-btn opacity-50 cursor-not-allowed" title="CV Not Available">
                                                <i class="ri-file-close-line"></i>
                                            </button>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        @endforeach
                    </div>


                    <div class="cd-pagination mt-4">{{ $savedApplicants->links() }}</div>
                @endif
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var token = document.querySelector('meta[name="csrf-token"]')?.content;
            var focusReturnEl = null;

            function rememberFocus(el) {
                focusReturnEl = el instanceof HTMLElement ? el : document.activeElement;
            }

            function restoreFocus() {
                if (focusReturnEl && typeof focusReturnEl.focus === 'function') {
                    setTimeout(function () { focusReturnEl.focus(); }, 0);
                }
            }

            document.querySelectorAll('.unsave-btn').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    rememberFocus(btn);
                    Swal.fire({
                        icon: 'question',
                        title: 'Remove from saved?',
                        showCancelButton: true,
                        confirmButtonText: 'Remove',
                        confirmButtonColor: '#dc2626'
                    }).then(function (r) {
                        if (!r.isConfirmed) {
                            restoreFocus();
                            return;
                        }
                        fetch(btn.dataset.url, {
                            method: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' }
                        }).then(function () {
                            btn.closest('.col-span-12, .md\\:col-span-6, .xl\\:col-span-4').remove();
                        }).catch(function () {
                            Swal.fire({ icon: 'error', title: 'Error' });
                            restoreFocus();
                        });
                    });
                });
            });
        });
    </script>

    @include('candidates.partials.walkthrough', [
        'wtSteps' => [
            ['target' => 'wt-hero',      'title' => 'Saved Applicants',    'icon' => 'ri-bookmark-fill',      'body' => 'Welcome to your Saved Applicants page! This is your shortlist of talented applicants you\'ve bookmarked while browsing.', 'position' => 'bottom'],
            ['target' => 'wt-hero-nav',  'title' => 'Quick Navigation',    'icon' => 'ri-links-line',         'body' => 'Browse Applicants takes you to the full applicant search page where you can discover and save more talent. Dashboard takes you back to your command center.', 'position' => 'bottom'],
            ['target' => 'wt-saved-grid','title' => 'Applicant Cards',     'icon' => 'ri-layout-grid-line',   'body' => 'Each card shows an applicant\'s name, title, location, and key details. You can view their profile, see their resume, or remove them from your saved list.', 'position' => 'top'],
        ],
        'wtKey' => 'employer_saved_candidates_v2',
    ])

</x-app-layout>
