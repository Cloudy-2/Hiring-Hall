@extends('employers.onboarding.layout')

@section('content')
    <div class="onboard-card">
        <h2>Review Information</h2>
        <p class="subtitle">Please review your company details before completing the setup.</p>

        <div class="review-sections">
            {{-- Company Basics --}}
            <section class="review-section">
                <div class="review-section-header">
                    <h3 class="review-section-title"><i class="ri-building-line"></i> Company Basics</h3>
                    <a href="{{ route('employer.onboarding.show', ['step' => 1]) }}" class="review-edit-link">Edit</a>
                </div>
                <div class="review-grid">
                    <div class="review-field">
                        <span class="review-label">Name</span>
                        <span class="review-value">{{ $company->name }}</span>
                    </div>
                    <div class="review-field">
                        <span class="review-label">Industry</span>
                        <span class="review-value">{{ $company->industry }}</span>
                    </div>
                    <div class="review-field">
                        <span class="review-label">Website</span>
                        <span class="review-value">{{ $company->website ?? '—' }}</span>
                    </div>
                    <div class="review-field">
                        <span class="review-label">Employees</span>
                        <span class="review-value">{{ $company->employees_count ?? '—' }}</span>
                    </div>
                    @if($company->established_year)
                    <div class="review-field">
                        <span class="review-label">Established</span>
                        <span class="review-value">{{ $company->established_year }}</span>
                    </div>
                    @endif
                </div>
            </section>

            {{-- Contact Info --}}
            <section class="review-section">
                <div class="review-section-header">
                    <h3 class="review-section-title"><i class="ri-contacts-line"></i> Contact Info</h3>
                    <a href="{{ route('employer.onboarding.show', ['step' => 2]) }}" class="review-edit-link">Edit</a>
                </div>
                <div class="review-grid">
                    <div class="review-field">
                        <span class="review-label">Location</span>
                        <span class="review-value">{{ $company->location }}</span>
                    </div>
                    <div class="review-field">
                        <span class="review-label">Email</span>
                        <span class="review-value">{{ $company->email }}</span>
                    </div>
                    <div class="review-field">
                        <span class="review-label">Phone</span>
                        <span class="review-value">{{ $company->phone }}</span>
                    </div>
                    <div class="review-field">
                        <span class="review-label">Contact Name</span>
                        <span class="review-value">{{ $company->contact_name ?? '—' }}</span>
                    </div>
                    <div class="review-field">
                        <span class="review-label">Contact Position</span>
                        <span class="review-value">{{ $company->contact_position ?? '—' }}</span>
                    </div>
                    <div class="review-field">
                        <span class="review-label">Contact Availability</span>
                        <span class="review-value">{{ $company->contact_availability_time ?? '—' }}</span>
                    </div>
                </div>
            </section>

            {{-- Profile (Description & Logo) --}}
            <section class="review-section">
                <div class="review-section-header">
                    <h3 class="review-section-title"><i class="ri-file-text-line"></i> Profile</h3>
                    <a href="{{ route('employer.onboarding.show', ['step' => 3]) }}" class="review-edit-link">Edit</a>
                </div>
                <div class="review-profile-block">
                    @if($company->logo_url)
                        <img src="{{ $company->logo_url }}" alt="Company logo" class="review-logo">
                    @else
                        <div class="review-logo review-logo-placeholder"><i class="ri-image-line"></i></div>
                    @endif
                    <div class="review-description-wrap">
                        <span class="review-label">About Company</span>
                        <p class="review-description">{{ $company->description }}</p>
                    </div>
                </div>
            </section>
        </div>

        <form id="employer-onboarding-finish-form" method="POST" action="{{ route('employer.onboarding.store', ['step' => 4]) }}" class="review-form">
            @csrf
            <input type="hidden" name="terms_agreed" value="">
            <div class="onboard-actions">
                <a href="{{ route('employer.onboarding.show', ['step' => 3]) }}" class="btn-back">
                    <i class="ri-arrow-left-line"></i> Back
                </a>
                <button type="button" class="btn-next" onclick="openOnboardingAgreementModal()">
                    Finish Setup <i class="ri-check-line"></i>
                </button>
            </div>
        </form>
    </div>

    @include('partials.agreement-modal-onboarding', [
        'modalId' => 'employer-onboarding-agreement-modal',
        'formId' => 'employer-onboarding-finish-form',
        'acceptButtonText' => 'I Agree & Finish Setup',
    ])

    <style>
        .review-sections { display: flex; flex-direction: column; gap: 20px; }
        .review-section {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: var(--radius);
            padding: 20px 24px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .review-section:hover { border-color: #cbd5e1; }
        .review-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid #e2e8f0;
        }
        .review-section-title {
            font-size: 13px;
            font-weight: 700;
            color: var(--navy);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .review-section-title i { color: var(--gold); font-size: 16px; }
        .review-edit-link {
            font-size: 13px;
            font-weight: 600;
            color: var(--gold-dark);
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 8px;
            transition: background 0.2s, color 0.2s;
        }
        .review-edit-link:hover { background: var(--gold-glow); color: var(--gold-dark); }
        .review-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px 24px;
        }
        @media (max-width: 520px) {
            .review-grid { grid-template-columns: 1fr; }
        }
        .review-field { display: flex; flex-direction: column; gap: 4px; }
        .review-label {
            font-size: 11px;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }
        .review-value {
            font-size: 14px;
            font-weight: 500;
            color: var(--navy);
            line-height: 1.4;
        }
        .review-profile-block {
            display: flex;
            gap: 20px;
            align-items: flex-start;
        }
        @media (max-width: 560px) {
            .review-profile-block { flex-direction: column; }
        }
        .review-logo {
            width: 72px;
            height: 72px;
            border-radius: var(--radius);
            object-fit: cover;
            border: 1px solid #e2e8f0;
            flex-shrink: 0;
        }
        .review-logo-placeholder {
            background: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            font-size: 28px;
        }
        .review-description-wrap { flex: 1; min-width: 0; }
        .review-description {
            font-size: 14px;
            color: #475569;
            line-height: 1.6;
            margin: 0;
            white-space: pre-wrap;
            word-break: break-word;
        }
        .review-form { margin-top: 32px; }
    </style>
@endsection
