{{-- 
    Status Pill Component
    Displays application/candidate status with semantic colour coding.
    
    Props:
    - $status (string): Status key from statusStyles mapping
    - $label (string, optional): Human label. If not provided, uses mapped label from $statusMap
    - $statusMap (array, optional): Status mapping array. Defaults to common cd-status classes.
    
    Usage:
    @include('employers.components.status-pill', [
        'status' => $app->status,
        'label' => $statusStyles[$app->status]['label'] ?? 'Unknown',
        'statusMap' => $statusStyles
    ])
--}}

@php
    $defaultStatusMap = [
        'applied' => ['class' => 'cd-status-info', 'label' => 'Applied'],
        'submitted' => ['class' => 'cd-status-info', 'label' => 'Applied'],
        'under_review' => ['class' => 'cd-status-warning', 'label' => 'Under Review'],
        'application_viewed' => ['class' => 'cd-status-info', 'label' => 'Viewed'],
        'viewed' => ['class' => 'cd-status-info', 'label' => 'Viewed'],
        'in_progress' => ['class' => 'cd-status-warning', 'label' => 'Interviewing'],
        'accepted' => ['class' => 'cd-status-success', 'label' => 'Accepted'],
        'not_selected' => ['class' => 'cd-status-danger', 'label' => 'Declined'],
        'no_longer_under_consideration' => ['class' => 'cd-status-danger', 'label' => 'Declined'],
        'closed' => ['class' => 'cd-status-gray', 'label' => 'Closed'],
        'archived' => ['class' => 'cd-status-gray', 'label' => 'Archived'],
    ];
    
    $map = $statusMap ?? $defaultStatusMap;
    $statusData = $map[$status] ?? $map['closed'];
    $displayLabel = $label ?? ($statusData['label'] ?? 'Unknown');
@endphp

<span class="cd-status-pill {{ $statusData['class'] ?? 'cd-status-gray' }}" aria-label="{{ $displayLabel }}">
    {{ $displayLabel }}
</span>
