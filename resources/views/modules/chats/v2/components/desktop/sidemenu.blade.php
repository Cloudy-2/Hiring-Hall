{{-- Desktop Sidemenu Component --}}
@php
    $canCreateGroups = auth()->user()->canCreateGroups();
    $isModerator = auth()->user()->isModerator();
    $maxServers = 10;
    $totalServers = count($servers);
    $displayServers = collect($servers)->take($maxServers);
    $hasMore = $totalServers > $maxServers;

    $getInitials = function ($name) {
        $words = explode(' ', trim($name));
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        return strtoupper(substr($name, 0, 2));
    };

    $getColorFromName = function ($name) {
        $colors = ['#6366f1', '#8b5cf6', '#ec4899', '#f97316', '#10b981', '#3b82f6', '#ef4444', '#f59e0b'];
        $hash = 0;
        for ($i = 0; $i < strlen($name); $i++) {
            $hash = ord($name[$i]) + (($hash << 5) - $hash);
        }
        return $colors[abs($hash) % count($colors)];
    };
@endphp

<div class="chat-v2-sidemenu hidden md:flex h-full w-[60px] flex-col items-center gap-1.5 p-1.5 transition-colors duration-200 bg-sidemenu-light dark:bg-sidemenu-dark border-r border-slate-200 dark:border-white/10">
    {{-- Logo --}}
    <a href="/dashboard" id="tour-logo"
        class="relative flex size-10 items-center justify-center rounded-xl bg-transparent transition-all hover:rounded-2xl overflow-hidden"
        title="Go to Dashboard">
        <img src="/assets/logo.png" alt="logo" class="size-8 object-contain">
    </a>

    <div class="h-0.5 w-6 rounded-full bg-slate-200 dark:bg-white/10"></div>

    {{-- Servers List --}}
    <div id="tour-servers" class="flex flex-1 flex-col items-center gap-1.5 overflow-y-auto scrollbar-hide">
        @forelse($displayServers as $index => $server)
            @php
                $isActive = $selectedConversation?->id === $server['id'];
                $badge = $server['unread'] ?? 0;
                $initials = $getInitials($server['name']);
                $bgColor = $getColorFromName($server['name']);
            @endphp
            <a href="{{ route('chats.v2', ['conversation' => $server['id']]) }}" data-conversation-id="{{ $server['id'] }}"
                data-server-name="{{ $server['name'] }}" data-server-type="{{ $server['type'] ?? 'group' }}"
                data-server-members="{{ $server['member_count'] ?? 0 }}"
                data-server-online="{{ $server['online_count'] ?? 0 }}"
                data-server-desc="{{ $server['description'] ?? '' }}" data-server-initials="{{ $initials }}"
                class="group relative flex size-10 items-center justify-center rounded-full transition-all hover:rounded-xl {{ $isActive ? 'ring-2 ring-primary/60' : '' }}"
                style="background-color: {{ $bgColor }};" title="{{ $server['name'] }}">
                <span class="text-white font-semibold text-sm">{{ $initials }}</span>
                @if($badge > 0)
                    <span
                        class="unread-badge absolute -bottom-0.5 -right-0.5 flex min-w-[1.1rem] items-center justify-center rounded-full border border-sidemenu-light dark:border-sidemenu-dark bg-red-500 px-0.5 text-[9px] font-semibold leading-none text-white">
                        {{ $badge > 99 ? '99+' : $badge }}
                    </span>
                @endif
            </a>
        @empty
            <div class="text-[12px] text-gray-500 dark:text-white/40 text-center px-1">
                @if($canCreateGroups)
                    Join a team
                @else
                    <h3>Waiting</h3>
                @endif
            </div>
        @endforelse

        {{-- View All Button (if more than 10 servers) --}}
        @if($hasMore)
            <button type="button"
                class="flex size-10 items-center justify-center rounded-full bg-slate-200 dark:bg-white/10 text-slate-600 dark:text-white/70 transition hover:rounded-xl hover:bg-slate-300 dark:hover:bg-white/20"
                data-open-chat-modal="all-servers" title="View All ({{ $totalServers }} servers)">
                <span class="text-[10px] font-bold text-black">+{{ $totalServers - $maxServers }}</span>
            </button>
        @endif

        {{-- Only show create group button for non-candidates --}}
        @if($canCreateGroups)
            <button type="button"
                class="flex size-10 items-center justify-center rounded-full bg-green-500 text-white transition hover:rounded-xl hover:bg-green-600"
                data-open-chat-modal="create-group" title="Create or join server">
                <i class="bi bi-plus-lg text-xl"></i>
            </button>
        @endif
    </div>

    {{-- Bottom Actions --}}
    <div id="tour-sidemenu-actions" class="mt-1 flex flex-col items-center gap-1.5">
        {{-- Moderator: Global Feed Button --}}
        @if($isModerator)
            <a href="{{ route('chats.manage.global-feed') }}" id="tour-global-feed"
                class="flex size-10 items-center justify-center rounded-full bg-slate-200 dark:bg-white/10 text-slate-600 dark:text-white/70 transition hover:bg-primary/20 dark:hover:bg-white/20 hover:text-primary dark:hover:text-primary"
                title="Global Feed - View All Messages">
                <i class="bi bi-broadcast text-lg"></i>
                <span class="text-[10px] font-bold text-black">Global Feed</span>
            </a>
        @endif
        {{-- Moderator: Manage Members Button --}}
        @if($isModerator)
            <button type="button" id="tour-manage-members" class="flex size-10 items-center justify-center rounded-full bg-slate-200 dark:bg-white/10 text-slate-600 dark:text-white/70 transition hover:bg-primary/20 dark:hover:bg-white/20 hover:text-primary dark:hover:text-primary"
                data-open-chat-modal="manage-members" title="Manage Members">
                <i class="bi bi-people-fill text-lg"></i>
                <span class="text-[10px] font-bold text-black">Manage Members</span>
            </button>
        @endif
        <button type="button" id="tour-explore" class="flex size-10 items-center justify-center rounded-full bg-slate-200 dark:bg-white/10 text-slate-600 dark:text-white/70 transition hover:bg-primary/20 dark:hover:bg-white/20 hover:text-primary dark:hover:text-primary"
            data-open-chat-modal="explore" title="Explore">
            <i class="bi bi-globe2 text-lg"></i>
            <span class="text-[10px] font-bold text-black">Explore</span>
        </button>
    </div>
</div>
