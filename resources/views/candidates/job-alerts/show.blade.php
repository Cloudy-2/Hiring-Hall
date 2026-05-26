<x-app-layout>
    <x-slot name="url_1">{"link": "/candidate/job-alerts", "text": "Job Alerts"}</x-slot>
    <x-slot name="active">Alert Results</x-slot>

    <div class="grid grid-cols-12 gap-x-6">
        <div class="col-span-12">
            <div class="box border">
                <div class="box-body">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                        <div>
                            <h6 class="font-bold text-2xl text-gray-700 dark:text-white">
                                <strong>{{ $alert->name ?: 'Job Alert' }}</strong>
                            </h6>
                            <p class="text-sm text-textmuted mb-0">
                                Showing jobs that match this alert.
                            </p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('candidate.job-alerts.edit', $alert) }}" class="ti-btn ti-btn-outline-primary ti-btn-sm">
                                <i class="ri-edit-line me-1"></i> Edit Alert
                            </a>
                            <a href="{{ route('candidate.job-alerts.index') }}" class="ti-btn ti-btn-outline-light ti-btn-sm">
                                Back
                            </a>
                        </div>
                    </div>

                    @if($jobs->isEmpty())
                        <div class="text-center py-12">
                            <span class="avatar avatar-xl avatar-rounded bg-secondary/10 !text-secondary mx-auto mb-3">
                                <i class="ri-search-eye-line text-2xl"></i>
                            </span>
                            <p class="text-textmuted mb-1">No jobs found for this alert.</p>
                            <p class="text-textmuted text-sm">Try updating your keywords or filters.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-12 gap-4">
                            @foreach($jobs as $job)
                                <div class="col-span-12 sm:col-span-6 xl:col-span-4 xxl:col-span-3">
                                    <div class="box border h-full mb-0 hover:shadow-md transition-shadow">
                                        <div class="box-body">
                                            <div class="flex items-start gap-3 mb-3">
                                                <div class="avatar avatar-md avatar-rounded bg-primary/10 flex-shrink-0">
                                                    @if($job->company?->logo_url)
                                                        <img src="{{ $job->company->logo_url }}" alt="Company">
                                                    @else
                                                        <span class="text-primary font-semibold text-sm">
                                                            {{ strtoupper(substr($job->company?->name ?? 'C', 0, 2)) }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <h6 class="font-medium text-sm mb-1 line-clamp-2">
                                                        <a href="{{ route('jobs.show', $job->slug) }}" class="hover:text-primary">
                                                            {{ $job->title }}
                                                        </a>
                                                    </h6>
                                                    <p class="text-xs text-textmuted mb-0 truncate">
                                                        {{ $job->company?->name ?? 'Company' }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="flex flex-wrap gap-1 mb-3">
                                                <span class="badge bg-light text-textmuted text-[10px]">
                                                    <i class="bi bi-geo-alt me-1"></i>{{ $job->location ?? 'Remote' }}
                                                </span>
                                                @if($job->employment_type)
                                                    <span class="badge bg-primary/10 text-primary text-[10px]">
                                                        {{ \Illuminate\Support\Str::headline($job->employment_type) }}
                                                    </span>
                                                @endif
                                                @if($job->category)
                                                    <span class="badge bg-info/10 text-info text-[10px]">
                                                        {{ \Illuminate\Support\Str::headline($job->category) }}
                                                    </span>
                                                @endif
                                            </div>
                                            <a href="{{ route('jobs.show', $job->slug) }}" class="ti-btn ti-btn-outline-primary ti-btn-sm w-full">
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $jobs->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

