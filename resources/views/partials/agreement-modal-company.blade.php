{{--
    Company Agreement and Terms modal.
    Usage: @include('partials.agreement-modal-company', ['modalId' => 'agreement-modal-company', 'formId' => 'company-register-form', 'acceptButtonText' => 'Accept & Register Company'])
    Agency name/address from config('agency'). Company name/address filled from form when modal opens.
--}}
@php
    $modalId = $modalId ?? 'agreement-modal-company';
    $formId = $formId ?? '';
    $acceptButtonText = $acceptButtonText ?? 'Accept & Continue';
    $agencyName = $agencyName ?? config('agency.name', '');
    $agencyAddress = $agencyAddress ?? config('agency.address', '');
@endphp
<style>
    .agreement-modal-wrap {
        position: fixed;
        inset: 0;
        z-index: 9999;
        font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, -apple-system, sans-serif;
    }

    .agreement-modal-wrap.hidden {
        display: none !important;
    }

    .agreement-modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.75);
        backdrop-filter: blur(4px);
    }

    .agreement-modal-center {
        position: fixed;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
        overflow-y: auto;
    }

    .agreement-modal-box {
        background: #fff;
        border-radius: 1.25rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        border: 1px solid rgba(226, 232, 240, 0.8);
        max-width: 48rem;
        width: 100%;
        margin: auto;
        max-height: 85vh;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .agreement-modal-header {
        padding: 1.5rem 2rem;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-bottom: 1px solid #e2e8f0;
        flex-shrink: 0;
    }

    .agreement-modal-header h2 {
        font-size: 1.5rem;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.025em;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .agreement-modal-header p {
        font-size: 0.935rem;
        color: #64748b;
        margin-top: 0.25rem;
        font-weight: 500;
    }

    .agreement-modal-body {
        padding: 2rem;
        overflow-y: auto;
        flex: 1 1 auto;
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 transparent;
    }

    .agreement-modal-body::-webkit-scrollbar {
        width: 6px;
    }
    .agreement-modal-body::-webkit-scrollbar-thumb {
        background-color: #cbd5e1;
        border-radius: 20px;
    }

    .term-section {
        background: #f8fafc;
        border: 1px solid #f1f5f9;
        border-radius: 1rem;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
        transition: border-color 0.2s ease;
    }
    .term-section:hover {
        border-color: #e2e8f0;
    }
    .term-section h3 {
        font-size: 1.05rem !important;
        font-weight: 700 !important;
        color: #1e293b !important;
        margin-top: 0 !important;
        margin-bottom: 0.75rem !important;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .agreement-participants {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        padding-bottom: 2rem;
        margin-bottom: 2rem;
        border-bottom: 2px dashed #f1f5f9;
    }

    .participant-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 0.875rem;
        padding: 1.25rem;
    }

    .participant-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        font-weight: 800;
        color: #64748b;
        letter-spacing: 0.05em;
        margin-bottom: 0.5rem;
    }
    .participant-name {
        font-weight: 700;
        color: #0f172a;
        font-size: 1rem;
        line-height: 1.3;
    }

    .agreement-btn-outline {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.75rem 1.5rem;
        border-radius: 0.75rem;
        font-weight: 700;
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #475569;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .agreement-btn-outline:hover {
        background: #f8fafc;
        color: #1e293b;
    }

    .agreement-btn-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.75rem 2rem;
        border-radius: 0.75rem;
        font-weight: 700;
        background: #4f46e5;
        color: #fff;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);
    }
    .agreement-btn-primary:hover:not(:disabled) {
        background: #4338ca;
        transform: translateY(-1px);
        box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);
    }
    .agreement-btn-primary:disabled {
        opacity: 0.45;
        cursor: not-allowed;
    }
