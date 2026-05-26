@php
    use Illuminate\Support\Facades\Storage;

    $user = auth()->user();
    $avatarUrl = $user?->profile_photo_url ?? ($user?->profile_photo_path ? Storage::url($user->profile_photo_path) : null);
    $initials = strtoupper(mb_substr($user->name ?? 'U', 0, 2));
@endphp

<div class="w-72 rounded-2xl bg-slate-900 text-white shadow-2xl overflow-hidden">
    <div class="h-20 bg-gradient-to-r from-indigo-500 via-fuchsia-500 to-rose-500"></div>
    <div class="px-4 pb-4 -mt-10 relative">
        <div class="flex items-end gap-3">
            <div class="h-16 w-16 rounded-full border-4 border-slate-900 overflow-hidden bg-slate-700 flex items-center justify-center text-xl font-semibold">
                @if ($avatarUrl)
                    <img src="{{ $avatarUrl }}" onerror="this.src = '/user.png'" alt="{{ $user?->name }}" class="h-full w-full object-cover">
                @else
                    <span>{{ $initials }}</span>
                @endif
            </div>
            <div class="pb-1 min-w-0">
                <p class="text-sm font-semibold truncate">{{ $user?->name ?? 'Guest' }}</p>
                @if ($user?->email)
                    <p class="text-xs text-slate-300 truncate">{{ $user->email }}</p>
                @endif
            </div>
        </div>

        <div class="mt-4 space-y-1 text-xs text-slate-200/90">
            <div class="flex items-center gap-2">
                <span class="inline-block h-2 w-2 rounded-full bg-amber-400"></span>
                <span>Idle</span>
            </div>
        </div>

        <div class="mt-4 pt-3 border-t border-slate-700/70 space-y-1 text-xs">
            <button type="button" class="w-full flex items-center justify-between px-2.5 py-1.5 rounded-md bg-slate-800/70 hover:bg-slate-700 transition">
                <span class="flex items-center gap-2">
                    <i class="bi bi-pencil-square text-slate-300"></i>
                    <span>Edit profile</span>
                </span>
                <span class="text-[10px] uppercase tracking-wide text-slate-400">Settings</span>
            </button>
            <button type="button" class="w-full flex items-center gap-2 px-2.5 py-1.5 rounded-md hover:bg-slate-800/60 transition">
                <i class="bi bi-circle-half text-slate-300"></i>
                <span>Set status</span>
            </button>
            <button type="button" class="w-full flex items-center gap-2 px-2.5 py-1.5 rounded-md hover:bg-slate-800/60 transition">
                <i class="bi bi-gear text-slate-300"></i>
                <span>Account settings</span>
            </button>
        </div>
    </div>
</div>
