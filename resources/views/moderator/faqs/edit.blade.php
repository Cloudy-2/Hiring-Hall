<x-app-layout page-title="Edit FAQ">
    <x-slot name="url_1">{"link": "/moderator/dashboard", "text": "Moderator"}</x-slot>
    <x-slot name="url_2">{"link": "/moderator/faqs", "text": "FAQ Management"}</x-slot>
    <x-slot name="active">Edit FAQ</x-slot>

    <div class="grid grid-cols-12 gap-6">
        <div class="xl:col-span-8 col-span-12">
            <div class="box border">
                <div class="box-header">
                    <div class="box-title">Edit FAQ</div>
                </div>
                <div class="box-body">
                    <form action="{{ route('moderator.faqs.update', $faq) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label">Question <span class="text-danger">*</span></label>
                            <input type="text" name="question" class="form-control @error('question') is-invalid @enderror" value="{{ old('question', $faq->question) }}" required>
                            @error('question')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Answer <span class="text-danger">*</span></label>
                            <textarea name="answer" class="form-control @error('answer') is-invalid @enderror" rows="6" required>{{ old('answer', $faq->answer) }}</textarea>
                            @error('answer')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="form-label">Category</label>
                                <input type="text" name="category" class="form-control @error('category') is-invalid @enderror" value="{{ old('category', $faq->category) }}" list="category-list">
                                <datalist id="category-list">
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat }}">
                                    @endforeach
                                </datalist>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label">Sort Order</label>
                                <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', $faq->sort_order) }}" min="0">
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', $faq->is_active) ? 'checked' : '' }}>
                                <span>Active</span>
                            </label>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="ti-btn ti-btn-primary">
                                <i class="ri-save-line me-1"></i> Update FAQ
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
                    <div class="box-title">Info</div>
                </div>
                <div class="box-body">
                    <p class="text-sm"><strong>Created:</strong> {{ $faq->created_at->format('M d, Y H:i') }}</p>
                    @if($faq->creator)
                        <p class="text-sm"><strong>By:</strong> {{ $faq->creator->name }}</p>
                    @endif
                    <p class="text-sm"><strong>Last Updated:</strong> {{ $faq->updated_at->format('M d, Y H:i') }}</p>
                </div>
            </div>

            <div class="box border mt-4 border-danger">
                <div class="box-header bg-danger/10">
                    <div class="box-title text-danger">Danger Zone</div>
                </div>
                <div class="box-body">
                    <form action="{{ route('moderator.faqs.destroy', $faq) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this FAQ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="ti-btn ti-btn-danger w-full">
                            <i class="ri-delete-bin-line me-1"></i> Delete FAQ
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
