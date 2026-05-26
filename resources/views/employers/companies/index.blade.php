<x-app-layout>

    <x-slot name="pageTitle">My Companies</x-slot>
    <x-slot name="url_1">{"link": "/employer/dashboard", "text": "Dashboard"}</x-slot>
    <x-slot name="active">Companies</x-slot>

    @include('employers.partials.employer-styles')

    <div class="grid grid-cols-12 gap-x-5 gap-y-4">

        {{-- ═══ Page Header ═══ --}}
        <x-modern-header
            chip="Company Profiles"
            title="My Companies"
            desc="Manage your registered company profiles. You need at least one verified company to post jobs."
            :container="false"
            headerClass="col-span-12"
        >
            <x-slot name="actions">
                <a href="{{ route('employer.companies.create') }}" class="cd-btn cd-btn-primary"><i class="ri-add-line"></i> Add Company</a>
                <a href="{{ route('employer.dashboard') }}" class="cd-btn cd-btn-outline"><i class="ri-dashboard-line"></i> Dashboard</a>
            </x-slot>
        </x-modern-header>

        {{-- ═══ Companies List ═══ --}}
        <div class="col-span-12" id="wt-companies-list">
            <div class="cd-section">
                <div class="cd-section-head">
                    <span class="cd-section-label"><i class="ri-building-2-fill"></i> Registered Companies</span>
                    <span class="text-xs text-gray-400">{{ $companies->total() }} total</span>
                </div>

                @if (session('status'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            if (window.Swal) Swal.fire({ icon: 'success', title: 'Success', text: @json(session('status')), timer: 2500, showConfirmButton: false });
                        });
                    </script>
                @endif

                @if($companies->isEmpty())
                    <div class="cd-empty">
                        <i class="ri-building-2-line"></i>
                        <p>You haven't registered any companies yet.</p>
                        <a href="{{ route('employer.companies.create') }}" class="cd-btn cd-btn-primary">Register a Company</a>
                    </div>
                @else
                    <div class="grid grid-cols-12 gap-4">
                        @foreach($companies as $company)
                            <div class="xl:col-span-4 md:col-span-6 col-span-12">
                                <div class="cd-company-card dark:bg-gray-800">

                                    {{-- Logo + name --}}
                                    <div class="cd-company-card-head">
                                        @if($company->logo_url)
                                            <img src="{{ $company->logo_url }}" alt="{{ $company->name }}" class="cd-company-logo">
                                        @else
                                            <div class="cd-company-logo-fallback">
                                                {{ strtoupper(substr($company->name, 0, 2)) }}
                                            </div>
                                        @endif
                                        <div class="cd-company-name-wrap">
                                            <div class="cd-company-name">{{ $company->name }}</div>
                                            @if($company->industry)
                                                <div class="cd-company-meta">{{ $company->industry }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Details --}}
                                    <div class="cd-company-details">
                                        @if($company->location)
                                            <span><i class="ri-map-pin-line me-1"></i>{{ $company->location }}</span>
                                        @endif
                                        @if($company->employees_count)
                                            <span><i class="ri-team-line me-1"></i>{{ number_format($company->employees_count) }} employees</span>
                                        @endif
                                        @if($company->website)
                                            <span><i class="ri-global-line me-1"></i><a href="{{ $company->website }}" target="_blank" class="cd-company-link">{{ parse_url($company->website, PHP_URL_HOST) }}</a></span>
                                        @endif
                                    </div>

                                    {{-- Verification badge --}}
                                    <div class="cd-company-status">
                                        @if($company->verified)
                                            <span class="cd-status-pill" style="background:#f0fdf4;color:#16a34a"><i class="ri-verified-badge-fill me-1"></i>Verified</span>
                                        @elseif($company->verification_status === 'pending')
                                            <span class="cd-status-pill" style="background:#fefce8;color:#ca8a04"><i class="ri-time-line me-1"></i>Pending Review</span>
                                        @elseif($company->verification_status === 'rejected')
                                            <span class="cd-status-pill" style="background:#fef2f2;color:#dc2626"><i class="ri-close-circle-line me-1"></i>Rejected</span>
                                            @if($company->rejection_reason)
                                                <div class="text-xs text-red-500 mt-1">{{ $company->rejection_reason }}</div>
                                            @endif
                                        @else
                                            <span class="cd-status-pill" style="background:#f9fafb;color:#6b7280">Unverified</span>
                                        @endif
                                    </div>

                                    {{-- Actions --}}
                                    <div class="cd-company-actions">
                                        <a href="{{ route('employer.companies.edit', $company) }}" class="cd-btn cd-btn-outline cd-btn-sm"><i class="ri-edit-line me-1"></i>Edit</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="cd-pagination mt-4">{{ $companies->links() }}</div>
                @endif
            </div>
        </div>

    </div>

    @include('candidates.partials.walkthrough', [
        'wtSteps' => [
            ['target' => 'wt-hero',           'title' => 'My Companies',          'icon' => 'ri-building-2-line', 'body' => 'Welcome to your Companies page! Companies are the foundation of your employer account — you need at least one verified company to post jobs.', 'position' => 'bottom'],
            ['target' => 'wt-companies-list', 'title' => 'Company Cards',         'icon' => 'ri-layout-grid-line','body' => 'Each card shows your company name, industry, location, and verification status. Click "Edit" to update details, or "Register New Company" in the hero to add more.', 'position' => 'top'],
        ],
        'wtKey' => 'employer_companies_v2',
    ])

</x-app-layout>
