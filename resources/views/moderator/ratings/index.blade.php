<x-app-layout page-title="Rating Management">
    <x-slot name="url_1">{"link": "/moderator/dashboard", "text": "Moderator"}</x-slot>
    <x-slot name="active">Rating Management</x-slot>

    <style>
        /* Dark Mode Overrides */
        [data-theme-mode="dark"] .box, .dark .box,
        [data-theme-mode="dark"] .bg-white, .dark .bg-white {
            background-color: rgba(255,255,255,0.02) !important;
            border-color: rgba(255,255,255,0.05) !important;
        }
        [data-theme-mode="dark"] .box-header, .dark .box-header {
            border-bottom-color: rgba(255,255,255,0.05) !important;
            background-color: rgba(255,255,255,0.01) !important;
        }
        [data-theme-mode="dark"] .border, .dark .border {
            border-color: rgba(255,255,255,0.05) !important;
        }
    </style>

    <x-modern-header chip="Rating Management" title="Rating Management" desc="Review and moderate user ratings. Use the filters below to switch between all, flagged, and hidden ratings.">
    </x-modern-header>

    <div class="grid grid-cols-12 gap-6 mx-auto pb-6 sm:px-6 lg:px-8">
        <div class="xl:col-span-12 col-span-12">
            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                <a href="{{ route('moderator.ratings.index', ['filter' => 'all']) }}"
                   class="bg-white dark:bg-slate-900 p-5 rounded-lg shadow-sm border border-gray-200 dark:border-slate-800 {{ $filter === 'all' ? 'border-l-4 border-l-primary' : '' }} hover:bg-primary/5 transition-colors">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">All Ratings</p>
                    <p class="mt-1 text-2xl font-extrabold text-primary">{{ $counts['all'] }}</p>
                </a>
                <a href="{{ route('moderator.ratings.index', ['filter' => 'flagged']) }}"
                   class="bg-white dark:bg-slate-900 p-5 rounded-lg shadow-sm border border-gray-200 dark:border-slate-800 {{ $filter === 'flagged' ? 'border-l-4 border-l-warning' : '' }} hover:bg-warning/5 transition-colors">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">Flagged</p>
                    <p class="mt-1 text-2xl font-extrabold text-warning">{{ $counts['flagged'] }}</p>
                </a>
                <a href="{{ route('moderator.ratings.index', ['filter' => 'hidden']) }}"
                   class="bg-white dark:bg-slate-900 p-5 rounded-lg shadow-sm border border-gray-200 dark:border-slate-800 {{ $filter === 'hidden' ? 'border-l-4 border-l-secondary' : '' }} hover:bg-secondary/5 transition-colors">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">Hidden</p>
                    <p class="mt-1 text-2xl font-extrabold text-secondary">{{ $counts['hidden'] }}</p>
                </a>
            </div>

            <div class="box border">
                <div class="box-header flex items-center justify-between">
                    <div class="box-title">{{ ucfirst($filter) }} Ratings</div>
                </div>
                <div class="box-body">
                    @if (session('status'))
                        <div class="alert alert-success mb-4">{{ session('status') }}</div>
                    @endif

                    @if($ratings->isEmpty())
                        <div class="text-center py-8">
                            <i class="ri-star-line text-4xl text-textmuted mb-3"></i>
                            <p class="text-textmuted">No ratings found.</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($ratings as $rating)
                                <div class="border rounded-lg p-4 {{ $rating->is_hidden ? 'opacity-50 bg-secondary/5' : '' }} {{ $rating->is_flagged ? 'border-warning bg-warning/5' : '' }}">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-start gap-3">
                                            <span class="avatar avatar-md avatar-rounded bg-primary/10 text-primary">
                                                {{ strtoupper(substr($rating->user->name ?? 'U', 0, 2)) }}
                                            </span>
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <span class="font-medium">{{ $rating->user->name ?? 'Unknown User' }}</span>
                                                    <div class="flex items-center text-warning">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <i class="ri-star-{{ $i <= $rating->rating ? 'fill' : 'line' }} text-sm"></i>
                                                        @endfor
                                                        <span class="text-sm ms-1">({{ $rating->rating }}/5)</span>
                                                    </div>
                                                </div>
                                                <p class="text-xs text-textmuted">
                                                    Rated:
                                                    @if($rating->rateable)
                                                        @if($rating->rateable_type === \App\Models\Company::class)
                                                            <strong>Company:</strong> {{ $rating->rateable->name }}
                                                            <a href="{{ route('moderator.companies.show', $rating->rateable) }}" class="text-primary ms-1">View company</a>
                                                        @elseif($rating->rateable_type === \App\Models\JobPosting::class)
                                                            <strong>Job:</strong> {{ $rating->rateable->title }}
                                                            @if($rating->rateable->company)
                                                                ({{ $rating->rateable->company->name }})
                                                                <a href="{{ route('moderator.jobs.show', $rating->rateable->id) }}" class="text-primary ms-1">View job</a>
                                                            @endif
                                                        @elseif($rating->rateable_type === \App\Models\ApplicantProfile::class)
                                                            <strong>Applicant:</strong> {{ $rating->rateable->display_name ?? $rating->rateable->user?->name ?? '—' }}
                                                        @else
                                                            {{ class_basename($rating->rateable_type) }} #{{ $rating->rateable_id }}
                                                        @endif
                                                    @else
                                                        Unknown
                                                    @endif
                                                    | {{ $rating->created_at->format('M d, Y H:i') }}
                                                </p>
                                                @if($rating->review)
                                                    <p class="mt-2 text-sm">{{ $rating->review }}</p>
                                                @endif
                                                <div class="flex items-center gap-2 mt-2">
                                                    @if($rating->is_flagged)
                                                        <span class="badge bg-warning/10 text-warning text-xs">
                                                            <i class="ri-flag-fill me-1"></i> Flagged
                                                        </span>
                                                    @endif
                                                    @if($rating->is_hidden)
                                                        <span class="badge bg-secondary/10 text-secondary text-xs">
                                                            <i class="ri-eye-off-line me-1"></i> Hidden
                                                        </span>
                                                    @endif
                                                </div>
                                                @if($rating->is_flagged && $rating->flag_reason)
                                                    <p class="text-xs text-warning mt-1"><strong>Flag reason:</strong> {{ $rating->flag_reason }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <a href="{{ route('moderator.ratings.show', $rating) }}" class="ti-btn ti-btn-sm ti-btn-info" title="View Details">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            @if(!$rating->is_hidden)
                                                <form action="{{ route('moderator.ratings.hide', $rating) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="ti-btn ti-btn-sm ti-btn-secondary" title="Hide">
                                                        <i class="ri-eye-off-line"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('moderator.ratings.unhide', $rating) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="ti-btn ti-btn-sm ti-btn-success" title="Unhide">
                                                        <i class="ri-eye-line"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            @if(!$rating->is_flagged)
                                                <button type="button" class="ti-btn ti-btn-sm ti-btn-warning flag-btn"
                                                        data-id="{{ $rating->id }}" title="Flag">
                                                    <i class="ri-flag-line"></i>
                                                </button>
                                            @else
                                                <form action="{{ route('moderator.ratings.unflag', $rating) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="ti-btn ti-btn-sm ti-btn-outline-warning" title="Unflag">
                                                        <i class="ri-flag-off-line"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('moderator.ratings.destroy', $rating) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to permanently delete this rating?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="ti-btn ti-btn-sm ti-btn-danger" title="Delete">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4">
                            {{ $ratings->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Flag Modal --}}
    <div id="flag-modal" class="fixed inset-0 z-50 hidden">
        <div class="fixed inset-0 bg-black/50" onclick="closeFlagModal()"></div>
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="bg-white dark:bg-slate-900 rounded-lg shadow-xl max-w-md w-full p-6 relative">
                <h3 class="text-lg font-semibold mb-4">Flag Rating</h3>
                <form id="flag-form" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label">Flag Reason <span class="text-danger">*</span></label>
                        <textarea name="reason" class="form-control" rows="3" required placeholder="Why is this rating being flagged?"></textarea>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" class="ti-btn ti-btn-light" onclick="closeFlagModal()">Cancel</button>
                        <button type="submit" class="ti-btn ti-btn-warning">Flag Rating</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openFlagModal(id) {
            document.getElementById('flag-form').action = '/moderator/ratings/' + id + '/flag';
            document.getElementById('flag-modal').classList.remove('hidden');
        }

        function closeFlagModal() {
            document.getElementById('flag-modal').classList.add('hidden');
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.flag-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    openFlagModal(this.dataset.id);
                });
            });
        });
    </script>

</x-app-layout>
