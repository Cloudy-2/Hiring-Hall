<div {{ $attributes->merge(['class' => 'md:grid md:grid-cols-1 gap-6']) }}>
    <x-section-title>
        <x-slot name="title">{{ $title }}</x-slot>
        <x-slot name="description">{{ $description }}</x-slot>
    </x-section-title>

    <div class="mt-5 md:mt-0">
        <div class="px-4 py-5 sm:p-6 bg-white dark:bg-slate-900 shadow sm:rounded-lg border border-gray-100 dark:border-white/10">
            {{ $content }}
        </div>
    </div>
</div>
