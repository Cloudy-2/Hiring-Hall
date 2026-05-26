<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Profile Information') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your account\'s profile information and email address.') }}
    </x-slot>

    <x-slot name="form">
        <!-- Profile Photo -->
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div x-data="{
                photoName: null,
                photoPreview: null,
                uploading: false,
                uploadError: null,
                uploadSuccess: false,
                selectPhoto() {
                    this.$refs.photo.click();
                },
                handlePhotoChange(e) {
                    const file = e.target.files[0];
                    if (!file) return;
                    this.photoName = file.name;
                    this.uploadError = null;
                    this.uploadSuccess = false;

                    // Show preview
                    const reader = new FileReader();
                    reader.onload = (ev) => { this.photoPreview = ev.target.result; };
                    reader.readAsDataURL(file);

                    // Upload immediately via AJAX
                    this.uploading = true;
                    const formData = new FormData();
                    formData.append('photo', file);

                    fetch('{{ route('user.profile-photo.update') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: formData
                    })
                    .then(async (res) => {
                        const data = await res.json();
                        if (res.ok && data.success) {
                            this.uploadSuccess = true;
                            this.uploadError = null;
                            // Reload page after short delay to show updated photo
                            setTimeout(() => window.location.reload(), 1000);
                        } else {
                            this.uploadError = data.message || (data.errors && data.errors.photo ? data.errors.photo[0] : 'Upload failed. Please try again.');
                            this.photoPreview = null;
                        }
                    })
                    .catch(() => {
                        this.uploadError = 'Network error. Please try again.';
                        this.photoPreview = null;
                    })
                    .finally(() => { this.uploading = false; });
                }
            }" class="col-span-6 border-b border-gray-100 pb-6 mb-2">
                <!-- Profile Photo File Input (not bound to Livewire) -->
                <input type="file" id="photo" class="hidden" x-ref="photo"
                    accept="image/*"
                    x-on:change="handlePhotoChange($event)" />

                <x-label for="photo" class="text-lg font-semibold mb-2" value="{{ __('Profile Photo') }}" />

                <div class="flex flex-col md:flex-row items-center gap-6">
                    <!-- Photo Display -->
                    <div class="relative">
                        <!-- Current Profile Photo -->
                        <div class="flex items-center" x-show="! photoPreview">
                            <span
                                class="inline-flex items-center justify-center w-32 h-32 rounded-xl overflow-hidden border-2 border-primary/20 bg-gray-50 dark:bg-slate-800">
                                @if ($this->user->profile_photo_path)
                                    <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ urlencode($this->user->name) }}&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981"
                                        alt="{{ $this->user->name }}" class="w-full h-full object-cover">
                                @endif
                            </span>
                        </div>

                        <!-- New Profile Photo Preview -->
                        <div class="flex items-center" x-show="photoPreview" style="display: none;">
                            <span
                                class="inline-flex items-center justify-center w-32 h-32 rounded-xl overflow-hidden border-2 border-primary/20 bg-gray-50 dark:bg-slate-800">
                                <img x-bind:src="photoPreview" alt="{{ $this->user->name }}"
                                    class="w-full h-full object-cover">
                            </span>
                        </div>
                    </div>

                    <div class="flex flex-col gap-2">
                        <p class="text-sm text-gray-600 dark:text-gray-400 max-w-xs">
                            {{ __('Update your profile photo. Standard square (1:1) aspect ratio recommended. Max 10MB.') }}
                        </p>
                        <div class="flex gap-2">
                            <x-secondary-button type="button" class="text-xs py-1" x-on:click.prevent="selectPhoto()" x-bind:disabled="uploading">
                                <span x-show="!uploading">{{ __('Change Photo') }}</span>
                                <span x-show="uploading" class="flex items-center gap-2">
                                    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                    </svg>
                                    {{ __('Uploading...') }}
                                </span>
                            </x-secondary-button>

                            @if ($this->user->profile_photo_path)
                                <x-secondary-button type="button"
                                    class="text-xs py-1 text-red-600 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20"
                                    wire:click="deleteProfilePhoto">
                                    {{ __('Remove') }}
                                </x-secondary-button>
                            @endif
                        </div>

                        {{-- Upload feedback --}}
                        <p x-show="uploadSuccess" x-transition class="text-sm text-green-600 mt-1">
                            ✓ {{ __('Photo uploaded successfully!') }}
                        </p>
                        <p x-show="uploadError" x-transition class="text-sm text-red-600 mt-1" x-text="uploadError"></p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Section 1: Basic Information -->
        <div class="col-span-6 mb-2 mt-4">
            <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Personal Information') }}</h3>
        </div>

        <div class="col-span-6 sm:col-span-3">
            <x-label for="name" value="{{ __('Full Name') }}" />
            <x-input id="name" type="text" class="mt-1 block w-full" wire:model="state.name"
                required autocomplete="name" />
            <x-input-error for="name" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-3">
            <x-label for="email" value="{{ __('Email Address') }}" />
            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && $this->user->hasVerifiedEmail())
                {{-- Email is verified - show as disabled/readonly --}}
                <div class="relative">
                    <x-input id="email" type="email"
                        class="mt-1 block w-full bg-gray-100 dark:bg-slate-800 text-gray-500 cursor-not-allowed"
                        value="{{ $this->user->email }}"
                        disabled
                        readonly />
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 mt-1">
                        <i class="ri-lock-line text-gray-400"></i>
                    </div>
                </div>
                <p class="text-sm mt-2 flex items-center gap-2 text-green-600">
                    <i class="ri-verified-badge-fill"></i>
                    {{ __('Email verified') }}
                </p>
                <p class="text-xs mt-1 text-gray-500 flex items-center gap-1">
                    <i class="ri-information-line"></i>
                    {{ __('Verified email addresses cannot be changed for security purposes.') }}
                </p>
            @else
                {{-- Email is not verified - allow editing --}}
                <x-input id="email" type="email" class="mt-1 block w-full"
                    wire:model="state.email" required autocomplete="username" />
                <x-input-error for="email" class="mt-2" />

                @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()))
                    <p class="text-sm mt-2 border border-yellow-200 bg-yellow-50 p-2 rounded flex items-center gap-2">
                        <i class="ri-error-warning-line text-yellow-600"></i>
                        {{ __('Your email address is unverified.') }}
                        <button type="button"
                            class="underline font-semibold text-gray-600 hover:text-gray-900 focus:outline-none"
                            wire:click.prevent="sendEmailVerification">
                            {{ __('Verify Now') }}
                        </button>
                    </p>
                    @if ($this->verificationLinkSent)
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('Verification link sent!') }}
                        </p>
                    @endif
                    <p class="text-xs mt-2 text-amber-600 flex items-center gap-1 border border-amber-200 bg-amber-50 p-2 rounded">
                        <i class="ri-alert-line"></i>
                        {{ __('Note: Once your email is verified, you will not be able to change it.') }}
                    </p>
                @endif
            @endif
        </div>

        <div class="col-span-6 sm:col-span-3">
            <x-label for="date_of_birth" value="{{ __('Date of Birth') }}" />
            <x-input id="date_of_birth" type="date" class="mt-1 block w-full"
                wire:model="state.date_of_birth" />
            <x-input-error for="date_of_birth" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-3">
            <x-label for="phone" value="{{ __('Mobile Phone') }}" />
            <input id="phone" type="hidden" wire:model="state.phone" />
            <div wire:ignore>
                <input id="phone_display" type="tel" class="mt-1 block w-full"
                    placeholder="900 000 0000" />
                <p id="phone_validation_feedback" class="mt-2 text-xs text-gray-500"></p>
            </div>
            <x-input-error for="phone" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-3">
            <x-label for="gender" value="{{ __('Gender') }}" />
            <select id="gender" class="mt-1 block w-full border-gray-300 dark:border-white/10 dark:bg-slate-950 dark:text-gray-100 rounded-md shadow-sm"
                wire:model="state.gender">
                <option value="">{{ __('Select gender') }}</option>
                <option value="male">{{ __('Male') }}</option>
                <option value="female">{{ __('Female') }}</option>
            </select>
            <x-input-error for="gender" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-3">
            <x-label for="marital_status" value="{{ __('Marital Status') }}" />
            <select id="marital_status"
                class="mt-1 block w-full border-gray-300 dark:border-white/10 dark:bg-slate-950 dark:text-gray-100 rounded-md shadow-sm"
                wire:model="state.marital_status">
                <option value="">{{ __('Select status') }}</option>
                <option value="single">{{ __('Single') }}</option>
                <option value="married">{{ __('Married') }}</option>
                <option value="separated">{{ __('Separated') }}</option>
                <option value="divorced">{{ __('Divorced') }}</option>
                <option value="widowed">{{ __('Widowed') }}</option>
            </select>
            <x-input-error for="marital_status" class="mt-2" />
        </div>

        <!-- Section 2: Address -->
        <div class="col-span-6 mb-2 mt-6">
            <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Location Details') }}</h3>
        </div>

        <div class="col-span-6">
            <x-label for="address" value="{{ __('Current Address') }}" />
            <textarea id="address"
                class="mt-1 block w-full border-gray-300 dark:border-white/10 dark:bg-slate-950 dark:text-gray-100 rounded-md shadow-sm" rows="3"
                wire:model="state.address"
                placeholder="Room/Block/Lot, Street, City, State/Province, Country"></textarea>
            <x-input-error for="address" class="mt-2" />
        </div>
    </x-slot>


    <x-slot name="actions">
        <x-action-message class="me-3" on="saved">
            {{ __('Saved.') }}
        </x-action-message>

        <x-button wire:loading.attr="disabled" wire:target="updateProfileInformation">
            <span wire:loading.remove wire:target="updateProfileInformation">{{ __('Save') }}</span>
            <span wire:loading wire:target="updateProfileInformation">{{ __('Saving...') }}</span>
        </x-button>
    </x-slot>
