<x-app-layout>

    <x-slot name="title">New Facebook Lead Registration</x-slot>
    <x-slot name="url_1">{"link": "/lead/facebook/list", "text": "Facebook Leads"}</x-slot>
    <x-slot name="url_2">{"link": "/lead/facebook/list", "text": "Manage"}</x-slot>
    <x-slot name="url_3">{"link": "/lead/facebook/contact/list", "text": "Contact"}</x-slot>
    <x-slot name="active">Registration</x-slot>
    <x-slot name="buttons">
    
    </x-slot>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Whoops! Something went wrong.</strong>
            <ul class="mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <hr>
    @endif
    <form action="{{ route('lead.facebook.store') }}" method="POST" enctype="multipart/form-data" id="client_form"
        autocomplete="off" class="space-y-6 p-6 bg-white shadow-md rounded-lg">
        @csrf

        <div class="grid grid-cols-3 gap-6">
            <!-- Date -->
            <div>
                <label for="date" class="block text-sm font-medium text-gray-700">Date <strong
                        class="text-danger">*</strong></label>
                <input type="date" name="date" id="date" 
                    class="mt-1 w-full border ti-form-input ps-3 border-gray-300 p-2 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>

            <!-- Business Name -->
            <div>
                <label for="business_name" class="block text-sm font-medium text-gray-700">Business Name <strong
                        class="text-danger">*</strong></label>
                <input type="text" name="business_name" id="business_name" placeholder="Enter Business Name" 
                    class="mt-1 w-full border ti-form-input ps-3 border-gray-300 p-2 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>

            <!-- Facebook Name -->
            <div>
                <label for="facebook_name" class="block text-sm font-medium text-gray-700">Facebook Name <strong
                        class="text-danger">*</strong></label>
                <input type="text" name="facebook_name" id="facebook_name" placeholder="Enter Facebook Name" 
                    class="mt-1 w-full border ti-form-input ps-3 border-gray-300 p-2 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-6">
            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email <strong
                        class="text-danger">*</strong></label>
                <input type="text" name="email" id="email" placeholder="email@example.com" 
                    class="mt-1 w-full border ti-form-input ps-3 border-gray-300 p-2 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>

            <!-- Contact Number -->
            <div>
                <label for="contact_number" class="block text-sm font-medium text-gray-700">Contact Number <strong
                        class="text-danger">*</strong></label>
                <input type="text" name="contact_number" id="contact_number" placeholder="Enter Contact Number"
                    
                    class="mt-1 w-full border ti-form-input ps-3 border-gray-300 p-2 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>

        </div>

            <!-- Company Problem/Issue -->
            <div>
                <label for="company_problem" class="block text-sm font-medium text-gray-700">Company Problem/Issue
                    <strong class="text-danger">*</strong></label>
                <textarea name="company_problem" id="company_problem" placeholder="Describe the problem" 
                    class="mt-1 w-full border ti-form-input ps-3 border-gray-300 p-2 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"></textarea>
            </div>

        <div class="grid grid-cols-3 gap-6">
            <!-- Location -->
            <div>
                <label for="location" class="block text-sm font-medium text-gray-700">Location <strong
                        class="text-danger">*</strong></label>
                <input type="text" name="location" id="location" placeholder="Enter Location" 
                    class="mt-1 w-full border ti-form-input ps-3 border-gray-300 p-2 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>

            <!-- Number of Employees -->
            <div>
                <label for="no_of_employees" class="block text-sm font-medium text-gray-700">Number of Employees <strong
                        class="text-danger">*</strong></label>
                <input type="number" name="no_of_employees" id="no_of_employees"
                    placeholder="Enter Number of Employees" 
                    class="mt-1 w-full border ti-form-input ps-3 border-gray-300 p-2 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>

            <!-- Interested Service -->
            <div>
                <label for="interested_service" class="block text-sm font-medium text-gray-700">Interested Service
                    <strong class="text-danger">*</strong></label>
                <input type="text" name="interested_service" id="interested_service"
                    placeholder="Enter Interested Service" 
                    class="mt-1 w-full border ti-form-input ps-3 border-gray-300 p-2 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6">
            <!-- Availability -->
            <div>
                <label for="availability" class="block text-sm font-medium text-gray-700">Availability <strong
                        class="text-danger">*</strong></label>
                <input type="text" name="availability" id="availability" placeholder="Enter Availability" 
                    class="mt-1 w-full border ti-form-input ps-3 border-gray-300 p-2 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                <textarea name="notes" id="notes" placeholder="Additional notes"
                    class="mt-1 w-full border ti-form-input ps-3 border-gray-300 p-2 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"></textarea>
            </div>
        </div>

        <hr class="border-gray-300">

        <div class="flex justify-end space-x-3">
            <button type="button" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md"
                data-hs-overlay="#create-contact">Cancel</button>
            <button type="submit" id="submit_btn"
                class="px-4 py-2 bg-blue-600 text-white rounded-md shadow-md hover:bg-blue-700">
                Create Lead
            </button>
        </div>


</x-app-layout>
