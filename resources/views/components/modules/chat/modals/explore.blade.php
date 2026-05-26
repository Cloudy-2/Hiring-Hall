@php
    $groupCollection = collect($groups ?? [])->take(8);
@endphp

<div data-chat-modal="explore" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 px-4 py-10">
    <div class="w-full max-w-2xl rounded-2xl bg-white dark:bg-sidebar-dark p-6 shadow-2xl text-black dark:text-white">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-xl font-semibold text-slate-900 dark:text-white">Join a Group</h3>
                <p class="text-xs text-black dark:text-white/40">Hand-picked communities your teammates are already
                    in.</p>
            </div>
            <button type="button" data-chat-modal-close="explore"
                class="rounded-full p-2 text-red-500 transition hover:bg-red-50 hover:text-red-600 active:scale-95">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <div class="mt-6 grid gap-4 md:grid-cols-2 max-h-[60vh] overflow-y-auto">
            @forelse($groupCollection as $group)
                <div
                    class="rounded-2xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-white/5 p-4 backdrop-blur transition hover:border-primary/50">
                    <div class="flex items-center gap-3">
                        @if($group['avatar'] ?? null)
                            <img src="{{ $group['avatar'] }}" class="size-10 rounded-xl object-cover"
                                alt="{{ $group['name'] }}">
                        @else
                            <div
                                class="size-10 rounded-xl bg-gradient-to-br from-indigo-500/60 to-purple-600/60 text-center text-base font-semibold uppercase leading-10 text-white">
                                {{ \Illuminate\Support\Str::of($group['initials'] ?? 'GR')->take(3) }}
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <p class="text-sm font-semibold text-slate-900 dark:text-white truncate">
                                    {{ $group['name'] }}
                                </p>
                                @if($group['is_public'] ?? false)
                                    <span
                                        class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                        Public
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-black dark:bg-white/10 dark:text-white/60">
                                        Private
                                    </span>
                                @endif
                            </div>
                            <p class="text-[11px] uppercase tracking-wide text-black dark:text-white/40">
                                @if(($group['unread'] ?? 0) > 0)
                                    {{ $group['unread'] }} unread
                                @else
                                    Up to speed
                                @endif
                            </p>
                        </div>
                    </div>
                    <p class="mt-3 text-sm text-black dark:text-white/60 line-clamp-2">
                        @if($group['description'] ?? null)
                            {{ $group['description'] }}
                        @else
                            A group for collaboration and discussion.
                        @endif
                    </p>
                    <div class="mt-4 flex items-center justify-between text-xs text-black">
                        <span class="inline-flex items-center gap-1">
                            <span class="material-symbols-outlined text-base text-black dark:text-white/40">group</span>
                            {{ $group['member_count'] ?? 0 }}
                            {{ ($group['member_count'] ?? 0) === 1 ? 'member' : 'members' }}
                        </span>
                        <a href="{{ route('chats.v2', ['conversation' => $group['id']]) }}"
                            class="inline-flex items-center gap-2 text-primary hover:underline">
                            Jump in
                            <span class="material-symbols-outlined text-base">arrow_forward</span>
                        </a>
                    </div>
                </div>
            @empty
                <div
                    class="col-span-2 rounded-2xl border border-dashed border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-white/5 p-6 text-center text-black dark:text-white/60">
                    <p class="text-base font-semibold text-slate-900 dark:text-white">No active groups yet</p>
                    <p class="text-sm text-black">Once you join or create a group, you'll see curated
                        suggestions here.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-6 flex items-center justify-between text-xs text-black">
            <div>
                Can't find the right squad?
                <button type="button" data-open-chat-modal="create-group"
                    class="inline-flex items-center gap-1.5 rounded-lg bg-gradient-to-r from-primary to-indigo-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:shadow-md hover:brightness-110 active:scale-95">
                    <span class="material-symbols-outlined text-base">add_circle</span>
                    Create Group
                </button>
            </div>
            <button type="button" data-chat-modal-close="explore"
                class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-600 transition-all duration-200 hover:bg-red-500 hover:text-white hover:border-red-500 active:scale-95">
                Close
            </button>
        </div>
    </div>
</div>