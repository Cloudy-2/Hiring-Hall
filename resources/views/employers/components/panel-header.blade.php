{{-- 
    Panel Header Component
    Displays a section/panel header with optional link back/forward.
    
    Props:
    - $title (string): Header title text
    - $link (string, optional): Optional link URL
    - $linkLabel (string, optional): Link label text. Defaults to 'View All'
    - $linkIcon (string, optional): Icon placement: 'prepend' or 'append'. Defaults to 'append'
    - $icon (string, optional): Remixicon class for left side of title
    
    Usage:
    @include('employers.components.panel-header', [
        'title' => 'Recent Applications',
        'link' => route('employer.applications.index'),
        'linkLabel' => 'View All'
    ])
--}}

<div class="edb-panel-head">
    @if($icon)
        <i class="{{ $icon }} me-2"></i>
    @endif
    
    <h2>{{ $title ?? 'Section' }}</h2>
    
    @if($link)
        <a href="{{ $link }}" class="edb-link">
            @if($linkIcon === 'prepend')
                <i class="ri-arrow-left-s-line"></i>
            @endif
            
            {{ $linkLabel ?? 'View All' }}
            
            @if($linkIcon !== 'prepend')
                <i class="ri-arrow-right-s-line"></i>
            @endif
        </a>
    @endif
</div>
