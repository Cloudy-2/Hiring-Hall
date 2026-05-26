<x-app-layout page-title="Manage Users" active="User Accounts" :breadcrumbs="[['label' => 'Users', 'url' => route('moderator.users.index')]]">

    <style>
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(1, minmax(0, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        @media (min-width: 640px) {
            .kpi-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (min-width: 1024px) {
            .kpi-grid {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }

        .kpi-card-selectable {
            background: #fff;
            border: 2px solid transparent;
            border-radius: 1.25rem;
            padding: 1.25rem;
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            gap: 1rem;
            cursor: pointer;
            user-select: none;
        }

        .kpi-card-selectable:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 20px -5px rgba(0, 0, 0, 0.1);
        }

        .kpi-card-selectable.active {
            border-color: rgb(var(--primary));
            background: rgba(var(--primary), 0.02);
            box-shadow: 0 0 0 4px rgba(var(--primary), 0.1);
        }

        .kpi-icon-wrapper {
            width: 3rem;
            height: 3rem;
            border-radius: 0.875rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            transition: all 0.3s ease;
        }

        .kpi-card-all .kpi-icon-wrapper {
            background: rgba(79, 70, 229, 0.1);
            color: #4f46e5;
        }

        .kpi-card-applicant .kpi-icon-wrapper {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }

        .kpi-card-employer .kpi-icon-wrapper {
            background: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
        }

        .kpi-card-moderator .kpi-icon-wrapper {
            background: rgba(14, 165, 233, 0.1);
            color: #0ea5e9;
        }

        .kpi-card-verified .kpi-icon-wrapper {
            background: rgba(34, 197, 94, 0.1);
            color: #22c55e;
        }

        .kpi-card-unverified .kpi-icon-wrapper {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        .kpi-info {
            flex: 1;
        }

        .kpi-label {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }

        .kpi-value {
            display: block;
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
        }

        [data-theme-mode="dark"] .kpi-card-selectable,
        .dark .kpi-card-selectable {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.05);
        }

        [data-theme-mode="dark"] .kpi-card-selectable.active,
        .dark .kpi-card-selectable.active {
            background: rgba(var(--primary), 0.1);
            border-color: rgb(var(--primary));
        }

        [data-theme-mode="dark"] .kpi-label,
        .dark .kpi-label {
            color: #94a3b8;
        }

        [data-theme-mode="dark"] .kpi-value,
        .dark .kpi-value {
            color: #f1f5f9;
        }
    </style>

    <x-modern-header class="text-2xl" chip="User Management" title="Manage Users"
        desc='View and manage all user accounts in the system.'>
        <x-slot name="actions">
            <div class="inline-flex items-center gap-2">
                <form method="GET" action="{{ route('moderator.users.index') }}" class="flex items-center gap-2"
                    id="filter-form">
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search name or email..." class="ti-form-input w-48 ps-9">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <i class="bi bi-search text-gray-400"></i>
                        </div>
                    </div>
                    <select name="role" id="role-select" class="ti-form-select w-36" onchange="this.form.submit()">
                        <option value="all" {{ request('role', 'all') === 'all' ? 'selected' : '' }}>All Roles
                        </option>
                        <option value="applicant" {{ request('role') === 'applicant' ? 'selected' : '' }}>
                            Applicant</option>
                        <option value="employer" {{ request('role') === 'employer' ? 'selected' : '' }}>
                            Employer</option>
                        <option value="moderator" {{ request('role') === 'moderator' ? 'selected' : '' }}>
                            Moderator</option>
                    </select>
                    <select name="verification" id="verification-select" class="ti-form-select w-40" onchange="this.form.submit()">
                        <option value="all" {{ request('verification', 'all') === 'all' ? 'selected' : '' }}>All Verification
                        </option>
                        <option value="verified" {{ request('verification') === 'verified' ? 'selected' : '' }}>
                            ✓ Verified</option>
                        <option value="unverified" {{ request('verification') === 'unverified' ? 'selected' : '' }}>
                            ✗ Unverified</option>
                    </select>
                    @if(request('search') || (request('role') && request('role') !== 'all') || (request('verification') && request('verification') !== 'all'))
                        <a href="{{ route('moderator.users.index') }}" class="ti-btn ti-btn-light ti-btn-sm">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </form>
            </div>
        </x-slot>
        <x-slot name="footer">
            <div class="kpi-grid">
                <!-- All Users -->
                <div onclick="selectRole('all')"
                    class="kpi-card-selectable kpi-card-all {{ request('role', 'all') === 'all' ? 'active' : '' }}"
                    id="card-all">
                    <div class="kpi-icon-wrapper">
                        <i class="ri-group-line"></i>
                    </div>
                    <div class="kpi-info">
                        <span class="kpi-label">Total Users</span>
                        <span class="kpi-value">{{ number_format($stats['total']) }}</span>
                    </div>
                </div>

                <!-- Applicants -->
                <div onclick="selectRole('applicant')"
                    class="kpi-card-selectable kpi-card-applicant {{ request('role') === 'applicant' ? 'active' : '' }}"
                    id="card-applicant">
                    <div class="kpi-icon-wrapper">
                        <i class="ri-user-search-line"></i>
                    </div>
                    <div class="kpi-info">
                        <span class="kpi-label">Applicants</span>
                        <span class="kpi-value">{{ number_format($stats['applicant']) }}</span>
                    </div>
                </div>

                <!-- Employers -->
                <div onclick="selectRole('employer')"
                    class="kpi-card-selectable kpi-card-employer {{ request('role') === 'employer' ? 'active' : '' }}"
                    id="card-employer">
                    <div class="kpi-icon-wrapper">
                        <i class="ri-building-line"></i>
                    </div>
                    <div class="kpi-info">
                        <span class="kpi-label">Employers</span>
                        <span class="kpi-value">{{ number_format($stats['employer']) }}</span>
                    </div>
                </div>

                <!-- Moderators -->
                <div onclick="selectRole('moderator')"
                    class="kpi-card-selectable kpi-card-moderator {{ request('role') === 'moderator' ? 'active' : '' }}"
                    id="card-moderator">
                    <div class="kpi-icon-wrapper">
                        <i class="ri-shield-user-line"></i>
                    </div>
                    <div class="kpi-info">
                        <span class="kpi-label">Moderators</span>
                        <span class="kpi-value">{{ number_format($stats['moderator']) }}</span>
                    </div>
                </div>

                <!-- Verified Users -->
                <div onclick="selectVerification('verified')"
                    class="kpi-card-selectable kpi-card-verified {{ request('verification') === 'verified' ? 'active' : '' }}"
                    id="card-verified">
                    <div class="kpi-icon-wrapper">
                        <i class="ri-verify-badge-fill"></i>
                    </div>
                    <div class="kpi-info">
                        <span class="kpi-label">Verified</span>
                        <span class="kpi-value">{{ number_format($stats['verified']) }}</span>
                    </div>
                </div>

                <!-- Unverified Users -->
                <div onclick="selectVerification('unverified')"
                    class="kpi-card-selectable kpi-card-unverified {{ request('verification') === 'unverified' ? 'active' : '' }}"
                    id="card-unverified">
                    <div class="kpi-icon-wrapper">
                        <i class="ri-time-line"></i>
                    </div>
                    <div class="kpi-info">
                        <span class="kpi-label">Unverified</span>
                        <span class="kpi-value">{{ number_format($stats['unverified']) }}</span>
                    </div>
                </div>
            </div>
        </x-slot>   
    </x-modern-header>

    <div class="grid grid-cols-12 gap-6 mx-auto pb-6 sm:px-6 lg:px-8">
        <div class="xl:col-span-12 col-span-12">
            <div class="box">
                <div class="box-body">
                    {{-- View Toggle Buttons --}}
                    <div class="flex items-center justify-between mt-3">
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Showing <strong id="shown-count">{{ $users->count() }}</strong> of <strong
                                id="total-count">{{ $users->total() }}</strong> users
                        </div>
                        <div class="inline-flex rounded-md shadow-sm" role="group">
                            <button type="button" onclick="switchView('table')" id="btn-table"
                                class="view-toggle-btn ti-btn ti-btn-sm ti-btn-primary rounded-e-none border-e-0">
                                <i class="bi bi-table me-1"></i> Table
                            </button>
                            <button type="button" onclick="switchView('list')" id="btn-list"
                                class="view-toggle-btn ti-btn ti-btn-sm ti-btn-light rounded-none border-e-0">
                                <i class="bi bi-list-ul me-1"></i> List
                            </button>
                            <button type="button" onclick="switchView('gallery')" id="btn-gallery"
                                class="view-toggle-btn ti-btn ti-btn-sm ti-btn-light rounded-s-none">
                                <i class="bi bi-grid-3x3-gap me-1"></i> Gallery
                            </button>
                        </div>
                    </div>

                    <hr class="mb-3 !mt-3">

                    {{-- ============================================ --}}
                    {{-- TABLE VIEW (default) --}}
                    {{-- ============================================ --}}
                    <div id="view-table" class="view-content">
                        <div class="overflow-x-auto">
                            <table class="ti-custom-table ti-striped-table ti-custom-table-hover">
                                <thead>
                                    <tr>
                                        <th class="!text-start">User</th>
                                        <th class="!text-start">Email</th>
                                        <th class="!text-start">Status</th>
                                        <th class="!text-start">Role</th>
                                        <th class="!text-start">Joined</th>
                                        <th class="!text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $user)
                                        <tr class="{{ $user->id === auth()->id() ? 'bg-primary/5' : '' }}">
                                            <td>
                                                <div class="flex items-center gap-3">
                                                    <span class="avatar avatar-md">
                                                        <img src="{{ $user->profile_photo_path ? asset('storage/' . ltrim($user->profile_photo_path, '/')) : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode($user->name ?? 'User') . '&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981' }}"
                                                            alt="{{ $user->name }}">
                                                    </span>
                                                    <div>
                                                        <p class="font-semibold text-gray-800 dark:text-white">
                                                            {{ $user->name }}
                                                            @if($user->id === auth()->id())
                                                                <span class="text-xs text-primary font-normal">(you)</span>
                                                            @endif
                                                        </p>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">ID:
                                                            {{ $user->id }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-gray-600 dark:text-gray-300">{{ $user->email }}</span>
                                            </td>
                                            <td>
                                                @if($user->email_verified_at)
                                                    <span class="inline-flex items-center gap-1">
                                                        <i class="bi bi-patch-check-fill text-success text-sm"></i>
                                                        <span class="text-success text-sm font-semibold">Verified</span>
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1">
                                                        <i class="bi bi-clock-history text-danger text-sm"></i>
                                                        <span class="text-danger text-sm font-semibold">Unverified</span>
                                                    </span>
                                                @endif
                                            </td>
                                            <td style="padding: 0;" class="relative">
                                                @php
                                                    $roleColors = [
                                                        'applicant' => '#00c875',
                                                        'employer' => '#fdab3d',
                                                        'moderator' => '#3b82f6',
                                                        'admin' => '#e2445c',
                                                    ];
                                                    $roleColor = $roleColors[$user->role] ?? '#c4c4c4';
                                                @endphp
                                                <div class="hs-dropdown ti-dropdown w-full h-full">
                                                    <button type="button"
                                                        class="w-full h-full flex items-center justify-center cursor-pointer hover:opacity-90 transition-all hs-dropdown-toggle"
                                                        style="background-color: {{ $roleColor }}; min-height: 52px;">
                                                        <span
                                                            class="text-white text-xs font-medium">{{ $user->role === 'applicant' ? 'Applicant' : ucfirst($user->role ?? 'Unknown') }}</span>
                                                        <i
                                                            class="bi bi-chevron-down text-white text-[10px] ml-1 opacity-70"></i>
                                                    </button>
                                                    <div class="hs-dropdown-menu ti-dropdown-menu hidden min-w-[160px]">
                                                        <div
                                                            class="px-3 py-2 border-b border-gray-200 dark:border-gray-700">
                                                            <span class="text-xs text-gray-500">Change role for
                                                                {{ $user->name }}</span>
                                                        </div>
                                                        @foreach(['applicant' => '#00c875', 'employer' => '#fdab3d', 'moderator' => '#3b82f6'] as $role => $color)
                                                            <form method="POST" action="/moderator/users/{{ $user->id }}/role"
                                                                class="m-0">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="role" value="{{ $role }}">
                                                                <button type="submit"
                                                                    class="ti-dropdown-item flex items-center gap-2 w-full {{ $user->role === $role ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                                                                    <span class="w-3 h-3 rounded-full"
                                                                        style="background-color: {{ $color }};"></span>
                                                                    {{ $role === 'applicant' ? 'Applicant' : ucfirst($role) }}
                                                                    @if($user->role === $role)
                                                                        <i class="bi bi-check-lg ml-auto text-success"></i>
                                                                    @endif
                                                                </button>
                                                            </form>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span
                                                    class="text-gray-500 dark:text-gray-400 text-sm">{{ $user->created_at->format('M d, Y') }}</span>
                                            </td>
                                            <td class="text-center">
                                                <div class="hs-dropdown ti-dropdown">
                                                    <button type="button" class="ti-btn ti-btn-sm ti-btn-light"
                                                        aria-expanded="false">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <div class="hs-dropdown-menu ti-dropdown-menu hidden">
                                                        <a href="{{ route('profile.public', $user) }}"
                                                            class="ti-dropdown-item">
                                                            <i class="bi bi-eye me-2"></i> View Profile
                                                        </a>
                                                        @if($user->id !== auth()->id())
                                                            <a href="{{ route('chats.v2', ['user' => $user->id]) }}"
                                                                class="ti-dropdown-item">
                                                                <i class="bi bi-chat-dots me-2"></i> Send Message
                                                            </a>
                                                            <div class="ti-dropdown-divider"></div>
                                                            <button type="button"
                                                                onclick="confirmImpersonate({{ $user->id }}, '{{ e($user->name) }}')"
                                                                class="ti-dropdown-item text-amber-600">
                                                                <i class="bi bi-incognito me-2"></i> Impersonate User
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-8">
                                                <div class="flex flex-col items-center gap-2">
                                                    <i class="bi bi-people text-4xl text-gray-300"></i>
                                                    <p class="text-gray-500">No users found</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- ============================================ --}}
                    {{-- LIST VIEW (infinite scroll) --}}
                    {{-- ============================================ --}}
                    <div id="view-list" class="view-content hidden">
                        <div id="list-container" class="flex flex-col gap-3">
                            @forelse($users as $user)
                                @include('modules.users.partials.list-item', ['user' => $user])
                            @empty
                                <div class="flex flex-col items-center gap-2 py-8">
                                    <i class="bi bi-people text-4xl text-gray-300"></i>
                                    <p class="text-gray-500">No users found</p>
                                </div>
                            @endforelse
                        </div>
                        {{-- Infinite Scroll Sentinel & Loader --}}
                        <div id="list-scroll-sentinel" class="flex justify-center py-4">
                            <div id="list-loader" class="hidden flex items-center gap-2 text-gray-400">
                                <div
                                    class="animate-spin w-5 h-5 border-2 border-primary border-t-transparent rounded-full">
                                </div>
                                <span class="text-sm">Loading more users...</span>
                            </div>
                        </div>
                        <div id="list-end-message" class="hidden text-center py-3">
                            <span class="text-xs text-gray-400"><i class="bi bi-check-circle me-1"></i>All users
                                loaded</span>
                        </div>
                    </div>

                    {{-- ============================================ --}}
                    {{-- GALLERY VIEW (infinite scroll) --}}
                    {{-- ============================================ --}}
                    <div id="view-gallery" class="view-content hidden">
                        <div id="gallery-container"
                            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                            @forelse($users as $user)
                                @include('modules.users.partials.gallery-item', ['user' => $user])
                            @empty
                                <div class="col-span-full flex flex-col items-center gap-2 py-8">
                                    <i class="bi bi-people text-4xl text-gray-300"></i>
                                    <p class="text-gray-500">No users found</p>
                                </div>
                            @endforelse
                        </div>
                        {{-- Infinite Scroll Sentinel & Loader --}}
                        <div id="gallery-scroll-sentinel" class="flex justify-center py-4">
                            <div id="gallery-loader" class="hidden flex items-center gap-2 text-gray-400">
                                <div
                                    class="animate-spin w-5 h-5 border-2 border-primary border-t-transparent rounded-full">
                                </div>
                                <span class="text-sm">Loading more users...</span>
                            </div>
                        </div>
                        <div id="gallery-end-message" class="hidden text-center py-3">
                            <span class="text-xs text-gray-400"><i class="bi bi-check-circle me-1"></i>All users
                                loaded</span>
                        </div>
                    </div>

                    {{-- Table pagination (only shown in table view) --}}
                    <div id="table-pagination">
                        @if($users->hasPages())
                            <div class="mt-4 pt-3 border-t border-defaultborder dark:border-defaultborder/10">
                                {{ $users->links('pagination::tailwind') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="impersonate-form" method="POST" action="{{ route('moderator.impersonate') }}" class="hidden">
        @csrf
        <input type="hidden" name="user_id" id="impersonate-user-id">
    </form>

    <script>
        // ========================================
        // Infinite Scroll State
        // ========================================
        const infiniteScroll = {
            nextPage: {{ $users->currentPage() + 1 }},
            hasMore: {{ $users->hasMorePages() ? 'true' : 'false' }},
            loading: false,
            baseUrl: '{{ route('moderator.users.index') }}',
            searchParams: new URLSearchParams(window.location.search),
            observer: null,
            shownCount: {{ $users->count() }},
            totalCount: {{ $users->total() }},
        };

        // ========================================
        // View Switching
        // ========================================
        function switchView(view) {
            // Hide all views
            document.querySelectorAll('.view-content').forEach(el => el.classList.add('hidden'));
            document.getElementById('view-' + view).classList.remove('hidden');

            // Update button styles
            document.querySelectorAll('.view-toggle-btn').forEach(btn => {
                btn.classList.remove('ti-btn-primary');
                btn.classList.add('ti-btn-light');
            });
            const activeBtn = document.getElementById('btn-' + view);
            activeBtn.classList.remove('ti-btn-light');
            activeBtn.classList.add('ti-btn-primary');

            // Show/hide table pagination
            const tablePagination = document.getElementById('table-pagination');
            if (view === 'table') {
                tablePagination.classList.remove('hidden');
            } else {
                tablePagination.classList.add('hidden');
            }

            // Save preference
            localStorage.setItem('users-view-preference', view);

            // Re-observe sentinels when switching to list/gallery
            if (view === 'list' || view === 'gallery') {
                setupInfiniteScroll();
            }
        }

        // ========================================
        // Infinite Scroll Logic
        // ========================================
        function setupInfiniteScroll() {
            if (infiniteScroll.observer) {
                infiniteScroll.observer.disconnect();
            }

            infiniteScroll.observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && infiniteScroll.hasMore && !infiniteScroll.loading) {
                        loadMoreUsers();
                    }
                });
            }, {
                rootMargin: '200px',
            });

            const listSentinel = document.getElementById('list-scroll-sentinel');
            const gallerySentinel = document.getElementById('gallery-scroll-sentinel');

            if (listSentinel) infiniteScroll.observer.observe(listSentinel);
            if (gallerySentinel) infiniteScroll.observer.observe(gallerySentinel);
        }

        async function loadMoreUsers() {
            if (infiniteScroll.loading || !infiniteScroll.hasMore) return;

            infiniteScroll.loading = true;

            // Show loaders
            document.getElementById('list-loader')?.classList.remove('hidden');
            document.getElementById('gallery-loader')?.classList.remove('hidden');

            try {
                // Build URL with current search/filter params
                const params = new URLSearchParams(infiniteScroll.searchParams);
                params.set('page', infiniteScroll.nextPage);

                const response = await fetch(infiniteScroll.baseUrl + '?' + params.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                });

                if (!response.ok) throw new Error('Network response was not ok');

                const data = await response.json();

                // Append list items
                const listContainer = document.getElementById('list-container');
                if (listContainer && data.list_html) {
                    listContainer.insertAdjacentHTML('beforeend', data.list_html);
                }

                // Append gallery items
                const galleryContainer = document.getElementById('gallery-container');
                if (galleryContainer && data.gallery_html) {
                    galleryContainer.insertAdjacentHTML('beforeend', data.gallery_html);
                }

                // Update state
                infiniteScroll.hasMore = data.has_more;
                infiniteScroll.nextPage = data.next_page;

                // Update shown count
                infiniteScroll.shownCount += listContainer ? listContainer.querySelectorAll(':scope > div:not(.flex.flex-col.items-center)').length - infiniteScroll.shownCount : 0;
                const newShown = document.getElementById('list-container').children.length;
                document.getElementById('shown-count').textContent = newShown;

                // Show end message if no more pages
                if (!data.has_more) {
                    document.getElementById('list-end-message')?.classList.remove('hidden');
                    document.getElementById('gallery-end-message')?.classList.remove('hidden');
                    document.getElementById('list-scroll-sentinel')?.classList.add('hidden');
                    document.getElementById('gallery-scroll-sentinel')?.classList.add('hidden');

                    if (infiniteScroll.observer) {
                        infiniteScroll.observer.disconnect();
                    }
                }
            } catch (error) {
                console.error('Error loading more users:', error);
            } finally {
                infiniteScroll.loading = false;
                document.getElementById('list-loader')?.classList.add('hidden');
                document.getElementById('gallery-loader')?.classList.add('hidden');
            }
        }

        // ========================================
        // Role Selection Logic
        // ========================================
        function selectRole(role) {
            // Update select dropdown
            const select = document.getElementById('role-select');
            if (select) {
                select.value = role;
            }

            // Update card active states
            document.querySelectorAll('.kpi-card-selectable').forEach(card => {
                card.classList.remove('active');
            });
            document.getElementById('card-' + role)?.classList.add('active');

            // Automatically submit the form
            const form = document.getElementById('filter-form');
            if (form) {
                form.submit();
            }
        }

        // ========================================
        // Verification Selection Logic
        // ========================================
        function selectVerification(status) {
            // Update select dropdown
            const select = document.getElementById('verification-select');
            if (select) {
                select.value = status;
            }

            // Update card active states - only for verification cards
            document.getElementById('card-verified')?.classList.remove('active');
            document.getElementById('card-unverified')?.classList.remove('active');
            document.getElementById('card-' + status)?.classList.add('active');

            // Automatically submit the form
            const form = document.getElementById('filter-form');
            if (form) {
                form.submit();
            }
        }

        // ========================================
        // Page Init
        // ========================================
        document.addEventListener('DOMContentLoaded', function () {
            const savedView = localStorage.getItem('users-view-preference') || 'gallery';
            switchView(savedView);
        });

        // ========================================
        // Impersonate Confirmation
        // ========================================
        function confirmImpersonate(userId, userName) {
            const safeUserName = String(userName).replace(/[<>&"']/g, function (c) {
                return { '<': '&lt;', '>': '&gt;', '&': '&amp;', '"': '&quot;', "'": '&#39;' }[c];
            });

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Impersonate ' + safeUserName + '?',
                    text: 'You will be viewing the system as this user. Click "Return to Your Account" to go back.',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#f59e0b',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, impersonate',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('impersonate-user-id').value = userId;
                        document.getElementById('impersonate-form').submit();
                    }
                });
            } else {
                if (confirm('Impersonate ' + safeUserName + '?')) {
                    document.getElementById('impersonate-user-id').value = userId;
                    document.getElementById('impersonate-form').submit();
                }
            }
        }
    </script>

</x-app-layout>