<x-app-layout page-title="Rating Details">
    <x-slot name="url_1">{"link": "/moderator/dashboard", "text": "Moderator"}</x-slot>
    <x-slot name="url_2">{"link": "/moderator/ratings", "text": "Rating Management"}</x-slot>
    <x-slot name="active">Rating #{{ $rating->id }}</x-slot>

    <style>
        /* Dark Mode Overrides */
        [data-theme-mode="dark"] .box, .dark .box,
        [data-theme-mode="dark"] .bg-white, .dark .bg-white {
            background-color: rgb(30, 32, 34) !important;
            border-color: rgba(255,255,255,0.05) !important;
        }
        [data-theme-mode="dark"] .box-header, .dark .box-header {
            border-bottom-color: rgba(255,255,255,0.05) !important;
            background-color: rgb(30, 32, 34) !important;
        }
        [data-theme-mode="dark"] thead, .dark thead,
        [data-theme-mode="dark"] .bg-gray-50\/50, .dark .bg-gray-50\/50 {
            background-color: rgb(30, 32, 34) !important;
        }
        [data-theme-mode="dark"] td, .dark td,
        [data-theme-mode="dark"] th, .dark th {
            border-color: rgba(255,255,255,0.05) !important;
        }
        [data-theme-mode="dark"] .bg-orange-50, .dark .bg-orange-50 {
            background-color: rgba(249,115,22,0.1) !important;
        }
    </style>

    <x-modern-header chip="Rating #{{ $rating->id }}"
        desc="Review and moderate user ratings. Use the filters below to switch between all, flagged, and hidden ratings.">
        <x-slot name="div">
            <div class="flex items-center gap-4 mb-6">
                <div class="flex items-center gap-1 text-warning text-2xl">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="ri-star-{{ $i <= $rating->rating ? 'fill' : 'line' }}"></i>
                    @endfor
                </div>
                <span class="text-2xl font-bold">{{ $rating->rating }}/5</span>
            </div>
        </x-slot>
        <x-slot name="badge">
            @if($rating->is_flagged)
                <span class="badge bg-warning/10 text-warning">
                    <i class="ri-flag-fill me-1"></i> Flagged
                </span>
            @endif
            @if($rating->is_hidden)
                <span class="badge bg-secondary/10 text-secondary">
                    <i class="ri-eye-off-line me-1"></i> Hidden
                </span>
            @else
                <span class="badge bg-success/10 text-success">
                    <i class="ri-eye-line me-1"></i> Visible
                </span>
            @endif
        </x-slot>   
    </x-modern-header>

    <div class="grid grid-cols-12 gap-6 mx-auto pb-6 sm:px-6 lg:px-8">
        <div class="xl:col-span-8 col-span-12">
            <div class="box border">
                <div class="box-body">
                    @if (session('status'))
                        <div class="alert alert-success mb-4">{{ session('status') }}</div>
                    @endif

                    @if($rating->review)
                        <div class="mb-6">
                            <h4 class="font-semibold mb-2">Review</h4>
                            <div class="p-4 rounded-lg">
                                <p>{{ $rating->review }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="mb-6 p-4 rounded-lg">
                        <label class="text-xs text-textmuted">Rated item</label>
                        @if($rating->rateable)
                            @if($rating->rateable_type === \App\Models\Company::class)
                                <p class="font-medium mt-1">Company: {{ $rating->rateable->name }}</p>
                                <a href="{{ route('moderator.companies.show', $rating->rateable) }}"
                                    class="text-primary text-sm">View company &rarr;</a>
                            @elseif($rating->rateable_type === \App\Models\JobPosting::class)
                                <p class="font-medium mt-1">Job: {{ $rating->rateable->title }}</p>
                                @if($rating->rateable->company)
                                    <p class="text-sm text-textmuted">Company: {{ $rating->rateable->company->name }}</p>
                                @endif
                                <a href="{{ route('moderator.jobs.show', $rating->rateable->id) }}"
                                    class="text-primary text-sm">View job &rarr;</a>
                            @elseif($rating->rateable_type === \App\Models\ApplicantProfile::class)
                                <p class="font-medium mt-1">Applicant:
                                    {{ $rating->rateable->display_name ?? $rating->rateable->user?->name ?? '—' }}</p>
                            @else
                                <p class="font-medium mt-1">{{ class_basename($rating->rateable_type) }}
                                    #{{ $rating->rateable_id }}</p>
                            @endif
                        @else
                            <p class="font-medium mt-1 text-textmuted">Unknown (type:
                                {{ class_basename($rating->rateable_type) }}, id: {{ $rating->rateable_id }})</p>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="text-xs text-textmuted">Created At</label>
                            <p class="font-medium">{{ $rating->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-textmuted">Updated At</label>
                            <p class="font-medium">{{ $rating->updated_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="border-t pt-4 mt-6">
                        <h4 class="font-semibold mb-4">Actions</h4>
                        <div class="flex flex-wrap gap-2">
                            @if(!$rating->is_hidden)
                                <button type="button" class="ti-btn ti-btn-secondary"
                                    onclick="document.getElementById('hide-section').classList.toggle('hidden')">
                                    <i class="ri-eye-off-line me-1"></i> Hide Rating
                                </button>
                            @else
                                <form action="{{ route('moderator.ratings.unhide', $rating) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    <button type="submit" class="ti-btn ti-btn-success">
                                        <i class="ri-eye-line me-1"></i> Unhide Rating
                                    </button>
                                </form>
                            @endif

                            @if(!$rating->is_flagged)
                                <button type="button" class="ti-btn ti-btn-warning"
                                    onclick="document.getElementById('flag-section').classList.toggle('hidden')">
                                    <i class="ri-flag-line me-1"></i> Flag Rating
                                </button>
                            @else
                                <form action="{{ route('moderator.ratings.unflag', $rating) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    <button type="submit" class="ti-btn ti-btn-outline-warning">
                                        <i class="ri-flag-off-line me-1"></i> Unflag Rating
                                    </button>
                                </form>
                            @endif

                            <form action="{{ route('moderator.ratings.destroy', $rating) }}" method="POST"
                                class="inline"
                                onsubmit="return confirm('Are you sure you want to permanently delete this rating? This cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ti-btn ti-btn-danger">
                                    <i class="ri-delete-bin-line me-1"></i> Delete Permanently
                                </button>
                            </form>
                        </div>

                        {{-- Hide Form --}}
                        <div id="hide-section" class="hidden mt-4 p-4 border rounded-lg bg-secondary/5">
                            <form action="{{ route('moderator.ratings.hide', $rating) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Notes (optional)</label>
                                    <textarea name="notes" class="form-control" rows="2"
                                        placeholder="Why is this rating being hidden?"></textarea>
                                </div>
                                <button type="submit" class="ti-btn ti-btn-secondary">Confirm Hide</button>
                            </form>
                        </div>

                        {{-- Flag Form --}}
                        <div id="flag-section" class="hidden mt-4 p-4 border rounded-lg bg-warning/5">
                            <form action="{{ route('moderator.ratings.flag', $rating) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Flag Reason <span class="text-danger">*</span></label>
                                    <textarea name="reason" class="form-control" rows="2" required
                                        placeholder="Why is this rating being flagged?"></textarea>
                                </div>
                                <button type="submit" class="ti-btn ti-btn-warning">Confirm Flag</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="xl:col-span-4 col-span-12">
            {{-- Reviewer Info --}}
            <div class="box border">
                <div class="box-header">
                    <div class="box-title">Reviewer</div>
                </div>
                <div class="box-body">
                    @if($rating->user)
                        <div class="flex items-center gap-3 mb-3">
                            <span class="avatar avatar-lg avatar-rounded bg-primary/10 text-primary">
                                {{ strtoupper(substr($rating->user->name, 0, 2)) }}
                            </span>
                            <div>
                                <p class="font-medium">{{ $rating->user->name }}</p>
                                <p class="text-xs text-textmuted">{{ $rating->user->email }}</p>
                            </div>
                        </div>
                        <p class="text-sm"><strong>Role:</strong> {{ ucfirst($rating->user->role) }}</p>
                        <p class="text-sm"><strong>Registered:</strong> {{ $rating->user->created_at->format('M d, Y') }}
                        </p>
                    @else
                        <p class="text-textmuted">User not found</p>
                    @endif
                </div>
            </div>

            {{-- Moderation History --}}
            @if($rating->moderated_at)
                <div class="box border mt-4">
                    <div class="box-header">
                        <div class="box-title">Moderation History</div>
                    </div>
                    <div class="box-body">
                        <p class="text-sm"><strong>Last Moderated:</strong>
                            {{ $rating->moderated_at->format('M d, Y H:i') }}</p>
                        @if($rating->moderatedBy)
                            <p class="text-sm"><strong>By:</strong> {{ $rating->moderatedBy->name }}</p>
                        @endif
                        @if($rating->moderation_notes)
                            <p class="text-sm mt-2"><strong>Notes:</strong></p>
                            <p class="text-sm text-textmuted">{{ $rating->moderation_notes }}</p>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

</x-app-layout>