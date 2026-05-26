<x-app-layout
    :pageTitle="$job->title"
    :active="$job->title"
    :breadcrumbs="(auth()->check() && auth()->user()->role === 'employer') ? [['label' => 'My Jobs', 'url' => route('employer.jobs.index')]] : [['label' => 'Job Listing', 'url' => route('jobs')]]">

    @if(session('status'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.Swal) {
                Swal.fire({
                    icon: 'success',
                    title: 'Successfully Applied!',
                    text: 'Wait for the employer to review your application.',
                    confirmButtonColor: '#5c67f7',
                    confirmButtonText: 'OK'
                });
            }
        });
    </script>
    @endif

    <link rel="stylesheet" href="/assets/libs/swiper/swiper-bundle.min.css">

    <div class="grid grid-cols-12 gap-x-6 mt-4  mx-auto pb-6 sm:px-6 lg:px-8">
        <div class="xxl:col-span-9 col-span-12">
            <div class="box !bg-primary/10 dark:!bg-primary/10">
                <div class="box-body">
                    <div class="box overflow-hidden job-info-banner" style="background: linear-gradient(135deg, #0ea5e9 0%, #6366f1 50%, #8b5cf6 100%); background-size: cover;">
                    </div>
                    <div class="box job-info-data mb-2">
                        <div class="box-body">
                            <div class="flex flex-wrap items-top justify-between gap-2">
                                <div>
                                    <div class="flex flex-wrap gap-2">
                                        <div>
                                            <span class="avatar avatar-lg border p-1 dark:border-defaultborder/10">
                                                <img src="{{ $job->company?->logo_url ?? 'https://api.dicebear.com/7.x/shapes/svg?seed=' . urlencode($job->company?->name ?? 'Company') }}" alt="">
                                            </span>
                                        </div>
                                        <div>
                                            <h5 class="font-medium mb-0 flex items-center">
                                                <a href="javascript:void(0);" class="">
                                                    {{ $job->title }}
                                                </a>
                                            </h5>
                                            @guest
                                            <a href="javascript:void(0);" class="text-xs text-textmuted dark:text-textmuted/50">
                                                <i class="bi bi-building"></i>
                                                <span class="inline-block" aria-hidden="true" style="filter: blur(4px); -webkit-filter: blur(4px);">{{ $job->company?->name ?? 'Company' }}</span>
                                                <span class="sr-only">Company</span>
                                            </a>
                                            @else
                                            <a href="javascript:void(0);" class="text-xs text-textmuted dark:text-textmuted/50">
                                                <i class="bi bi-building"></i>
                                                {{ $job->company?->name ?? 'Company' }}
                                            </a>
                                            @endguest
                                            @if($job->company)
                                            <div class="flex items-center gap-1.5 mt-1 text-xs text-textmuted dark:text-textmuted/50">
                                                <span class="sr-only">Company rating:</span>
                                                <div class="company-rating-stars-detail inline-flex gap-0.5 {{ (auth()->check() && empty($isEmployerPreview)) ? 'cursor-pointer' : '' }}" data-rating="{{ $job->company->rating ?? 0 }}" data-rate-url="{{ route('companies.rate', ['company' => $job->company->id]) }}" data-can-rate="{{ (auth()->check() && empty($isEmployerPreview)) ? '1' : '0' }}">
                                                    @php $avgRating = $job->company->rating ?? 0; @endphp
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <=floor($avgRating))
                                                        <span class="star-btn" data-star="{{ $i }}" style="color:#f59e0b"><i class="bi bi-star-fill"></i></span>
                                                        @elseif($i - 0.5 <= $avgRating)
                                                            <span class="star-btn" data-star="{{ $i }}" style="color:#f59e0b"><i class="bi bi-star-half"></i></span>
                                                            @else
                                                            <span class="star-btn" data-star="{{ $i }}" style="color:#f59e0b"><i class="bi bi-star"></i></span>
                                                            @endif
                                                            @endfor
                                                </div>
                                                <span class="company-rating-count-detail">(<span class="rating-count-value">{{ $job->company->rating_count ?? 0 }}</span>)</span>
                                            </div>
                                            @endif
                                            <div class="flex mt-3">
                                                <div>
                                                    <p class="mb-1">
                                                        <i class="bi bi-geo-alt me-1"></i>
                                                        {{ $job->location ?? 'Remote' }}
                                                    </p>
                                                    <p>
                                                        <i class="bi bi-briefcase me-1"></i>
                                                        @if($job->experience_min_years || $job->experience_max_years)
                                                        {{ $job->experience_min_years ?? '?' }} - {{ $job->experience_max_years ?? '?' }} Yrs Experience
                                                        @else
                                                        Experience required
                                                        @endif
                                                    </p>
                                                </div>
                                                <div class="ms-4">
                                                    <p class="mb-1">
                                                        <i class="bi bi-coin me-1"></i>
                                                        @if($job->salary_min || $job->salary_max)
                                                        <span class="font-medium">
                                                            {{ $job->salary_currency ?? 'USD' }} {{ number_format($job->salary_min ?? 0) }}
                                                            - {{ number_format($job->salary_max ?? ($job->salary_min ?? 0)) }}
                                                        </span> / per month
                                                        @else
                                                        <span class="font-medium">Competitive pay</span>
                                                        @endif
                                                    </p>
                                                    <p>
                                                        <i class="bi bi-mortarboard me-1"></i>
                                                        {{ $job->company?->industry ? Str::headline($job->company->industry) : 'Relevant field' }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="popular-tags mt-3">
                                                <a href="javascript:void(0);"
                                                    class="badge rounded-full bg-info/10 text-info">
                                                    <i class="bi bi-clock me-1"></i>
                                                    {{ Str::headline(str_replace('_', ' ', $job->employment_type ?? 'Full Time')) }}
                                                    @if($job->remote_type)
                                                    &middot; {{ Str::headline($job->remote_type) }}
                                                    @endif
                                                </a>
                                                <a href="javascript:void(0);"
                                                    class="badge rounded-full bg-primarytint2color/10 text-primarytint2color">
                                                    <i class="bi bi-briefcase me-1"></i>
                                                    {{ $job->category ?? '—' }}
                                                </a>
                                                @if($job->posted_at)
                                                <span class="badge rounded-full bg-success/10 text-success">
                                                    <i class="bi bi-calendar-event me-1"></i>
                                                    Posted {{ $job->posted_at->format('M d, Y') }}
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end ms-auto">
                                    <div class="flex gap-1 items-center mt-4 pt-3">
                                        @if($job->closes_at)
                                        <div class="hs-tooltip ti-main-tooltip">
                                            <i class="bi bi-info-circle text-info">
                                                <span
                                                    class="hs-tooltip-content  ti-main-tooltip-content py-1 px-2 !bg-black !text-xs !font-medium !text-white shadow-sm "
                                                    role="tooltip">
                                                    Closes {{ $job->closes_at->diffForHumans() }}
                                                </span>
                                            </i>
                                        </div>
                                        <p class="mb-0">
                                            @php
                                            $daysLeft = max(0, (int) now()->diffInDays($job->closes_at, false));
                                            $deadlinePassed = $job->closes_at->isPast();
                                            @endphp
                                            @if($deadlinePassed)
                                            <span class="font-medium text-danger"><i class="bi bi-x-circle me-1"></i>Deadline has passed</span>
                                            @else
                                            <span class="font-medium text-warning"><i class="bi bi-info-circle me-1"></i>{{ $daysLeft }} {{ Str::plural('day', $daysLeft) }} left</span> to apply for this job
                                            @endif
                                        </p>
                                        @endif
                                    </div>
                                    @if(empty($isEmployerPreview))
                                    <div class="btn-list mt-3 flex flex-wrap items-center justify-center gap-2">
                                        @guest
                                        <form method="POST" action="{{ route('jobs.apply', $job->slug) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="ti-btn mb-0 bg-primary text-white">
                                                Apply Now
                                            </button>
                                        </form>
                                        <a href="{{ route('jobs.apply_guest', $job->slug) }}" class="ti-btn mb-0 ti-btn-icon ti-btn-sm rounded-md border bg-white text-danger border-danger/40" title="Sign in to save">
                                            <i class="ri-heart-line"></i>
                                        </a>
                                        @else
                                        @if(auth()->user()->role === 'applicant' && !empty($hasApplied) && $hasApplied)
                                        <button type="button" class="ti-btn mb-0 bg-success/10 text-success cursor-default" disabled>
                                            Applied
                                        </button>
                                        @elseif($deadlinePassed)
                                        <button type="button" class="ti-btn mb-0 bg-danger/10 text-danger cursor-default" disabled>
                                            <i class="bi bi-x-circle me-1"></i> Deadline Passed
                                        </button>
                                        @else
                                        <form id="job-apply-form" method="POST" action="{{ route('jobs.apply', $job->slug) }}" class="inline">
                                            @csrf
                                            <input type="hidden" name="terms_agreed" value="">
                                            <button type="button" class="ti-btn mb-0 bg-primary text-white" onclick="document.getElementById('agreement-modal-va-apply').classList.remove('hidden')">
                                                Apply Now
                                            </button>
                                        </form>
                                        @endif
                                        <form method="POST" action="{{ route('jobs.save', $job->slug) }}" class="inline job-save-form">
                                            @csrf
                                            <button type="submit"
                                                class="ti-btn mb-0 ti-btn-icon ti-btn-sm rounded-md border job-save-toggle {{ $isSaved ? 'bg-danger text-white border-danger' : 'bg-white text-danger border-danger/40' }}"
                                                data-saved="{{ $isSaved ? '1' : '0' }}"
                                                title="{{ $isSaved ? 'Saved' : 'Add to Saved Jobs' }}">
                                                <i class="{{ $isSaved ? 'ri-heart-fill' : 'ri-heart-line' }}"></i>
                                            </button>
                                        </form>
                                        @endguest
                                        <div class="hs-tooltip ti-main-tooltip [--placement:top] inline-block">
                                            <button type="button"
                                                class="ti-btn mb-0 ti-btn-icon bg-primarytint2color/10 text-primarytint2color rounded-md btn-wave me-0"
                                                onclick="copyJobLink('{{ route('jobs.show', $job->slug) }}', this)">
                                                <i class="ri-share-line"></i>
                                                <span
                                                    class="hs-tooltip-content ti-main-tooltip-content py-1 px-2 !bg-black !text-xs !font-medium !text-white shadow-sm dark:bg-slate-700"
                                                    role="tooltip">
                                                    Copy job link
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            {{-- JOB DESCRIPTION --}}
            <div class="box border">
                <div class="box-body">
                    <h6 class="font-medium mb-3">Job Description</h6>
                    @if($job->summary)
                    <p class="opacity-90 mb-4">{{ $job->summary }}</p>
                    @endif
                    @if($job->description)
                    <p class="mb-4 opacity-90">{!! nl2br(e($job->description)) !!}</p>
                    @endif

                    @if($job->responsibilities)
                    <h6 class="font-medium">Key Responsibilities</h6>
                    <ol class="ti-list-group border-0 ps-5 list-decimal mb-3">
                        @foreach(preg_split('/\r?\n/', $job->responsibilities) as $responsibility)
                        @if(trim($responsibility))
                        <li class="ti-list-group-item !border-0 !py-1 !ps-0">{{ $responsibility }}</li>
                        @endif
                        @endforeach
                    </ol>
                    @endif

                    @if($job->requirements)
                    <h6 class="font-medium">Requirements</h6>
                    <ul class="ti-list-group border-0 ps-5 list-decimal mb-3">
                        @foreach(preg_split('/\r?\n/', $job->requirements) as $requirement)
                        @if(trim($requirement))
                        <li class="ti-list-group-item !border-0 !py-1 !ps-0">{{ $requirement }}</li>
                        @endif
                        @endforeach
                    </ul>
                    @endif
                </div>
            </div>

            {{-- APPLICATIONS / ACTIVITY SUMMARY --}}
            <div class="box border mt-6">
                <div class="box-header">
                    <div class="box-title">Activity on this job</div>
                </div>
                <div class="box-body">
                    @if($applications->isEmpty())
                    <p class="mb-0 text-textmuted">No applicants yet. Encourage applicants to apply!</p>
                    @else
                    @if(auth()->guest() || (auth()->check() && auth()->user()->role === 'applicant'))
                    @php
                    $total = $applications->count();
                    $proposalsLabel = $total > 0 && $total < 5 ? 'Less than 5' : $total;
                        $interviewing=$applications->where('status', 'interviewing')->count();
                        $invited = $applications->where('status', 'invited')->count();
                        @endphp
                        <div class="rounded-md bg-white dark:bg-slate-800 shadow-sm border p-3">
                            <div class="flex items-center gap-6 text-sm flex-wrap">
                                <div class="flex items-center gap-2">
                                    <span class="font-medium">Proposals:</span>
                                    <button type="button" aria-label="What is a proposal" class="ms-1 inline-flex items-center justify-center w-5 h-5 rounded-full bg-success/10 text-success border border-success/20" onclick="openActivityModal()">
                                        <i class="ri-question-line" style="font-size:14px"></i>
                                    </button>
                                    <span class="text-textmuted ms-2">{{ $proposalsLabel }}</span>
                                </div>

                                <div class="flex items-center gap-2">
                                    <span class="font-medium">Interviewing:</span>
                                    <span class="text-textmuted ms-1">{{ $interviewing }}</span>
                                </div>

                                <div class="flex items-center gap-2">
                                    <span class="font-medium">Invites sent:</span>
                                    <span class="text-textmuted ms-1">{{ $invited }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Activity Info Modal -->
                        <div id="activity-info-modal" class="hidden fixed inset-0 z-[9999] flex items-start sm:items-center justify-center py-8 px-4 overflow-auto">
                            <div class="activity-overlay fixed inset-0 bg-black/60 opacity-0 transition-opacity duration-250" style="backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);" onclick="closeActivityModal()"></div>
                            <div id="activity-info-modal-content" tabindex="-1" role="dialog" aria-modal="true" class="relative bg-white dark:bg-slate-800 rounded-lg shadow-2xl max-w-lg w-full p-6 z-[10000] overflow-auto transform opacity-0 scale-95 translate-y-2 transition-all duration-200" style="max-height: calc(100vh - 4rem);">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-center gap-3">
                                        <span class="avatar avatar-sm bg-primary/10 text-primary">
                                            <i class="ri-information-line"></i>
                                        </span>
                                        <h5 class="text-lg font-semibold">Activity explanation</h5>
                                    </div>
                                    <button type="button" class="text-textmuted hover:text-textmuted/80 p-2 rounded-md" onclick="closeActivityModal()" aria-label="Close modal">
                                        <i class="ri-close-line"></i>
                                    </button>
                                </div>
                                <div class="mt-3 border-t border-defaultborder/30"></div>
                                <div class="mt-4 text-sm text-textmuted">
                                    <p><strong>Proposals</strong>: The total number of applicants who have submitted applications for this job. For privacy, small counts are shown as "Less than 5" to avoid revealing exact identities.</p>
                                    <p class="mt-2"><strong>Interviewing</strong>: Applicants who have been moved to an interview stage by the employer.</p>
                                    <p class="mt-2"><strong>Invites sent</strong>: Number of interview invites or messages sent to applicants.</p>
                                    <p class="mt-3 text-xs text-textmuted">This information is shared to give visitors a general idea of activity without exposing applicant details.</p>
                                </div>
                                <div class="mt-4 text-end">
                                    <button type="button" class="ti-btn bg-primary text-white" onclick="closeActivityModal()">Close</button>
                                </div>
                            </div>
                        </div>

                        <script>
                            var __activity_modal_prev_scroll = {
                                x: 0,
                                y: 0
                            };

                            function openActivityModal() {
                                var modal = document.getElementById('activity-info-modal');
                                var content = document.getElementById('activity-info-modal-content');
                                var overlay = modal ? modal.querySelector('.activity-overlay') : null;
                                if (!modal || !content || !overlay) return;
                                // save current scroll pos
                                __activity_modal_prev_scroll.x = window.scrollX || window.pageXOffset;
                                __activity_modal_prev_scroll.y = window.scrollY || window.pageYOffset;
                                modal.classList.remove('hidden');
                                // lock background scroll
                                document.documentElement.classList.add('overflow-hidden');
                                document.body.classList.add('overflow-hidden', 'h-screen');

                                // ensure starting animation state
                                overlay.classList.remove('opacity-100');
                                overlay.classList.add('opacity-0');
                                content.classList.remove('opacity-100', 'scale-100', 'translate-y-0');
                                content.classList.add('opacity-0', 'scale-95', 'translate-y-2');

                                // play animation
                                requestAnimationFrame(function() {
                                    overlay.classList.remove('opacity-0');
                                    overlay.classList.add('opacity-100');
                                    content.classList.remove('opacity-0', 'scale-95', 'translate-y-2');
                                    content.classList.add('opacity-100', 'scale-100', 'translate-y-0');
                                });

                                // focus the modal content without scrolling the page
                                try {
                                    content.focus({
                                        preventScroll: true
                                    });
                                } catch (e) {
                                    content.focus();
                                    window.scrollTo(__activity_modal_prev_scroll.x, __activity_modal_prev_scroll.y);
                                }

                                document.addEventListener('keydown', activityModalKeydown);
                            }

                            function closeActivityModal() {
                                var modal = document.getElementById('activity-info-modal');
                                var content = document.getElementById('activity-info-modal-content');
                                var overlay = modal ? modal.querySelector('.activity-overlay') : null;
                                if (!modal || !content || !overlay) return;

                                // animate out
                                overlay.classList.remove('opacity-100');
                                overlay.classList.add('opacity-0');
                                content.classList.remove('opacity-100', 'scale-100', 'translate-y-0');
                                content.classList.add('opacity-0', 'scale-95', 'translate-y-2');

                                // when transition ends, hide the modal and restore scroll
                                var done = function() {
                                    modal.classList.add('hidden');
                                    document.documentElement.classList.remove('overflow-hidden');
                                    document.body.classList.remove('overflow-hidden', 'h-screen');
                                    try {
                                        window.scrollTo(__activity_modal_prev_scroll.x, __activity_modal_prev_scroll.y);
                                    } catch (e) {}
                                    document.removeEventListener('keydown', activityModalKeydown);
                                };

                                // listen for transitionend on content
                                content.addEventListener('transitionend', function handler(e) {
                                    if (e.target === content) {
                                        content.removeEventListener('transitionend', handler);
                                        done();
                                    }
                                });
                                // fallback timeout
                                setTimeout(done, 300);
                            }

                            function activityModalKeydown(e) {
                                if (e.key === 'Escape') {
                                    closeActivityModal();
                                }
                            }
                        </script>
                        @else
                        <div class="divide-y divide-defaultborder/40">
                            @foreach($applications as $application)
                            <div class="py-3 flex flex-wrap items-center gap-3">
                                <div>
                                    <span class="avatar avatar-rounded bg-primary/10 text-primary">
                                        <i class="ri-user-line"></i>
                                    </span>
                                </div>
                                <div class="flex-auto">
                                    <h6 class="mb-0 font-medium">
                                        {{ $application->applicantProfile->display_name ?? $application->user->name ?? 'Candidate' }}
                                    </h6>
                                    <p class="mb-0 text-xs text-textmuted">
                                        Status: <span class="font-semibold">{{ Str::headline($application->status) }}</span>
                                        @if($application->applied_at)
                                        &middot; Applied {{ $application->applied_at->diffForHumans() }}
                                        @endif
                                    </p>
                                </div>
                                <div class="text-sm text-textmuted">
                                    @if($isEmployerPreview && $application->applicantProfile)
                                    <a href="{{ route('applicants.details', ['applicant' => $application->applicantProfile->id]) }}" class="text-primary">View profile</a>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                        @endif
                </div>
            </div>
        </div>

        {{-- RIGHT SIDEBAR --}}
        <div class="xxl:col-span-3 col-span-12">
            <div class="box border">
                <div class="box-header">
                    <div class="box-title">Job Highlights</div>
                </div>
                <div class="box-body">
                    <div class="mb-3 text-[14px] flex items-center">
                        <span
                            class="avatar avatar-sm border dark:border-defaultborder/10 border-defaultborder leading-none avatar-rounded me-2 bg-info/10 !text-info">
                            <i class="ri-map-pin-line"></i>
                        </span>
                        <span class="text-textmuted dark:text-textmuted/50">Work Setup</span>
                        <span class="ms-auto font-medium">
                            {{ isset($job->highlight_work_setup) && $job->highlight_work_setup !== '' ? Str::headline(str_replace('_', ' ', $job->highlight_work_setup)) : ($job->remote_type ? Str::headline(str_replace('_', ' ', $job->remote_type)) : 'Flexible') }}
                        </span>
                    </div>
                    <div class="mb-3 flex items-center">
                        <span
                            class="avatar avatar-sm border dark:border-defaultborder/10 border-defaultborder  leading-none avatar-rounded me-2 bg-danger/10 !text-danger">
                            <i class="ri-time-line"></i>
                        </span>
                        <span class="text-textmuted dark:text-textmuted/50">Shift Schedule</span>
                        <span class="ms-auto font-medium">
                            {{ isset($job->highlight_shift_schedule) && $job->highlight_shift_schedule !== '' ? Str::headline(str_replace('_', ' ', $job->highlight_shift_schedule)) : ($job->employment_type ? Str::headline(str_replace('_', ' ', $job->employment_type)) : 'Standard') }}
                        </span>
                    </div>
                    <div class="mb-3 flex items-center">
                        <span
                            class="avatar avatar-sm border dark:border-defaultborder/10 border-defaultborder leading-none avatar-rounded me-2 bg-success/10 !text-success">
                            <i class="ri-cash-line"></i>
                        </span>
                        <span class="text-textmuted dark:text-textmuted/50">Monthly Rate</span>
                        <span class="ms-auto font-medium">
                            @if($job->highlight_monthly_rate)
                            {{ $job->highlight_monthly_rate }}
                            @elseif($job->salary_min || $job->salary_max)
                            {{ $job->salary_currency ?? 'USD' }} {{ number_format($job->salary_min ?? 0) }} - {{ number_format($job->salary_max ?? ($job->salary_min ?? 0)) }}
                            @else
                            TBD
                            @endif
                        </span>
                    </div>
                    <div class="mb-0 flex items-center">
                        <span
                            class="avatar avatar-sm border dark:border-defaultborder/10 border-defaultborder leading-none avatar-rounded me-2 bg-primarytint2color/10 !text-primarytint2color">
                            <i class="ri-award-line"></i>
                        </span>
                        <span class="text-textmuted dark:text-textmuted/50">Employee Benefits</span>
                        <span class="ms-auto font-medium">
                            {{ $job->highlight_benefits ?? ($job->company?->employees_count ? $job->company->employees_count.' team members' : 'Growth culture') }}
                        </span>
                    </div>
                </div>
            </div>

            @if(empty($isEmployerPreview))
            @if($relatedJobs->isNotEmpty())
            <div class="mb-1">
                <h6 class="font-medium mb-3">Related Jobs</h6>
                <div class="swiper swiper-vertical overflow-hidden swiper-related-jobs">
                    <div class="swiper-wrapper">
                        @foreach($relatedJobs as $relatedJob)
                        <div class="swiper-slide">
                            <div class="box featured-jobs shadow-none border dark:border-defaultborder/10 border-defaultborder">
                                <div class="box-body">
                                    <div class="flex mb-3 gap-2 xxl:flex-nowrap">
                                        <div>
                                            <span class="avatar avatar-md border dark:border-defaultborder/10 border-defaultborder p-1">
                                                <img src="{{ $relatedJob->company?->logo_url ?? 'https://api.dicebear.com/7.x/shapes/svg?seed=' . urlencode($relatedJob->company?->name ?? 'Company') }}" alt="">
                                            </span>
                                        </div>
                                        <div class="ms-1 grow !w-[75%] truncate">
                                            <h6 class="font-medium mb-0 flex items-center truncate !w-[75%]">
                                                <a href="{{ route('jobs.show', $relatedJob->slug) }}" class="truncate">{{ $relatedJob->title }}</a>
                                            </h6>
                                            <a href="{{ route('jobs.show', $relatedJob->slug) }}" class="text-xs text-textmuted dark:text-textmuted/50">
                                                <i class="bi bi-building"></i>
                                                @guest
                                                <span class="inline-block" aria-hidden="true" style="filter: blur(4px); -webkit-filter: blur(4px);">{{ $relatedJob->company?->name ?? 'Company' }}</span>
                                                <span class="sr-only">Company</span>
                                                @else
                                                {{ $relatedJob->company?->name ?? 'Company' }}
                                                @endguest
                                            </a>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="popular-tags mb-3 flex gap-1 flex-wrap xxl:flex-nowrap">
                                            @if($relatedJob->location)
                                            <span class="badge rounded-full bg-info/10 text-info">
                                                <i class="bi bi-geo-alt me-1"></i> {{ $relatedJob->location }}
                                            </span>
                                            @endif
                                            @if($relatedJob->category)
                                            <span class="badge rounded-full bg-primarytint2color/10 text-primarytint2color">{{ $relatedJob->category }}</span>
                                            @endif
                                        </div>
                                        <h6 class="font-medium mb-0">
                                            @if($relatedJob->salary_min || $relatedJob->salary_max)
                                            {{ $relatedJob->salary_currency ?? 'USD' }} {{ number_format($relatedJob->salary_min ?? 0) }} - {{ number_format($relatedJob->salary_max ?? ($relatedJob->salary_min ?? 0)) }}
                                            @else
                                            Competitive pay
                                            @endif
                                        </h6>
                                    </div>
                                    <a href="{{ route('jobs.show', $relatedJob->slug) }}" class="font-medium ti-btn ti-btn-sm bg-primary text-white grid text-nowrap">
                                        View job
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- JOB ALERTS --}}
            <div class="box border">
                <div class="box-body">
                    <h6 class="font-medium mb-3">Subscribe to Job Alerts</h6>
                    <div class="input-group mb-3">
                        <input type="email" class="form-control !border-s"
                            placeholder="Your Email Address"
                            aria-label="job-email" aria-describedby="job-subscribe">
                        <button class="ti-btn bg-primary text-white !m-0" type="button"
                            id="job-subscribe">
                            Subscribe
                        </button>
                    </div>
                    <label class="form-check-label">
                        By subscribing, you accept our
                        <a href="javascript:void(0);" class="text-success">
                            <u>privacy policy</u>
                        </a>.
                    </label>
                </div>
            </div>
            @endif
        </div>
    </div>


    <!-- Swiper JS -->
    <script src="/assets/libs/swiper/swiper-bundle.min.js"></script>

    <!-- Swiper jobs JS -->
    <script src="/assets/js/job-details.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('job_posted'))
            if (window.Swal) {
                Swal.fire({
                    icon: 'success',
                    title: 'Job posted!',
                    text: 'Your job post is now live. This is the preview your applicants will see.',
                    confirmButtonText: 'Got it',
                });
            }
            @endif
        });

        (function() {
            function showJobToast(message) {
                // Notifications disabled for details page save/copy actions
                console.log(message);
            }

            function setSavedStyles(btn, saved) {
                var icon = btn.querySelector('i');
                if (saved) {
                    btn.dataset.saved = '1';
                    btn.classList.remove('bg-white', 'text-danger', 'border-danger/40');
                    btn.classList.add('bg-danger', 'text-white', 'border-danger');
                    if (icon) {
                        icon.classList.remove('bi-heart', 'ri-heart-line');
                        icon.classList.add('bi-heart-fill', 'ri-heart-fill');
                    }
                } else {
                    btn.dataset.saved = '0';
                    btn.classList.remove('bg-danger', 'text-white', 'border-danger');
                    btn.classList.add('bg-white', 'text-danger', 'border-danger/40');
                    if (icon) {
                        icon.classList.remove('bi-heart-fill', 'ri-heart-fill');
                        icon.classList.add('bi-heart', 'ri-heart-line');
                    }
                }
            }

            function handleJobSaveSubmit(event) {
                event.preventDefault();
                var form = event.target;
                var btn = form.querySelector('.job-save-toggle');
                if (!btn) {
                    form.submit();
                    return;
                }

                var url = form.getAttribute('action');
                var tokenTag = document.querySelector('meta[name="csrf-token"]');
                var token = tokenTag ? tokenTag.getAttribute('content') : null;

                btn.disabled = true;

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8'
                    },
                    body: ''
                }).then(function(resp) {
                    if (!resp.ok) throw new Error('Request failed');
                    return resp.json();
                }).then(function(data) {
                    if (data.status === 'saved') {
                        setSavedStyles(btn, true);
                        showJobToast('Job saved');
                    } else if (data.status === 'removed') {
                        setSavedStyles(btn, false);
                        showJobToast('Removed from saved jobs');
                    } else {
                        showJobToast('Updated');
                    }
                }).catch(function() {
                    showJobToast('Something went wrong. Please try again.');
                }).finally(function() {
                    btn.disabled = false;
                });
            }

            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('form.job-save-form').forEach(function(form) {
                    form.addEventListener('submit', handleJobSaveSubmit);
                });
            });

            function markCopyButtonState(btn) {
                if (!btn) return;

                btn.classList.add('bg-success/10', 'text-success');
                btn.classList.remove('bg-primarytint2color/10', 'text-primarytint2color');

                setTimeout(function() {
                    btn.classList.remove('bg-success/10', 'text-success');
                    btn.classList.add('bg-primarytint2color/10', 'text-primarytint2color');
                }, 2000);
            }

            window.copyJobLink = function(url, btn) {
                function doCopy() {
                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        return navigator.clipboard.writeText(url);
                    }
                    return new Promise(function(resolve, reject) {
                        try {
                            var temp = document.createElement('input');
                            temp.value = url;
                            document.body.appendChild(temp);
                            temp.select();
                            document.execCommand('copy');
                            document.body.removeChild(temp);
                            resolve();
                        } catch (e) {
                            reject(e);
                        }
                    });
                }

                doCopy().then(function() {
                    markCopyButtonState(btn);
                    showJobToast('Link copied');
                }).catch(function() {
                    showJobToast('Unable to copy link');
                });
            };
        })();

        document.addEventListener('DOMContentLoaded', function() {
            var container = document.querySelector('.company-rating-stars-detail');
            if (!container) return;
            var rateUrl = container.dataset.rateUrl;
            var canRate = container.dataset.canRate === '1';
            if (!rateUrl || !canRate) return;
            var stars = container.querySelectorAll('.star-btn');
            var countEl = document.querySelector('.company-rating-count-detail .rating-count-value');
            var csrfToken = document.querySelector('meta[name="csrf-token"]') && document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            function setStarsDisplay(avg) {
                var r = parseFloat(avg) || 0;
                stars.forEach(function(s, i) {
                    var icon = s.querySelector('i');
                    var starNum = i + 1;
                    if (starNum <= Math.floor(r)) icon.className = 'bi bi-star-fill';
                    else if (starNum - 0.5 <= r) icon.className = 'bi bi-star-half';
                    else icon.className = 'bi bi-star';
                });
            }

            stars.forEach(function(star, index) {
                star.addEventListener('mouseenter', function() {
                    stars.forEach(function(s, i) {
                        var icon = s.querySelector('i');
                        icon.className = i <= index ? 'bi bi-star-fill' : 'bi bi-star';
                    });
                });
            });
            container.addEventListener('mouseleave', function() {
                setStarsDisplay(container.dataset.rating);
            });
            stars.forEach(function(star) {
                star.addEventListener('click', function() {
                    var rating = parseInt(star.dataset.star, 10);
                    fetch(rateUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            rating: rating
                        })
                    }).then(function(resp) {
                        return resp.json();
                    }).then(function(data) {
                        if (data.success) {
                            container.dataset.rating = data.average_rating;
                            setStarsDisplay(data.average_rating);
                            if (countEl) countEl.textContent = data.rating_count;
                            if (window.Swal) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Rating Submitted',
                                    text: 'You rated this company ' + rating + ' star' + (rating > 1 ? 's' : ''),
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            }
                        }
                    }).catch(function(err) {
                        console.error('Rating error:', err);
                    });
                });
            });
        });
    </script>

    @include('partials.agreement-modal-va', [
    'modalId' => 'agreement-modal-va-apply',
    'formId' => 'job-apply-form',
    'acceptButtonText' => 'Accept & Apply',
    'agencyName' => config('agency.name'),
    'agencyAddress' => config('agency.address'),
    'applicantName' => auth()->user()?->name,
    'applicantAddress' => auth()->user()?->address ?? '',
    ])

</x-app-layout>
