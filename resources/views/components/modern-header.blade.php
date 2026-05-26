@props([
    'chip' => null,      // Badge text e.g. "Applicant Search"
    'title' => '',        // Main heading e.g. "Browse Applicants"
    'desc' => '',        // Sub-description (raw HTML allowed)
    'actions' => null,      // Optional slot for right-side action buttons
    'footer' => null,       // Optional slot for footer content
    'div' => null,          // Optional slot for content above the title
    'badge' => null,        // Optional slot for top-right status badge
    'container' => true,    // Wrap with max-width container
    'id' => 'wt-hero',      // Optional section id
    'headerClass' => '',    // Additional classes for header section
])

<style>
    /* ── Modern Minimalist Header ── */
    .cf-header-section {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #e2e8f0;
        margin-bottom: 1.5rem;
        position: relative;
    }

    .cf-header-content { flex: 1; }

    .cf-header-title {
        font-size: 2.1rem;
        font-weight: 800;
        color: #1e293b;
        letter-spacing: -0.02em;
        margin-bottom: 0.75rem;
        line-height: 1.2;
    }

    .cf-header-desc {
        font-size: 1rem;
        color: #64748b;
        max-width: 700px;
        line-height: 1.5;
        margin: 0;
    }

    .cf-header-div {
        font-size: 1rem;
        color: #64748b;
        max-width: 700px;
        line-height: 1.5;
        margin: 0;
    }

    .cf-header-div b { color: #6366f1; font-weight: 700; }

    .cf-header-desc b { color: #6366f1; font-weight: 700; }

    .cf-header-actions {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.25rem;
        flex-wrap: wrap;
    }

    .cf-header-footer {
        margin-top: 0.5rem;
        padding-top: 0.5rem;
        border-top: 1px solid #e2e8f0;
    }

    .cf-header-badge {
        display: inline-flex;
        align-items: center;
        margin-bottom: auto;
    }

    .cf-active-chip {
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #6366f1;
        background: rgba(99, 102, 241, 0.1);
        padding: 2px 10px;
        border-radius: 20px;
    }

    /* ── Dark Mode ── */
    :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .cf-header-section {
        border-bottom-color: rgba(255, 255, 255, 0.08) !important;
        background: rgb(30, 32, 35) !important;
    }

    :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .cf-header-title {
        color: #f8fafc !important;
    }

    :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .cf-header-desc {
        color: #94a3b8 !important;
    }

    /* ── Responsive ── */
    @media (max-width: 992px) {
        .cf-header-section {
            flex-direction: column;
            align-items: flex-start;
            gap: 1.2rem;
        }
        .cf-header-title { font-size: 1.75rem; }
        .cf-header-actions { width: 100%; flex-wrap: wrap; }
        .cf-header-footer { width: 100%; flex-wrap: wrap; }
    }

    @media (max-width: 640px) {
        .cf-header-title { font-size: 1.45rem; }
        .cf-header-desc { font-size: 0.92rem; }
        .cf-header-div { font-size: 0.92rem; }
    }
    /* ── cd-section card base (self-contained so all pages get the card look) ── */
    .cd-section {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.25rem;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.06);
        transition: box-shadow 160ms ease, border-color 160ms ease;
    }

    :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .cd-section {
        background: rgb(30, 32, 35) !important;
        border-color: rgba(255, 255, 255, 0.08) !important;
        box-shadow: none !important;
    }

    .cf-header-footer {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #e2e8f0;
    }

    :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .cf-header-footer {
        border-top-color: rgba(255, 255, 255, 0.08) !important;
    }

    :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .cf-header-div {
        border-top-color: rgba(255, 255, 255, 0.08) !important;
    }

</style>

@if($container)
<div class="max-w-7xl mx-auto pt-4 pb-1 px-4 sm:px-6 lg:px-8">
@endif
    <div class="cf-header-section cd-section !mb-3 {{ $headerClass }}" @if($id) id="{{ $id }}" @endif>

        @if($chip)
            <div class="ribbon-2 ribbon-primary ribbon-left">
                <span class="cf-active-chip !text-white">{{ $chip }}</span>
            </div>
        @endif

        <div class="cf-header-content mt-6 pt-2">
            @isset($div)
                <div class="cf-header-div">
                    {{ $div }}
                </div>
            @endisset
            @isset($titleContent)
                <h1 class="cf-header-title">{{ $titleContent }}</h1>
            @else
                <h1 class="cf-header-title">{{ $title }}</h1>
            @endisset
            @isset($description)
                <p class="cf-header-desc">{{ $description }}</p>
            @elseif($desc)
                <p class="cf-header-desc">{!! $desc !!}</p>
            @endif
        </div>

        <div class="flex flex-col items-end gap-2">
            @isset($badge)
                <div class="cf-header-badge">
                    {{ $badge }}
                </div>
            @endisset

            @if($actions)
                <div class="cf-header-actions">
                    {{ $actions }}
                </div>
            @endif
        </div>
    </div>
    @isset($footer)
        <div class="cf-header-footer">
            {{ $footer }}
        </div>
    @endisset
@if($container)
</div>
@endif
