{{-- Member Item Component --}}
@php
    $isSystemModerator = $member['is_system_moderator'] ?? false;
    $memberSystemRole = $member['system_role'] ?? null;
    $isStaff = $isSystemModerator || in_array($memberSystemRole, ['admin', 'super_admin']);
    $participantRole = $member['role'] ?? 'member';
    $memberId = $member['user_id'] ?? $member['id'] ?? '';
    $isOwnUser = (int)$memberId === (int)auth()->id();
    $isModerator = auth()->user()->isModerator();
    $isStatusHidden = $member['is_status_hidden'] ?? false;
    $statusClass = $isOnline 
        ? ($member['status'] === 'online' ? 'bg-green-500' : ($member['status'] === 'idle' ? 'bg-yellow-400' : 'bg-red-500'))
        : 'bg-gray-400';
    
    // Determine badge label for staff
    $staffBadgeTitle = match($memberSystemRole) {
        'super_admin' => 'Super Admin',
        'admin' => 'Admin',
        default => 'Moderator'
    };
@endphp

<div class="member-item group relative flex items-center gap-2 px-2 py-1.5 rounded-md hover:bg-slate-100 dark:hover:bg-white/10 transition cursor-pointer" data-user-id="{{ $memberId }}" data-system-role="{{ $memberSystemRole }}">
    <div class="relative flex-shrink-0">
        <img src="{{ $member['avatar'] }}" class="size-7 rounded-md object-cover {{ !$isOnline ? 'opacity-60 grayscale' : '' }}" alt="{{ $member['name'] }}">
        <span data-user-presence="{{ $memberId }}" class="absolute -bottom-0.5 -right-0.5 size-2.5 rounded-full border-2 border-white dark:border-sidebar-dark {{ $statusClass }}"></span>
    </div>
    <div class="min-w-0 flex-1">
        <div class="flex items-center gap-1">
            <span class="text-sm {{ $isOnline ? 'text-gray-800 dark:text-white' : 'text-gray-500 dark:text-white/50' }} truncate">{{ $member['name'] }}</span>
            @if($isStaff)
                <i class="bi bi-patch-check-fill text-[10px] {{ $isOnline ? 'text-blue-500' : 'text-blue-500/50' }}" title="{{ $staffBadgeTitle }}"></i>
            @elseif($participantRole === 'admin')
                <i class="bi bi-shield-fill text-[10px] {{ $isOnline ? 'text-amber-500' : 'text-amber-500/50' }}" title="Admin"></i>
            @endif
        </div>
    </div>
    
    {{-- Own user dropdown (moderators only) --}}
    @if($isModerator && $isOwnUser)
        <button type="button" class="own-status-btn opacity-0 group-hover:opacity-100 flex items-center justify-center size-6 rounded hover:bg-slate-200 dark:hover:bg-white/10 text-gray-500 dark:text-white/50 transition">
            <i class="bi bi-three-dots text-xs"></i>
        </button>
        <div class="own-status-dropdown hidden absolute right-0 top-full mt-1 w-36 rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-sidebar-dark shadow-xl z-[10000] py-1">
            <button type="button" class="toggle-status-visibility w-full flex items-center gap-2 px-3 py-1.5 text-xs text-gray-700 dark:text-white hover:bg-slate-100 dark:hover:bg-white/10 transition" data-hidden="{{ $isStatusHidden ? 'true' : 'false' }}">
                <i class="bi {{ $isStatusHidden ? 'bi-eye' : 'bi-eye-slash' }} text-gray-500 dark:text-white/60"></i>
                <span>{{ $isStatusHidden ? 'Show Status' : 'Hide Status' }}</span>
            </button>
        </div>
    @endif
    
    {{-- Other user dropdown (moderators only, not for own user or other moderators) --}}
    @if($isModerator && !$isOwnUser && !$isSystemModerator)
        <button type="button" class="member-action-btn opacity-0 group-hover:opacity-100 flex items-center justify-center size-6 rounded hover:bg-slate-200 dark:hover:bg-white/10 text-gray-500 dark:text-white/50 transition">
            <i class="bi bi-three-dots text-xs"></i>
        </button>
        <div class="member-action-dropdown hidden absolute right-0 top-full mt-1 w-32 rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-sidebar-dark shadow-xl z-[10000] py-1"
             data-user-id="{{ $memberId }}"
             data-user-name="{{ $member['name'] }}"
             data-conversation-id="{{ $selectedConversation->id }}">
            <button type="button" class="sidebar-mod-action w-full flex items-center gap-2 px-3 py-1.5 text-xs text-gray-700 dark:text-white hover:bg-slate-100 dark:hover:bg-white/10 transition" data-action="mute">
                <i class="bi bi-mic-mute text-amber-500"></i> Mute
            </button>
            <button type="button" class="sidebar-mod-action w-full flex items-center gap-2 px-3 py-1.5 text-xs text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/10 transition" data-action="kick">
                <i class="bi bi-box-arrow-right"></i> Remove
            </button>
        </div>
    @endif
</div>
