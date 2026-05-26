{{-- 
    Empty State Component
    Displays empty list state with icon, message, and optional CTA button.
    
    Props:
    - $icon (string): Remixicon class (e.g., 'ri-inbox-archive-line')
    - $message (string): Primary empty message text
    - $subMessage (string, optional): Secondary description text
    - $ctaLabel (string, optional): Call-to-action button text
    - $ctaLink (string, optional): CTA link URL
    - $variant (string, optional): 'default' or 'compact'. Default is 'default'
    
    Usage:
    @include('employers.components.empty-state', [
        'icon' => 'ri-inbox-archive-line',
        'message' => 'No applications yet.',
        'subMessage' => 'Create a job posting to start receiving applications.',
        'ctaLabel' => 'Post a Job',
        'ctaLink' => route('jobs.create')
    ])
--}}

@php
    $variant = $variant ?? 'default';
    $isCompact = $variant === 'compact';
@endphp

<div class="cd-empty">
    <i class="{{ $icon ?? 'ri-inbox-line' }}"></i>
    <p>{{ $message ?? 'No data available.' }}</p>
    
    @if($subMessage)
        <span class="text-sm text-gray-500">{{ $subMessage }}</span>
    @endif
    
    @if($ctaLabel && $ctaLink)
        <a href="{{ $ctaLink }}" class="cd-empty-cta cd-btn cd-btn-sm cd-btn-primary" role="button">
            {{ $ctaLabel }}
        </a>
    @endif
</div>
