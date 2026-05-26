<x-app-layout>
    <x-slot name="title">Job Form Options</x-slot>

    <x-slot name="url_1">{"link": "/moderator/dashboard", "text": "Moderator"}</x-slot>
    <x-slot name="active">Job Form Options</x-slot>

    <style>
        /* Dark Mode Overrides for Job Form Options */
        [data-theme-mode="dark"] .box,
        .dark .box {
            background-color: rgba(255, 255, 255, 0.02) !important;
            border-color: rgba(255, 255, 255, 0.05) !important;
        }

        [data-theme-mode="dark"] .box-header,
        .dark .box-header {
            border-bottom-color: rgba(255, 255, 255, 0.05) !important;
            background-color: transparent !important;
        }

        [data-theme-mode="dark"] .bg-light,
        .dark .bg-light {
            background-color: rgba(255, 255, 255, 0.03) !important;
        }

        [data-theme-mode="dark"] .hover\:bg-light:hover,
        .dark .hover\:bg-light:hover {
            background-color: rgba(255, 255, 255, 0.06) !important;
        }

        .box-body {
            padding: 0 !important;
        }
    </style>

    <x-modern-header chip="Job Form Options" title="Manage Dropdown Options"
        desc='Manage the dropdown options that appear in the Post a Job form.'>
    </x-modern-header>

    <div class="grid grid-cols-12 gap-6 mx-auto pb-6 sm:px-6 lg:px-8">
        <div class="col-span-12">
            <div class="box-body">
                @if (session('status'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            if (window.Swal) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: @json(session('status')),
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            }
                        });
                    </script>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                    @foreach($optionTypes as $type => $config)
                        <div class="box border mb-0">
                            <div class="box-header bg-light flex items-center justify-between py-2">
                                <div class="flex items-center gap-2">
                                    <i class="{{ $config['icon'] }} text-primary"></i>
                                    <span class="font-semibold text-sm">{{ $config['label'] }}</span>
                                </div>
                                <button type="button" class="ti-btn ti-btn-sm ti-btn-primary add-option-btn"
                                    data-type="{{ $type }}" data-label="{{ $config['label'] }}"
                                    aria-label="Add option for {{ $config['label'] }}">
                                    <i class="ri-add-line" aria-hidden="true"></i>
                                </button>
                            </div>
                            <div class="box-body p-2 max-h-[300px] overflow-y-auto">
                                <div class="space-y-1" id="options-{{ $type }}">
                                    @forelse($options[$type] as $option)
                                        <div class="flex items-center justify-between p-2 rounded hover:bg-light group option-row"
                                            data-id="{{ $option->id }}">
                                            <div class="flex items-center gap-2">
                                                <span class="text-xs text-textmuted">{{ $option->sort_order }}</span>
                                                <span
                                                    class="text-sm {{ !$option->is_active ? 'line-through text-textmuted' : '' }}">{{ $option->label }}</span>
                                                <span class="text-xs text-textmuted">({{ $option->value }})</span>
                                            </div>
                                            <div class="flex items-center gap-1">
                                                <button type="button"
                                                    class="ti-btn ti-btn-icon ti-btn-sm ti-btn-soft-primary edit-option-btn"
                                                    data-id="{{ $option->id }}" data-value="{{ $option->value }}"
                                                    data-label="{{ $option->label }}" data-sort="{{ $option->sort_order }}"
                                                    data-active="{{ $option->is_active ? '1' : '0' }}"
                                                    aria-label="Edit {{ $option->label }}">
                                                    <i class="ri-edit-line text-xs" aria-hidden="true"></i>
                                                </button>
                                                <button type="button"
                                                    class="ti-btn ti-btn-icon ti-btn-sm ti-btn-soft-danger delete-option-btn"
                                                    data-id="{{ $option->id }}" data-label="{{ $option->label }}"
                                                    aria-label="Delete {{ $option->label }}">
                                                    <i class="ri-delete-bin-line text-xs" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-textmuted text-xs text-center py-4">No options yet. Use the button
                                            above to add one.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @include('moderator.partials.dropdown-options-modals')

</x-app-layout>