@props([
    'name' => '',
    'title' => '',
    'subtitle' => '',
    'icon' => 'info',
    'maxWidth' => 'md', // sm, md, lg, xl
])

@php
    $maxWidthClasses = [
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        '2xl' => 'max-w-2xl',
    ];
    $widthClass = $maxWidthClasses[$maxWidth] ?? 'max-w-md';
@endphp

<div data-chat-modal="{{ $name }}"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 px-4 py-10 md:items-center">
    <div class="w-full {{ $widthClass }} rounded-2xl bg-white dark:bg-sidebar-dark p-6 shadow-2xl">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                @if($subtitle)
                    <p class="text-[11px] uppercase tracking-[0.3em] text-slate-400 dark:text-white/40">{{ $subtitle }}</p>
                @endif
                <h3 class="text-xl font-semibold text-slate-900 dark:text-white">{{ $title }}</h3>
            </div>
            <button type="button" data-chat-modal-close="{{ $name }}"
                class="rounded-full p-2 text-slate-400 dark:text-white/60 transition hover:bg-slate-100 dark:hover:bg-white/10 hover:text-slate-600 dark:hover:text-white">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        {{-- Content --}}
        <div class="mt-4">
            {{ $slot }}
        </div>
    </div>
</div>
