@php
    use Illuminate\Support\Facades\Storage;

    $groupConversations = $conversations->filter(fn($c) => (bool) ($c->is_group ?? false));
    $serverFavorites = $serverFavorites
        ?? $groupConversations->map(function ($conv) {
            $abbr = strtoupper(mb_substr($conv->display_title, 0, 2));
            $colorSeed = crc32($conv->display_title);
            $palette = ['#6366f1', '#10b981', '#f97316', '#ec4899', '#0ea5e9', '#a855f7'];
            $color = $palette[$colorSeed % count($palette)];

            return [
                'name' => $conv->display_title,
                'abbr' => $abbr,
                'color' => $color,
                'id' => $conv->id,
            ];
        })->values();

    $quickActions = $quickActions ?? [
        ['icon' => 'bi-stars', 'label' => 'Quests', 'badge' => null, 'iconColor' => 'text-indigo-400'],
    ];
@endphp

<aside class="w-96 flex min-h-0 h-full rounded-2xl overflow-hidden shadow-xl border text-slate-800 bg-white"
    style="border-color:#e2e8f0;background:#ffffff;">
    {{-- SERVER RAIL --}}
    <div class="w-16 flex flex-col items-center py-4 space-y-3 bg-slate-100 h-full"
        style="background:#f3f4f6;">
        <button type="button" class="h-12 w-12 rounded-full bg-indigo-500 flex items-center justify-center text-2xl text-white hover:scale-105 transition">
            <i class="bi bi-discord"></i>
        </button>
        <div class="w-8 border-t border-slate-200 my-1"></div>
        @forelse($serverFavorites as $server)
            <button type="button" class="server-group-btn h-12 w-12 rounded-full flex items-center justify-center text-xs font-semibold transition text-white hover:scale-105"
                style="background:{{ $server['color'] ?? '#6366f1' }}"
                title="{{ $server['name'] }}"
                data-conversation-id="{{ $server['id'] }}">
                {{ $server['abbr'] }}
            </button>
        @empty
            <div data-server-placeholder="1" class="h-12 w-12 rounded-full border border-dashed border-slate-300 text-xs text-slate-400 flex items-center justify-center">
                --
            </div>
        @endforelse
        <button type="button" data-new-chat-trigger="group" class="h-12 w-12 rounded-full bg-white border border-slate-300 text-slate-500 flex items-center justify-center hover:border-indigo-200 hover:bg-indigo-50 transition" title="Create Group">
            <i class="bi bi-plus"></i>
        </button>
        <button type="button" data-open-chat-modal="explore" class="h-12 w-12 rounded-full bg-white border border-slate-200 text-slate-400 flex items-center justify-center hover:text-slate-600 transition" title="Explore">
            <i class="bi bi-compass"></i>
        </button>
        <div class="flex-1"></div>
        <button type="button" data-open-chat-modal="downloads" class="h-12 w-12 rounded-full bg-white border border-slate-200 text-slate-500 flex items-center justify-center hover-border-indigo-200 transition" title="Downloads">
            <i class="bi bi-cloud-arrow-down"></i>
        </button>
    </div>

    {{-- CHAT PANEL --}}
    <div class="flex-1 flex flex-col h-full min-h-0 text-slate-700 bg-white"
        style="background:#ffffff;">
        {{-- Header --}}
        <div class="px-4 py-3 border-b border-slate-200 flex flex-col gap-2 bg-white">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-semibold tracking-wide text-slate-800">Direct Messages</h2>
                <button type="button" data-new-chat-trigger="dm" title="Start DM"
                    class="text-slate-400 text-lg hover:text-slate-600">
                    <i class="bi bi-plus-lg"></i>
                </button>
            </div>
            <div class="relative">
                <input type="text" id="conversation-search" placeholder="Find or start a conversation"
                    class="w-full bg-slate-50 border border-slate-200 rounded-2xl pl-10 pr-4 py-2 text-[13px] text-slate-700 placeholder:text-slate-400 focus:ring-2 focus:ring-indigo-400 focus:border-transparent">
            </div>
        </div>


        {{-- Lists --}}
        <div id="conversations-list"
            class="flex-1 min-h-0 overflow-y-auto text-sm scrollbar-thin scrollbar-track-transparent bg-white"
            style="--tw-scrollbar-thumb:#cbd5f5;background:#ffffff;flex-grow:1;">
            <section class="px-4 pt-4 pb-2 space-y-3">
                <div class="flex items-center justify-between text-[11px] tracking-[0.35em] uppercase text-slate-400">
                    <span>Direct Messages</span>
                    <span class="text-slate-500">{{ $conversations->where('is_group', false)->count() }}</span>
                </div>
                <ul id="dm-conversations-list" class="space-y-1">
            @php
                $dmConversations = $conversations
                    ->filter(fn($c) => !(bool) ($c->is_group ?? false))
                    ->sortByDesc(function ($c) {
                        return $c->last_message_at ?? $c->updated_at;
                    });
            @endphp

            @forelse($dmConversations as $conv)
                @php
                    $rawLastMsg = $conv->last_message_body ?? 'No messages yet';
                    $lastMsg = trim(strip_tags($rawLastMsg));
                    $unread = $conv->unread_count ?? 0;
                    $lastAt = $conv->last_message_at ?? $conv->updated_at;

                    $authUser = auth()->user();

                    // Find the "other" user in this DM
                    $otherParticipant = $conv->participants->firstWhere('user_id', '!=', $authUser->id);
                    $otherUser = $otherParticipant?->user;

                    $avatarUrl =
                        $otherUser?->profile_photo_url ??
                        ($otherUser?->profile_photo_path ? Storage::url($otherUser->profile_photo_path) : null);

                    $initials = strtoupper(mb_substr($conv->display_title, 0, 2));
                @endphp
                <li class="conversation-item group cursor-pointer"
                    data-conversation-id="{{ $conv->id }}"
                    data-conversation-title="{{ $conv->display_title }}" data-is-group="0"
                    data-last-at="{{ optional($lastAt)->toIso8601String() }}"
                    data-created-at="{{ optional($conv->created_at)->toIso8601String() }}"
                    @if ($avatarUrl) data-avatar-url="{{ $avatarUrl }}" @endif
                    @if ($otherUser) data-other-user-id="{{ $otherUser->id }}"
                    data-other-user-name="{{ $otherUser->name }}"
                    data-other-user-email="{{ $otherUser->email }}" @endif>
                    <div class="flex items-center justify-between px-3 py-2 rounded-lg bg-slate-50 border border-transparent group-hover:border-slate-200 group-hover:bg-white transition">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-full flex items-center justify-center overflow-hidden shadow-[inset_0_1px_1px_rgba(255,255,255,.6)] bg-gradient-to-br from-indigo-50 via-white to-indigo-100 border border-indigo-100">
                                @if ($avatarUrl)
                                    <img src="{{ $avatarUrl }}" onerror="this.src = '/user.png'" alt="{{ $conv->display_title }}" class="h-10 w-10 rounded-full object-cover border border-white shadow">
                                @else
                                    <span class="text-[11px] font-semibold tracking-wide text-indigo-600">{{ $initials }}</span>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-black truncate conv-title">{{ $conv->display_title }}</p>
                                <p class="text-[11px] text-slate-500 truncate conv-last-message">{{ $lastMsg }}</p>
                            </div>
                        </div>
                        <div class="flex flex-col items-end gap-1 text-[10px]">
                            <span class="conv-unread-badge {{ $unread ? 'inline-flex' : 'hidden' }} items-center justify-center rounded-full bg-rose-500 text-white px-2 py-0.5">
                                {{ $unread ?: '' }}
                            </span>
                            <span class="text-slate-400 conv-last-at">{{ $lastAt ? $lastAt->format('h:i A') : '' }}</span>
                        </div>
                    </div>
                </li>
            @empty
                <li class="text-[11px] text-slate-400 px-3 py-2">No direct messages yet.</li>
            @endforelse
            </ul>
            </section>

            {{-- Hidden list for group conversations (for JS compatibility) --}}
            <ul id="group-conversations-list" class="hidden"></ul>
        </div>

        {{-- USER FOOTER --}}
        <div class="px-4 py-3 border-t border-slate-200 bg-white flex items-center justify-between text-[13px] relative group mt-auto flex-shrink-0">
            <div class="flex items-center gap-2">
                <div class="h-8 w-8 rounded-full bg-indigo-400 text-white flex items-center justify-center font-semibold">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-800">{{ auth()->user()->name ?? 'Guest' }}</p>
                    <p class="text-[11px] text-slate-400">Idle</p>
                </div>
            </div>
            <div class="flex items-center gap-3 text-slate-400">
                <button type="button" data-open-chat-modal="mic" class="hover:text-slate-600"><i class="bi bi-mic-mute"></i></button>
                <button type="button" data-open-chat-modal="headphones" class="hover:text-slate-600"><i class="bi bi-headphones"></i></button>
                <button type="button" data-open-chat-modal="settings" class="hover:text-slate-600"><i class="bi bi-gear"></i></button>
            </div>

            <div class="hidden group-hover:block absolute bottom-16 left-2 z-40">
                @include('components.modules.chat.profile-popover')
            </div>
        </div>
    </div>
</aside>
