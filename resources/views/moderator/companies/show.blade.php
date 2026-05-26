@use('Illuminate\Support\Facades\Storage')
<x-app-layout
    page-title="Company Details"
    :breadcrumbs="[
            ['label' => 'Registered Companies', 'url' => route('moderator.companies.index')],
        ]"
    active="{{ $company->name }}"
>

    <div class="grid grid-cols-12 gap-6">
        <div class="xl:col-span-8 col-span-12">
            <div class="box border">
                <div class="box-header flex items-center justify-between">
                    <h2 class="box-title m-0">Company Details</h2>
                    <div>
                        @if($company->verification_status === 'approved')
                            <span class="badge bg-success/10 text-success" aria-label="Status: Verified">
                                <i class="ri-checkbox-circle-line me-1" aria-hidden="true"></i> Verified
                            </span>
                        @elseif($company->verification_status === 'pending')
                            <span class="badge bg-warning/10 text-warning" aria-label="Status: Pending Review">
                                <i class="ri-time-line me-1" aria-hidden="true"></i> Pending Review
                            </span>
                        @else
                            <span class="badge bg-danger/10 text-danger" aria-label="Status: Rejected">
                                <i class="ri-close-circle-line me-1" aria-hidden="true"></i> Rejected
                            </span>
                        @endif
                    </div>
                </div>
                <div class="box-body">
                    @if (session('status'))
                        <div class="alert alert-success mb-4" role="status" aria-live="polite">{{ session('status') }}</div>
                    @endif

                    @error('rejection_reason')
                        <div class="alert alert-danger mb-4" role="alert">{{ $message }}</div>
                    @enderror

                    <div class="flex items-start gap-4 mb-6">
                        <span class="avatar avatar-xxl avatar-rounded border">
                            @if($company->logo_url)
                                <img src="{{ $company->logo_url }}" alt="{{ $company->name }} logo">
                            @else
                                <span class="bg-primary/10 text-primary flex items-center justify-center w-full h-full text-2xl" aria-hidden="true">
                                    {{ strtoupper(substr($company->name, 0, 2)) }}
                                </span>
                            @endif
                        </span>
                        <div>
                            <h2 class="text-xl font-bold">{{ $company->name }}</h2>
                            @if($company->website)
                                <a href="{{ $company->website }}" target="_blank" rel="noopener noreferrer" class="text-primary" aria-label="Company website (opens in new tab)">{{ $company->website }}</a>
                            @endif
                            @if($company->description)
                                <p class="text-textmuted mt-2">{{ $company->description }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="text-xs text-textmuted">Industry</label>
                            <p class="font-medium">{{ $company->industry ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-textmuted">Location</label>
                            <p class="font-medium">{{ $company->location ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-textmuted">Email</label>
                            <p class="font-medium">{{ $company->email ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-textmuted">Phone</label>
                            <p class="font-medium">{{ $company->phone ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-textmuted">Established</label>
                            <p class="font-medium">{{ $company->established_year ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-textmuted">Employees</label>
                            <p class="font-medium">{{ $company->employees_count ?? '-' }}</p>
                        </div>
                    </div>

                    {{-- Business Registration Information --}}
                    <div class="border rounded-lg p-4 mb-6 {{ ($company->registration_type && $company->registration_number && $company->registration_document_url) ? 'bg-primary/5' : 'bg-warning/5' }}">
                        <h3 class="font-semibold {{ ($company->registration_type && $company->registration_number && $company->registration_document_url) ? 'text-primary' : 'text-warning' }} mb-4 flex items-center gap-2">
                            <i class="ri-verified-badge-line" aria-hidden="true"></i> Business Registration Information
                            @if(!$company->registration_type && !$company->registration_number && !$company->registration_document_url)
                                <span class="badge bg-warning/10 text-warning text-xs ms-2">Not Provided</span>
                            @elseif(!$company->registration_type || !$company->registration_number || !$company->registration_document_url)
                                <span class="badge bg-warning/10 text-warning text-xs ms-2">Incomplete</span>
                            @else
                                <span class="badge bg-success/10 text-success text-xs ms-2">Complete</span>
                            @endif
                        </h3>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="text-xs text-textmuted">Registration Type</label>
                                <p class="font-medium">
                                    @if($company->registration_type === 'SEC')
                                        <span class="badge bg-primary/10 text-primary">SEC</span> Securities and Exchange Commission
                                    @elseif($company->registration_type === 'DTI')
                                        <span class="badge bg-info/10 text-info">DTI</span> Department of Trade and Industry
                                    @elseif($company->registration_type === 'BIR')
                                        <span class="badge bg-warning/10 text-warning">BIR</span> Bureau of Internal Revenue
                                    @elseif($company->registration_type)
                                        <span class="badge bg-secondary/10 text-secondary">{{ $company->registration_type }}</span>
                                    @else
                                        <span class="text-danger"><i class="ri-close-circle-line me-1"></i> Not provided</span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <label class="text-xs text-textmuted">Registration Number</label>
                                @if($company->registration_number)
                                    <p class="font-medium font-mono">{{ $company->registration_number }}</p>
                                @else
                                    <p class="text-danger"><i class="ri-close-circle-line me-1"></i> Not provided</p>
                                @endif
                            </div>
                        </div>

                        <div class="border-t pt-4">
                            <label class="text-xs text-textmuted block mb-2">Registration Document</label>
                            @if($company->registration_document_url)
                            <div class="flex items-center gap-3">
                                @php
                                    $extension = strtolower(pathinfo($company->registration_document_url, PATHINFO_EXTENSION));
                                    $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif']);
                                    $isPdf = $extension === 'pdf';
                                @endphp

                                @if($isImage)
                                    <div class="border rounded-lg overflow-hidden" style="max-width: 200px;">
                                        <a href="{{ Storage::url($company->registration_document_url) }}" target="_blank" rel="noopener noreferrer" aria-label="Open registration document in new tab">
                                            <img src="{{ Storage::url($company->registration_document_url) }}" alt="Registration document preview" class="w-full h-auto">
                                        </a>
                                    </div>
                                @endif

                                <div class="flex flex-col gap-2">
                                    <a href="{{ Storage::url($company->registration_document_url) }}" target="_blank" rel="noopener noreferrer"
                                       class="ti-btn ti-btn-primary ti-btn-sm"
                                       aria-label="{{ $isPdf ? 'View PDF document (opens in new tab)' : 'View full image (opens in new tab)' }}">
                                        @if($isPdf)
                                            <i class="ri-file-pdf-line me-1" aria-hidden="true"></i> View PDF Document
                                        @else
                                            <i class="ri-image-line me-1" aria-hidden="true"></i> View Full Image
                                        @endif
                                    </a>
                                    <a href="{{ Storage::url($company->registration_document_url) }}" download
                                       class="ti-btn ti-btn-outline-primary ti-btn-sm"
                                       aria-label="Download registration document">
                                        <i class="ri-download-line me-1" aria-hidden="true"></i> Download
                                    </a>
                                    <span class="text-xs text-textmuted">
                                        File: {{ basename($company->registration_document_url) }}
                                    </span>
                                </div>
                            </div>
                            @else
                            <p class="text-danger text-sm"><i class="ri-close-circle-line me-1"></i> No document uploaded</p>
                            @endif
                        </div>
                    </div>

                    {{-- Business Address --}}
                    <div class="border rounded-lg p-4 mb-6 {{ ($company->business_address && $company->city && $company->province) ? 'bg-success/5' : 'bg-warning/5' }}">
                        <h3 class="font-semibold {{ ($company->business_address && $company->city && $company->province) ? 'text-success' : 'text-warning' }} mb-4 flex items-center gap-2">
                            <i class="ri-map-pin-line" aria-hidden="true"></i> Registered Business Address
                            @if(!$company->business_address && !$company->city && !$company->province)
                                <span class="badge bg-warning/10 text-warning text-xs ms-2">Not Provided</span>
                            @elseif(!$company->business_address || !$company->city || !$company->province)
                                <span class="badge bg-warning/10 text-warning text-xs ms-2">Incomplete</span>
                            @else
                                <span class="badge bg-success/10 text-success text-xs ms-2">Complete</span>
                            @endif
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs text-textmuted">Street Address</label>
                                @if($company->business_address)
                                    <p class="font-medium">{{ $company->business_address }}</p>
                                @else
                                    <p class="text-danger"><i class="ri-close-circle-line me-1"></i> Not provided</p>
                                @endif
                            </div>
                            <div>
                                <label class="text-xs text-textmuted">City</label>
                                @if($company->city)
                                    <p class="font-medium">{{ $company->city }}</p>
                                @else
                                    <p class="text-danger"><i class="ri-close-circle-line me-1"></i> Not provided</p>
                                @endif
                            </div>
                            <div>
                                <label class="text-xs text-textmuted">Province/State</label>
                                @if($company->province)
                                    <p class="font-medium">{{ $company->province }}</p>
                                @else
                                    <p class="text-danger"><i class="ri-close-circle-line me-1"></i> Not provided</p>
                                @endif
                            </div>
                            <div>
                                <label class="text-xs text-textmuted">Postal Code</label>
                                <p class="font-medium">{{ $company->postal_code ?? '-' }}</p>
                            </div>
                            <div class="col-span-2">
                                <label class="text-xs text-textmuted">Country</label>
                                <p class="font-medium">{{ $company->country ?? 'Philippines' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Agreement & Terms --}}
                    <div class="border rounded-lg p-4 mb-6 {{ $company->terms_agreed_at ? 'bg-success/5' : 'bg-warning/5' }}">
                        <h3 class="font-semibold {{ $company->terms_agreed_at ? 'text-success' : 'text-warning' }} mb-4 flex items-center gap-2">
                            <i class="ri-file-text-line" aria-hidden="true"></i> Agreement & Terms
                            @if($company->terms_agreed_at)
                                <span class="badge bg-success/10 text-success text-xs ms-2">Accepted {{ $company->terms_agreed_at->format('M j, Y g:i A') }}</span>
                            @else
                                <span class="badge bg-warning/10 text-warning text-xs ms-2">Not recorded</span>
                            @endif
                        </h3>
                        <p class="text-sm text-textmuted mb-3">Company registration agreement and conditions.</p>
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('moderator.companies.agreement', $company) }}" target="_blank" rel="noopener noreferrer" class="ti-btn ti-btn-primary ti-btn-sm" aria-label="View agreement (opens in new tab)">
                                <i class="ri-eye-line me-1" aria-hidden="true"></i> View agreement
                            </a>
                            <a href="{{ route('moderator.companies.agreement', $company) }}" target="_blank" rel="noopener noreferrer" class="ti-btn ti-btn-outline-primary ti-btn-sm" aria-label="Open agreement to print or save as PDF">
                                <i class="ri-download-line me-1" aria-hidden="true"></i> Download / Print PDF
                            </a>
                        </div>
                    </div>

                    @if($company->rejection_reason)
                        <div class="alert alert-danger mb-4" role="alert">
                            <strong>Rejection reason:</strong> {{ $company->rejection_reason }}
                        </div>
                    @endif

                    @if($company->verification_status === 'pending')
                        <section class="border-t pt-4 mt-4" aria-labelledby="company-verification-heading">
                            <h3 id="company-verification-heading" class="font-semibold mb-4">Verification Actions</h3>
                            <div class="flex flex-wrap gap-2">
                                <form action="{{ route('moderator.companies.approve', $company) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="ti-btn ti-btn-success" aria-label="Approve this company">
                                        <i class="ri-check-line me-1" aria-hidden="true"></i> Approve Company
                                    </button>
                                </form>
                                <button type="button"
                                        class="ti-btn ti-btn-danger reject-toggle-btn"
                                        aria-expanded="false"
                                        aria-controls="reject-section"
                                        aria-label="Show rejection form"
                                        data-reject-section="reject-section">
                                    <i class="ri-close-line me-1" aria-hidden="true"></i> Reject Company
                                </button>
                            </div>

                            <div id="reject-section"
                                 class="hidden mt-4 p-4 border rounded-lg bg-danger/5 border-danger/20"
                                 role="region"
                                 aria-labelledby="company-reject-form-heading">
                                <h4 id="company-reject-form-heading" class="font-semibold mb-3 text-danger">Provide rejection reason</h4>
                                <form action="{{ route('moderator.companies.reject', $company) }}" method="POST" class="company-reject-form">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="company-rejection_reason" class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                                        <textarea name="rejection_reason"
                                                  id="company-rejection_reason"
                                                  class="form-control"
                                                  rows="3"
                                                  required
                                                  placeholder="e.g. Registration document is unclear or expired"
                                                  aria-describedby="company-rejection-hint"></textarea>
                                        <p id="company-rejection-hint" class="text-sm text-textmuted mt-1">This will be sent to the company owner.</p>
                                    </div>
                                    <button type="submit" class="ti-btn ti-btn-danger company-reject-submit">Confirm Rejection</button>
                                </form>
                            </div>
                        </section>
                    @endif
                </div>
            </div>
        </div>

        <div class="xl:col-span-4 col-span-12">
            <div class="box border">
                <div class="box-header">
                    <div class="box-title">Owner Information</div>
                </div>
                <div class="box-body">
                    @if($company->user)
                        <div class="flex items-center gap-3 mb-4">
                            <span class="avatar avatar-lg avatar-rounded bg-primary/10 text-primary" aria-hidden="true">
                                {{ strtoupper(substr($company->user->name, 0, 2)) }}
                            </span>
                            <div>
                                <p class="font-medium">{{ $company->user->name }}</p>
                                <p class="text-xs text-textmuted">{{ $company->user->email }}</p>
                            </div>
                        </div>
                        <p class="text-xs text-textmuted">Registered: {{ $company->user->created_at->format('M d, Y') }}</p>
                    @else
                        <p class="text-textmuted">No owner assigned (seeded company)</p>
                    @endif
                </div>
            </div>

            @if($company->verified_at)
                <div class="box border mt-4">
                    <div class="box-header">
                        <div class="box-title">Verification Info</div>
                    </div>
                    <div class="box-body">
                        <p class="text-sm"><strong>Verified at:</strong> {{ $company->verified_at->format('M d, Y H:i') }}</p>
                        @if($company->verifiedByUser)
                            <p class="text-sm"><strong>Verified by:</strong> {{ $company->verifiedByUser->name }}</p>
                        @endif
                    </div>
                </div>
            @endif

            <div class="box border mt-4">
                <div class="box-header">
                    <div class="box-title">Job Postings</div>
                </div>
                <div class="box-body">
                    <p class="text-2xl font-bold">{{ $company->jobPostings->count() }}</p>
                    <p class="text-xs text-textmuted">Total jobs posted</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var toggleBtn = document.querySelector('.reject-toggle-btn');
            var rejectSection = document.getElementById('reject-section');
            if (toggleBtn && rejectSection) {
                toggleBtn.addEventListener('click', function() {
                    var isHidden = rejectSection.classList.toggle('hidden');
                    toggleBtn.setAttribute('aria-expanded', isHidden ? 'false' : 'true');
                    toggleBtn.setAttribute('aria-label', isHidden ? 'Show rejection form' : 'Hide rejection form');
                });
            }
            var rejectForm = document.querySelector('.company-reject-form');
            var rejectSubmit = document.querySelector('.company-reject-submit');
            if (rejectForm && rejectSubmit) {
                rejectForm.addEventListener('submit', function() {
                    rejectSubmit.disabled = true;
                    rejectSubmit.innerHTML = '<span class="inline-block animate-spin shrink-0 size-4 border-2 border-current border-transparent rounded-full me-1.5 align-middle" role="status" aria-hidden="true"></span> Submitting…';
                });
            }
        });
    </script>

</x-app-layout>
