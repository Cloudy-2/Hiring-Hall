<x-app-layout page-title="Activity Log Details">
    <x-slot name="url_1">{"link": "/admin/dashboard", "text": "Admin"}</x-slot>
    <x-slot name="url_2">{"link": "/admin/activity-logs", "text": "Activity Logs"}</x-slot>
    <x-slot name="active">Log #{{ $activityLog->id }}</x-slot>

    <x-modern-header chip="Audit Trail" title="Activity Log Details"
        desc="View comprehensive information about this specific system event.">
    </x-modern-header>

    <div class="grid grid-cols-12 gap-6 mx-auto pb-6 sm:px-6 lg:px-8">
        <div class="xl:col-span-8 col-span-12">
            <div class="box border">
                <div class="box-header">
                    <div class="box-title">Activity Details</div>
                </div>
                <div class="box-body">
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="text-xs text-textmuted">Action</label>
                            @php
                                $actionColors = [
                                    'created' => 'success',
                                    'updated' => 'info',
                                    'deleted' => 'danger',
                                    'login' => 'primary',
                                    'logout' => 'secondary',
                                ];
                                $color = $actionColors[$activityLog->action] ?? 'secondary';
                            @endphp
                            <p class="font-medium">
                                <span
                                    class="badge bg-{{ $color }}/10 text-{{ $color }}">{{ ucfirst($activityLog->action) }}</span>
                            </p>
                        </div>
                        <div>
                            <label class="text-xs text-textmuted">Date/Time</label>
                            <p class="font-medium">{{ $activityLog->created_at->format('M d, Y H:i:s') }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-textmuted">Model Type</label>
                            <p class="font-medium">
                                @if($activityLog->model_type)
                                    {{ class_basename($activityLog->model_type) }} #{{ $activityLog->model_id }}
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="text-xs text-textmuted">IP Address</label>
                            <p class="font-medium font-mono">{{ $activityLog->ip_address ?? '-' }}</p>
                        </div>
                    </div>

                    @if($activityLog->description)
                        <div class="mb-6">
                            <label class="text-xs text-textmuted">Description</label>
                            <p class="font-medium">{{ $activityLog->description }}</p>
                        </div>
                    @endif

                    @if($activityLog->user_agent)
                        <div class="mb-6">
                            <label class="text-xs text-textmuted">User Agent</label>
                            <p class="text-sm text-textmuted font-mono break-all">{{ $activityLog->user_agent }}</p>
                        </div>
                    @endif

                    @php $changes = $activityLog->getChangedAttributes(); @endphp
                    @if(!empty($changes))
                        <div class="mb-6">
                            <label class="text-xs text-textmuted mb-2 block">Changes</label>
                            <div class="bg-light dark:bg-slate-800 rounded-lg overflow-hidden">
                                <table class="w-full text-sm">
                                    <thead class="bg-secondary/10">
                                        <tr>
                                            <th class="text-left p-2">Field</th>
                                            <th class="text-left p-2">Old Value</th>
                                            <th class="text-left p-2">New Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($changes as $field => $change)
                                            <tr class="border-t border-gray-200 dark:border-slate-700">
                                                <td class="p-2 font-medium">{{ $field }}</td>
                                                <td class="p-2 text-danger">
                                                    <del>{{ is_array($change['old']) ? json_encode($change['old']) : $change['old'] }}</del>
                                                </td>
                                                <td class="p-2 text-success">
                                                    {{ is_array($change['new']) ? json_encode($change['new']) : $change['new'] }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    @if($activityLog->old_values && $activityLog->action === 'deleted')
                        <div class="mb-6">
                            <label class="text-xs text-textmuted mb-2 block">Deleted Data</label>
                            <pre
                                class="bg-light dark:bg-slate-800 p-4 rounded-lg text-sm overflow-auto max-h-96">{{ json_encode($activityLog->old_values, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    @endif

                    @if($activityLog->new_values && $activityLog->action === 'created')
                        <div class="mb-6">
                            <label class="text-xs text-textmuted mb-2 block">Created Data</label>
                            <pre
                                class="bg-light dark:bg-slate-800 p-4 rounded-lg text-sm overflow-auto max-h-96">{{ json_encode($activityLog->new_values, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="xl:col-span-4 col-span-12">
            <div class="box border">
                <div class="box-header">
                    <div class="box-title">User Info</div>
                </div>
                <div class="box-body">
                    @if($activityLog->user)
                        <div class="flex items-center gap-3 mb-3">
                            <span class="avatar avatar-lg avatar-rounded bg-primary/10 text-primary">
                                {{ strtoupper(substr($activityLog->user->name, 0, 2)) }}
                            </span>
                            <div>
                                <p class="font-medium">{{ $activityLog->user->name }}</p>
                                <p class="text-xs text-textmuted">{{ $activityLog->user->email }}</p>
                            </div>
                        </div>
                        <p class="text-sm"><strong>Role:</strong> {{ ucfirst($activityLog->user->role) }}</p>
                        <a href="{{ route('moderator.activity-logs.index', ['user_id' => $activityLog->user->id]) }}"
                            class="ti-btn ti-btn-sm ti-btn-primary mt-3">
                            View User's Activity
                        </a>
                    @else
                        <p class="text-textmuted">Guest User (Not logged in)</p>
                    @endif
                </div>
            </div>

            <div class="box border mt-4">
                <div class="box-header">
                    <div class="box-title">Quick Actions</div>
                </div>
                <div class="box-body space-y-2">
                    <a href="{{ route('moderator.activity-logs.index') }}" class="ti-btn ti-btn-light w-full">
                        <i class="ri-arrow-left-line me-1"></i> Back to Logs
                    </a>
                    @if($activityLog->model_type)
                        <a href="{{ route('moderator.activity-logs.index', ['model_type' => $activityLog->model_type]) }}"
                            class="ti-btn ti-btn-secondary w-full">
                            View All {{ class_basename($activityLog->model_type) }} Logs
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

</x-app-layout>