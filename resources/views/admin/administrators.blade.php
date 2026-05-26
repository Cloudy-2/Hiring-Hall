<x-app-layout>

    <x-slot name="pageTitle">Administrator Management</x-slot>
    <x-slot name="active">Administrator Management</x-slot>

    <x-modern-header chip="System Administration" title="Administrator Management" desc="Manage elevated access accounts and system infrastructure permissions.">
    </x-modern-header>

    <div class="grid grid-cols-12 gap-6 mx-auto pb-6 sm:px-6 lg:px-8">
        <!-- Create Administrator Form -->
        <div class="col-span-12 lg:col-span-4">
            <div class="box border">
                <div class="box-header bg-danger/10">
                    <div class="box-title text-danger flex items-center gap-2">
                        <i class="ri-shield-user-line"></i> Create Administrator
                    </div>
                </div>
                <div class="box-body">
                    <div class="alert alert-warning mb-4 text-sm">
                        <i class="ri-alert-line me-1"></i>
                        Administrators have elevated privileges. Only create accounts for trusted personnel.
                    </div>
                    <form action="{{ route('admin.administrators.create') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium mb-1">Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Full Name" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="admin@example.com"
                                required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Min 8 characters"
                                required minlength="8">
                        </div>
                        <input type="hidden" name="role" value="admin">
                        <button type="submit" class="ti-btn ti-btn-danger w-full">
                            <i class="ri-shield-user-line me-1"></i> Create Administrator
                        </button>
                    </form>
                </div>
            </div>

            <!-- Role Hierarchy Info -->
            <div class="box border mt-4">
                <div class="box-header">
                    <div class="box-title">Role Hierarchy</div>
                </div>
                <div class="box-body">
                    <div class="space-y-3 text-sm">
                        <div class="flex items-start gap-3 p-3 rounded-lg bg-danger/5 border border-danger/20">
                            <i class="ri-vip-crown-2-fill text-danger text-lg"></i>
                            <div>
                                <p class="font-semibold text-danger">Super Admin</p>
                                <p class="text-textmuted text-xs">Can create admin accounts, full system access</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 p-3 rounded-lg bg-warning/5 border border-warning/20">
                            <i class="ri-shield-user-fill text-warning text-lg"></i>
                            <div>
                                <p class="font-semibold text-warning">Admin</p>
                                <p class="text-textmuted text-xs">Can create moderators/staff, manage users</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 p-3 rounded-lg bg-primary/5 border border-primary/20">
                            <i class="ri-user-settings-fill text-primary text-lg"></i>
                            <div>
                                <p class="font-semibold text-primary">Moderator</p>
                                <p class="text-textmuted text-xs">Can manage content, monitor chats</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Administrators List -->
        <div class="col-span-12 lg:col-span-8">
            <div class="box border">
                <div class="box-header flex items-center justify-between">
                    <div class="box-title">Administrators</div>
                    <form action="" method="GET" class="flex gap-2">
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..."
                            value="{{ request('search') }}">
                        <button type="submit" class="ti-btn ti-btn-sm ti-btn-primary">Search</button>
                    </form>
                </div>
                <div class="box-body">
                    @if(session('success'))
                        <div class="alert alert-success mb-4">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger mb-4">{{ session('error') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table whitespace-nowrap table-bordered">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Email Verified</th>
                                    <th>Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>
                                            <div class="flex items-center gap-2">
                                                <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}"
                                                    class="w-8 h-8 rounded-full object-cover">
                                                <span>{{ $user->name }}</span>
                                                @if($user->id === auth()->id())
                                                    <span class="badge bg-info/10 text-info text-xs">You</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if($user->role === 'super_admin')
                                                <span class="badge bg-danger/10 text-danger flex items-center gap-1 w-fit">
                                                    <i class="ri-vip-crown-2-fill"></i> Super Admin
                                                </span>
                                            @else
                                                <span class="badge bg-warning/10 text-warning flex items-center gap-1 w-fit">
                                                    <i class="ri-shield-user-fill"></i> Admin
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <form
                                                action="{{ route('admin.administrators.toggle-email-verification', $user) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @if($user->email_verified_at)
                                                    <button type="submit"
                                                        class="badge bg-success/10 text-success cursor-pointer hover:bg-success/20 transition"
                                                        title="Click to remove verification">
                                                        <i class="ri-checkbox-circle-fill me-1"></i> Verified
                                                    </button>
                                                @else
                                                    <button type="submit"
                                                        class="badge bg-danger/10 text-danger cursor-pointer hover:bg-danger/20 transition"
                                                        title="Click to verify email">
                                                        <i class="ri-close-circle-fill me-1"></i> Not Verified
                                                    </button>
                                                @endif
                                            </form>
                                        </td>
                                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-textmuted py-4">No administrators found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($users->hasPages())
                        <div class="mt-4">
                            {{ $users->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</x-app-layout>