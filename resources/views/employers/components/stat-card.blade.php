{{-- 
    Stat Card Component
    Displays a single statistic with icon, label, and value.
    
    Props:
    - $icon (string): Remixicon class (e.g., 'ri-briefcase-line')
    - $label (string): Stat label text
    - $value (string|number): Stat value to display
    - $chip (string, optional): Optional chip/badge class for icon background
    - $trend (string, optional): 'up', 'down', or null. Shows trend indicator.
    - $trendValue (string, optional): Trend percentage/value text
    
    Usage:
    @include('employers.components.stat-card', [
        'icon' => 'ri-briefcase-line',
        'label' => 'Active Jobs',
        'value' => 5,
        'chip' => 'edb-icon-purple'
    ])
--}}

<article class="edb-stat-card">
    <span class="edb-stat-icon {{ $chip ?? '' }}">
        <i class="{{ $icon ?? 'ri-question-line' }}"></i>
    </span>
    <div>
        <p class="edb-stat-label">{{ $label ?? 'Stat' }}</p>
        <p class="edb-stat-value">{{ $value ?? '0' }}</p>
        
        @if($trend)
            <p class="edb-stat-trend edb-trend-{{ $trend }}">
                <i class="ri-arrow-{{ $trend === 'up' ? 'up' : 'down' }}-s-line"></i>
                {{ $trendValue ?? '' }}
            </p>
        @endif
    </div>
</article>