</style>
<div id="{{ $modalId }}" class="agreement-modal-wrap hidden" role="dialog" aria-modal="true" aria-labelledby="{{ $modalId }}-title" aria-describedby="{{ $modalId }}-desc">
    <div class="agreement-modal-backdrop" aria-hidden="true" data-agreement-close></div>
    <div class="agreement-modal-center">
        <div class="agreement-modal-box">
            <div class="agreement-modal-header">
                <h2>
                    <i class="ri-shield-user-line text-indigo-600" style="font-size: 1.75rem;"></i>
                    Company Agreement and Terms
                </h2>
                <p>Please review our registration terms and conditions carefully</p>
            </div>
            <div id="{{ $modalId }}-desc" class="agreement-modal-body">
                <div class="agreement-participants">
                    <div class="participant-card">
                        <div class="participant-label">Agency (Facilitator)</div>
                        <div class="participant-name">{{ $agencyName ? e($agencyName) : 'Hill Business Consulting' }}</div>
                        <div style="font-size: 0.8rem; color: #64748b; margin-top: 0.25rem;">{{ $agencyAddress ? e($agencyAddress) : '11350 Monier Park Pl. Rancho, Cordova, CA 95742' }}</div>
                    </div>
                    <div class="participant-card">
                        <div class="participant-label">Company (Principal)</div>
                        <div class="participant-name" id="{{ $modalId }}-company-name">—</div>
                        <div style="font-size: 0.8rem; color: #64748b; margin-top: 0.25rem;" id="{{ $modalId }}-company-address">—</div>
                    </div>
                </div>

                <div style="margin-bottom: 2rem; line-height: 1.6; color: #475569; font-size: 0.935rem;">
                    <p>This <strong>Terms and Agreement</strong> governs the professional relationship between the parties listed above. By registering, you confirm your intent to engage with our platform's marketplace of skilled Virtual Assistants.</p>
                </div>

                <div class="term-section">
                    <h3>1. Nature of Service</h3>
                    <p>1.1 The Agency acts as a <strong>third-party intermediary</strong> connecting Companies with qualified Virtual Assistants (VAs).</p>
                    <p>1.2 The Agency facilitates recruitment, screening, onboarding, and ongoing support services.</p>
                </div>

                <div class="term-section">
                    <h3>2. Registration & Verification</h3>
                    <p>2.1 Companies must provide accurate legal documentation. The Agency reserves the right to verify business credentials before final approval.</p>
                </div>

                <div class="term-section">
                    <h3>3. Non-Circumvention</h3>
                    <p>3.1 The Company agrees <strong>not</strong> to directly hire or contract any VA introduced by the Agency outside of our secure ecosystem.</p>
                    <p>3.2 Violation of this clause may result in placement fees and suspension of services.</p>
                </div>

                <div class="term-section">
                    <h3>4. Confidentiality</h3>
                    <p>4.1 Both parties agree to maintain strict confidentiality regarding proprietary data, trade secrets, and personal information shared during the engagement.</p>
                </div>

                <div class="term-section">
                    <h3>5. Payment Terms</h3>
                    <p>5.1 All management and service fees must be settled as per the agreed schedule. Late payments may result in service interruption.</p>
                </div>

                <div class="term-section">
                    <h3>6. Performance & Mediating</h3>
                    <p>6.1 The Agency acts as a mediator for disputes. Replacement VAs can be provided within 1–3 days if performance standards are not met.</p>
                </div>
            </div>
            <div style="padding:1.5rem 2rem;border-top:1px solid #f1f5f9;flex-shrink:0;display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:1rem;background: #fff;">
                <label style="display:flex;align-items:center;gap:0.75rem;cursor:pointer;user-select:none;">
                    <input
                        type="checkbox"
                        id="{{ $modalId }}-agree"
                        style="
                            width: 1.25rem;
                            height: 1.25rem;
                            cursor: pointer;
                            accent-color: #4f46e5;
                            border: 2px solid #64748b;
                            border-radius: 0.3rem;
                            background-color: #fff;
                            box-shadow: 0 0 0 1px #fff, 0 0 0 2px #64748b22;
                        "
                    >
                    <span style="font-size: 0.935rem; color: #334155; font-weight: 600;">
                        I agree to the terms and conditions
                    </span>
                </label>
                <div style="display:flex;gap:0.75rem;">
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
    function closeModal() { modal.classList.add('hidden'); }
    function openModal() {
        var form = formId ? document.getElementById(formId) : null;
        var nameEl = document.getElementById('{{ $modalId }}-company-name');
        var addrEl = document.getElementById('{{ $modalId }}-company-address');
        if (form && nameEl) {
            var nameInput = form.elements.namedItem('name');
            nameEl.textContent = (nameInput && nameInput.value && String(nameInput.value).trim()) ? String(nameInput.value).trim() : '—';
        }
        if (form && addrEl) {
            var parts = [];
            var addrFields = ['business_address', 'city', 'province', 'postal_code', 'country'];
            for (var i = 0; i < addrFields.length; i++) {
                var input = form.elements.namedItem(addrFields[i]);
                if (input && input.value && String(input.value).trim()) {
                    parts.push(String(input.value).trim());
                }
            }
            addrEl.textContent = parts.length ? parts.join(', ') : '—';
        }
        if (agreeCheck) { agreeCheck.checked = false; agreeCheck.focus(); }
        acceptBtn.disabled = true;
        if (acceptLabel) acceptBtn.textContent = acceptLabel;
        modal.classList.remove('hidden');
    }
    window.openCompanyAgreementModal = openModal;
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
