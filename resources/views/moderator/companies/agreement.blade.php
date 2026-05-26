<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Company Agreement — {{ $company->name }}</title>
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; max-width: 800px; margin: 0 auto; padding: 2rem; color: #1f2937; line-height: 1.6; }
        h1 { font-size: 1.5rem; margin-bottom: 0.5rem; }
        h2 { font-size: 1.125rem; margin-top: 1.5rem; margin-bottom: 0.5rem; font-weight: 600; }
        p { margin: 0.5rem 0; }
        .meta { font-size: 0.875rem; color: #6b7280; margin-bottom: 2rem; }
        .agreed-badge { display: inline-block; background: #d1fae5; color: #065f46; padding: 0.25rem 0.75rem; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 600; margin-top: 0.5rem; }
        .no-agreed { color: #b91c1c; font-size: 0.875rem; }
        .actions { margin-top: 2rem; padding-top: 1rem; border-top: 1px solid #e5e7eb; display: flex; gap: 1rem; flex-wrap: wrap; }
        .btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border-radius: 0.5rem; font-weight: 500; text-decoration: none; font-size: 0.875rem; cursor: pointer; border: 1px solid #d1d5db; background: #f9fafb; color: #374151; }
        .btn-primary { background: #4f46e5; color: #fff; border-color: #4f46e5; }
        @media print {
            .actions, .no-print { display: none !important; }
            body { padding: 1rem; }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <a href="{{ route('moderator.companies.show', $company) }}" class="btn" style="margin-bottom: 1rem;">← Back to company</a>
    </div>

    <h1>Company Registration — Agreement and Terms</h1>
    <p class="meta">{{ $company->name }} — Generated {{ now()->format('M j, Y g:i A') }}</p>

    @if($company->terms_agreed_at)
        <p><span class="agreed-badge">✓ Accepted agreement on {{ $company->terms_agreed_at->format('F j, Y \a\t g:i A') }}</span></p>
    @else
        <p class="no-agreed">This company did not record acceptance of the agreement (registered before terms were required).</p>
    @endif

    <p>This Terms and Agreement (“Agreement”) is entered into by and between:</p>
    <p><strong>Agency Name:</strong> {{ $agencyName ? e($agencyName) : '—' }}<br><strong>Address:</strong> {{ $agencyAddress ? e($agencyAddress) : '—' }}</p>
    <p>and</p>
    <p><strong>Company Name:</strong> {{ $company->name }}<br><strong>Address:</strong> {{ $companyAddress }}</p>
    <p>Collectively referred to as the “Parties.”</p>
    <p>By registering with the Agency, the Company agrees to the following terms and conditions:</p>

    <h2>1. Nature of Service</h2>
    <p>1.1 The Agency acts as a <strong>third-party intermediary</strong> connecting Companies with qualified Virtual Assistants (VAs).</p>
    <p>1.2 The Agency is not the direct employer of the Company’s chosen VA unless otherwise stated in a separate written agreement.</p>
    <p>1.3 The Agency facilitates recruitment, screening, onboarding, coordination, and support services.</p>

    <h2>2. Registration Requirements</h2>
    <p>2.1 The Company must provide accurate and complete business information, including: Legal Business Name, Business Registration Documents, Authorized Representative, Contact Details, Nature of Business.</p>
    <p>2.2 The Agency reserves the right to verify submitted documents before approval.</p>
    <p>2.3 The Agency may decline registration at its discretion if requirements are not met.</p>

    <h2>3. Service Fees and Payment Terms</h2>
    <p>3.1 The Company agrees to pay the Agency: Monthly Service/Management Fee and Other Applicable Charges as agreed.</p>
    <p>3.2 Payments must be made on or before the agreed due date.</p>
    <p>3.3 Late payments may result in: Service suspension, Additional penalty fees, Termination of agreement.</p>
    <p>3.4 All fees are non-refundable unless otherwise stated in writing.</p>

    <h2>4. Responsibilities of the Company</h2>
    <p>The Company agrees to: Provide clear job descriptions and expectations; Maintain professional and respectful communication with assigned VAs; Not directly hire, absorb, or bypass the Agency to employ the VA without written consent; Comply with applicable labor and business laws in their jurisdiction.</p>

    <h2>5. Non-Circumvention Clause</h2>
    <p>5.1 The Company agrees not to directly hire or contract any VA introduced by the Agency outside of the Agency’s system.</p>
    <p>5.2 If the Company hires a VA directly without Agency consent within the agreed period of introduction, the Company agrees to pay a placement fee as specified in a separate agreement.</p>

    <h2>6. Confidentiality</h2>
    <p>6.1 Both Parties agree to maintain confidentiality of proprietary, business, and personal information shared during the engagement.</p>
    <p>6.2 Confidential information shall not be disclosed to third parties without written consent.</p>

    <h2>7. Replacement and Dispute Policy</h2>
    <p>7.1 The Agency may provide a replacement VA within 1–3 days if performance standards are not met.</p>
    <p>7.2 The Company agrees to provide documented feedback before requesting replacement.</p>
    <p>7.3 The Agency acts as mediator in disputes between the Company and VA.</p>

    <h2>8. Limitation of Liability</h2>
    <p>8.1 The Agency shall not be liable for: Company business losses; VA misconduct beyond Agency control; Technical failures outside Agency systems.</p>
    <p>8.2 The Company assumes responsibility for management decisions and supervision of the VA’s work.</p>

    <h2>9. Termination</h2>
    <p>9.1 Either Party may terminate this Agreement with written notice.</p>
    <p>9.2 Immediate termination may occur in cases of: Breach of contract; Non-payment; Illegal or unethical conduct.</p>
    <p>9.3 Outstanding balances must be settled before termination is finalized.</p>

    <h2>10. Governing Law</h2>
    <p>This Agreement shall be governed by the laws of the jurisdiction agreed by the Parties.</p>

    <h2>11. Acceptance of Terms</h2>
    <p>By registering with the Agency, the Company acknowledges that they have read, understood, and agreed to these Terms and Conditions.</p>

    <div class="actions no-print">
        <a href="{{ route('moderator.companies.agreement', $company) }}" target="_blank" class="btn btn-primary" aria-label="View agreement in new tab">Open in new tab</a>
        <button type="button" class="btn" onclick="window.print();" aria-label="Print or save as PDF">Print / Save as PDF</button>
    </div>
</body>
</html>
