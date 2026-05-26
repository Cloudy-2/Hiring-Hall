<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div>
        <div x-data="{ activeTab: 'profile' }" class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            {{-- Navigation Tabs --}}
            <div class="mb-8 border-b border-gray-200 dark:border-white/10">
                <nav class="flex flex-wrap -mb-px gap-6" aria-label="Tabs">
                    <button @click="activeTab = 'profile'"
                        :class="activeTab === 'profile' ? 'border-primary text-primary' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:border-gray-300 dark:hover:border-gray-700'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all duration-200">
                        <i class="ri-user-settings-line me-2"></i>{{ __('Profile Information') }}
                    </button>

                    @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                        <button @click="activeTab = 'password'"
                            :class="activeTab === 'password' ? 'border-primary text-primary' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:border-gray-300 dark:hover:border-gray-700'"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all duration-200">
                            <i class="ri-lock-password-line me-2"></i>{{ __('Update Password') }}
                        </button>
                    @endif

                    @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                        <button @click="activeTab = '2fa'"
                            :class="activeTab === '2fa' ? 'border-primary text-primary' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:border-gray-300 dark:hover:border-gray-700'"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all duration-200">
                            <i class="ri-shield-keyhole-line me-2"></i>{{ __('Two Factor Auth') }}
                        </button>
                    @endif

                    <button @click="activeTab = 'sessions'"
                        :class="activeTab === 'sessions' ? 'border-primary text-primary' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:border-gray-300 dark:hover:border-gray-700'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all duration-200">
                        <i class="ri-computer-line me-2"></i>{{ __('Browser Session') }}
                    </button>

                    @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                        <button @click="activeTab = 'danger'"
                            :class="activeTab === 'danger' ? 'border-red-500 text-red-500' : 'border-transparent text-gray-400 hover:text-red-400'"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all duration-200">
                            <i class="ri-delete-bin-line me-2"></i>{{ __('Delete Account') }}
                        </button>
                    @endif
                </nav>
            </div>

            {{-- Tab Content --}}
            <div class="mt-6">
                {{-- Profile Info Tab --}}
                <div x-show="activeTab === 'profile'" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform translate-y-2"
                    x-transition:enter-end="opacity-100 transform translate-y-0">
                    @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                        @livewire('profile.update-profile-information-form')
                    @endif
                </div>

                {{-- Password Tab --}}
                @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                    <div x-show="activeTab === 'password'" x-cloak x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform translate-y-2"
                        x-transition:enter-end="opacity-100 transform translate-y-0">
                        @livewire('profile.update-password-form')
                    </div>
                @endif

                {{-- 2FA Tab --}}
                @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                    <div x-show="activeTab === '2fa'" x-cloak x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform translate-y-2"
                        x-transition:enter-end="opacity-100 transform translate-y-0">
                        @livewire('profile.two-factor-authentication-form')
                    </div>
                @endif

                {{-- Browser Sessions Tab --}}
                <div x-show="activeTab === 'sessions'" x-cloak x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform translate-y-2"
                    x-transition:enter-end="opacity-100 transform translate-y-0">
                    @livewire('profile.logout-other-browser-sessions-form')
                </div>

                {{-- Danger Zone Tab --}}
                @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                    <div x-show="activeTab === 'danger'" x-cloak x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform translate-y-2"
                        x-transition:enter-end="opacity-100 transform translate-y-0">
                        @livewire('profile.delete-user-form')
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.Livewire && typeof window.Livewire.on === 'function' && window.Swal) {
                window.Livewire.on('saved', function () {
                    Swal.fire({
                        icon: 'success',
                        title: 'Changes saved',
                        text: 'Your settings have been updated successfully.',
                        timer: 2500,
                        showConfirmButton: false
                    });
                });
            }
        });
    </script>
</x-app-layout>