@php
    $tagColors = [
        ['name' => 'Indigo', 'value' => '#6366f1'],
        ['name' => 'Blue', 'value' => '#3b82f6'],
        ['name' => 'Green', 'value' => '#22c55e'],
        ['name' => 'Yellow', 'value' => '#eab308'],
        ['name' => 'Orange', 'value' => '#f97316'],
        ['name' => 'Red', 'value' => '#ef4444'],
        ['name' => 'Pink', 'value' => '#ec4899'],
        ['name' => 'Purple', 'value' => '#a855f7'],
        ['name' => 'Slate', 'value' => '#64748b'],
    ];
@endphp

{{-- Hidden trigger for SweetAlert-based tag creation --}}
<div id="create-personal-tag-trigger" data-chat-modal="create-personal-tag" class="hidden"></div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const tagColors = @json($tagColors);
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    // Override the modal open to use SweetAlert instead
    document.querySelectorAll('[data-open-chat-modal="create-personal-tag"]').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();
            e.stopPropagation();
            
            const { value: formValues } = await Swal.fire({
                title: '<i class="bi bi-bookmark-plus text-primary"></i> Create Personal Tag',
                html: `
                    <div class="text-left space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tag Name</label>
                            <input id="swal-tag-name" class="swal2-input !m-0 !w-full" placeholder="My Notes, Ideas, Saved..." maxlength="50" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Color</label>
                            <div class="flex flex-wrap gap-2 justify-center" id="swal-color-picker">
                                ${tagColors.map((c, i) => `
                                    <button type="button" class="swal-color-btn w-8 h-8 rounded-full border-2 transition hover:scale-110 ${i === 0 ? 'border-white ring-2 ring-offset-2' : 'border-transparent'}" 
                                        style="background-color: ${c.value}; --ring-color: ${c.value}" 
                                        data-color="${c.value}" title="${c.name}"></button>
                                `).join('')}
                            </div>
                        </div>
                        <div class="flex items-center gap-3 pt-2">
                            <input type="checkbox" id="swal-tag-private" checked class="w-4 h-4 text-primary rounded border-gray-300 focus:ring-primary">
                            <label for="swal-tag-private" class="text-sm text-gray-700 dark:text-gray-300">
                                <span class="font-medium">Private</span> - Only you can see this tag
                            </label>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: '<i class="bi bi-bookmark-plus-fill mr-1"></i> Create Tag',
                confirmButtonColor: '#2b2bee',
                cancelButtonText: 'Cancel',
                focusConfirm: false,
                customClass: {
                    popup: 'rounded-2xl',
                    title: 'text-lg',
                    htmlContainer: '!px-6',
                },
                didOpen: () => {
                    const popup = Swal.getPopup();
                    const nameInput = popup.querySelector('#swal-tag-name');
                    nameInput.focus();

                    // Color picker logic
                    let selectedColor = tagColors[0].value;
                    popup.querySelectorAll('.swal-color-btn').forEach(btn => {
                        btn.addEventListener('click', () => {
                            popup.querySelectorAll('.swal-color-btn').forEach(b => {
                                b.classList.remove('border-white', 'ring-2', 'ring-offset-2');
                                b.classList.add('border-transparent');
                            });
                            btn.classList.remove('border-transparent');
                            btn.classList.add('border-white', 'ring-2', 'ring-offset-2');
                            btn.style.setProperty('--tw-ring-color', btn.dataset.color);
                            selectedColor = btn.dataset.color;
                        });
                    });

                    // Store selected color for retrieval
                    popup.dataset.selectedColor = selectedColor;
                    popup.addEventListener('click', (e) => {
                        if (e.target.classList.contains('swal-color-btn')) {
                            popup.dataset.selectedColor = e.target.dataset.color;
                        }
                    });
                },
                preConfirm: () => {
                    const popup = Swal.getPopup();
                    const name = popup.querySelector('#swal-tag-name').value.trim();
                    const isPrivate = popup.querySelector('#swal-tag-private').checked;
                    const color = popup.dataset.selectedColor || tagColors[0].value;

                    if (!name) {
                        Swal.showValidationMessage('Please enter a tag name');
                        return false;
                    }

                    return { name, color, is_private: isPrivate };
                }
            });

            if (formValues) {
                // Submit to server
                try {
                    const response = await fetch('{{ route("personal-tags.store") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            name: formValues.name,
                            color: formValues.color,
                            is_private: formValues.is_private ? 1 : 0,
                            icon: 'bookmark',
                        }),
                    });

                    const data = await response.json();

                    if (response.ok) {
                        await Swal.fire({
                            icon: 'success',
                            title: 'Tag Created!',
                            html: `<span class="inline-flex items-center gap-2"><i class="bi bi-bookmark-fill" style="color: ${formValues.color}"></i> <strong>${formValues.name}</strong></span> is ready to use.`,
                            timer: 2000,
                            showConfirmButton: false,
                        });
                        window.location.reload();
                    } else {
                        throw new Error(data.message || 'Failed to create tag');
                    }
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.message,
                    });
                }
            }
        });
    });
});
</script>
