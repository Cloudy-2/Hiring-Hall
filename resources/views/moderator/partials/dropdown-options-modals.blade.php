{{-- Add Option Modal --}}
<div id="addOptionModal" class="hs-overlay hidden ti-modal">
    <div class="hs-overlay-open:mt-7 ti-modal-box mt-0 ease-out">
        <div class="ti-modal-content">
            <div class="ti-modal-header">
                <h6 class="modal-title text-[1rem] font-semibold">Add New Option</h6>
                <button type="button" class="hs-dropdown-toggle !text-[1rem] !font-semibold"
                    data-hs-overlay="#addOptionModal">
                    <span class="sr-only">Close</span>
                    <i class="ri-close-line"></i>
                </button>
            </div>
            <form id="addOptionForm" method="POST">
                @csrf
                <div class="ti-modal-body">
                    <input type="hidden" name="type" id="addOptionType">
                    <div class="mb-3">
                        <label class="ti-form-label">Type</label>
                        <input type="text" id="addOptionTypeLabel" class="ti-form-input" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="ti-form-label" for="addOptionValue">Value <span
                                class="text-danger">*</span></label>
                        <input type="text" name="value" id="addOptionValue" class="ti-form-input" required
                            placeholder="e.g. full_time">
                        <small class="text-textmuted">Internal value (lowercase, no spaces)</small>
                        <span id="addOptionValue-error" class="text-danger text-sm mt-1 block"></span>
                    </div>
                    <div class="mb-3">
                        <label class="ti-form-label" for="addOptionLabel">Label <span
                                class="text-danger">*</span></label>
                        <input type="text" name="label" id="addOptionLabel" class="ti-form-input" required
                            placeholder="e.g. Full Time">
                        <small class="text-textmuted">Display text shown to users</small>
                        <span id="addOptionLabel-error" class="text-danger text-sm mt-1 block"></span>
                    </div>
                    <div class="mb-3">
                        <label class="ti-form-label" for="addOptionSort">Sort Order</label>
                        <input type="number" name="sort_order" id="addOptionSort" class="ti-form-input" value="0"
                            min="0">
                        <small class="text-textmuted">Lower numbers appear first</small>
                        <span id="addOptionSort-error" class="text-danger text-sm mt-1 block"></span>
                    </div>
                </div>
                <div class="ti-modal-footer">
                    <button type="button" class="ti-btn ti-btn-secondary"
                        data-hs-overlay="#addOptionModal">Cancel</button>
                    <button type="submit" class="ti-btn ti-btn-primary" id="addOptionSubmitBtn">Add Option</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const storeUrl = '{{ route("moderator.dropdown-options.store") }}';
        const updateUrlBase = '{{ url("/moderator/dropdown-options") }}';
        const deleteUrlBase = '{{ url("/moderator/dropdown-options") }}';
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

        function setSubmitLoading(btn, loading, label = 'Saving…') {
            if (!btn) return;
            btn.disabled = loading;
            if (loading) {
                btn.dataset.originalHtml = btn.innerHTML;
                btn.innerHTML = '<span class="inline-block animate-spin shrink-0 size-4 border-2 border-current border-transparent rounded-full" role="status"></span> ' + label;
            } else {
                btn.innerHTML = btn.dataset.originalHtml || btn.textContent;
                delete btn.dataset.originalHtml;
            }
        }

        function clearAddOptionErrors() {
            ['addOptionValue', 'addOptionLabel', 'addOptionSort'].forEach(id => {
                const el = document.getElementById(id + '-error');
                if (el) el.textContent = '';
            });
        }

        function showFormErrors(errors, fieldToId) {
            for (const [field, messages] of Object.entries(errors)) {
                const id = fieldToId[field];
                const el = id ? document.getElementById(id + '-error') : null;
                if (el && messages && messages[0]) el.textContent = messages[0];
            }
        }

        function openModal(id) {
            const el = document.getElementById(id);
            if (!el) return;
            if (typeof HSOverlay !== 'undefined' && HSOverlay.open) {
                HSOverlay.open(el);
            } else {
                // Fallback: manually show the modal
                el.classList.remove('hidden');
                el.classList.add('open');
                el.style.display = 'block';
                document.body.classList.add('overflow-hidden');
            }
        }

        function closeModal(id) {
            const el = document.getElementById(id);
            if (!el) return;
            if (typeof HSOverlay !== 'undefined' && HSOverlay.close) {
                HSOverlay.close(el);
            } else {
                el.classList.add('hidden');
                el.classList.remove('open');
                el.style.display = 'none';
                document.body.classList.remove('overflow-hidden');
            }
        }

        // ============ ADD OPTION ============
        document.querySelectorAll('.add-option-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const type = this.dataset.type;
                const label = this.dataset.label;
                document.getElementById('addOptionType').value = type;
                document.getElementById('addOptionTypeLabel').value = label;
                document.getElementById('addOptionValue').value = '';
                document.getElementById('addOptionLabel').value = '';
                document.getElementById('addOptionSort').value = '0';
                clearAddOptionErrors();
                openModal('addOptionModal');
            });
        });

        document.getElementById('addOptionForm').addEventListener('submit', function (e) {
            e.preventDefault();
            clearAddOptionErrors();
            const submitBtn = document.getElementById('addOptionSubmitBtn');
            setSubmitLoading(submitBtn, true, 'Adding…');
            const formData = new FormData(this);

            fetch(storeUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
                .then(res => res.json().then(data => ({ ok: res.ok, status: res.status, data })))
                .then(({ ok, status, data }) => {
                    if (ok && data.status === 'ok') {
                        Swal.fire({
                            icon: 'success', title: 'Success',
                            text: 'Option added successfully.',
                            timer: 1500, showConfirmButton: false
                        }).then(() => location.reload());
                        return;
                    }
                    if (status === 422 && data.errors) {
                        showFormErrors(data.errors, { value: 'addOptionValue', label: 'addOptionLabel', sort_order: 'addOptionSort' });
                        Swal.fire({ icon: 'warning', title: 'Please fix the form', text: data.message || 'Check the fields below and try again.' });
                        return;
                    }
                    Swal.fire({ icon: 'error', title: 'Could not add option', text: data.message || 'Something went wrong.' });
                })
                .catch(() => Swal.fire({ icon: 'error', title: 'Could not add option', text: 'The request failed. Check your connection and try again.' }))
                .finally(() => setSubmitLoading(submitBtn, false));
        });

        // ============ EDIT OPTION (SweetAlert) ============
        document.querySelectorAll('.edit-option-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.dataset.id;
                const currentValue = this.dataset.value;
                const currentLabel = this.dataset.label;
                const currentSort = this.dataset.sort;
                const currentActive = this.dataset.active === '1';

                Swal.fire({
                    title: 'Edit Option',
                    html: `
                    <div class="text-left">
                        <div class="mb-3">
                            <label class="block text-sm font-medium mb-1">Value <span class="text-red-500">*</span></label>
                            <input type="text" id="swal-edit-value" class="swal2-input !mx-0 !w-full" value="${currentValue}" placeholder="e.g. full_time">
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium mb-1">Label <span class="text-red-500">*</span></label>
                            <input type="text" id="swal-edit-label" class="swal2-input !mx-0 !w-full" value="${currentLabel}" placeholder="e.g. Full Time">
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium mb-1">Sort Order</label>
                            <input type="number" id="swal-edit-sort" class="swal2-input !mx-0 !w-full" value="${currentSort}" min="0">
                        </div>
                        <div class="mb-1">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="swal-edit-active" ${currentActive ? 'checked' : ''}>
                                <span class="text-sm font-medium">Active</span>
                            </label>
                            <small class="text-gray-500">Inactive options won't appear in dropdowns</small>
                        </div>
                    </div>
                `,
                    showCancelButton: true,
                    confirmButtonText: 'Save Changes',
                    cancelButtonText: 'Cancel',
                    focusConfirm: false,
                    preConfirm: () => {
                        const value = document.getElementById('swal-edit-value').value.trim();
                        const label = document.getElementById('swal-edit-label').value.trim();
                        if (!value || !label) {
                            Swal.showValidationMessage('Value and Label are required');
                            return false;
                        }
                        return {
                            value: value,
                            label: label,
                            sort_order: parseInt(document.getElementById('swal-edit-sort').value) || 0,
                            is_active: document.getElementById('swal-edit-active').checked ? 1 : 0,
                        };
                    }
                }).then((result) => {
                    if (!result.isConfirmed) return;

                    const formData = new FormData();
                    formData.append('_token', csrfToken);
                    formData.append('_method', 'PATCH');
                    formData.append('value', result.value.value);
                    formData.append('label', result.value.label);
                    formData.append('sort_order', result.value.sort_order);
                    formData.append('is_active', result.value.is_active);

                    fetch(`${updateUrlBase}/${id}`, {
                        method: 'POST',
                        body: formData,
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                    })
                        .then(res => res.json().then(data => ({ ok: res.ok, data })))
                        .then(({ ok, data }) => {
                            if (ok && data.status === 'ok') {
                                Swal.fire({ icon: 'success', title: 'Updated', text: 'Option updated successfully.', timer: 1500, showConfirmButton: false })
                                    .then(() => location.reload());
                            } else {
                                Swal.fire({ icon: 'error', title: 'Could not update', text: data.message || 'Something went wrong.' });
                            }
                        })
                        .catch(() => Swal.fire({ icon: 'error', title: 'Error', text: 'The request failed.' }));
                });
            });
        });

        // ============ DELETE OPTION (SweetAlert) ============
        document.querySelectorAll('.delete-option-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.dataset.id;
                const label = this.dataset.label;

                Swal.fire({
                    title: 'Delete Option',
                    html: `<p>Are you sure you want to delete "<strong>${label}</strong>"?</p><p class="text-sm text-gray-500 mt-2">This action cannot be undone.</p>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    confirmButtonText: 'Delete',
                    cancelButtonText: 'Cancel',
                }).then((result) => {
                    if (!result.isConfirmed) return;

                    const formData = new FormData();
                    formData.append('_token', csrfToken);
                    formData.append('_method', 'DELETE');

                    fetch(`${deleteUrlBase}/${id}`, {
                        method: 'POST',
                        body: formData,
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                    })
                        .then(res => res.json().then(data => ({ ok: res.ok, data })))
                        .then(({ ok, data }) => {
                            if (ok && data.status === 'ok') {
                                Swal.fire({ icon: 'success', title: 'Deleted', text: 'Option deleted successfully.', timer: 1500, showConfirmButton: false })
                                    .then(() => location.reload());
                            } else {
                                Swal.fire({ icon: 'error', title: 'Could not delete', text: data.message || 'Something went wrong.' });
                            }
                        })
                        .catch(() => Swal.fire({ icon: 'error', title: 'Error', text: 'The request failed.' }));
                });
            });
        });
    });
</script>