</x-form-section>

@push('scripts')
    <link rel="stylesheet" href="{{ asset('assets/libs/intl-tel-input/build/css/intlTelInput.min.css') }}">
    <script src="{{ asset('assets/libs/intl-tel-input/build/js/intlTelInput.min.js') }}"></script>
    <script>
        async function lookupCountryCode() {
            try {
                const response = await fetch('https://ipapi.co/json');
                const data = await response.json();
                return (data?.country_code || 'PH').toLowerCase();
            } catch (error) {
                return 'ph';
            }
        }

        function initModernPhoneField(initialPhone) {
            const phoneInput = document.getElementById('phone_display');
            const hiddenInput = document.getElementById('phone');
            const feedback = document.getElementById('phone_validation_feedback');

            if (!phoneInput || !hiddenInput || typeof window.intlTelInput !== 'function') {
                return;
            }

            if (phoneInput.dataset.itiReady === '1' && phoneInput._itiInstance) {
                if ((initialPhone || '').trim() !== '') {
                    phoneInput._itiInstance.setNumber(initialPhone);
                }
                return;
            }

            const iti = window.intlTelInput(phoneInput, {
                initialCountry: 'auto',
                geoIpLookup: async (callback) => callback(await lookupCountryCode()),
                separateDialCode: false,
                nationalMode: false,
                formatOnDisplay: true,
                autoPlaceholder: 'polite',
                preferredCountries: ['ph', 'us', 'gb', 'ca', 'au'],
                utilsScript: "{{ asset('assets/libs/intl-tel-input/build/js/utils.js') }}",
            });

            const normalizeInternationalNumber = (rawValue) => {
                const value = (rawValue || '').trim();
                if (value === '') {
                    return '';
                }

                if (value.startsWith('+')) {
                    return value;
                }

                const digits = value.replace(/\D/g, '');
                if (digits === '') {
                    return '';
                }

                const selectedCountry = iti.getSelectedCountryData();
                const dialCode = selectedCountry?.dialCode || '';

                if (dialCode && digits.startsWith(dialCode)) {
                    return `+${digits}`;
                }

                return dialCode ? `+${dialCode}${digits}` : `+${digits}`;
            };

            const sanitizePhoneInputValue = () => {
                let value = phoneInput.value || '';
                value = value.replace(/[^0-9+()\-\s]/g, '');
                value = value.replace(/(?!^)\+/g, '');
                phoneInput.value = value;
            };

            const setValidationFeedback = () => {
                if (!feedback) {
                    return;
                }

                const hasInput = phoneInput.value.trim().length > 0;
                if (!hasInput) {
                    feedback.textContent = '';
                    feedback.className = 'mt-2 text-xs text-gray-500';
                    return;
                }

                if (!iti.isValidNumber()) {
                    feedback.textContent = 'Invalid number for selected country';
                    feedback.className = 'mt-2 text-xs text-rose-600';
                } else {
                    feedback.textContent = '';
                    feedback.className = 'mt-2 text-xs text-gray-500';
                }
            };

            if ((initialPhone || '').trim() !== '') {
                iti.setNumber(normalizeInternationalNumber(initialPhone));
            }

            const syncPhoneState = () => {
                const fullNumber = iti.getNumber();
                const valueToStore = fullNumber || phoneInput.value || '';
                hiddenInput.value = valueToStore;
                hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
                setValidationFeedback();
            };

            const syncCountryFromTypedDialCode = () => {
                const typedValue = phoneInput.value.trim();
                if (typedValue.startsWith('+')) {
                    iti.setNumber(typedValue);
                }
            };

            const seedNumberFromCountryPattern = () => {
                const currentValue = phoneInput.value.trim();
                if (currentValue !== '') {
                    return;
                }

                const selectedCountry = iti.getSelectedCountryData();
                const dialPrefix = selectedCountry?.dialCode ? `+${selectedCountry.dialCode}` : '';

                if (dialPrefix === '') {
                    return;
                }

                iti.setNumber(dialPrefix);
            };

            phoneInput.addEventListener('input', () => {
                sanitizePhoneInputValue();
                syncCountryFromTypedDialCode();
                syncPhoneState();
            });
            phoneInput.addEventListener('change', syncPhoneState);
            phoneInput.addEventListener('countrychange', () => {
                seedNumberFromCountryPattern();
                syncPhoneState();
            });

            syncPhoneState();
            phoneInput.dataset.itiReady = '1';
            phoneInput._itiInstance = iti;
        }

        function bootPhoneField() {
            initModernPhoneField(document.getElementById('phone')?.value || @js($state['phone'] ?? ''));
        }

        document.addEventListener('DOMContentLoaded', () => {
            bootPhoneField();

            if (window.Livewire && typeof window.Livewire.hook === 'function') {
                window.Livewire.hook('morph.updated', () => {
                    bootPhoneField();
                });
            }
        });

        document.addEventListener('livewire:initialized', () => {
            bootPhoneField();

            if (!window.Livewire || typeof window.Livewire.on !== 'function') {
                return;
            }

            window.Livewire.on('saved', () => {
                const isDark = document.documentElement.classList.contains('dark');
                Swal.fire({
                    title: 'Success!',
                    text: 'Profile information updated successfully.',
                    icon: 'success',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    background: isDark ? '#1a1c1e' : '#ffffff',
                    color: isDark ? '#f3f4f6' : '#1a1a1a',
                    iconColor: '#6366f1',
                    customClass: {
                        popup: 'premium-toast'
                    }
                });
            });
        });
    </script>
    <style>
        .premium-toast {
            border-radius: 12px !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
            border: 1px solid rgba(99, 102, 241, 0.1) !important;
        }

        .iti {
            width: 100%;
        }

        .iti input#phone_display {
            width: 100%;
            border-radius: 0.625rem;
            border: 1px solid rgb(209 213 219);
            min-height: 2.625rem;
            padding-left: 2.9rem;
            background-color: #fff;
            color: #111827;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .iti input#phone_display:focus {
            border-color: rgb(59 130 246);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
            outline: none;
        }

        .iti__flag-container .iti__selected-flag {
            background: #f8fafc;
            border-right: 1px solid #e5e7eb;
            border-top-left-radius: 0.625rem;
            border-bottom-left-radius: 0.625rem;
            padding: 0 0.5rem;
        }

        .iti__country-list {
            border-radius: 0.75rem;
            border: 1px solid #e5e7eb;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.14);
            font-size: 0.875rem;
            max-height: 260px;
        }

        .iti__country.iti__highlight {
            background: #eff6ff;
        }

        .dark .iti input#phone_display {
            border-color: rgba(255, 255, 255, 0.1);
            background-color: rgb(2 6 23);
            color: rgb(243 244 246);
        }

        .dark .iti__flag-container .iti__selected-flag {
            background: rgb(15 23 42);
            border-right-color: rgba(255, 255, 255, 0.1);
        }

        .dark .iti__country-list {
            background: rgb(15 23 42);
            border-color: rgba(255, 255, 255, 0.1);
            color: rgb(226 232 240);
        }

        .dark .iti__country.iti__highlight {
            background: rgba(59, 130, 246, 0.2);
        }
    </style>
@endpush
