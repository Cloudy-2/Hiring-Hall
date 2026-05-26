<x-app-layout page-title="Company Verification">
    <x-slot name="url_1">{"link": "/moderator/dashboard", "text": "Moderator"}</x-slot>
    <x-slot name="active">Registered Companies</x-slot>

    <style>
        /* Dark Mode Overrides */
        [data-theme-mode="dark"] .box, .dark .box,
        [data-theme-mode="dark"] .bg-white, .dark .bg-white {
            background-color: rgba(255,255,255,0.02) !important;
            border-color: rgba(255,255,255,0.05) !important;
        }
        [data-theme-mode="dark"] .box-header, .dark .box-header {
            border-bottom-color: rgba(255,255,255,0.05) !important;
            background-color: rgba(255,255,255,0.01) !important;
        }
        [data-theme-mode="dark"] thead, .dark thead,
        [data-theme-mode="dark"] .bg-gray-50\/50, .dark .bg-gray-50\/50 {
            background-color: rgba(255,255,255,0.02) !important;
        }
        [data-theme-mode="dark"] td, .dark td,
        [data-theme-mode="dark"] th, .dark th {
            border-color: rgba(255,255,255,0.05) !important;
        }
    </style>

    <x-modern-header chip="Company Verification" title="Registered Companies" desc="Review and verify company registrations. Use the filters below to switch between pending, approved, and rejected.">
    </x-modern-header>

    <div class="grid grid-cols-12 gap-6 mx-auto pb-6 sm:px-6 lg:px-8">
        <div class="xl:col-span-12 col-span-12">
            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4" role="tablist" aria-label="Verification status filters">
                <a href="{{ route('moderator.companies.index', array_filter(['status' => 'pending', 'search' => $search ?? null])) }}"
                   class="bg-white dark:bg-slate-900 p-5 rounded-lg shadow-sm border border-gray-200 dark:border-slate-800 {{ $status === 'pending' ? 'border-l-4 border-l-warning' : '' }} hover:bg-warning/5 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-primary"
                   aria-current="{{ $status === 'pending' ? 'true' : 'false' }}">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">Pending Review</p>
                    <p class="mt-1 text-2xl font-extrabold text-warning" aria-hidden="true">{{ $counts['pending'] }}</p>
                </a>
                <a href="{{ route('moderator.companies.index', array_filter(['status' => 'approved', 'search' => $search ?? null])) }}"
                   class="bg-white dark:bg-slate-900 p-5 rounded-lg shadow-sm border border-gray-200 dark:border-slate-800 {{ $status === 'approved' ? 'border-l-4 border-l-success' : '' }} hover:bg-success/5 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-primary"
                   aria-current="{{ $status === 'approved' ? 'true' : 'false' }}">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">Approved</p>
                    <p class="mt-1 text-2xl font-extrabold text-success" aria-hidden="true">{{ $counts['approved'] }}</p>
                </a>
                <a href="{{ route('moderator.companies.index', array_filter(['status' => 'rejected', 'search' => $search ?? null])) }}"
                   class="bg-white dark:bg-slate-900 p-5 rounded-lg shadow-sm border border-gray-200 dark:border-slate-800 {{ $status === 'rejected' ? 'border-l-4 border-l-danger' : '' }} hover:bg-danger/5 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-primary"
                   aria-current="{{ $status === 'rejected' ? 'true' : 'false' }}">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">Rejected</p>
                    <p class="mt-1 text-2xl font-extrabold text-danger" aria-hidden="true">{{ $counts['rejected'] }}</p>
                </a>
            </div>

            <div class="box border">
                <div class="box-header flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-5 border-b border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800/30">
                    <div class="flex items-center gap-3">
                        <h2 class="box-title m-0">{{ ucfirst($status) }} Companies</h2>
                        @if($companies->total() > 0)
                            <span class="text-sm text-gray-500 dark:text-gray-400" aria-live="polite">
                                ({{ $companies->firstItem() }}–{{ $companies->lastItem() }} of {{ number_format($companies->total()) }})
                            </span>
                        @endif
                    </div>
                    <form action="{{ route('moderator.companies.index') }}" method="GET" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 flex-1 sm:flex-initial max-w-md sm:max-w-none" role="search" aria-label="Search companies">
                        <input type="hidden" name="status" value="{{ $status }}">
                        <label for="company-search" class="sr-only">Search by company name, owner, or industry</label>
                        <div class="relative flex-1">
                            <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500">
                                <i class="ri-search-line" aria-hidden="true"></i>
                            </span>
                            <input type="search"
                                   id="company-search"
                                   name="search"
                                   value="{{ old('search', $search ?? '') }}"
                                   placeholder="Company, owner, or industry..."
                                   class="form-control form-control-sm w-full pl-9 pr-4 py-2 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors"
                                   autocomplete="off"
                                   aria-describedby="company-search-hint">
                        </div>
                        <div class="flex gap-2 flex-shrink-0">
                            <button type="submit" class="ti-btn ti-btn-sm ti-btn-primary rounded-lg px-4">
                                <i class="ri-search-line me-1.5 sm:me-1" aria-hidden="true"></i>
                                <span>Search</span>
                            </button>
                            @if(!empty($search))
                                <a href="{{ route('moderator.companies.index', ['status' => $status]) }}" class="ti-btn ti-btn-sm ti-btn-light rounded-lg px-4" aria-label="Clear search">
                                    <i class="ri-close-line me-1" aria-hidden="true"></i>
                                    <span>Clear</span>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
                <p id="company-search-hint" class="sr-only">Results update when you submit. Search by company name, owner name, email, or industry.</p>
                <div class="box-body">
                    @if (session('status'))
                        <div class="alert alert-success mb-4" role="status" aria-live="polite">{{ session('status') }}</div>
                    @endif
                    @error('rejection_reason')
                        <div class="alert alert-danger mb-4" role="alert">{{ $message }}</div>
                    @enderror

                    @if($companies->isEmpty())
                        <div class="text-center py-8">
                            <i class="ri-building-line text-4xl text-textmuted mb-3" aria-hidden="true"></i>
                            @if(!empty($search))
                                <p class="text-textmuted">No {{ $status }} companies match your search.</p>
                                <p class="text-sm text-textmuted mt-1">Try different keywords or <a href="{{ route('moderator.companies.index', ['status' => $status]) }}" class="text-primary hover:underline">clear the search</a>.</p>
                            @else
                                <p class="text-textmuted">No {{ $status }} companies found.</p>
                            @endif
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table whitespace-nowrap table-bordered">
                                <thead>
                                    <tr>
                                        <th>Company</th>
                                        <th>Owner</th>
                                        <th>Industry</th>
                                        <th>Verification Docs</th>
                                        <th>Terms agreed</th>
                                        <th>Registered</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($companies as $company)
                                        <tr>
                                            <td>
                                                <div class="flex items-center gap-3">
                                                    <span class="avatar avatar-md avatar-rounded border">
                                                        @if($company->logo_url)
                                                            <img src="{{ $company->logo_url }}" alt="{{ $company->name }}">
                                                        @else
                                                            <span class="bg-primary/10 text-primary flex items-center justify-center w-full h-full">
                                                                {{ strtoupper(substr($company->name, 0, 2)) }}
                                                            </span>
                                                        @endif
                                                    </span>
                                                    <div>
                                                        <span class="font-medium">{{ $company->name }}</span>
                                                        @if($company->website)
                                                            <br><a href="{{ $company->website }}" target="_blank" class="text-xs text-primary">{{ $company->website }}</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($company->user)
                                                    {{ $company->user->name }}<br>
                                                    <span class="text-xs text-textmuted">{{ $company->user->email }}</span>
                                                @else
                                                    <span class="text-textmuted">-</span>
                                                @endif
                                            </td>
                                            <td>{{ $company->industry ?? '-' }}</td>
                                            <td>
                                                <div class="flex flex-col gap-1">
                                                    @if($company->registration_type && $company->registration_number)
                                                        <span class="badge bg-success/10 text-success text-xs">
                                                            <i class="ri-checkbox-circle-line me-1"></i> {{ $company->registration_type }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning/10 text-warning text-xs">
                                                            <i class="ri-alert-line me-1"></i> No Reg. Info
                                                        </span>
                                                    @endif
                                                    @if($company->registration_document_url)
                                                        <span class="badge bg-primary/10 text-primary text-xs">
                                                            <i class="ri-file-text-line me-1"></i> Doc Uploaded
                                                        </span>
                                                    @else
                                                        <span class="badge bg-danger/10 text-danger text-xs">
                                                            <i class="ri-file-warning-line me-1"></i> No Document
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                @if($company->terms_agreed_at)
                                                    <span class="badge bg-success/10 text-success text-xs" title="Accepted {{ $company->terms_agreed_at->format('M j, Y g:i A') }}">
                                                        <i class="ri-checkbox-circle-line me-1"></i> {{ $company->terms_agreed_at->format('M j, Y') }}
                                                    </span>
                                                    <div class="flex flex-wrap gap-1 mt-1">
                                                        <a href="{{ route('moderator.companies.agreement', $company) }}" target="_blank" rel="noopener noreferrer" class="text-xs text-primary hover:underline">View</a>
                                                        <span class="text-gray-400">|</span>
                                                        <a href="{{ route('moderator.companies.agreement', $company) }}" target="_blank" rel="noopener noreferrer" class="text-xs text-primary hover:underline">Download</a>
                                                    </div>
                                                @else
                                                    <span class="badge bg-warning/10 text-warning text-xs">Not agreed</span>
                                                @endif
                                            </td>
                                            <td>{{ $company->created_at->format('M d, Y') }}</td>
                                            <td class="text-center">
                                                <div class="flex items-center justify-center gap-2">
                                                    <a href="{{ route('moderator.companies.show', $company) }}" class="ti-btn ti-btn-sm ti-btn-info" aria-label="View {{ $company->name }}">
                                                        <i class="ri-eye-line" aria-hidden="true"></i>
                                                    </a>
                                                    @if($company->verification_status === 'pending')
                                                        <form action="{{ route('moderator.companies.approve', $company) }}" method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit" class="ti-btn ti-btn-sm ti-btn-success" aria-label="Approve {{ $company->name }}">
                                                                <i class="ri-check-line" aria-hidden="true"></i>
                                                            </button>
                                                        </form>
                                                        <button type="button" class="ti-btn ti-btn-sm ti-btn-danger reject-btn"
                                                                data-company-id="{{ $company->id }}"
                                                                data-company-name="{{ $company->name }}"
                                                                aria-label="Reject {{ $company->name }}">
                                                            <i class="ri-close-line" aria-hidden="true"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $companies->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Rejection Modal --}}
    <div id="reject-modal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="reject-modal-title" aria-describedby="reject-modal-desc">
        <div class="fixed inset-0 bg-black/50" onclick="closeRejectModal()" aria-hidden="true"></div>
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="bg-white dark:bg-slate-900 rounded-lg shadow-2xl border-2 border-gray-200 dark:border-slate-600 ring-4 ring-black/10 dark:ring-white/10 max-w-md w-full p-6 relative">
                <h3 id="reject-modal-title" class="text-lg font-semibold mb-2">Reject Company</h3>
                <p id="reject-modal-desc" class="text-textmuted mb-4">Rejecting: <strong id="reject-company-name"></strong>. Provide a reason so the owner can address it.</p>
                <form id="reject-form" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="rejection_reason" class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea name="rejection_reason"
                                  id="rejection_reason"
                                  class="form-control"
                                  rows="3"
                                  required
                                  placeholder="e.g. Registration document is unclear or expired"
                                  aria-describedby="rejection-reason-hint"></textarea>
                        <p id="rejection-reason-hint" class="text-sm text-textmuted mt-1">This will be sent to the company owner.</p>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" class="ti-btn ti-btn-light" onclick="closeRejectModal()">Cancel</button>
                        <button type="submit" class="ti-btn ti-btn-danger" id="reject-submit-btn">Reject Company</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openRejectModal(companyId, companyName) {
            document.getElementById('reject-company-name').textContent = companyName;
            document.getElementById('reject-form').action = '/moderator/companies/' + companyId + '/reject';
            document.getElementById('reject-modal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('reject-modal').classList.add('hidden');
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.reject-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    openRejectModal(this.dataset.companyId, this.dataset.companyName);
                });
            });

            var rejectForm = document.getElementById('reject-form');
            var rejectSubmitBtn = document.getElementById('reject-submit-btn');
            if (rejectForm && rejectSubmitBtn) {
                rejectForm.addEventListener('submit', function() {
                    rejectSubmitBtn.disabled = true;
                    rejectSubmitBtn.innerHTML = '<span class="inline-block animate-spin shrink-0 size-4 border-2 border-current border-transparent rounded-full me-1.5 align-middle" role="status" aria-hidden="true"></span> Rejecting…';
                });
            }
        });
    </script>

</x-app-layout>
