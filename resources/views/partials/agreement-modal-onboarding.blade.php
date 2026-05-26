{{--
    Data sharing terms and agreement modal for onboarding completion (employer and applicant).
    Usage: @include('partials.agreement-modal-onboarding', ['modalId' => '...', 'formId' => '...', 'acceptButtonText' => '...'])
    Button that opens: type="button" and onclick="openOnboardingAgreementModal()" (or window.openOnboardingAgreementModal from same modalId).
--}}
@php
$modalId = $modalId ?? 'agreement-modal-onboarding';
$formId = $formId ?? '';
$acceptButtonText = $acceptButtonText ?? 'I Agree & Finish';
$agencyName = $agencyName ?? config('agency.name', '');
@endphp
<style>
    .agreement-modal-wrap {
        position: fixed;
        inset: 0;
        z-index: 9999;
    }

    .agreement-modal-wrap.hidden {
        display: none !important;
    }

    .agreement-modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, .5);
    }

    .agreement-modal-center {
        position: fixed;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        overflow-y: auto;
    }

    .agreement-modal-box {
        background: #fff;
        border-radius: 0.75rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, .25);
        border: 1px solid #e5e7eb;
        max-width: 42rem;
        width: 100%;
        margin: 2rem 0;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
    }

    .agreement-modal-box .agreement-modal-body {
        overflow-y: auto;
        flex: 1 1 auto;
        min-height: 0;
    }

    .agreement-modal-box .agreement-btn-outline {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-weight: 500;
        border: 1px solid #d1d5db;
        background: #fff;
        color: #374151;
        cursor: pointer;
    }

    .agreement-modal-box .agreement-btn-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-weight: 500;
        background: #4f46e5;
        color: #fff;
        border: none;
        cursor: pointer;
    }

    .agreement-modal-box .agreement-btn-primary:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .agreement-modal-box input[type="checkbox"] {
        appearance: auto;
        -webkit-appearance: checkbox;
    }
</style>
<div id="{{ $modalId }}" class="agreement-modal-wrap hidden" role="dialog" aria-modal="true" aria-labelledby="{{ $modalId }}-title" aria-describedby="{{ $modalId }}-desc">
    <div class="agreement-modal-backdrop" aria-hidden="true" data-agreement-close></div>
    <div class="agreement-modal-center">
        <div class="agreement-modal-box">
            <div style="padding:1.25rem;border-bottom:1px solid #e5e7eb;flex-shrink:0;">
                <h2 id="{{ $modalId }}-title" style="font-size:1.25rem;font-weight:600;color:#111;">Terms and Agreement for Data Sharing</h2>
                <p style="font-size:0.875rem;color:#6b7280;margin-top:0.25rem;">Please read and accept before completing your setup</p>
            </div>
            <div id="{{ $modalId }}-desc" class="agreement-modal-body" style="padding:1.25rem;font-size:0.875rem;color:#374151;">
                <p>By completing your setup, you agree to the following terms regarding the collection, use, and sharing of your data.</p>

                <h3 style="font-weight:600;color:#111;margin-top:1rem;">1. Data We Collect and Use</h3>
                <p>To provide recruitment and matching services, {{ $agencyName ? e($agencyName) : 'the Agency' }} and its platform collect and process the information you provide during registration and in your profile. This may include: contact details, professional information, skills, experience, and any documents you upload.</p>

                <h3 style="font-weight:600;color:#111;margin-top:1rem;">2. How Your Data Is Shared</h3>
                <p>2.1 Your profile or company information may be shared with potential matches (employers or applicants) to facilitate recruitment and hiring.</p>
                <p>2.2 We may share necessary information with service providers who assist in operating the platform, under strict confidentiality obligations.</p>
                <p>2.3 We will not sell your personal data to third parties for marketing purposes.</p>

                <h3 style="font-weight:600;color:#111;margin-top:1rem;">3. Your Consent</h3>
                <p>By clicking “I Agree” below, you confirm that you have read and understood this agreement and consent to the collection, use, and sharing of your data as described above and in our full Privacy Policy and Terms of Service.</p>

                <h3 style="font-weight:600;color:#111;margin-top:1rem;">4. Questions</h3>
                <p>If you have questions about how your data is used or shared, please contact us before completing your setup.</p>
            </div>
            <div style="padding:1.25rem;border-top:1px solid #e5e7eb;flex-shrink:0;display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:0.75rem;">
                <label style="display:flex;align-items:center;gap:0.75rem;cursor:pointer;">
                    <input
                        type="checkbox"
                        id="{{ $modalId }}-agree"
                        style="width:1.25rem;height:1.25rem;border-radius:0.25rem;border:2px solid #4f46e5;accent-color:#4f46e5;box-shadow:0 0 0 2px #d1d5db;">
                    <span style="font-size:0.95rem;color:#1e293b;font-weight:500;">I have read and agree to the terms and conditions for data sharing above</span>
                </label>
                <div style="display:flex;gap:0.5rem;">
                    <button type="button" class="agreement-btn-outline" data-agreement-close>Cancel</button>
                    <button type="button" class="agreement-btn-primary" id="{{ $modalId }}-accept" disabled data-agreement-accept data-form-id="{{ $formId }}" title="Check the box above to enable" data-accept-label="{{ $acceptButtonText }}">{{ $acceptButtonText }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    (function() {
        var modal = document.getElementById('{{ $modalId }}');
        var agreeCheck = document.getElementById('{{ $modalId }}-agree');
        var acceptBtn = document.getElementById('{{ $modalId }}-accept');
        var formId = '{{ $formId }}';
        var acceptLabel = acceptBtn ? acceptBtn.getAttribute('data-accept-label') : '';
        if (!modal || !acceptBtn) return;

        function closeModal() {
            modal.classList.add('hidden');
        }

        function openModal() {
            if (agreeCheck) { agreeCheck.checked = false; agreeCheck.focus(); }
            acceptBtn.disabled = true;
            if (acceptLabel) acceptBtn.textContent = acceptLabel;
            modal.classList.remove('hidden');
        }
        window.openOnboardingAgreementModal = openModal;
        modal.querySelectorAll('[data-agreement-close]').forEach(function(el) {
            el.addEventListener('click', closeModal);
        });
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) closeModal();
        });
        agreeCheck && agreeCheck.addEventListener('change', function() {
            acceptBtn.disabled = !agreeCheck.checked;
        });
        acceptBtn.addEventListener('click', function() {
            if (acceptBtn.disabled) return;
            acceptBtn.disabled = true;
            acceptBtn.textContent = 'Submitting…';
            var form = formId ? document.getElementById(formId) : null;
            if (form) {
                var termsInput = form.querySelector('input[name="terms_agreed"]');
                if (termsInput) termsInput.value = '1';
                form.submit();
            } else {
                closeModal();
                if (acceptLabel) acceptBtn.textContent = acceptLabel;
            }
        });
    })();
</script>
