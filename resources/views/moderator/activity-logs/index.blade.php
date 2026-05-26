<x-app-layout page-title="Activity Logs">
    <x-slot name="url_1">{"link": "/admin/dashboard", "text": "Admin"}</x-slot>
    <x-slot name="active">Activity Logs</x-slot>

    <x-modern-header chip="Audit Trail" title="Activity Logs" desc="Track and review user interactions and system activities.">
    </x-modern-header>

    <div class="grid grid-cols-12 gap-6 mx-auto pb-6 sm:px-6 lg:px-8">
        {{-- Filters --}}
        <div class="col-span-12">
            <div class="box border">
                <div class="box-header">
                    <div class="box-title">Filters</div>
                </div>
                <div class="box-body">
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div>
                            <label class="form-label">Action</label>
                            <select name="action" class="form-control form-control-sm">
                                <option value="">All Actions</option>
                                @foreach($actions as $act)
                                    <option value="{{ $act }}" {{ $action === $act ? 'selected' : '' }}>{{ ucfirst($act) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label">User</label>
                            <select name="user_id" class="form-control form-control-sm">
                                <option value="">All Users</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $userId == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Model Type</label>
                            <select name="model_type" class="form-control form-control-sm">
                                <option value="">All Models</option>
                                @foreach($modelTypes as $type)
                                    <option value="{{ $type }}" {{ $modelType === $type ? 'selected' : '' }}>{{ class_basename($type) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label">From Date</label>
                            <input type="date" name="date_from" class="form-control form-control-sm" value="{{ $dateFrom }}">
                        </div>
                        <div>
                            <label class="form-label">To Date</label>
                            <input type="date" name="date_to" class="form-control form-control-sm" value="{{ $dateTo }}">
                        </div>
                        <div class="md:col-span-5 flex gap-2">
                            <button type="submit" class="ti-btn ti-btn-primary ti-btn-sm">
                                <i class="ri-filter-line me-1"></i> Apply Filters
                            </button>
                            <a href="{{ route('moderator.activity-logs.index') }}" class="ti-btn ti-btn-light ti-btn-sm">
                                Clear
                            </a>
                            <a href="{{ route('moderator.activity-logs.export', request()->query()) }}" class="ti-btn ti-btn-success ti-btn-sm ms-auto">
                                <i class="ri-download-line me-1"></i> Export CSV
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Logs Table --}}
        <div class="col-span-12">
            <div class="box border">
                <div class="box-header">
                    <div class="box-title">Activity Logs ({{ $logs->total() }} total)</div>
                </div>
                <div class="box-body">
                    @if($logs->isEmpty())
                        <div class="text-center py-8">
                            <i class="ri-history-line text-4xl text-textmuted mb-3"></i>
                            <p class="text-textmuted">No activity logs found.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table whitespace-nowrap table-bordered text-sm">
                                <thead>
                                    <tr>
                                        <th>Date/Time</th>
                                        <th>User</th>
                                        <th>Action</th>
                                        <th>Model</th>
                                        <th>Description</th>
                                        <th>IP Address</th>
                                        <th class="text-center">Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($logs as $log)
                                        <tr>
                                            <td>
                                                <span class="text-xs">{{ $log->created_at->format('M d, Y') }}</span>
                                                <br><span class="text-xs text-textmuted">{{ $log->created_at->format('H:i:s') }}</span>
                                            </td>
                                            <td>
                                                @if($log->user)
                                                    <span class="font-medium">{{ $log->user->name }}</span>
                                                    <br><span class="text-xs text-textmuted">{{ $log->user->email }}</span>
                                                @else
                                                    <span class="text-textmuted">Guest</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $actionColors = [
                                                        'created' => 'success',
                                                        'updated' => 'info',
                                                        'deleted' => 'danger',
                                                        'login' => 'primary',
                                                        'logout' => 'secondary',
                                                    ];
                                                    $color = $actionColors[$log->action] ?? 'secondary';
                                                @endphp
                                                <span class="badge bg-{{ $color }}/10 text-{{ $color }}">{{ ucfirst($log->action) }}</span>
                                            </td>
                                            <td>
                                                @if($log->model_type)
                                                    <span class="font-medium">{{ class_basename($log->model_type) }}</span>
                                                    <br><span class="text-xs text-textmuted">#{{ $log->model_id }}</span>
                                                @else
                                                    <span class="text-textmuted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="text-xs">{{ Str::limit($log->description, 40) }}</span>
                                            </td>
                                            <td>
                                                <span class="text-xs font-mono">{{ $log->ip_address }}</span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('moderator.activity-logs.show', $log) }}" class="ti-btn ti-btn-sm ti-btn-info" title="View Details">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $logs->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
