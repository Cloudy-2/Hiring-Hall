{{--
    VA (Virtual Assistant) Agreement and Terms modal — for job applications.
    Pass agencyName, agencyAddress, applicantName, applicantAddress (or they default from config/auth).
    Usage: @include('partials.agreement-modal-va', ['modalId' => '...', 'formId' => '...', 'acceptButtonText' => '...', 'agencyName' => config('agency.name'), 'applicantName' => auth()->user()?->name])
    Open with openVaAgreementModal() for focus management and consistent behavior.
--}}
@php
$modalId = $modalId ?? 'agreement-modal-va';
$formId = $formId ?? '';
$acceptButtonText = $acceptButtonText ?? 'Accept & Continue';
$agencyName = $agencyName ?? config('agency.name', '');
$agencyAddress = $agencyAddress ?? config('agency.address', '');
$applicantName = $applicantName ?? (auth()->user() ? auth()->user()->name : '');
$applicantAddress = $applicantAddress ?? (auth()->user()?->address ?? '');
@endphp
<style>
    .agreement-modal-wrap {
        position: fixed;
        inset: 0;
        z-index: 9999;
        font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, -apple-system, sans-serif;
        color-scheme: light;
        --agreement-overlay: rgba(15, 23, 42, 0.75);
        --agreement-box-bg: #ffffff;
        --agreement-box-border: rgba(226, 232, 240, 0.8);
        --agreement-header-bg: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        --agreement-header-border: #e2e8f0;
        --agreement-title: #0f172a;
        --agreement-muted: #64748b;
        --agreement-body-scroll: #cbd5e1;
        --agreement-section-bg: #f8fafc;
        --agreement-section-border: #f1f5f9;
        --agreement-section-border-hover: #e2e8f0;
        --agreement-section-title: #1e293b;
        --agreement-participants-border: #f1f5f9;
        --agreement-card-bg: #ffffff;
        --agreement-card-border: #e2e8f0;
        --agreement-text-main: #0f172a;
        --agreement-text-secondary: #475569;
        --agreement-footer-bg: #ffffff;
        --agreement-footer-border: #f1f5f9;
        --agreement-outline-btn-bg: #ffffff;
        --agreement-outline-btn-border: #e2e8f0;
        --agreement-outline-btn-text: #475569;
        --agreement-outline-btn-hover-bg: #f8fafc;
        --agreement-outline-btn-hover-text: #1e293b;
        --agreement-checkbox-label: #334155;
    }

    html.dark .agreement-modal-wrap,
    body.dark .agreement-modal-wrap,
    .dark .agreement-modal-wrap {
        color-scheme: dark;
        --agreement-overlay: rgba(2, 6, 23, 0.82);
        --agreement-box-bg: #0f172a;
        --agreement-box-border: rgba(71, 85, 105, 0.8);
        --agreement-header-bg: linear-gradient(135deg, #111827 0%, #0b1220 100%);
        --agreement-header-border: #334155;
        --agreement-title: #f8fafc;
        --agreement-muted: #94a3b8;
        --agreement-body-scroll: #475569;
        --agreement-section-bg: #111827;
        --agreement-section-border: #1e293b;
        --agreement-section-border-hover: #334155;
        --agreement-section-title: #e2e8f0;
        --agreement-participants-border: #1e293b;
        --agreement-card-bg: #0b1220;
        --agreement-card-border: #334155;
        --agreement-text-main: #f8fafc;
        --agreement-text-secondary: #cbd5e1;
        --agreement-footer-bg: #0f172a;
        --agreement-footer-border: #1e293b;
        --agreement-outline-btn-bg: #0f172a;
        --agreement-outline-btn-border: #334155;
        --agreement-outline-btn-text: #cbd5e1;
        --agreement-outline-btn-hover-bg: #1e293b;
        --agreement-outline-btn-hover-text: #f8fafc;
        --agreement-checkbox-label: #e2e8f0;
    }

    .agreement-modal-wrap.hidden {
        display: none !important;
    }

    .agreement-modal-backdrop {
        position: fixed;
        inset: 0;
        background: var(--agreement-overlay);
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
        background: var(--agreement-box-bg);
        border-radius: 1.25rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        border: 1px solid var(--agreement-box-border);
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
        background: var(--agreement-header-bg);
        border-bottom: 1px solid var(--agreement-header-border);
        flex-shrink: 0;
    }

    .agreement-modal-header h2 {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--agreement-title);
        letter-spacing: -0.025em;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .agreement-modal-header p {
        font-size: 0.935rem;
        color: var(--agreement-muted);
        margin-top: 0.25rem;
        font-weight: 500;
    }

    .agreement-modal-body {
        padding: 2rem;
        overflow-y: auto;
        flex: 1 1 auto;
        scrollbar-width: thin;
        scrollbar-color: var(--agreement-body-scroll) transparent;
    }

    .agreement-modal-body::-webkit-scrollbar {
        width: 6px;
    }
    .agreement-modal-body::-webkit-scrollbar-thumb {
        background-color: var(--agreement-body-scroll);
        border-radius: 20px;
    }

    .term-section {
        background: var(--agreement-section-bg);
        border: 1px solid var(--agreement-section-border);
        border-radius: 1rem;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
        transition: border-color 0.2s ease;
    }
    .term-section:hover {
        border-color: var(--agreement-section-border-hover);
    }
    .term-section h3 {
        font-size: 1.05rem !important;
        font-weight: 700 !important;
        color: var(--agreement-section-title) !important;
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
        border-bottom: 2px dashed var(--agreement-participants-border);
    }

    .participant-card {
        background: var(--agreement-card-bg);
        border: 1px solid var(--agreement-card-border);
        border-radius: 0.875rem;
        padding: 1rem;
    }
    .participant-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        font-weight: 800;
        color: var(--agreement-muted);
        letter-spacing: 0.05em;
        margin-bottom: 0.5rem;
    }
    .participant-name {
        font-weight: 700;
        color: var(--agreement-text-main);
        font-size: 1rem;
    }

    .participant-address {
        font-size: 0.8rem;
        color: var(--agreement-muted);
        margin-top: 0.25rem;
    }

    .agreement-intro {
        margin-bottom: 2rem;
        line-height: 1.6;
        color: var(--agreement-text-secondary);
    }

    .agreement-footer {
        padding: 1.5rem 2rem;
        border-top: 1px solid var(--agreement-footer-border);
        flex-shrink: 0;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        background: var(--agreement-footer-bg);
    }

    .agreement-checkbox-label {
        font-size: 0.935rem;
        font-weight: 600;
        color: var(--agreement-checkbox-label);
    }

    .agreement-btn-outline {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.75rem 1.5rem;
        border-radius: 0.75rem;
        font-weight: 700;
        border: 1px solid var(--agreement-outline-btn-border);
        background: var(--agreement-outline-btn-bg);
        color: var(--agreement-outline-btn-text);
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .agreement-btn-outline:hover {
        background: var(--agreement-outline-btn-hover-bg);
        color: var(--agreement-outline-btn-hover-text);
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
                <h2 id="{{ $modalId }}-title">
                    <i class="ri-file-shield-2-line text-indigo-600 dark:text-indigo-400" style="font-size: 1.75rem;"></i>
                    Agreement and Terms
                </h2>
                <p>Please review our Virtual Assistant terms and conditions below</p>
            </div>
            <div id="{{ $modalId }}-desc" class="agreement-modal-body">
                <div class="agreement-participants">
                    <div class="participant-card">
                        <div class="participant-label">Agency (The Party)</div>
                        <div class="participant-name">{{ $agencyName ?: 'Hill Business Consulting' }}</div>
                        <div class="participant-address">{{ $agencyAddress ?: 'San Francisco, CA' }}</div>
                    </div>
                    <div class="participant-card">
                        <div class="participant-label">Virtual Assistant (You)</div>
                        <div class="participant-name">{{ $applicantName ?: 'Pending Verification' }}</div>
                        <div class="participant-address">{{ $applicantAddress ?: '—' }}</div>
                    </div>
                </div>

                <div class="agreement-intro">
                    <p>This <strong>Virtual Assistant Terms and Agreement</strong> governs the professional relationship between the parties listed above. By submitting an application, you agree to uphold these standards.</p>
                </div>

                <div class="term-section">
                    <h3>1. Nature of Engagement</h3>
                    <p>1.1 The Agency acts as a <strong>third-party intermediary</strong> connecting Virtual Assistants (VAs) with registered Companies.</p>
                    <p>1.2 The VA operates as an <strong>Independent Contractor</strong>. You are responsible for your own taxes and legal compliance.</p>
                </div>

                <div class="term-section">
                    <h3>2. Screening & Verification</h3>
                    <p>2.1 You agree to provide accurate documentation (Resume, Skillsets, and Government ID). Falsification results in immediate removal.</p>
                </div>

                <div class="term-section">
                    <h3>3. Non-Circumvention</h3>
                    <p>The VA agrees <strong>not</strong> to directly negotiate or accept payments from clients introduced by the Agency outside of our secure platform.</p>
                </div>

                <div class="term-section">
                    <h3>4. Confidentiality</h3>
                    <p>Strict confidentiality applies to all client data, passwords, and trade secrets. This survives the end of the engagement.</p>
                </div>

                <div class="term-section">
                    <h3>5. Quality & Conduct</h3>
                    <p>Maintain professional communication, meet deadlines, and deliver high-quality work. Poor performance may result in reassignment.</p>
                </div>

                <div class="term-section">
                    <h3>6. Acceptance of Terms</h3>
                    <p>By clicking "Accept & Apply", you confirm that you have read, understood, and agreed to all terms outlined here.</p>
                </div>
            </div>
            <div class="agreement-footer">
                <label style="display:flex;align-items:center;gap:0.75rem;cursor:pointer;user-select:none;">
                    <input
                        type="checkbox"
                        id="{{ $modalId }}-agree"
                        style="
                            width: 1.25rem;
                            height: 1.25rem;
                            cursor: pointer;
                            accent-color: #4f46e5;
                        ">
                        <span class="agreement-checkbox-label">I agree to the terms and conditions</span>
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

        function closeModal() {
            modal.classList.add('hidden');
        }
        function openModal() {
            if (agreeCheck) { agreeCheck.checked = false; agreeCheck.focus(); }
            acceptBtn.disabled = true;
            if (acceptLabel) acceptBtn.textContent = acceptLabel;
            modal.classList.remove('hidden');
        }
        window.openVaAgreementModal = openModal;

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
