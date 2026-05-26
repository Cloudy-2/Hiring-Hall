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
            <div x-data="{photoName: null, photoPreview: null}" class="col-span-6 border-b border-gray-100 pb-6 mb-2">
                <!-- Profile Photo File Input -->
                <input type="file" id="photo" class="hidden" wire:model.live="photo" x-ref="photo" x-on:change="
                                                photoName = $refs.photo.files[0].name;
                                                const reader = new FileReader();
                                                reader.onload = (e) => {
                                                photoPreview = e.target.result;
                                            };
                                            reader.readAsDataURL($refs.photo.files[0]);
                                        " />

                <x-label for="photo" class="text-lg font-semibold mb-2" value="{{ __('Profile Photo') }}" />

                <div class="flex flex-col md:flex-row items-center gap-6">
                    <!-- Photo Display -->
                    <div class="relative">
                        <!-- Current Profile Photo -->
                        <div class="flex items-center" x-show="! photoPreview">
                            <span
                                class="inline-flex items-center justify-center w-32 h-32 rounded-xl overflow-hidden border-2 border-primary/20 bg-gray-50">
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
                                class="inline-flex items-center justify-center w-32 h-32 rounded-xl overflow-hidden border-2 border-primary/20 bg-gray-50">
                                <img x-bind:src="photoPreview" alt="{{ $this->user->name }}"
                                    class="w-full h-full object-cover">
                            </span>
                        </div>

                        <!-- <button type="button"
                                    class="absolute -bottom-2 -right-2 p-2 bg-primary text-white rounded-full shadow-lg hover:bg-primary-dark transition-colors"
                                    x-on:click.prevent="$refs.photo.click()">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </button> -->
                    </div>

                    <div class="flex flex-col gap-2">
                        <p class="text-sm text-gray-600 max-w-xs">
                            {{ __('Update your profile photo. Standard square (1:1) aspect ratio recommended.') }}
                        </p>
                        <div class="flex gap-2">
                            <x-secondary-button type="button" class="text-xs py-1" x-on:click.prevent="$refs.photo.click()">
                                {{ __('Change Photo') }}
                            </x-secondary-button>

                            @if ($this->user->profile_photo_path)
                                <x-secondary-button type="button"
                                    class="text-xs py-1 text-red-600 hover:text-red-700 hover:bg-red-50"
                                    wire:click="deleteProfilePhoto">
                                    {{ __('Remove') }}
                                </x-secondary-button>
                            @endif
                        </div>
                    </div>
                </div>

                <x-input-error for="photo" class="mt-2" />
            </div>
        @endif

        <!-- Section 1: Basic Information -->
        <div class="col-span-6 mb-2 mt-4">
            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">{{ __('Personal Information') }}</h3>
        </div>

        <div class="col-span-6 sm:col-span-3">
            <x-label for="name" value="{{ __('Full Name') }}" />
            <x-input id="name" type="text" class="mt-1 block w-full bg-gray-50 focus:bg-white" wire:model="state.name"
                required autocomplete="name" />
            <x-input-error for="name" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-3">
            <x-label for="email" value="{{ __('Email Address') }}" />
            <x-input id="email" type="email" class="mt-1 block w-full bg-gray-50 focus:bg-white"
                wire:model="state.email" required autocomplete="username" />
            <x-input-error for="email" class="mt-2" />

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && !$this->user->hasVerifiedEmail())
                <p class="text-sm mt-2 border border-yellow-200 bg-yellow-50 p-2 rounded">
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
            @endif
        </div>

        <div class="col-span-6 sm:col-span-3">
            <x-label for="date_of_birth" value="{{ __('Date of Birth') }}" />
            <x-input id="date_of_birth" type="date" class="mt-1 block w-full bg-gray-50 focus:bg-white"
                wire:model="state.date_of_birth" />
            <x-input-error for="date_of_birth" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-3">
            <x-label for="phone" value="{{ __('Mobile Phone') }}" />
            <x-input id="phone" type="text" class="mt-1 block w-full bg-gray-50 focus:bg-white" wire:model="state.phone"
                placeholder="+1 (555) 000-0000" />
            <x-input-error for="phone" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-3">
            <x-label for="gender" value="{{ __('Gender') }}" />
            <select id="gender" class="mt-1 block w-full border-gray-300 bg-gray-50 focus:bg-white rounded-md shadow-sm"
                wire:model="state.gender">
                <option value="">{{ __('Select gender') }}</option>
                <option value="Male">{{ __('Male') }}</option>
                <option value="Female">{{ __('Female') }}</option>
            </select>
            <x-input-error for="gender" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-3">
            <x-label for="marital_status" value="{{ __('Marital Status') }}" />
            <select id="marital_status"
                class="mt-1 block w-full border-gray-300 bg-gray-50 focus:bg-white rounded-md shadow-sm"
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
            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">{{ __('Location Details') }}</h3>
        </div>

        <div class="col-span-6">
            <x-label for="address" value="{{ __('Current Address') }}" />
            <textarea id="address"
                class="mt-1 block w-full border-gray-300 bg-gray-50 focus:bg-white rounded-md shadow-sm" rows="3"
                wire:model="state.address"
                placeholder="Room/Block/Lot, Street, City, State/Province, Country"></textarea>
            <x-input-error for="address" class="mt-2" />
        </div>

        <!-- Section 3: Social Profiles -->
        <!-- <div class="col-span-6 mb-2 mt-6">
            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">{{ __('Social Presence') }}</h3>
        </div> -->

        <!-- <div class="col-span-6 sm:col-span-3">
            <div class="flex items-center gap-2 mb-1">
                <i class="ri-facebook-box-fill text-blue-600 text-lg"></i>
                <x-label for="social_facebook" value="{{ __('Facebook profile') }}" />
            </div>
            <x-input id="social_facebook" type="url" class="block w-full text-sm bg-gray-50 focus:bg-white"
                wire:model="state.social_facebook" placeholder="https://facebook.com/username" />
            <x-input-error for="social_facebook" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-3">
            <div class="flex items-center gap-2 mb-1">
                <i class="ri-twitter-x-fill text-black text-lg"></i>
                <x-label for="social_twitter" value="{{ __('Twitter / X') }}" />
            </div>
            <x-input id="social_twitter" type="url" class="block w-full text-sm bg-gray-50 focus:bg-white"
                wire:model="state.social_twitter" placeholder="https://x.com/username" />
            <x-input-error for="social_twitter" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-2">
            <div class="flex items-center gap-2 mb-1">
                <i class="ri-instagram-fill text-pink-600 text-lg"></i>
                <x-label for="social_instagram" value="{{ __('Instagram') }}" />
            </div>
            <x-input id="social_instagram" type="url" class="block w-full text-sm bg-gray-50 focus:bg-white"
                wire:model="state.social_instagram" />
            <x-input-error for="social_instagram" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-2">
            <div class="flex items-center gap-2 mb-1">
                <i class="ri-github-fill text-gray-800 text-lg"></i>
                <x-label for="social_github" value="{{ __('GitHub') }}" />
            </div>
            <x-input id="social_github" type="url" class="block w-full text-sm bg-gray-50 focus:bg-white"
                wire:model="state.social_github" />
            <x-input-error for="social_github" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-2">
            <div class="flex items-center gap-2 mb-1">
                <i class="ri-youtube-fill text-red-600 text-lg"></i>
                <x-label for="social_youtube" value="{{ __('YouTube') }}" />
            </div>
            <x-input id="social_youtube" type="url" class="block w-full text-sm bg-gray-50 focus:bg-white"
                wire:model="state.social_youtube" />
            <x-input-error for="social_youtube" class="mt-2" />
        </div> -->
        
    </x-slot>


    <x-slot name="actions">
        <x-action-message class="me-3" on="saved">
            {{ __('Saved.') }}
        </x-action-message>

        <x-button wire:loading.attr="disabled" wire:target="photo">
            {{ __('Save') }}
        </x-button>
    </x-slot>
</x-form-section>

@push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('saved', () => {
                Swal.fire({
                    title: 'Success!',
                    text: 'Profile information updated successfully.',
                    icon: 'success',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    background: '#ffffff',
                    color: '#1a1a1a',
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
    </style>
@endpush
