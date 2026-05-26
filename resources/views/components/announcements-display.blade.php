@php
    $announcements = \App\Models\Announcement::getActiveForUser(auth()->user());
@endphp

@if($announcements->isNotEmpty())
<div class="mb-4 space-y-3">
    @foreach($announcements as $announcement)
        @php
            $typeStyles = [
                'info' => [
                    'bg' => 'bg-blue-50 dark:bg-blue-900/20',
                    'border' => 'border-blue-200 dark:border-blue-800',
                    'icon' => 'ri-information-line',
                    'iconColor' => 'text-blue-500',
                    'titleColor' => 'text-blue-800 dark:text-blue-200',
                    'textColor' => 'text-blue-700 dark:text-blue-300',
                ],
                'success' => [
                    'bg' => 'bg-green-50 dark:bg-green-900/20',
                    'border' => 'border-green-200 dark:border-green-800',
                    'icon' => 'ri-checkbox-circle-line',
                    'iconColor' => 'text-green-500',
                    'titleColor' => 'text-green-800 dark:text-green-200',
                    'textColor' => 'text-green-700 dark:text-green-300',
                ],
                'warning' => [
                    'bg' => 'bg-yellow-50 dark:bg-yellow-900/20',
                    'border' => 'border-yellow-200 dark:border-yellow-800',
                    'icon' => 'ri-alert-line',
                    'iconColor' => 'text-yellow-500',
                    'titleColor' => 'text-yellow-800 dark:text-yellow-200',
                    'textColor' => 'text-yellow-700 dark:text-yellow-300',
                ],
                'danger' => [
                    'bg' => 'bg-red-50 dark:bg-red-900/20',
                    'border' => 'border-red-200 dark:border-red-800',
                    'icon' => 'ri-error-warning-line',
                    'iconColor' => 'text-red-500',
                    'titleColor' => 'text-red-800 dark:text-red-200',
                    'textColor' => 'text-red-700 dark:text-red-300',
                ],
            ];
            $style = $typeStyles[$announcement->type] ?? $typeStyles['info'];
        @endphp
        <div class="rounded-lg border p-4 {{ $style['bg'] }} {{ $style['border'] }} {{ $announcement->is_pinned ? 'ring-2 ring-primary/30' : '' }}">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 mt-0.5">
                    <i class="{{ $style['icon'] }} text-xl {{ $style['iconColor'] }}"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <h4 class="font-semibold {{ $style['titleColor'] }}">{{ $announcement->title }}</h4>
                        @if($announcement->is_pinned)
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-primary/10 text-primary">
                                <i class="ri-pushpin-fill me-1"></i> Pinned
                            </span>
                        @endif
                    </div>
                    <p class="text-sm {{ $style['textColor'] }}">{{ $announcement->content }}</p>
                    <p class="text-xs mt-2 opacity-60 {{ $style['textColor'] }}">
                        {{ $announcement->published_at?->diffForHumans() ?? $announcement->created_at->diffForHumans() }}
                    </p>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endif
