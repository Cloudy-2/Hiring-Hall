<!-- Recent Users -->
<div class="box border">
    <div class="box-header flex items-center justify-between">
        <div class="box-title">Recent Users</div>
        <a href="{{ route('moderator.users.index') }}" class="ti-btn ti-btn-sm ti-btn-primary">
            <i class="ri-user-line me-1"></i> View All Users
        </a>
    </div>
    <div class="box-body">
        <div class="table-responsive">
            <table class="table whitespace-nowrap table-bordered min-w-full">
                <thead>
                    <tr class="border-b border-defaultborder">
                        <th scope="col" class="text-start">User</th>
                        <th scope="col" class="text-start">Email</th>
                        <th scope="col" class="text-start">Role</th>
                        <th scope="col" class="text-start">Joined</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentUsers as $user)
                    <tr class="border-b border-defaultborder">
                        <td>
                            <div class="flex items-center gap-3">
                                <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-8 h-8 rounded-full object-cover">
                                <span class="font-medium">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="text-textmuted">{{ $user->email }}</td>
                        <td>
                            <span class="badge {{ $user->role === 'applicant' ? 'bg-success/10 text-success' : ($user->role === 'employer' ? 'bg-warning/10 text-warning' : ($user->role === 'moderator' ? 'bg-info/10 text-info' : 'bg-danger/10 text-danger')) }}">
                                {{ $user->role === 'applicant' ? 'Applicant' : ucfirst(str_replace('_', ' ', $user->role)) }}
                            </span>
                        </td>
                        <td class="text-textmuted text-sm">{{ $user->created_at->diffForHumans() }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-textmuted py-4">No users found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($recentUsers->hasPages())
        <div class="mt-4 pt-3 border-t border-defaultborder">
            {{ $recentUsers->appends(['tab' => 'users'])->links('pagination::simple-tailwind') }}
        </div>
        @endif
    </div>
</div>
