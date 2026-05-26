<x-app-layout>

    <x-slot name="url_1">{"link": "/candidate/dashboard", "text": "Dashboard"}</x-slot>
    <x-slot name="active">Job Alerts</x-slot>

    <div class="grid grid-cols-12 gap-x-6">
        <div class="xxl:col-span-12 col-span-12">
            <div class="box border">
                <div class="box-body">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-4">
                        <div>
                            <h6 class="font-bold text-2xl text-gray-700 dark:text-white">
                                <strong>Job Alerts</strong>
                            </h6>
                            <span class="text-sm text-gray-600 dark:text-gray-300">
                                Get notified when new jobs match your criteria.
                            </span>
                        </div>
                        <a href="{{ route('candidate.job-alerts.create') }}" class="ti-btn ti-btn-primary ti-btn-sm">
                            <i class="bi bi-plus-lg me-1"></i> Create Alert
                        </a>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($alerts->isEmpty())
                        <div class="text-center py-12">
                            <div class="avatar avatar-xl avatar-rounded bg-light mx-auto mb-3">
                                <i class="bi bi-bell text-textmuted text-2xl"></i>
                            </div>
                            <p class="text-textmuted mb-3">You don't have any job alerts yet.</p>
                            <p class="text-textmuted text-sm mb-4">Create an alert to receive email notifications when new jobs match your preferences.</p>
                            <a href="{{ route('candidate.job-alerts.create') }}" class="ti-btn ti-btn-primary ti-btn-sm">
                                <i class="bi bi-plus-lg me-1"></i> Create Your First Alert
                            </a>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($alerts as $alert)
                                <div class="border border-defaultborder/60 dark:border-defaultborder/20 rounded-lg p-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <h6 class="font-semibold mb-0">
                                                {{ $alert->name ?: 'Job Alert' }}
                                            </h6>
                                            @if(!$alert->is_active)
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </div>
                                        <div class="flex flex-wrap gap-2 text-sm text-textmuted">
                                            @if($alert->keywords)
                                                <span><i class="bi bi-search me-1"></i>{{ $alert->keywords }}</span>
                                            @endif
                                            @if($alert->location)
                                                <span><i class="bi bi-geo-alt me-1"></i>{{ $alert->location }}</span>
                                            @endif
                                            @if($alert->category)
                                                <span><i class="bi bi-tag me-1"></i>{{ Str::headline($alert->category) }}</span>
                                            @endif
                                            @if($alert->employment_type)
                                                <span><i class="bi bi-briefcase me-1"></i>{{ Str::headline($alert->employment_type) }}</span>
                                            @endif
                                            @if($alert->remote_type)
                                                <span><i class="bi bi-laptop me-1"></i>{{ Str::headline($alert->remote_type) }}</span>
                                            @endif
                                            @if(!$alert->keywords && !$alert->location && !$alert->category && !$alert->employment_type && !$alert->remote_type)
                                                <span class="text-textmuted">All jobs</span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-textmuted mb-0 mt-1">
                                            {{ Str::headline($alert->frequency) }} notifications
                                            @if($alert->last_sent_at)
                                                · Last sent {{ $alert->last_sent_at->diffForHumans() }}
                                            @else
                                                · Not yet sent
                                            @endif
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('candidate.job-alerts.edit', $alert) }}" class="ti-btn ti-btn-outline-primary ti-btn-sm">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST" action="{{ route('candidate.job-alerts.destroy', $alert) }}" class="inline" onsubmit="return confirm('Delete this job alert?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="ti-btn ti-btn-outline-danger ti-btn-sm">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
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

</x-app-layout>
