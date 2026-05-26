<x-app-layout>
    <x-slot name="title">Manage Facebook Leads</x-slot>
    <x-slot name="url_1">{"link": "/lead/facebook/list", "text": "Facebook Leads"}</x-slot>
    <x-slot name="url_2">{"link": "/lead/facebook/list", "text": "Manage"}</x-slot>
    <x-slot name="active">Facebook Leads</x-slot>
    <x-slot name="buttons">
        <a href="/crm/lead/facebook/create" class="ti-btn ti-btn-light text-dark bg-white !border-0 btn-wave me-0">
            <i class="bi bi-plus-lg me-1"></i> 
            <span class="mx-1">Register Facebook Lead</span>
        </a>
    </x-slot>
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('v1/style-table.css') }}">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <div class="grid grid-cols-12 gap-6">
        <div class="xl:col-span-12 col-span-12">
            <div class="box custom-box">
                <div class="box-body">
                    <i class="bi bi-info-circle px-1"></i> You can manage the relationship here.
                    <hr class="mb-3 mt-3">
                    @include('pages.CRM.tables.fblist')
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
