<x-app-layout>

    <x-slot name="return">{"link": "/relationship/list", "text": "Back"}</x-slot>
    <x-slot name="url_1">{"link": "/relationship/list", "text": "Manage Relationship"}</x-slot>
    <x-slot name="active">Registration</x-slot>

    <form action="{{ route('contact.create') }}" id="registration-form" method="POST" enctype="multipart/form-data"
        autocapitalize="true" autocomplete="off">
        @csrf
        @php
            $user = Auth::user();
            $clientId = $user->company ?? $user->id;
        @endphp

        <input type="hidden" name="client_id" value="{{ $clientId }}">

        <div class="grid grid-cols-12 gap-x-6">
            <div class="xl:col-span-12 col-span-12">
                <div class="box">
                    <div class="box-body">
                        <h6 class="font-bold text-2xl text-gray-700 dark:text-white">
                            <strong>New Relationship</strong>
                        </h6>
                        <span>You can create the your new relationship here.</span>
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

                        <div class="grid grid-cols-12 sm:gap-x-6 gap-y-3">

                            <!-- Company Name -->
                            <div class="xl:col-span-12 col-span-12">
                                <label for="company_name" class="form-label">Company Name: <strong
                                        class="text-danger">*</strong></label>
                                <div class="relative">
                                    <input type="text" name="company_name"
                                        class="ti-form-input rounded-sm ps-11 focus:z-10" id="step-1" required
                                        placeholder="Enter company name">
                                    <div
                                        class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                                        <i class="bi bi-building"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Type -->
                            <div class="xl:col-span-4 col-span-12">
                                <label for="type" class="form-label">Type: <strong
                                        class="text-danger">*</strong></label>
                                <div class="relative">
                                    <select name="type" id="type" class="form-select p-2 px-4" required>
                                        <option value="" disabled selected>-</option>
                                        <option value="Supplier">Supplier</option>
                                        <option value="Distributor">Distributor</option>
                                        <option value="General Contractor">General Contractor</option>
                                        <option value="Subcontractor">Subcontractor</option>
                                        <option value="Other">Other</option>
                                        <option value="Architect">Architect</option>
                                        <option value="Owner">Owner</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Lead Source -->
                            <div class="xl:col-span-8 col-span-12">
                                <label for="lead_source" class="form-label">Lead Source: <strong
                                        class="text-danger">*</strong></label>
                                <div class="relative">
                                    <select name="lead_source" id="step-2" class="form-select p-2 px-4">
                                        <option value="" disabled selected>-</option>
                                        @if (Auth::user()->email !== 'juan@planpanther.pro')
                                            <option value="Website">Website</option>
                                            <option value="Referral">Referral</option>
                                            <option value="Social Media">Social Media</option>
                                            <option value="Email Campaign">Email Campaign</option>
                                            <option value="Phone Inquiry">Phone Inquiry</option>
                                            <option value="Walk-In">Walk-In</option>
                                            <option value="Event">Event</option>
                                            <option value="Advertisement">Advertisement</option>
                                            <option value="Online Listing">Online Listing</option>
                                            <option value="Cold Call">Cold Call</option>
                                            <option value="Partner">Partner</option>
                                            <option value="Existing Customer">Existing Customer</option>
                                            <option value="Search Engine">Search Engine</option>
                                            <option value="Chat Support">Chat Support</option>
                                            <option value="SMS Campaign">SMS Campaign</option>
                                        @else
                                            <option value="Plan Panther Subscription">Plan Panther Subscription</option>
                                            <option value="No Plan Panther Subscription">No Plan Panther Subscription
                                            </option>
                                            <option value="Potential Plan Panther Subscription">Potential Plan Panther
                                                Subscription</option>
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <!-- City -->
                            <div class="xl:col-span-2 col-span-12">
                                <label for="city" class="form-label">City: <strong
                                        class="text-danger">*</strong></label>
                                <div class="relative">
                                    <input type="text" name="city" id="city"
                                        class="ti-form-input rounded-sm ps-11 focus:z-10"
                                        placeholder="Enter City here.">
                                    <div
                                        class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                                        <i class="bi bi-geo-alt"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- State -->
                            <div class="xl:col-span-2 col-span-12">
                                <label for="state" class="form-label">State: <strong
                                        class="text-danger">*</strong></label>
                                <div class="relative">
                                    <input type="text" name="state" id="state"
                                        class="ti-form-input rounded-sm ps-11 focus:z-10"
                                        placeholder="Enter State here.">
                                    <div
                                        class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                                        <i class="bi bi-map"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Zip Code -->
                            <div class="xl:col-span-2 col-span-12">
                                <label for="zip" class="form-label">Zip Code: <strong
                                        class="text-danger">*</strong></label>
                                <div class="relative">
                                    <input type="text" name="zip" id="zip"
                                        class="ti-form-input rounded-sm ps-11 focus:z-10" placeholder="Code">
                                    <div
                                        class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                                        <i class="bi bi-123"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="xl:col-span-6 col-span-12">
                                <label for="address" class="form-label">Additional Address:</label>
                                <div class="relative">
                                    <input type="text" name="address" id="address"
                                        class="ti-form-input rounded-sm ps-11 focus:z-10" placeholder="Enter address">
                                    <div
                                        class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                                        <i class="bi bi-house-door"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Phone -->
                            <div class="xl:col-span-4 col-span-12">
                                <label for="phone" class="form-label">Phone number: <strong
                                        class="text-danger">*</strong></label>
                                <div class="relative">
                                    <input type="text" name="phone" id="phone"
                                        class="ti-form-input rounded-sm ps-11 focus:z-10"
                                        placeholder="Enter phone number">
                                    <div
                                        class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                                        <i class="bi bi-telephone"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="xl:col-span-4 col-span-12">
                                <label for="email" class="form-label">Email Address: </label>
                                <div class="relative">
                                    <input type="email" name="email" id="email"
                                        class="ti-form-input rounded-sm ps-11 focus:z-10"
                                        placeholder="Enter Email Address">
                                    <div
                                        class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                                        <i class="bi bi-envelope"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Fax -->
                            <div class="xl:col-span-4 col-span-12">
                                <label for="fax" class="form-label">Fax:</label>
                                <div class="relative">
                                    <input type="text" name="fax" id="fax"
                                        class="ti-form-input rounded-sm ps-11 focus:z-10"
                                        placeholder="Enter fax number">
                                    <div
                                        class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                                        <i class="bi bi-printer"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Website -->
                            <div class="xl:col-span-4 col-span-12">
                                <label for="website" class="form-label">Website:</label>
                                <div class="relative">
                                    <input type="url" name="website" id="website"
                                        class="ti-form-input rounded-sm ps-11 focus:z-10"
                                        placeholder="Enter website URL">
                                    <div
                                        class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                                        <i class="bi bi-globe2"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- License -->
                            <div class="xl:col-span-4 col-span-12">
                                <label for="license" class="form-label">License:</label>
                                <div class="relative">
                                    <input type="text" name="license" id="license"
                                        class="ti-form-input rounded-sm ps-11 focus:z-10"
                                        placeholder="Enter license details">
                                    <div
                                        class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                                        <i class="bi bi-card-checklist"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Insurance -->
                            <div class="xl:col-span-4 col-span-12">
                                <label for="insurance" class="form-label">Insurance:</label>
                                <div class="relative">
                                    <input type="text" name="insurance" id="insurance"
                                        class="ti-form-input rounded-sm ps-11 focus:z-10"
                                        placeholder="Enter insurance details">
                                    <div
                                        class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                                        <i class="bi bi-shield-check"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Notes -->
                            <div class="xl:col-span-12 col-span-12">
                                <label class="form-label">Notes:</label>
                                <div class="relative">
                                    <textarea name="notes" id="notes" class="ti-form-input rounded-sm ps-11 focus:z-10" rows="3"
                                        placeholder="Enter any additional notes"></textarea>
                                    <div class="absolute top-2 start-0 flex items-center ps-4 pointer-events-none">
                                        <i class="bi bi-pencil-square"></i>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="box-footer flex gap-3 justify-end">
                        <a href="/relationship/list/" style="border-color: #FF6B54; "
                            class="bg-gray-100 text-danger px-4 py-2 rounded-md hover:bg-gray-300 transition">
                            <i class="bi bi-x-lg"></i>
                            <span class="mx-1">Discard</span>
                        </a>
                        <button type="submit" id="step-3"
                            class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-green-800 transition">
                            <i class="bi bi-check2-circle"></i>
                            <span class="mx-1">Save Changes</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

</x-app-layout>
