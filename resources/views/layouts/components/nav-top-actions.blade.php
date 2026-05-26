<div class="header-content-right flex items-center gap-1.5 sm:gap-2.5 min-w-0 justify-end">

    <!-- Theme Toggle -->
    <div class="header-element theme-switcher">
        <a href="javascript:;"
            class="theme-toggle-btn w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50/50 dark:bg-slate-800/20 hover:bg-slate-100 dark:hover:bg-slate-800/50 text-slate-600 dark:text-slate-200 group"
            onclick="toggleDarkMode()">
            <i class="theme-toggle-moon ri-moon-line text-[1.15rem] dark:hidden"></i>
            <i class="theme-toggle-sun ri-sun-line text-[1.15rem] hidden dark:block"></i>
        </a>
    </div>

    <!-- Settings (Desktop Only) -->
    <div class="header-element settings-desktop-only">
        <a href="javascript:;"
            class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50/50 dark:bg-slate-800/20 hover:bg-slate-100 dark:hover:bg-slate-800/50 text-slate-500 dark:text-slate-400 group"
            data-hs-overlay="#hs-overlay-switcher">
            <i class="ri-settings-4-line text-[1.15rem]"></i>
        </a>
    </div>

    <!-- Guest Actions -->
    @guest
        <div class="header-element flex items-center gap-2">
            <a href="{{ route('login') }}"
                class="px-4 py-2 text-[13px] font-bold text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-all">
                Login
            </a>
            <a href="{{ route('register') }}"
                class="px-5 py-2.5 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700 shadow-sm hover:shadow-indigo-500/20 transition-all text-[13px]">
                Sign Up
            </a>
        </div>
    @endguest

    <!-- Notifications -->
    @auth
        @php
            $navUnreadNotifs = auth()->user()->unreadNotifications()->count();
            $navUnreadMsgs = \Illuminate\Support\Facades\DB::table('chat_conversation_participants')
                ->where('user_id', auth()->id())
                ->whereNull('left_at')
                ->sum('unread_count');
            $finalNavCount = $navUnreadNotifs + $navUnreadMsgs;
        @endphp
        <div class="header-element relative hs-dropdown ti-dropdown !overflow-visible">
            <button type="button"
                class="relative w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50/50 dark:bg-slate-800/20 hover:bg-slate-100 dark:hover:bg-slate-800/50 text-slate-500 dark:text-slate-400 group !overflow-visible"
                id="messageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="ri-notification-3-line text-[1.15rem]"></i>
                @if($finalNavCount > 0)
                    <div
                        class="absolute top-0 right-0 -mr-1 -mt-1 flex h-4 w-4 items-center justify-center z-[999] pointer-events-none">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-60 jf-pulse-allowed"></span>
                        <span
                            class="relative inline-flex rounded-full h-4 w-4 bg-rose-600 text-[10px] font-black text-white items-center justify-center border border-white dark:border-slate-900 shadow-sm"
                            style="background-color: #e11d48 !important; line-height: 0;">
                            {{ $finalNavCount }}
                        </span>
                    </div>
                @endif
            </button>
            <div class="main-header-dropdown hs-dropdown-menu ti-dropdown-menu hidden z-[1200] shadow-[0_20px_50px_rgba(0,0,0,0.15)] bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800/50 rounded-[24px] mt-4 overflow-hidden header-dropdown-animate"
                style="width: 360px !important; min-width: 360px !important;" data-popper-placement="none">
                <div class="absolute -top-2 w-4 h-4 bg-white dark:bg-slate-900 border-t border-l border-slate-200 dark:border-slate-800/50 z-0"
                    style="right: 22px !important; left: auto !important; transform: rotate(45deg) !important;">
                </div>
                <div class="relative z-10 overflow-hidden rounded-[24px]">
                    <div
                        class="px-5 py-4 bg-white dark:bg-slate-900 border-b border-slate-100 dark:border-slate-800/40">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-2">
                                <p
                                    class="mb-0 text-[11px] font-extrabold uppercase tracking-[0.12em] text-slate-400">
                                    Notifications</p>
                                @if($finalNavCount > 0)
                                    <span
                                        class="inline-flex items-center justify-center w-5 h-5 bg-rose-500 text-white text-[10px] font-black rounded-full ring-4 ring-rose-500/10">
                                        {{ $finalNavCount }}
                                    </span>
                                @endif
                            </div>
                            <a href="javascript:;" onclick="markAllNotificationsRead()"
                                class="text-[10px] font-bold text-indigo-500 hover:text-indigo-600 dark:text-indigo-400 uppercase tracking-wider">Mark
                                all read</a>
                        </div>
                    </div>
                    <ul class="list-none mb-0 max-h-[420px] overflow-y-auto split-scrollbar"
                        id="header-notification-scroll">
                        @forelse(($displayNotifications ?? collect()) as $notification)
                            @php
                                $notifUrl = $notification->data['action_url'] ?? $notification->data['url'] ?? '#';
                                $isUnread = !$notification->read_at;
                            @endphp
                            <li class="relative hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-all group"
                                id="notif-{{ $notification->id }}">
                                @if($isUnread)
                                    <div
                                        class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-indigo-500 rounded-r-full shadow-[0_0_12px_rgba(99,102,241,0.4)]">
                                    </div>
                                @endif
                                <a href="{{ $notifUrl }}" onclick="markNotificationRead('{{ $notification->id }}')"
                                    class="p-5 flex gap-4">
                                    <div
                                        class="w-11 h-11 rounded-[14px] bg-gradient-to-br from-indigo-50 to-indigo-100/50 dark:from-indigo-900/40 dark:to-indigo-900/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center flex-shrink-0 shadow-sm group-hover:scale-105 transition-transform">
                                        <i
                                            class="{{ $notification->data['icon'] ?? 'ri-notification-3-line' }} text-[20px]"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between mb-1">
                                            <p
                                                class="text-[14px] font-extrabold text-slate-800 dark:text-white truncate pr-2">
                                                {{ $notification->data['title'] ?? 'System Update' }}
                                            </p>
                                            <span class="text-[10px] font-medium text-slate-400 whitespace-nowrap">
                                                {{ $notification->created_at ? $notification->created_at->diffForHumans(null, true) : '' }}
                                            </span>
                                        </div>
                                        <p
                                            class="text-[12px] text-slate-500 dark:text-slate-400 leading-relaxed mb-0 line-clamp-2">
                                            {{ $notification->data['message'] ?? 'Check your account for new updates.' }}
                                        </p>
                                    </div>
                                </a>
                            </li>
                        @empty
                            <li class="p-12 text-center">
                                <div
                                    class="w-16 h-16 rounded-[20px] bg-slate-50 dark:bg-slate-800/40 text-slate-300 dark:text-slate-600 flex items-center justify-center mx-auto mb-4 border border-slate-100 dark:border-slate-800/50">
                                    <i class="ri-notification-off-line text-3xl"></i>
                                </div>
                                <p class="text-sm font-bold text-slate-800 dark:text-white mb-1 tracking-tight">All
                                    caught up!</p>
                                <p class="text-xs text-slate-400 tracking-tight">You have no unread notifications.</p>
                            </li>
                        @endforelse
                    </ul>
                    <div
                        class="p-4 bg-slate-50 dark:bg-slate-900 text-center border-t border-slate-100 dark:border-slate-800/40">
                        <a href="{{ route('notifications.index') }}"
                            class="inline-flex items-center justify-center w-full py-2.5 px-4 rounded-xl text-[11px] font-black text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 dark:text-slate-400 dark:hover:text-white dark:hover:bg-indigo-900/40 transition-all uppercase tracking-[0.15em]">
                            View All Activity <i class="ri-arrow-right-line ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endauth

    <!-- User Profile -->
    @auth
        <div class="header-element hs-dropdown ti-dropdown flex items-center ms-1">
            <a href="javascript:;"
                class="profile-trigger flex items-center gap-2 py-1.5 px-2 rounded-xl bg-slate-50/30 dark:bg-slate-800/10 hover:bg-slate-50 dark:hover:bg-slate-800/50 group"
                id="mainHeaderProfile" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="relative inline-flex header-profile-avatar-wrap">
                    <img src="{{ Auth::user()->profile_photo_path ? asset('storage/' . ltrim(Auth::user()->profile_photo_path, '/')) : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode(Auth::user()->name ?? 'User') . '&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981' }}"
                        alt="{{ Auth::user()->name }}"
                        class="w-8 h-8 rounded-full object-cover header-profile-avatar-img">
                    <span
                        class="avatar-badge absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-500 rounded-full border-2 hidden sm:block"></span>
                </div>
                <div class="flex items-center gap-1.5 hidden sm:flex">
                    <span class="text-[13px] font-medium text-slate-400 header-welcome">Welcome,&nbsp;</span>
                    <span
                        class="text-[13px] font-bold text-slate-700 header-username">{{ Str::of(Auth::user()->name)->before(' ') }}</span>
                    <i
                        class="ri-arrow-down-s-line text-slate-300 dark:text-slate-500 group-hover:text-slate-600 transition-none"></i>
                </div>
            </a>
            <div class="main-header-dropdown profile-header-dropdown hs-dropdown-menu ti-dropdown-menu hidden shadow-[0_20px_50px_rgba(0,0,0,0.15)] bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800/50 rounded-[28px] mt-4 w-[280px] overflow-visible header-dropdown-animate"
                aria-labelledby="mainHeaderProfile">

                <div class="relative z-10 overflow-hidden rounded-[28px]">
                    <!-- Profile Header -->
                    <div
                        class="p-6 flex items-center gap-4 bg-white dark:bg-slate-900 border-b border-slate-50 dark:border-slate-800/40">
                        <img src="{{ Auth::user()->profile_photo_path ? asset('storage/' . ltrim(Auth::user()->profile_photo_path, '/')) : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode(Auth::user()->name ?? 'User') . '&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981' }}"
                            alt="{{ Auth::user()->name }}"
                            class="w-12 h-12 rounded-full object-cover ring-4 ring-slate-50 dark:ring-slate-800/50 shadow-sm flex-shrink-0">
                        <div class="flex-1 min-w-0">
                            <p
                                class="text-[15px] font-bold text-slate-800 dark:text-white truncate mb-0.5 leading-tight tracking-tight">
                                {{ Auth::user()->name }}</p>
                            <p
                                class="text-[12px] font-medium text-slate-400 dark:text-slate-500 truncate mb-0 leading-tight">
                                {{ Auth::user()->email }}</p>
                        </div>
                    </div>

                    <!-- Menu Items -->
                    <div class="p-3 space-y-1 user-profile-menu-items">
                        <!-- Theme Switcher (Mobile Only) -->
                        <a class="theme-settings-mobile flex items-center gap-4 p-3 rounded-2xl text-[14px] font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800/50 group transition-all"
                            href="javascript:;" data-hs-overlay="#hs-overlay-switcher">
                            <div class="w-5 flex items-center justify-center">
                                <i
                                    class="ri-settings-4-line text-[19px] text-slate-400 group-hover:text-slate-600 dark:group-hover:text-white transition-colors"></i>
                            </div>
                            Theme settings
                        </a>

                        <a class="user-dropdown-item flex items-center gap-4 p-3 rounded-2xl text-[14px] font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800/50 group transition-all"
                            href="/user/profile">
                            <div class="w-5 flex items-center justify-center">
                                <i
                                    class="ri-user-3-line text-[19px] text-slate-400 group-hover:text-slate-600 dark:group-hover:text-white transition-colors"></i>
                            </div>
                            Account settings
                        </a>
                        <a class="user-dropdown-item flex items-center gap-4 p-3 rounded-2xl text-[14px] font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800/50 group transition-all"
                            href="{{ route('faq.index') }}">
                            <div class="w-5 flex items-center justify-center">
                                <i
                                    class="ri-question-line text-[19px] text-slate-400 group-hover:text-slate-600 dark:group-hover:text-white transition-colors"></i>
                            </div>
                            Help center
                        </a>
                        <div class="my-1 border-t border-slate-50 dark:border-slate-800/40 mx-2"></div>
                        <a href="{{ route('logout') }}"
                            class="user-dropdown-item flex items-center gap-4 p-3 rounded-2xl text-[14px] font-bold hover:bg-rose-50 dark:hover:bg-rose-900/10 group transition-all"
                            style="color: #ef4444 !important;"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <div class="w-5 flex items-center justify-center">
                                <i class="ri-logout-box-r-line text-[19px] group-hover:opacity-80 transition-opacity"
                                    style="color: #ef4444 !important;"></i>
                            </div>
                            Log out
                        </a>
                        <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                            @csrf</form>
                    </div>
                </div>
            </div>
        </div>
    @endauth

</div>
