{{-- 
    Sidemenu V2 - Category/Section Header
    
    Usage:
    @include('components.navigation.sidemenu-v2.partials.category', ['name' => 'Dashboard'])
--}}

<li class="slide__category" data-category-name="{{ $name ?? 'Category' }}" data-category-icon="{{ $icon ?? 'ri-folder-2-fill' }}">
    <span class="category-name">
        @if(isset($icon))
            <i class="{{ $icon }} me-2 align-middle text-lg"></i>
        @endif
        {{ $name ?? 'Category' }}
    </span>
</li>
