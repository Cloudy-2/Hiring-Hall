{{-- Desktop Skeleton UI for Chat Messages --}}
<div id="desktop-skeleton-messages" class="skeleton-messages space-y-4 p-4">
    @for($i = 0; $i < 8; $i++)
        @php $isShort = $i % 3 === 2; @endphp
        <div class="flex gap-3 animate-pulse">
            {{-- Avatar skeleton --}}
            <div class="size-10 rounded-full bg-gray-200 dark:bg-white/10 flex-shrink-0"></div>
            
            <div class="flex-1 space-y-2">
                {{-- Name and time --}}
                <div class="flex items-center gap-3">
                    <div class="h-4 w-28 bg-gray-200 dark:bg-white/10 rounded"></div>
                    <div class="h-3 w-14 bg-gray-100 dark:bg-white/5 rounded"></div>
                </div>
                
                {{-- Message body lines --}}
                <div class="space-y-1.5">
                    <div class="h-4 bg-gray-200 dark:bg-white/10 rounded {{ $isShort ? 'w-2/5' : 'w-full max-w-md' }}"></div>
                    @if(!$isShort)
                        <div class="h-4 w-3/5 max-w-xs bg-gray-200 dark:bg-white/10 rounded"></div>
                    @endif
                    @if($i % 4 === 0)
                        <div class="h-4 w-2/5 max-w-[200px] bg-gray-200 dark:bg-white/10 rounded"></div>
                    @endif
                </div>
                
                {{-- Occasional attachment skeleton --}}
                @if($i === 2 || $i === 5)
                    <div class="mt-2 h-32 w-48 bg-gray-200 dark:bg-white/10 rounded-lg"></div>
                @endif
            </div>
        </div>
    @endfor
</div>
