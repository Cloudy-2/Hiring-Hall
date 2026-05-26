@php
    $href = $href ?? '#';
    $icon = $icon ?? 'ri-circle-line';
    $label = $label ?? 'Menu Item';
    $badge = $badge ?? null;
    $badgeColor = $badgeColor ?? 'primary';
    $active = $active ?? false;
    $submenu = $submenu ?? null;
@endphp

<li class="slide {{ $submenu ? 'has-sub' : '' }} {{ $active ? 'active' : '' }}">
    <a href="{{ $submenu ? 'javascript:void(0);' : $href }}"
        class="side-menu__item {{ $active ? 'active-menu' : '' }} relative transition-all duration-300"
        data-bs-toggle="tooltip" data-bs-placement="right" title="{{ $label }}">
        <div class="absolute left-0 top-0 h-full w-1.5 opacity-0 transition-all duration-300"
            style="background-color: var(--sb-accent);">
        </div>
        <i class="side-menu__icon {{ $icon }}"></i>
        <span class="side-menu__label">{{ $label }}</span>

        @if($badge)
            <span class="ml-auto flex h-5 w-5 side-menu__icon" style="display: block">
                <span class="mx-2 translate-middle badge !rounded-full bg-{{ $badgeColor }} icon-badge"
                    style="display: block; width: 100%;">
                    {{ is_numeric($badge) && $badge > 99 ? '99+' : $badge }}
                </span>
            </span>
        @endif

        @if($submenu)
            <i class="ri-arrow-right-s-line side-menu__angle"></i>
        @endif
    </a>

    @if($submenu)
        <ul class="slide-menu">
            @foreach($submenu as $subitem)
                @include('components.navigation.sidemenu-v2.partials.menu-item', array_merge([
                    'submenu' => null,
                    'badge' => null,
                    'active' => false,
                    'icon' => 'ri-circle-line'
                ], $subitem))
            @endforeach
                        </ul>
    @endif
</li>
