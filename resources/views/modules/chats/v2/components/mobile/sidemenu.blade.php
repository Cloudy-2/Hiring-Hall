{{-- Mobile Sidemenu Component (Slide-out) --}}
@php
    $getInitials = function($name) {
        $words = explode(' ', trim($name));
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        return strtoupper(substr($name, 0, 2));
    };
    
    $getColorFromName = function($name) {
        $colors = ['#6366f1', '#8b5cf6', '#ec4899', '#f97316', '#10b981', '#3b82f6', '#ef4444', '#f59e0b'];
        $hash = 0;
        for ($i = 0; $i < strlen($name); $i++) {
            $hash = ord($name[$i]) + (($hash << 5) - $hash);
        }
        return $colors[abs($hash) % count($colors)];
    };
@endphp

<div class="chat-v2-sidemenu md:hidden flex h-full w-[60px] flex-col items-center gap-1.5 py-1.5 px-2 transition-colors duration-200 bg-sidemenu-light dark:bg-sidemenu-dark border-r border-slate-200 dark:border-white/10">
    {{-- Logo --}}
    <a href="/dashboard" class="relative flex size-10 items-center justify-center rounded-xl bg-transparent transition-all hover:rounded-2xl overflow-hidden" title="Go to Dashboard"
        onclick="setTimeout(() => window.closeMobileNav?.(), 100)">
        <img src="/assets/logo.png" alt="logo" class="size-8 object-contain">
    </a>

    <div class="h-0.5 w-6 rounded-full bg-slate-300 dark:bg-white/10"></div>

    {{-- Servers List --}}
    <div class="flex flex-1 flex-col items-center gap-1.5 overflow-y-auto scrollbar-hide">
        @forelse($servers as $index => $server)
            @php
                $isActive = $selectedConversation?->id === $server['id'];
                $badge = $server['unread'] ?? 0;
                $initials = $getInitials($server['name']);
                $bgColor = $getColorFromName($server['name']);
            @endphp
            <a href="{{ route('chats.v2', ['conversation' => $server['id']]) }}"
                onclick="window.closeMobileNav?.()"
                data-conversation-id="{{ $server['id'] }}"
                class="group relative flex size-10 items-center justify-center rounded-full transition-all hover:rounded-xl {{ $isActive ? 'ring-2 ring-primary/60' : '' }}"
                style="background-color: {{ $bgColor }};">
                <span class="text-white font-semibold text-sm">{{ $initials }}</span>
                @if($badge > 0)
                    <span class="unread-badge absolute -bottom-0.5 -right-0.5 flex min-w-[1.1rem] items-center justify-center rounded-full border border-sidemenu-light dark:border-sidemenu-dark bg-red-500 px-0.5 text-[9px] font-semibold leading-none text-white">
                        {{ $badge > 99 ? '99+' : $badge }}
                    </span>
                @endif
            </a>
        @empty
            <div class="text-[10px] text-gray-500 dark:text-white/40 text-center px-1">Join a team</div>
        @endforelse

        <button type="button"
            class="flex size-10 items-center justify-center rounded-full bg-green-500 text-white transition hover:rounded-xl hover:bg-green-600"
            data-open-chat-modal="create-group" title="Create or join server"
            onclick="setTimeout(() => window.closeMobileNav?.(), 100)">
            <i class="bi bi-plus-lg text-xl"></i>
        </button>
    </div>

    {{-- Bottom Actions --}}
    <div class="mt-1 flex flex-col items-center gap-1.5">
        {{-- Join Requests - Only visible for group admins (controlled via JS) --}}
        <button type="button" id="sidemenu-join-requests-btn-mobile" class="hidden relative flex size-10 items-center justify-center rounded-full bg-slate-200 dark:bg-white/10 text-slate-600 dark:text-white/70 transition hover:bg-primary/20 dark:hover:bg-white/20 hover:text-primary"
            data-open-chat-modal="join-requests" title="Join Requests"
            onclick="setTimeout(() => window.closeMobileNav?.(), 100)">
            <i class="bi bi-person-plus-fill text-lg"></i>
            <span id="sidemenu-requests-badge-mobile" class="hidden absolute -top-0.5 -right-0.5 flex min-w-[1rem] items-center justify-center rounded-full bg-red-500 px-0.5 text-[9px] font-semibold text-white">0</span>
        </button>
        <button type="button" class="flex size-10 items-center justify-center rounded-full bg-slate-200 dark:bg-white/10 text-slate-600 dark:text-white/70 transition hover:bg-primary/20 dark:hover:bg-white/20 hover:text-primary"
            data-open-chat-modal="todo" title="My Tasks"
            onclick="setTimeout(() => window.closeMobileNav?.(), 100)">
            <i class="bi bi-check2-square text-lg"></i>
        </button>
        <button type="button" class="flex size-10 items-center justify-center rounded-full bg-slate-200 dark:bg-white/10 text-slate-600 dark:text-white/70 transition hover:bg-primary/20 dark:hover:bg-white/20 hover:text-primary"
            data-open-chat-modal="downloads" title="Downloads"
            onclick="setTimeout(() => window.closeMobileNav?.(), 100)">
            <i class="bi bi-download text-lg"></i>
        </button>
        <button type="button" class="flex size-10 items-center justify-center rounded-full bg-slate-200 dark:bg-white/10 text-slate-600 dark:text-white/70 transition hover:bg-primary/20 dark:hover:bg-white/20 hover:text-primary"
            data-open-chat-modal="explore" title="Explore"
            onclick="setTimeout(() => window.closeMobileNav?.(), 100)">
            <i class="bi bi-globe2 text-lg"></i>
        </button>
    </div>
</div>
