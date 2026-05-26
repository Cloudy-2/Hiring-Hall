<x-app-layout>

    @php
        $decryptedId = decrypt_id($id) ?? $id;
        $info = App\Models\ContactPerson::where('id', $decryptedId)->first();
        $company = App\Models\Contact::where('id', $info->company_id)->first();
    @endphp

    <x-slot name="return">{"link": "/relationship/list/details/{{ $info->company_id }}", "text": "Back"}</x-slot>
    <x-slot name="title">Contact Person Details</x-slot>
    <x-slot name="url_1">{"link": "/relationship/list/", "text": "Manage Relationship"}</x-slot>
    <x-slot name="url_2">{"link": "/relationship/list/details/{{ $info->company_id }}", "text":
        "{{ $company->company_name }}"}</x-slot>
    <x-slot name="active">Contact Person</x-slot>
    <x-slot name="buttons"></x-slot>

    <div class="grid grid-cols-12 gap-x-6">
        <div class="xxl:col-span-12 col-span-12">
            <div class="box">
                <form action="{{ route('relationship.contact.update', $decryptedId) }}" method="POST"
                    enctype="multipart/form-data" autocomplete="on">
                    @csrf
                    <div class="box-body">
                        <h6 class="font-bold text-2xl text-gray-700 dark:text-white">
                            <strong>Contact Person Information</strong>
                        </h6>
                        <span>You can modify the contact person details here.</span>
                        <hr class="mb-3 !mt-3">
                        @if ($errors->any())
                            <div
                                class="alert alert-danger alert-dismissible fade show custom-alert-icon shadow-sm flex items-center mx-3">
                                <div>
                                    <strong class="text-danger">Whoops! Something went wrong:</strong>
                                    <ul class="list-disc list-inside mt-2 mx-4">
                                        @foreach ($errors->all() as $error)
                                            <li class="text-dark"><i>{{ $error }}</i></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                        <table class="ti-custom-table !border border-defaultborder dark:border-defaultborder/10">
                            <tbody>
                                <tr class="border-b border-defaultborder dark:border-defaultborder/10">
                                    <td class="text-end border-2 border-defaultborder dark:border-defaultborder/10"
                                        width="120">
                                        First Name:</td>
                                    <td
                                        class="border-2 border-defaultborder dark:border-defaultborder/10 font-bold !p-0 !m-0">
                                        <div class="relative p-1">
                                            <input type="text" name="first_name" value="{{ $info->first_name }}"
                                                class="ti-form-input rounded-sm ps-11 focus:z-10"
                                                placeholder="Enter first name">
                                            <div
                                                class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none">
                                                <i class="bi bi-person"></i>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end border-2 border-defaultborder dark:border-defaultborder/10"
                                        width="120">
                                        Last Name:
                                    </td>
                                    <td
                                        class="border-2 border-defaultborder dark:border-defaultborder/10 font-bold !p-0 !m-0">
                                        <div class="relative p-1">
                                            <input type="text" name="last_name" value="{{ $info->last_name }}"
                                                class="ti-form-input rounded-sm ps-11 focus:z-10"
                                                placeholder="Enter last name">
                                            <div
                                                class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none">
                                                <i class="bi bi-person"></i>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Position / Location -->
                                <tr class="border-b border-defaultborder dark:border-defaultborder/10">
                                    <td class="text-end border-2 border-defaultborder dark:border-defaultborder/10">
                                        Position:</td>
                                    <td
                                        class="border-2 border-defaultborder dark:border-defaultborder/10 font-bold !p-0 !m-0">
                                        <div class="relative p-1">
                                            <input type="text" name="position" value="{{ $info->position }}"
                                                class="ti-form-input rounded-sm ps-11 focus:z-10"
                                                placeholder="Enter position">
                                            <div
                                                class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none">
                                                <i class="bi bi-building"></i>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end border-2 border-defaultborder dark:border-defaultborder/10">
                                        Location:</td>
                                    <td
                                        class="border-2 border-defaultborder dark:border-defaultborder/10 font-bold !p-0 !m-0">
                                        <div class="relative p-1">
                                            <input type="text" name="location" value="{{ $info->location }}"
                                                class="ti-form-input rounded-sm ps-11 focus:z-10"
                                                placeholder="Enter location">
                                            <div
                                                class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none">
                                                <i class="bi bi-geo-alt"></i>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Phone / Facebook -->
                                <tr class="border-b border-defaultborder dark:border-defaultborder/10">
                                    <td class="text-end border-2 border-defaultborder dark:border-defaultborder/10">
                                        Phone:</td>
                                    <td
                                        class="border-2 border-defaultborder dark:border-defaultborder/10 font-bold !p-0 !m-0">
                                        <div class="relative p-1">
                                            <input type="text" name="phone" value="{{ $info->phone }}"
                                                class="ti-form-input rounded-sm ps-11 focus:z-10"
                                                placeholder="Phone number">
                                            <div
                                                class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none">
                                                <i class="bi bi-telephone"></i>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end border-2 border-defaultborder dark:border-defaultborder/10">
                                        Facebook:</td>
                                    <td
                                        class="border-2 border-defaultborder dark:border-defaultborder/10 font-bold !p-0 !m-0">
                                        <div class="relative p-1">
                                            <input type="text" name="facebook" value="{{ $info->facebook }}"
                                                class="ti-form-input rounded-sm ps-11 focus:z-10"
                                                placeholder="Facebook URL">
                                            <div
                                                class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none">
                                                <i class="bi bi-facebook"></i>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Twitter / LinkedIn -->
                                <tr class="border-b border-defaultborder dark:border-defaultborder/10">
                                    <td class="text-end border-2 border-defaultborder dark:border-defaultborder/10">
                                        Twitter / X:</td>
                                    <td
                                        class="border-2 border-defaultborder dark:border-defaultborder/10 font-bold !p-0 !m-0">
                                        <div class="relative p-1">
                                            <input type="text" name="twitter" value="{{ $info->twitter }}"
                                                class="ti-form-input rounded-sm ps-11 focus:z-10"
                                                placeholder="Twitter handle">
                                            <div
                                                class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none">
                                                <i class="bi bi-twitter"></i>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end border-2 border-defaultborder dark:border-defaultborder/10">
                                        LinkedIn:</td>
                                    <td
                                        class="border-2 border-defaultborder dark:border-defaultborder/10 font-bold !p-0 !m-0">
                                        <div class="relative p-1">
                                            <input type="text" name="linkedin" value="{{ $info->linkedin }}"
                                                class="ti-form-input rounded-sm ps-11 focus:z-10"
                                                placeholder="LinkedIn profile">
                                            <div
                                                class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none">
                                                <i class="bi bi-linkedin"></i>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Bio -->
                                <tr class="border-b border-defaultborder dark:border-defaultborder/10">
                                    <td class="text-end border-2 border-defaultborder dark:border-defaultborder/10">
                                        Bio:</td>
                                    <td colspan="3"
                                        class="border-2 border-defaultborder dark:border-defaultborder/10 font-bold !p-0 !m-0">
                                        <div class="relative p-1">
                                            <textarea name="biography" rows="2" class="ti-form-input rounded-sm ps-11 focus:z-10"
                                                placeholder="Write a short bio...">{{ $info->biography }}</textarea>
                                            <div
                                                class="absolute top-4 start-0 flex items-center ps-4 pointer-events-none">
                                                <i class="bi bi-pencil-square"></i>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <input type="hidden" name="contact_id" value="{{ $decryptedId }}">
                            </tbody>
                        </table>
                    </div>

                    <div class="box-footer flex gap-3 justify-end">
                        <a href="/relationship/list/details/{{ $info->company_id }}" style="border-color: #FF6B54; "
                            class="bg-gray-100 text-danger px-4 py-2 rounded-md hover:bg-gray-300 transition">
                            <i class="bi bi-x-lg "></i>
                            <span class="mx-1">Discard</span>
                        </a>
                        <button type="submit" id="step-3"
                            class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-green-800 transition">
                            <i class="bi bi-check2-circle"></i>
                            <span class="mx-1">Save Changes</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


</x-app-layout>
