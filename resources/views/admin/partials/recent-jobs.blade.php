<!-- Recent Jobs -->
<div class="box border">
    <div class="box-header flex items-center justify-between">
        <div class="box-title">Recent Job Postings</div>
    </div>
    <div class="box-body">
        <div class="table-responsive">
            <table class="table whitespace-nowrap table-bordered min-w-full">
                <thead>
                    <tr class="border-b border-defaultborder">
                        <th scope="col" class="text-start">Job Title</th>
                        <th scope="col" class="text-start">Company</th>
                        <th scope="col" class="text-start">Status</th>
                        <th scope="col" class="text-start">Posted</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentJobs as $job)
                    <tr class="border-b border-defaultborder">
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-light flex items-center justify-center">
                                    <i class="ri-briefcase-line text-primary"></i>
                                </div>
                                <span class="font-medium">{{ Str::limit($job->title, 40) }}</span>
                            </div>
                        </td>
                        <td class="text-textmuted">{{ $job->company->name ?? 'Unknown' }}</td>
                        <td>
                            <span class="badge {{ $job->status === 'active' ? 'bg-success/10 text-success' : ($job->status === 'closed' ? 'bg-danger/10 text-danger' : 'bg-secondary/10 text-secondary') }}">
                                {{ ucfirst($job->status) }}
                            </span>
                        </td>
                        <td class="text-textmuted text-sm">{{ $job->created_at->diffForHumans() }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-textmuted py-4">No jobs found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($recentJobs->hasPages())
        <div class="mt-4 pt-3 border-t border-defaultborder">
            {{ $recentJobs->appends(['tab' => 'jobs'])->links('pagination::simple-tailwind') }}
        </div>
        @endif
    </div>
</div>
