<x-app-layout>
    <x-slot name="pageTitle">Staff Management</x-slot>
    <x-slot name="active">Staff Management</x-slot>

    <style>
        :is(.dark, [data-theme-mode="dark"]) .box {
            background-color: rgb(30, 32, 34);
            border-color: rgba(255, 255, 255, 0.05);
        }

        :is(.dark, [data-theme-mode="dark"]) .box-header {
            border-bottom-color: rgba(255, 255, 255, 0.05);
        }
    </style>

    <x-modern-header chip="Administration" title="Staff Management"
        desc="Create and manage your system administrators and staff members.">
    </x-modern-header>

    <div class="mx-auto mt-2 pb-6 sm:px-6 lg:px-8">
        <div class="grid grid-cols-12 gap-6">
            <!-- Create Staff Form -->
            <div class="col-span-12 lg:col-span-4">
                <div class="box border h-full">
                    <div class="box-header">
                        <div class="box-title">Create Staff Member</div>
                    </div>
                    <div class="box-body">
                        <form action="{{ route('admin.staff.create') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-semibold mb-1">Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Full Name" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-1">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="email@example.com"
                                    required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-1">Password</label>
                                <input type="password" name="password" class="form-control"
                                    placeholder="Min 8 characters" required minlength="8">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-1">Role</label>
                                <select name="role" class="form-control text-sm" required>
                                    <option value="moderator">Moderator</option>
                                    <option value="employer">Employer</option>
                                    <option value="applicant">Applicant</option>
                                </select>
                            </div>
                            <button type="submit" class="ti-btn ti-btn-primary w-full mt-2">
                                <i class="ri-user-add-line me-1"></i> Create Staff
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Staff List -->
            <div class="col-span-12 lg:col-span-8">
                <div class="box border">
                    <div class="box-header flex items-center justify-between flex-wrap gap-3">
                        <div class="box-title">Existing Staff</div>
                        <form action="" method="GET" class="flex items-center gap-2 flex-1 max-w-md">
                            <div class="relative flex-1">
                                <span
                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-textmuted">
                                    <i class="ri-search-line"></i>
                                </span>
                                <input type="text" name="search" class="form-control form-control-sm pl-9"
                                    placeholder="Search name or email..." value="{{ request('search') }}">
                            </div>
                            <select name="role" class="form-control form-control-sm w-32" onchange="this.form.submit()">
                                <option value="all">All Roles</option>
                                <option value="moderator" {{ request('role') === 'moderator' ? 'selected' : '' }}>
                                    Moderator</option>
                                <option value="employer" {{ request('role') === 'employer' ? 'selected' : '' }}>Employer
                                </option>
                                <option value="applicant" {{ request('role') === 'applicant' ? 'selected' : '' }}>
                                    Applicant</option>
                            </select>
                        </form>
                    </div>
                    <div class="box-body px-0">
                        @if(session('success'))
                            <div class="px-4">
                                <div class="alert alert-success mb-4">{{ session('success') }}</div>
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="px-4">
                                <div class="alert alert-danger mb-4">{{ session('error') }}</div>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table whitespace-nowrap min-w-full">
                                <thead>
                                    <tr class="bg-light dark:bg-white/5 font-bold text-xs uppercase tracking-wider">
                                        <th class="px-4 py-3 text-left">User Info</th>
                                        <th class="px-4 py-3 text-left">Role</th>
                                        <th class="px-4 py-3 text-left">Verification</th>
                                        <th class="px-4 py-3 text-left">Joined</th>
                                        <th class="px-4 py-3 text-right">Update Role</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-defaultborder/10">
                                    @forelse($users as $user)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center gap-3">
                                                    <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}"
                                                        class="w-10 h-10 rounded-full object-cover border border-defaultborder/10">
                                                    <div>
                                                        <div class="font-semibold text-sm">{{ $user->name }}</div>
                                                        <div class="text-xs text-textmuted leading-none mt-0.5">
                                                            {{ $user->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span
                                                    class="px-2 py-1 text-[10px] uppercase font-bold rounded-full {{ $user->role === 'moderator' ? 'bg-primary/10 text-primary' : ($user->role === 'employer' ? 'bg-warning/10 text-warning' : 'bg-success/10 text-success') }}">
                                                    {{ $user->role === 'applicant' ? 'Applicant' : ucfirst($user->role) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <form action="{{ route('admin.staff.toggle-email-verification', $user) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    @if($user->email_verified_at)
                                                        <button type="submit"
                                                            class="flex items-center gap-1.5 text-xs font-semibold text-success hover:bg-success/5 px-2 py-1 rounded transition"
                                                            title="Click to remove verification">
                                                            <i class="ri-checkbox-circle-fill"></i> Verified
                                                        </button>
                                                    @else
                                                        <button type="submit"
                                                            class="flex items-center gap-1.5 text-xs font-semibold text-danger hover:bg-danger/5 px-2 py-1 rounded transition"
                                                            title="Click to verify email">
                                                            <i class="ri-close-circle-fill"></i> Unverified
                                                        </button>
                                                    @endif
                                                </form>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-textmuted">
                                                {{ $user->created_at->format('M d, Y') }}
                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                <form action="{{ route('moderator.users.update-role', $user) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <select name="role"
                                                        class="form-control form-control-sm w-32 inline-block ml-auto"
                                                        onchange="this.form.submit()">
                                                        <option value="moderator" {{ $user->role === 'moderator' ? 'selected' : '' }}>Moderator</option>
                                                        <option value="employer" {{ $user->role === 'employer' ? 'selected' : '' }}>Employer</option>
                                                        <option value="applicant" {{ $user->role === 'applicant' ? 'selected' : '' }}>Applicant</option>
                                                    </select>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-textmuted py-12">
                                                <div class="mb-2"><i class="ri-user-search-line text-4xl opacity-20"></i>
                                                </div>
                                                No staff members found matching criteria
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($users->hasPages())
                            <div class="px-4 py-3 border-t border-defaultborder/10">
                                {{ $users->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>