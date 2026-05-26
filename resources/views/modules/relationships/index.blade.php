<x-app-layout>

    <x-slot name="title">Manage Relationships</x-slot>
    <x-slot name="url_1">{"link": "/relationship/list", "text": "Manage Relationship"}</x-slot>
    <x-slot name="active">Relationship</x-slot>

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

        [data-theme-mode="dark"] .custom-box,
        .dark .custom-box {
            background-color: rgba(255, 255, 255, 0.02) !important;
        }
    </style>

    <x-modern-header chip="CRM" title="Manage Relationships" desc="You can manage & add new relationships here.">
        <x-slot:actions>
            <a href="/relationship/create" class="ti-btn ti-btn-primary">
                <i class="ri-add-line me-1"></i> New Relationship
            </a>
        </x-slot:actions>
    </x-modern-header>

    <div class="grid grid-cols-12 gap-6 mx-auto pb-6 sm:px-6 lg:px-8">
        <div class="xl:col-span-12 col-span-12">
            <div class="box">
                <div class="box-body">
                    <div class="custom-box">
                        @include('modules.relationships.tables.company')
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('modules.relationships.partials.preview-modal')

    <script>
        function previewRelationship(row) {
            if (typeof row === 'string') {
                row = JSON.parse(decodeURIComponent(row));
            }

            document.getElementById('preview-company_name').textContent = row.company_name || '-';
            document.getElementById('preview-type').textContent = row.type || '-';
            document.getElementById('preview-lead_source').textContent = row.lead_source || '-';
            document.getElementById('preview-phone').textContent = row.phone || '-';
            document.getElementById('preview-email').textContent = row.email || '-';
            document.getElementById('preview-fax').textContent = row.fax || '-';
            document.getElementById('preview-website').textContent = row.website || '-';
            document.getElementById('preview-license').textContent = row.license || '-';
            document.getElementById('preview-insurance').textContent = row.insurance || '-';
            document.getElementById('preview-notes').textContent = row.notes || '-';

            // Full Address
            let fullAddress = '';
            if (row.address) fullAddress += row.address;
            if (row.city) fullAddress += (fullAddress ? ', ' : '') + row.city;
            if (row.state) fullAddress += (fullAddress ? ', ' : '') + row.state;
            if (row.zip) fullAddress += (fullAddress ? ' ' : '') + row.zip;

            document.getElementById('preview-full-address').textContent = fullAddress || '-';

            if (window.HSOverlay) {
                HSOverlay.open('#preview-modal');
            } else {
                const modal = document.getElementById('preview-modal');
                modal.classList.remove('hidden');
            }
        }

        function remove_data(id, context) {
            if (context !== 'company') return;
            Swal.fire({
                title: 'Delete this relationship?',
                text: 'This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="bi bi-trash me-1"></i> Yes, delete it',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
            }).then(function (result) {
                if (!result.isConfirmed) return;
                var url = '/relationship/' + id;
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = url;
                form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE">';
                document.body.appendChild(form);
                form.submit();
            });
        }
    </script>

</x-app-layout>