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
    class="group relative rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-lg transition-all duration-300 {{ $user->id === auth()->id() ? 'ring-2 ring-primary/30' : '' }}">
    {{-- Top Banner --}}
    <div class="h-12 relative" style="background: linear-gradient(135deg, {{ $roleColor }}dd, {{ $roleColor }}88);">
        <div class="absolute top-2 right-2">
            <span
                class="inline-flex items-center text-[10px] font-bold px-2 py-0.5 rounded-full bg-white/20 text-white backdrop-blur-sm">
                {{ ucfirst($user->role ?? 'Unknown') }}
            </span>
        </div>
        @if($user->id === auth()->id())
            <div class="absolute top-2 left-2">
                <span
                    class="inline-flex items-center text-[10px] font-bold px-2 py-0.5 rounded-full bg-white/20 text-white backdrop-blur-sm">
                    <i class="bi bi-star-fill me-1 text-[8px]"></i> You
                </span>
            </div>
        @endif
    </div>
    {{-- Avatar --}}
    <div class="flex justify-center -mt-8 relative z-10">
        <span class="avatar avatar-xl ring-4 ring-white dark:ring-gray-800 rounded-full shadow-md">
            <img src="{{ $user->profile_photo_path ? asset('storage/' . ltrim($user->profile_photo_path, '/')) : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode($user->name ?? 'User') . '&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981' }}"
                alt="{{ $user->name }}" class="rounded-full">
        </span>
    </div>
    {{-- Info --}}
    <div class="p-4 pt-2 text-center">
        <h6 class="font-semibold text-gray-800 dark:text-white text-sm truncate">
            {{ $user->name }}
        </h6>
        <p class="text-xs text-gray-500 dark:text-gray-400 truncate mt-0.5">
            {{ $user->email }}
        </p>
        <div class="flex items-center justify-center gap-3 mt-2">
            <span class="text-[10px] text-gray-400">
                <i class="bi bi-calendar3 me-0.5"></i>{{ $user->created_at->format('M d, Y') }}
            </span>
            @if($user->email_verified_at)
                <span class="text-[10px] text-success font-semibold">
                    <i class="bi bi-patch-check-fill me-0.5"></i>Verified
                </span>
            @else
                <span class="text-[10px] text-danger font-semibold">
                    <i class="bi bi-clock-history me-0.5"></i>Unverified
                </span>
            @endif
        </div>
        {{-- Action Buttons --}}
        <div class="flex items-center justify-center gap-1.5 mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
            <a href="{{ route('profile.public', $user) }}" class="ti-btn ti-btn-sm ti-btn-soft-primary !py-1 !px-2"
                title="View Profile">
                <i class="bi bi-eye text-xs"></i>
            </a>
            @if($user->id !== auth()->id())
                <a href="{{ route('chats.v2', ['user' => $user->id]) }}"
                    class="ti-btn ti-btn-sm ti-btn-soft-info !py-1 !px-2" title="Message">
                    <i class="bi bi-chat-dots text-xs"></i>
                </a>
                <button type="button" onclick="confirmImpersonate({{ $user->id }}, '{{ e($user->name) }}')"
                    class="ti-btn ti-btn-sm ti-btn-soft-warning !py-1 !px-2" title="Impersonate">
                    <i class="bi bi-incognito text-xs"></i>
                </button>
            @endif
        </div>
    </div>
</div>