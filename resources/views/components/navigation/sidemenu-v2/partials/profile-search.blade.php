<!-- Overlay Toggle Button (Above Avatar) -->
<!-- <div class="sidemenu-toggle-slide w-full flex justify-end px-4 pt-3" style="display: none;">
    <a href="javascript:void(0);" onclick="window.toggleSidemenuV2();" class="text-slate-500 hover:text-indigo-600 transition-colors bg-black/5 hover:bg-black/10 dark:bg-white/5 dark:hover:bg-white/10 w-7 h-7 rounded-lg flex items-center justify-center sidemenu-toggle-inner">
        <i class="ri-indent-decrease text-[18px]"></i>
    </a>
</div> -->

<!-- Profile Section -->
<!-- <div class="sb-profile">
    @php
        $user = auth()->user();
        $avatarUrl = $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name ?? 'User') . '&color=7F9CF5&background=EBF4FF';
    @endphp
    <img src="{{ $avatarUrl }}" alt="Avatar" class="sb-profile-avatar" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name ?? 'User') }}&color=7F9CF5&background=EBF4FF'">
    <div class="sb-profile-info">
        <span class="sb-profile-name">{{ $user->name ?? 'User' }}</span>
        <span class="sb-profile-role">{{ ucfirst($user->role ?? 'User') }}</span>
    </div>
</div> -->

<div class="sb-profile" onclick="window.location.href='/'" style="cursor: pointer;">
    <img  src="{{ asset('assets/raw/hillbcs-logo.png') }}" alt="Avatar" class="sb-hillbcs-logo">
    <div class="sb-hillbcs-info">
        <span class="sb-hillbcs-name">HILL BUSINESS</span>
        <span class="sb-hillbcs-role">CONSULTING SERVICES</span>
    </div>
</div>

<!--
<img src="{{ asset('assets/flogo.webp') }}" alt="Logo" class="py-3">
<hr class="mb-3"> -->

<!-- Search Bar -->
<div class="sb-search-wrapper">
    <i class="ri-search-line sb-search-icon"></i>
    <input type="text" class="sb-search-input" placeholder="Search..." id="sidebar-search">
</div>
