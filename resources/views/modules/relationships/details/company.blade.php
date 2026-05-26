<x-app-layout>

    @php
        $company = App\Models\Contact::where('id', $id)->first();
    @endphp

    <x-slot name="return">{"link": "/relationship/list", "text": "Back"}</x-slot>
    <x-slot name="url_1">{"link": "/relationship/list", "text": "Manage Relationship"}</x-slot>
    <x-slot name="active">{{ $company->company_name }}</x-slot>

    <div class="grid grid-cols-12 gap-x-6">

        <div class="xxl:col-span-12 col-span-12">
            <div class="box">
                @include('pages.relationships.details.partials.details')
            </div>
        </div>

        <div class="xxl:col-span-12 col-span-12">
            <div class="box">
                <div class="box-body">
                    <h6 class="font-bold text-2xl text-gray-700 dark:text-white">
                        <strong>Relationship Contacts</strong>
                    </h6>
                    <span>You can create and remove the relationship contact here.</span>
                    <hr class="mb-3 !mt-3">
                    @include('pages.relationships.tables.companycontacts')
                </div>
            </div>
        </div>

        <div class="xxl:col-span-12 col-span-12">
            <div class="box">
                <div class="box-body">
                    <h6 class="font-bold text-2xl text-gray-700 dark:text-white">
                        <strong>Relationship Linked Projects</strong>
                    </h6>
                    <span>You can check and navigate the project link of this relationship.</span>
                    <hr class="mb-3 !mt-3">
                    @include('pages.relationships.tables.projects')
                </div>
            </div>
        </div>

    </div>

    @include('pages.relationships.partials.company-contact')

</x-app-layout>