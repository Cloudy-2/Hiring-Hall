{{-- CHANNEL/CONVERSATION SIDEBAR - HARDCODED --}}
<div class="w-64 bg-discord-dark flex flex-col border-r">
    {{-- Header --}}
    <div class="px-4 py-3 border-b flex items-center justify-between">
        <h2 class="text-sm font-bold text-white">Friends</h2>
        <button type="button" class="text-slate-400 hover:text-white transition">
            <i class="bi bi-plus-lg"></i>
        </button>
    </div>

    {{-- Search --}}
    <div class="px-3 py-2">
        <input type="text" id="v2-channel-search" placeholder="Find or start a conversation"
            class="w-full bg-discord-darker border border-discord-darker rounded-lg px-3 py-2 text-xs text-white placeholder:text-slate-500 focus:outline-none focus:border-indigo-500 focus:bg-discord-darker">
    </div>

    {{-- Navigation Tabs --}}
    <div class="px-3 py-2 flex gap-2 border-b">
        <button class="px-3 py-1 text-xs font-medium text-white bg-discord-darker rounded hover:bg-discord-dark transition">
            <i class="bi bi-people-fill mr-1"></i>Friends
        </button>
        <button class="px-3 py-1 text-xs font-medium text-discord-muted hover:text-white transition">
            All
        </button>
        <button class="px-3 py-1 text-xs font-medium text-discord-muted hover:text-white transition">
            Pending <span class="badge-unread ml-1">1</span>
        </button>
    </div>

    {{-- Conversations List - Hardcoded --}}
    <div class="flex-1 overflow-y-auto px-2 py-2 space-y-1">
        <!-- Menu Items -->
        <div class="px-2 py-2 rounded hover:bg-discord-darker transition-colors cursor-pointer flex items-center gap-3">
            <i class="bi bi-people-fill text-slate-400"></i>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-medium text-white">Friends</p>
            </div>
            <span class="badge-unread">1</span>
        </div>

        <div class="px-2 py-2 rounded hover:bg-discord-darker transition-colors cursor-pointer flex items-center gap-3">
            <i class="bi bi-chat-dots text-slate-400"></i>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-medium text-white">Message Requests</p>
            </div>
            <span class="badge-unread">1</span>
        </div>

        <div class="px-2 py-2 rounded hover:bg-discord-darker transition-colors cursor-pointer flex items-center gap-3">
            <i class="bi bi-star text-slate-400"></i>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-medium text-white">Nitro</p>
            </div>
        </div>

        <div class="px-2 py-2 rounded hover:bg-discord-darker transition-colors cursor-pointer flex items-center gap-3">
            <i class="bi bi-bag text-slate-400"></i>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-medium text-white">Shop</p>
            </div>
            <span class="text-[10px] bg-red-500 text-white px-2 py-0.5 rounded-full font-bold">NEW</span>
        </div>

        <div class="px-2 py-2 rounded hover:bg-discord-darker transition-colors cursor-pointer flex items-center gap-3">
            <i class="bi bi-compass text-slate-400"></i>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-medium text-white">Quests</p>
            </div>
        </div>

        <!-- Frequent Friends Section -->
        <div class="px-2 py-3 mt-2">
            <p class="text-xs font-bold text-discord-muted uppercase tracking-wider">Frequent Friends</p>
            <div class="flex gap-2 mt-2">
                <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center text-white text-xs font-bold hover:scale-110 transition-transform cursor-pointer">
                    D
                </div>
                <div class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center text-white text-xs font-bold hover:scale-110 transition-transform cursor-pointer">
                    M
                </div>
                <div class="h-8 w-8 rounded-full bg-purple-500 flex items-center justify-center text-white text-xs font-bold hover:scale-110 transition-transform cursor-pointer">
                    K
                </div>
                <div class="h-8 w-8 rounded-full bg-red-500 flex items-center justify-center text-white text-xs font-bold hover:scale-110 transition-transform cursor-pointer">
                    +
                </div>
            </div>
        </div>

        <!-- Direct Messages Section -->
        <div class="px-2 py-3 mt-2">
            <p class="text-xs font-bold text-discord-muted uppercase tracking-wider">Direct Messages</p>
        </div>

        <!-- DM Item 1 -->
        <div class="conversation-item px-2 py-2 rounded hover:bg-discord-darker transition-colors cursor-pointer flex items-center gap-3">
            <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                D
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-medium text-white truncate">Dyno</p>
                <p class="text-xs text-discord-muted truncate">dyno.gg | help</p>
            </div>
        </div>

        <!-- DM Item 2 -->
        <div class="conversation-item px-2 py-2 rounded hover:bg-discord-darker transition-colors cursor-pointer flex items-center gap-3">
            <div class="h-8 w-8 rounded-full bg-purple-500 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                W
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-medium text-white truncate">wuwa bot</p>
                <p class="text-xs text-discord-muted truncate">Wuthering Waves</p>
            </div>
        </div>

        <!-- DM Item 3 -->
        <div class="conversation-item px-2 py-2 rounded hover:bg-discord-darker transition-colors cursor-pointer flex items-center gap-3">
            <div class="h-8 w-8 rounded-full bg-pink-500 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                P
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-medium text-white truncate">ProBot</p>
                <p class="text-xs text-discord-muted truncate">✨</p>
            </div>
        </div>

        <!-- DM Item 4 -->
        <div class="conversation-item px-2 py-2 rounded hover:bg-discord-darker transition-colors cursor-pointer flex items-center gap-3">
            <div class="h-8 w-8 rounded-full bg-cyan-500 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                S
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-medium text-white truncate">Sx4 Canary</p>
                <p class="text-xs text-discord-muted truncate">🤖</p>
            </div>
        </div>

        <!-- DM Item 5 -->
        <div class="conversation-item px-2 py-2 rounded hover:bg-discord-darker transition-colors cursor-pointer flex items-center gap-3">
            <div class="h-8 w-8 rounded-full bg-yellow-500 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                W
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-medium text-white truncate">Welcomer</p>
                <p class="text-xs text-discord-muted truncate">👋</p>
            </div>
        </div>

        <!-- DM Item 6 -->
        <div class="conversation-item px-2 py-2 rounded hover:bg-discord-darker transition-colors cursor-pointer flex items-center gap-3">
            <div class="h-8 w-8 rounded-full bg-red-500 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                M
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-medium text-white truncate">Maki</p>
                <p class="text-xs text-discord-muted truncate">🎮</p>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="px-3 py-3 border-t flex items-center gap-2">
        <div class="h-8 w-8 rounded-full bg-indigo-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
            N
        </div>
        <div class="min-w-0 flex-1">
            <p class="text-xs font-medium text-white truncate">nacht</p>
            <p class="text-[11px] text-discord-muted">Idle</p>
        </div>
        <button type="button" class="text-discord-muted hover:text-white transition">
            <i class="bi bi-three-dots-vertical text-sm"></i>
        </button>
    </div>
</div>
