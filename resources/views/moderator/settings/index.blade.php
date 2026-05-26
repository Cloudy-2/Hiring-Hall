<x-app-layout
    page-title="Application Settings"
    :breadcrumbs="[
        ['label' => 'Admin', 'url' => route('admin.dashboard')],
    ]"
    active="Settings"
>
    <div class="box">
        <div class="box-body">
            <div class="mb-4">
                <h6 class="font-bold text-2xl text-gray-700 dark:text-white">
                    <strong>Application Settings</strong>
                </h6>
                <span class="text-sm text-gray-600 dark:text-gray-300">
                    Key-value settings stored in the database. Leave value empty to remove a setting.
                </span>
            </div>

            @if (session('success'))
                <div class="mb-4 rounded-lg bg-green-100 dark:bg-green-900/30 px-4 py-3 text-sm text-green-800 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 rounded-lg bg-red-100 dark:bg-red-900/30 px-4 py-3 text-sm text-red-800 dark:text-red-200">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="overflow-x-auto">
                    <table class="table min-w-full">
                        <thead class="border-b border-defaultborder dark:border-defaultborder/10 bg-gray-50 dark:bg-white/5">
                            <tr>
                                <th class="px-3 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 w-48">Key</th>
                                <th class="px-3 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($settings as $setting)
                                <tr class="border-b border-defaultborder dark:border-defaultborder/10">
                                    <td class="px-3 py-2">
                                        <span class="font-mono text-sm text-gray-700 dark:text-gray-200">{{ $setting->key }}</span>
                                    </td>
                                    <td class="px-3 py-2">
                                        <input type="text" name="settings[{{ $setting->key }}]" value="{{ old('settings.'.$setting->key, $setting->value) }}" class="form-control form-control-sm w-full max-w-md" placeholder="Value (leave empty to delete)">
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="border-b border-defaultborder dark:border-defaultborder/10 bg-gray-50/50 dark:bg-white/5">
                                <td class="px-3 py-2">
                                    <input type="text" name="new_key" value="{{ old('new_key') }}" class="form-control form-control-sm w-full max-w-xs font-mono" placeholder="New key (e.g. app_name)">
                                </td>
                                <td class="px-3 py-2">
                                    <input type="text" name="new_value" value="{{ old('new_value') }}" class="form-control form-control-sm w-full max-w-md" placeholder="New value">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <p class="text-sm text-textmuted">Keys may contain letters, numbers, and underscores only.</p>

                <button type="submit" class="ti-btn ti-btn-primary">
                    <i class="bi bi-check-lg"></i> Save settings
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
