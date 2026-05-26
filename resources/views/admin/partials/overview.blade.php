<!-- Overview Stats Grid -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <!-- Total Jobs -->
    <div class="box border mb-0">
        <div class="box-body">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-secondary/10 flex items-center justify-center">
                    <i class="ri-file-list-3-line text-xl text-secondary"></i>
                </div>
                <div>
                    <p class="text-textmuted text-sm">Total Jobs</p>
                    <h4 class="text-xl font-semibold">{{ number_format($stats['total_jobs']) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Conversations -->
    <div class="box border mb-0">
        <div class="box-body">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-purple-500/10 flex items-center justify-center">
                    <i class="ri-chat-3-line text-xl text-purple-500"></i>
                </div>
                <div>
                    <p class="text-textmuted text-sm">Conversations</p>
                    <h4 class="text-xl font-semibold">{{ number_format($stats['total_conversations']) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Messages -->
    <div class="box border mb-0">
        <div class="box-body">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-teal-500/10 flex items-center justify-center">
                    <i class="ri-message-2-line text-xl text-teal-500"></i>
                </div>
                <div>
                    <p class="text-textmuted text-sm">Messages</p>
                    <h4 class="text-xl font-semibold">{{ number_format($stats['total_messages']) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Moderators -->
    <div class="box border mb-0">
        <div class="box-body">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center">
                    <i class="ri-shield-user-line text-xl text-primary"></i>
                </div>
                <div>
                    <p class="text-textmuted text-sm">Moderators</p>
                    <h4 class="text-xl font-semibold">{{ number_format($stats['total_moderators'] ?? 0) }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Role Distribution -->
<div class="box border">
    <div class="box-header">
        <div class="box-title">User Distribution by Role</div>
    </div>
    <div class="box-body">
        <div class="space-y-4">
            @php
                $total = $stats['total_users'] ?: 1;
                $roles = [
                    ['name' => 'Applicants', 'count' => $stats['total_applicants'], 'color' => 'success'],
                    ['name' => 'Employers', 'count' => $stats['total_employers'], 'color' => 'warning'],
                    ['name' => 'Moderators', 'count' => $stats['total_moderators'] ?? 0, 'color' => 'info'],
                    ['name' => 'Admins', 'count' => $stats['total_admins'] ?? 0, 'color' => 'danger'],
                ];
            @endphp

            @foreach($roles as $role)
            @php $percentage = ($role['count'] / $total) * 100; @endphp
            <div>
                <div class="flex justify-between mb-1">
                    <span class="text-sm font-medium">{{ $role['name'] }}</span>
                    <span class="text-sm text-textmuted">{{ number_format($role['count']) }} ({{ number_format($percentage, 1) }}%)</span>
                </div>
                <div class="progress progress-sm">
                    <div class="progress-bar bg-{{ $role['color'] }}" role="progressbar" style="width: {{ $percentage }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
