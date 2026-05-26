<x-app-layout page-title="Add FAQ">
    <x-slot name="url_1">{"link": "/moderator/dashboard", "text": "Moderator"}</x-slot>
    <x-slot name="url_2">{"link": "/moderator/faqs", "text": "FAQ Management"}</x-slot>
    <x-slot name="active">Add FAQ</x-slot>

    <div class="grid grid-cols-12 gap-6">
        <div class="xl:col-span-8 col-span-12">
            <div class="box border">
                <div class="box-header">
                    <div class="box-title">Add New FAQ</div>
                </div>
                <div class="box-body">
                    <form action="{{ route('moderator.faqs.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label">Question <span class="text-danger">*</span></label>
                            <input type="text" name="question" class="form-control @error('question') is-invalid @enderror" value="{{ old('question') }}" required placeholder="e.g., How do I create an account?">
                            @error('question')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Answer <span class="text-danger">*</span></label>
                            <textarea name="answer" class="form-control @error('answer') is-invalid @enderror" rows="6" required placeholder="Write the answer here...">{{ old('answer') }}</textarea>
                            @error('answer')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="form-label">Category</label>
                                <input type="text" name="category" class="form-control @error('category') is-invalid @enderror" value="{{ old('category') }}" list="category-list" placeholder="e.g., Account, Jobs, Payments">
                                <datalist id="category-list">
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat }}">
                                    @endforeach
                                </datalist>
                                <small class="text-textmuted">Type a new category or select existing</small>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label">Sort Order</label>
                                <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', 0) }}" min="0">
                                <small class="text-textmuted">Lower numbers appear first</small>
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', true) ? 'checked' : '' }}>
                                <span>Active</span>
                            </label>
                            <small class="text-textmuted">Inactive FAQs won't be displayed publicly</small>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="ti-btn ti-btn-primary">
                                <i class="ri-save-line me-1"></i> Save FAQ
                            </button>
                            <a href="{{ route('moderator.faqs.index') }}" class="ti-btn ti-btn-light">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="xl:col-span-4 col-span-12">
            <div class="box border">
                <div class="box-header">
                    <div class="box-title">Tips</div>
                </div>
                <div class="box-body">
                    <ul class="list-disc list-inside text-sm text-textmuted space-y-2">
                        <li>Keep questions clear and concise</li>
                        <li>Provide detailed but easy-to-understand answers</li>
                        <li>Use categories to group related FAQs</li>
                        <li>Set sort order to control the display sequence</li>
                        <li>Use the active toggle to hide FAQs temporarily</li>
                    </ul>
                </div>
            </div>

            @if(!empty($categories))
            <div class="box border mt-4">
                <div class="box-header">
                    <div class="box-title">Existing Categories</div>
                </div>
                <div class="box-body">
                    <div class="flex flex-wrap gap-2">
                        @foreach($categories as $cat)
                            <span class="badge bg-primary/10 text-primary">{{ $cat }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

</x-app-layout>
