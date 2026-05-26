@php
    $roleColors = [
        'applicant' => '#00c875',
        'employer' => '#fdab3d',
        'moderator' => '#3b82f6',
        'admin' => '#e2445c',
    ];
    $roleColor = $roleColors[$user->role] ?? '#c4c4c4';
@endphp
<div
    class="flex items-center justify-between p-4 rounded-lg border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow {{ $user->id === auth()->id() ? 'bg-primary/5 border-primary/20' : 'bg-white dark:bg-gray-800/50' }}">
    {{-- Left: Avatar + User Info --}}
    <div class="flex items-center gap-4">
        <div class="relative">
            <span class="avatar avatar-lg">
                <img src="{{ $user->profile_photo_path ? asset('storage/' . ltrim($user->profile_photo_path, '/')) : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode($user->name ?? 'User') . '&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981' }}"
                    alt="{{ $user->name }}" class="rounded-full">
            </span>
            @if($user->email_verified_at)
                <span
                    class="absolute -bottom-0.5 -right-0.5 w-4 h-4 bg-success rounded-full border-2 border-white dark:border-gray-800 flex items-center justify-center">
                    <i class="bi bi-check text-white text-[8px]"></i>
                </span>
            @endif
        </div>
        <div>
            <div class="flex items-center gap-2">
                <h6 class="font-semibold text-gray-800 dark:text-white text-base">
                    {{ $user->name }}
                </h6>
                @if($user->id === auth()->id())
                    <span class="badge bg-primary/10 text-primary text-[10px] px-1.5 py-0.5 rounded">You</span>
                @endif
                <span class="inline-flex items-center gap-1 text-xs font-medium px-2 py-0.5 rounded-full text-white"
                    style="background-color: {{ $roleColor }};">
                    {{ ucfirst($user->role ?? 'Unknown') }}
                </span>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                <i class="bi bi-envelope me-1"></i>{{ $user->email }}
            </p>
            <div class="flex items-center gap-3 mt-1">
                <span class="text-xs text-gray-400">
                    <i class="bi bi-calendar3 me-1"></i>Joined {{ $user->created_at->format('M d, Y') }}
                </span>
                @if($user->email_verified_at)
                    <span class="text-xs text-success font-semibold">
                        <i class="bi bi-patch-check-fill me-1"></i>Verified
                    </span>
                @else
                    <span class="text-xs text-danger font-semibold">
                        <i class="bi bi-clock-history me-1"></i>Unverified
                    </span>
                @endif
            </div>
        </div>
    </div>
    {{-- Right: Actions --}}
    <div class="flex items-center gap-2">
        <a href="{{ route('profile.public', $user) }}" class="ti-btn ti-btn-sm ti-btn-soft-primary"
            title="View Profile">
            <i class="bi bi-eye"></i>
        </a>
        @if($user->id !== auth()->id())
            <a href="{{ route('chats.v2', ['user' => $user->id]) }}" class="ti-btn ti-btn-sm ti-btn-soft-info"
                title="Send Message">
                <i class="bi bi-chat-dots"></i>
            </a>
            <button type="button" onclick="confirmImpersonate({{ $user->id }}, '{{ e($user->name) }}')"
                class="ti-btn ti-btn-sm ti-btn-soft-warning" title="Impersonate">
                <i class="bi bi-incognito"></i>
            </button>
        @endif
    </div>
</div>