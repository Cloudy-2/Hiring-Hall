{{-- Mobile Skeleton UI for Chat Messages --}}
<div id="mobile-skeleton-messages" class="skeleton-messages space-y-3 p-3">
    @for($i = 0; $i < 6; $i++)
        @php $isShort = $i % 3 === 2; @endphp
        <div class="flex gap-2 animate-pulse">
            {{-- Avatar skeleton --}}
            <div class="size-8 rounded-full bg-gray-200 dark:bg-white/10 flex-shrink-0"></div>
            
            <div class="flex-1 space-y-1.5">
                {{-- Name and time --}}
                <div class="flex items-center gap-2">
                    <div class="h-3.5 w-20 bg-gray-200 dark:bg-white/10 rounded"></div>
                    <div class="h-2.5 w-10 bg-gray-100 dark:bg-white/5 rounded"></div>
                </div>
                
                {{-- Message body lines --}}
                <div class="space-y-1">
                    <div class="h-3.5 bg-gray-200 dark:bg-white/10 rounded {{ $isShort ? 'w-1/3' : 'w-full max-w-[280px]' }}"></div>
                    @if(!$isShort)
                        <div class="h-3.5 w-3/5 max-w-[200px] bg-gray-200 dark:bg-white/10 rounded"></div>
                    @endif
                </div>
                
                {{-- Occasional attachment skeleton --}}
                @if($i === 1 || $i === 4)
                    <div class="mt-1.5 h-24 w-36 bg-gray-200 dark:bg-white/10 rounded-lg"></div>
                @endif
            </div>
        </div>
    @endfor
</div>
