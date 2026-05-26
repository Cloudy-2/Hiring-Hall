<x-app-layout>

    @php
        $info = App\Models\ContactPerson::where('id', $id)->first();
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
                <form action="{{ route('relationship.contact.update', $info->id) }}" method="POST"
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

                                <input type="hidden" name="contact_id" value="{{ $id }}">
                            </tbody>
                        </table>
                    </div>

                    <div class="box-footer flex gap-3 justify-end">
                        <button type="submit" id="step-3"
                            class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-800 transition">
                            <i class="bi bi-check2-circle"></i>
                            <span class="mx-1">Save</span>
                        </button>
                        <a href="/relationship/list/{{ $info->company_id }}" style="border-color: #FF6B54; "
                            class="bg-gray-100 text-danger px-4 py-2 rounded-md hover:bg-gray-300 transition">
                            <i class="bi bi-trash"></i>
                            <span class="mx-1">Discard</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>






    {{-- <div class="box-body overflow-y-auto">
                        <div class="grid grid-cols-12 gap-x-6 gap-y-3">


                            <!-- Profile Image Upload -->
                            <div class="xl:col-span-12 col-span-12">
                                <div class="flex items-start flex-wrap gap-4">
                                    <div>
                                        <span class="avatar avatar-xxl">
                                            <img src="/avatar.png" alt="" id="profile-img">
                                        </span>
                                    </div>
                                    <div>
                                        <span class="font-medium block mb-2">Profile Picture</span>
                                        <div class="btn-list mb-1">
                                            <label for="profile-change"
                                                class="ti-btn ti-btn-sm ti-btn-soft-light text-dark btn-wave waves-effect waves-light">
                                                <i class="ri-upload-2-line me-1"></i>Change Image
                                            </label>
                                            <input type="file" name="photo" id="profile-change" class="hidden"
                                                accept="image/*">
                                            <button type="button"
                                                class="ti-btn ti-btn-sm ti-btn-light btn-wave waves-effect waves-light"><i
                                                    class="ri-delete-bin-line me-1"></i>Remove</button>
                                        </div>
                                        <span class="block text-xs text-textmuted dark:text-textmuted/50">
                                            Use JPEG, PNG, or GIF. Best size: 200x200 pixels. Keep it under 5MB
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- JavaScript to handle image preview -->
                            <script>
                                document.getElementById('profile-change').addEventListener('change', function(event) {
                                    const file = event.target.files[0];
                                    if (file) {
                                        const reader = new FileReader();
                                        reader.onload = function(e) {
                                            document.getElementById('profile-img').src = e.target.result;
                                        };
                                        reader.readAsDataURL(file);
                                    }
                                });

                                document.getElementById('remove-img').addEventListener('click', function() {
                                    document.getElementById('profile-img').src = "/assets/images/faces/9.jpg"; // Default image
                                    document.getElementById('profile-change').value = ''; // Clear file input
                                });
                            </script>

                            <div class="xl:col-span-12 col-span-12">
                                <hr>
                            </div>

                            <!-- First Name -->
                            <div class="xl:col-span-4 col-span-12">
                                <label for="first-name" class="form-label">First Name : <strong
                                        class="text-danger">*</strong></label>
                                <div class="relative">
                                    <input type="text" value=" {{ $info->first_name }}" name="first_name"
                                        id="first-name" placeholder="Enter First Name"
                                        class="ti-form-input rounded-sm ps-11 focus:z-10 focus:z-10">
                                    <div
                                        class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                                        <i class="bi bi-person"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Last Name -->
                            <div class="xl:col-span-4 col-span-12">
                                <label for="last-name" class="form-label">Last Name : <strong
                                        class="text-danger">*</strong></label>
                                <div class="relative">
                                    <input type="text" name="last_name" id="last-name"
                                        placeholder="Enter Last Name"
                                        class="ti-form-input rounded-sm ps-11 focus:z-10 focus:z-10">
                                    <div
                                        class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                                        <i class="bi bi-person"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Company -->
                            <div class="xl:col-span-4 col-span-12">
                                <label for="company" class="form-label">Position : <strong
                                        class="text-danger">*</strong></label>
                                <div class="relative">
                                    <input type="text" name="position" id="position" placeholder="Position"
                                        class="ti-form-input rounded-sm ps-11 focus:z-10 focus:z-10">
                                    <div
                                        class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                                        <i class="bi bi-building"></i>
                                    </div>
                                </div>
                            </div>


                            <!-- Location -->
                            <div class="xl:col-span-12 col-span-12">
                                <label for="location" class="form-label">Location :</label>
                                <div class="relative">
                                    <input type="text" name="location" id="location" placeholder="City, Country"
                                        class="ti-form-input rounded-sm ps-11 focus:z-10 focus:z-10">
                                    <div
                                        class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                                        <i class="bi bi-geo-alt"></i>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="contact_id" value="{{ $id }}">

                            <!-- Phone -->
                            <div class="xl:col-span-4 col-span-12">
                                <label for="contact-phone" class="form-label">Phone : <strong
                                        class="text-danger">*</strong></label>
                                <div class="relative">
                                    <input type="tel" name="phone" id="contact-phone"
                                        placeholder="Enter Phone Number"
                                        class="ti-form-input rounded-sm ps-11 focus:z-10 focus:z-10">
                                    <div
                                        class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                                        <i class="bi bi-telephone"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="xl:col-span-{{ $company->lead_source == 'Plan Panther Subscription' || $company->lead_source == 'Potential Plan Panther Subscription' ? '4' : '8'}} col-span-12">
                                <label for="contact-mail" class="form-label">Email Address : <strong
                                        class="text-danger">*</strong></label>
                                <div class="relative">
                                    <input type="email" name="email" id="contact-mail" placeholder="Enter Email Address"
                                        class="ti-form-input rounded-sm ps-11 focus:z-10 focus:z-10">
                                    <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                                        <i class="bi bi-envelope"></i>
                                    </div>
                                </div>
                            </div>

                <!-- Website -->
                @if ($company->lead_source == 'Plan Panther Subscription' || $company->lead_source == 'Potential Plan Panther Subscription')
                                <div class="xl:col-span-4 col-span-12">
                                    <div class="relative">
                                        <label for="password" class="block text-sm font-medium text-gray-700">Password
                                            <strong class="text-danger">*</strong></label>
                                        <div class="flex items-center space-x-2">
                                            <input type="text" name="password" id="password" value="{{ Str::random(10) }}"
                                                class="mt-1 w-full text-lg ti-form-input ps-11 focus:z-10 text-start border border-gray-300 p-2 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                                            <div
                                                class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-2 pt-6 mt-1">
                                                <i class="bi bi-unlock"></i>
                                            </div>
                                            <button type="button" id="generate_password" onclick="generatePassword()"
                                                class="px-3 py-2 ti-btn ti-btn-light border-2 shadow border-gray-400 text-dark rounded-md">
                                                <i class="bi bi-magic"></i>
                                            </button>
                                            <button type="button" id="copy_password" onclick="copyPassword()"
                                                class="px-3 py-2 ti-btn ti-btn-light text-dark shadow rounded-md">
                                                <i class="bi bi-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif



                <!-- Facebook -->
                <div class="xl:col-span-4 col-span-12">
                    <label for="facebook" class="form-label">Facebook :</label>
                    <div class="relative">
                        <input type="text" name="facebook" id="facebook" placeholder="Facebook Profile"
                            class="ti-form-input rounded-sm ps-11 focus:z-10 focus:z-10">
                        <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                            <i class="bi bi-facebook"></i>
                        </div>
                    </div>
                </div>

                <!-- Twitter / X -->
                <div class="xl:col-span-4 col-span-12">
                    <label for="twitter" class="form-label">Twitter / X : </label>
                    <div class="relative">
                        <input type="text" name="twitter" id="twitter" placeholder="Twitter Handle"
                            class="ti-form-input rounded-sm ps-11 focus:z-10 focus:z-10">
                        <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                            <i class="bi bi-twitter"></i>
                        </div>
                    </div>
                </div>

                <div class="xl:col-span-4 col-span-12">
                    <label for="linkedin" class="form-label">LinkedIn : </label>
                    <div class="relative">
                        <input type="text" name="linkedin" id="linkedin" placeholder="LinkedIn Profile"
                            class="ti-form-input rounded-sm ps-11 focus:z-10 focus:z-10">
                        <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                            <i class="bi bi-linkedin"></i>
                        </div>
                    </div>
                </div>

                <!-- Biography -->
                <div class="xl:col-span-12 col-span-12">
                    <label for="biography" class="form-label">Biographical Info : </label>
                    <textarea class="form-control" name="biography" id="biography" rows="2" placeholder="Write a short bio..."></textarea>
                </div>

            </div>

        </div> --}}


    {{-- <div class="ti-modal-footer">
            <button type="button" class="bg-gray-100 text-dark px-4 py-2 rounded-md hover:bg-gray-300 transition"
                data-hs-overlay="#create-va">Cancel</button>
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-800 transition">
                <i class="bi bi-check2-circle"></i>
                <span class="mx-1">Save</span>
            </button>
        </div> --}}


</x-app-layout>
