<x-app-layout>
    <x-slot name="title">Register New Contact Person</x-slot>
    <x-slot name="url_1">{"link": "/contact/list", "text": "Relationship"}</x-slot>
    <x-slot name="url_2">{"link": "/contact-person/list", "text": "Manage"}</x-slot>
    <x-slot name="url_3">{"link": "/contact-person/list/", "text": "Contact Persons"}</x-slot>
    <x-slot name="active">New Contact Person</x-slot>

    <form action="{{ route('contact-persons.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-12 gap-x-6">
            <div class="xl:col-span-12 col-span-12">
                <div class="box">
                    <div class="box-body">

                        @if ($errors->any())
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                                <strong class="font-bold">Whoops! Something went wrong.</strong>
                                <ul class="mt-2 list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="grid grid-cols-12 sm:gap-x-6 gap-y-3">
                            <div class="xl:col-span-12 col-span-12">
                                <label for="contact_id" class="form-label">Associated Contact:</label>
                                <select name="contact_id" class="form-select p-2 px-4" required>
                                    <option value="" disabled selected>Select a Contact</option>
                                    @foreach (App\Models\Contact::get() as $contact)
                                        <option value="{{ $contact->id }}">{{ $contact->company_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="xl:col-span-4 col-span-12">
                                <label for="name" class="form-label">Full Name:</label>
                                <input type="text" name="name" class="form-control form-control-lg" required>
                            </div>

                            <div class="xl:col-span-4 col-span-12">
                                <label for="position" class="form-label">Position:</label>
                                <input type="text" name="position" class="form-control form-control-lg">
                            </div>

                            <div class="xl:col-span-4 col-span-12">
                                <label for="phone" class="form-label">Phone Number:</label>
                                <input type="text" name="phone" class="form-control form-control-lg">
                            </div>

                            <div class="xl:col-span-4 col-span-12">
                                <label for="email" class="form-label">Email Address:</label>
                                <input type="email" name="email" class="form-control form-control-lg">
                            </div>

                            <div class="xl:col-span-12 col-span-12">
                                <label class="form-label">Notes:</label>
                                <textarea name="notes" class="form-control form-control-lg p-2" rows="4"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        <button type="submit" class="ti-btn ti-btn-primary btn-wave ms-auto float-end">Create Contact Person</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</x-app-layout>
