@props(['employers' => []])

<div class="ef-grid" id="ef-grid">
    @forelse($employers as $employer)
        <x-employers.employer-card :employer="$employer" />
    @empty
        <div class="col-span-full">
            <div class="cd-empty cd-empty-large">
                <i class="ri-building-2-line"></i>
                <p>No employers found</p>
                <p class="text-xs text-gray-400">Try adjusting your search or filters</p>
            </div>
        </div>
    @endforelse
</div>

<style>
    .ef-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.5rem;
        align-items: stretch;
    }
    
    @media (max-width: 640px) {
        .ef-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
    