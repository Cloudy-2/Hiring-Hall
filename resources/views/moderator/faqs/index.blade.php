<x-app-layout page-title="FAQs">
    <x-slot name="url_1">{"link": "/moderator/dashboard", "text": "Moderator"}</x-slot>
    <x-slot name="active">FAQ Management</x-slot>

    <style>
        /* Dark Mode Overrides */
        [data-theme-mode="dark"] .box,
        .dark .box,
        [data-theme-mode="dark"] .bg-white,
        .dark .bg-white {
            background-color: rgba(255, 255, 255, 0.02) !important;
            border-color: rgba(255, 255, 255, 0.05) !important;
        }

        [data-theme-mode="dark"] .box-header,
        .dark .box-header {
            border-bottom-color: rgba(255, 255, 255, 0.05) !important;
            background-color: rgba(255, 255, 255, 0.01) !important;
        }

        [data-theme-mode="dark"] thead,
        .dark thead,
        [data-theme-mode="dark"] .bg-gray-50\/50,
        .dark .bg-gray-50\/50 {
            background-color: rgba(255, 255, 255, 0.02) !important;
        }

        [data-theme-mode="dark"] td,
        .dark td,
        [data-theme-mode="dark"] th,
        .dark th {
            border-color: rgba(255, 255, 255, 0.05) !important;
        }
    </style>
    </style>

    <x-modern-header chip="Knowledge Base" title="FAQ Management"
        desc="Manage frequently asked questions and categories.">
        <x-slot:actions>
            <a href="{{ route('moderator.faqs.create') }}" class="ti-btn ti-btn-primary">
                <i class="ri-add-line me-1"></i> Add FAQ
            </a>
        </x-slot:actions>
    </x-modern-header>

    <div class="grid grid-cols-12 gap-6 mx-auto pb-6 sm:px-6 lg:px-8">
        <div class="xl:col-span-12 col-span-12">
            <div class="box border">
                <div class="box-header flex items-center justify-between">
                    <div class="box-title flex items-center gap-4">
                        FAQ Management
                        @if(!empty($categories))
                            <select class="form-control form-control-sm w-auto"
                                onchange="window.location.href='{{ route('moderator.faqs.index') }}?category=' + this.value">
                                <option value="">All Categories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" {{ $category === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                </div>
                <div class="box-body">
                    @if (session('status'))
                        <div class="alert alert-success mb-4">{{ session('status') }}</div>
                    @endif

                    @if($faqs->isEmpty())
                        <div class="text-center py-8">
                            <i class="ri-question-answer-line text-4xl text-textmuted mb-3"></i>
                            <p class="text-textmuted">No FAQs found.</p>
                            <a href="{{ route('moderator.faqs.create') }}" class="ti-btn ti-btn-primary mt-4">
                                Add First FAQ
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table whitespace-nowrap table-bordered" id="faq-table">
                                <thead>
                                    <tr>
                                        <th class="w-10">Order</th>
                                        <th>Question</th>
                                        <th>Category</th>
                                        <th>Status</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($faqs as $faq)
                                        <tr data-id="{{ $faq->id }}" class="{{ !$faq->is_active ? 'opacity-50' : '' }}">
                                            <td>
                                                <span class="badge bg-secondary/10 text-secondary">{{ $faq->sort_order }}</span>
                                            </td>
                                            <td>
                                                <div class="max-w-md">
                                                    <p class="font-medium truncate">{{ $faq->question }}</p>
                                                    <p class="text-xs text-textmuted truncate">
                                                        {{ Str::limit($faq->answer, 80) }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td>
                                                @if($faq->category)
                                                    <span class="badge bg-primary/10 text-primary">{{ $faq->category }}</span>
                                                @else
                                                    <span class="text-textmuted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($faq->is_active)
                                                    <span class="badge bg-success/10 text-success">Active</span>
                                                @else
                                                    <span class="badge bg-secondary/10 text-secondary">Inactive</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="flex items-center justify-center gap-1">
                                                    <a href="{{ route('moderator.faqs.edit', $faq) }}"
                                                        class="ti-btn ti-btn-sm ti-btn-info" title="Edit">
                                                        <i class="ri-edit-line"></i>
                                                    </a>
                                                    <form action="{{ route('moderator.faqs.toggle-active', $faq) }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit"
                                                            class="ti-btn ti-btn-sm {{ $faq->is_active ? 'ti-btn-success' : 'ti-btn-warning' }}"
                                                            title="{{ $faq->is_active ? 'Deactivate' : 'Activate' }}">
                                                            <i
                                                                class="ri-{{ $faq->is_active ? 'eye-line' : 'eye-off-line' }}"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('moderator.faqs.destroy', $faq) }}" method="POST"
                                                        class="inline"
                                                        onsubmit="return confirm('Are you sure you want to delete this FAQ?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="ti-btn ti-btn-sm ti-btn-danger"
                                                            title="Delete">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $faqs->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</x-app-layout>