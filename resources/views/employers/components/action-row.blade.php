{{-- 
    Action Row Component
    Displays a horizontal row of action buttons with consistent spacing and keyboard hints.
    
    Props:
    - $actions (array): Array of action items with keys: 'label', 'icon', 'href', 'class' (optional)
    - $showHint (bool, optional): Whether to show keyboard hint below. Defaults to true.
    - $variant (string, optional): 'compact' or 'full'. Defaults to 'full'
    
    Action item structure:
    [
        'label' => 'Button Label',
        'icon' => 'ri-icon-line',    // optional
        'href' => '/path/to/action',  // href OR method
        'method' => 'POST',            // method for form submission
        'class' => 'cd-btn-primary'    // optional, applies to button
    ]
    
    Usage:
    @include('employers.components.action-row', [
        'actions' => [
            ['label' => 'Edit', 'icon' => 'ri-pencil-line', 'href' => route('edit', $id)],
            ['label' => 'Delete', 'icon' => 'ri-delete-bin-line', 'href' => route('delete', $id), 'method' => 'DELETE']
        ]
    ])
--}}

@php
    $variant = $variant ?? 'full';
    $showHint = $showHint ?? true;
    $isCompact = $variant === 'compact';
@endphp

<div class="cd-action-cluster" role="group" aria-label="Actions">
    @foreach($actions as $action)
        @if($action['method'] ?? null)
            <form action="{{ $action['href'] }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                @csrf
                @method(strtoupper($action['method']))
                <button type="submit" class="cd-btn cd-btn-sm {{ $action['class'] ?? 'cd-btn-outline' }}" title="{{ $action['label'] }}">
                    @if($action['icon'] ?? null)
                        <i class="{{ $action['icon'] }} me-1"></i>
                    @endif
                    {{ $action['label'] }}
                </button>
            </form>
        @else
            <a href="{{ $action['href'] }}" class="cd-btn cd-btn-sm {{ $action['class'] ?? 'cd-btn-outline' }}" title="{{ $action['label'] }}">
                @if($action['icon'] ?? null)
                    <i class="{{ $action['icon'] }} me-1"></i>
                @endif
                {{ $action['label'] }}
            </a>
        @endif
    @endforeach
</div>

@if($showHint && !$isCompact)
    <div class="cd-kbd-hint" aria-label="Keyboard help">
        <span><span class="cd-kbd">Tab</span> Navigate actions</span>
        <span><span class="cd-kbd">Enter</span>/<span class="cd-kbd">Space</span> Activate</span>
    </div>
@endif
