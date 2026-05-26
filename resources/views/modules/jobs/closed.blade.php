<x-app-layout>

    <x-slot name="pageTitle">Job Closed</x-slot>
    <x-slot name="url_1">{"link": "/jobs", "text": "Jobs"}</x-slot>
    <x-slot name="active">Job Closed</x-slot>

    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            <div class="box border">
                <div class="box-body p-8 text-center">
                    <div class="flex flex-col items-center justify-center py-8">
                        <div class="w-24 h-24 rounded-full bg-danger/10 flex items-center justify-center mb-6">
                            <i class="ri-door-closed-line text-danger text-5xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-defaulttextcolor mb-3">Job Posting Closed</h2>
                        <p class="text-textmuted text-[15px] max-w-md mb-6">
                            This job posting is no longer accepting applications. The employer has closed this position.
                        </p>

                        @if($job)
                        <div class="bg-light rounded-lg p-4 mb-6 max-w-md w-full">
                            <h3 class="font-semibold text-defaulttextcolor mb-1">{{ $job->title }}</h3>
                            <p class="text-textmuted text-sm">{{ $job->company?->name ?? 'Company' }}</p>
                            @if($job->closes_at)
                            <p class="text-textmuted text-xs mt-2">
                                Closed on {{ $job->closes_at->format('M d, Y') }}
                            </p>
                            @endif
                        </div>
                        @endif

                        <div class="flex items-center gap-3">
                            <a href="{{ route('jobs') }}" class="ti-btn ti-btn-primary">
                                <i class="ri-search-line me-2"></i>
                                Browse Other Jobs
                            </a>
                            <a href="{{ route('applicant.applications.index') }}" class="ti-btn ti-btn-outline-secondary">
                                <i class="ri-file-list-3-line me-2"></i>
                                My Applications
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
