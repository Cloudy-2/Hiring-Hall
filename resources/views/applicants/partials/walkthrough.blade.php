{{-- ═══════════════ WALKTHROUGH TOUR PARTIAL ═══════════════ --}}
{{-- Usage: @include('candidates.partials.walkthrough', ['wtSteps' => [...], 'wtKey' => 'page_name']) --}}

<style>
    .wt-overlay {
        position: fixed; inset: 0; z-index: 9998;
        background: rgba(0,0,0,.55);
        opacity: 0; transition: opacity 0.3s ease;
        pointer-events: none;
    }
    .wt-overlay.active { opacity: 1; pointer-events: auto; }

    .wt-highlight {
        position: relative; z-index: 9999 !important;
        box-shadow: 0 0 0 6px rgba(99,102,241,.5), 0 0 40px 8px rgba(99,102,241,.2) !important;
        border-radius: 14px; transition: box-shadow 0.3s ease;
    }

    .wt-tooltip {
        position: fixed; z-index: 10000;
        background: #fff; border-radius: 14px;
        box-shadow: 0 16px 48px rgba(0,0,0,.16);
        padding: 1.25rem 1.5rem 1rem;
        max-width: 380px; width: 90vw;
        opacity: 0; transform: translateY(12px);
        transition: opacity 0.3s ease, transform 0.3s ease;
        font-family: var(--cd-font, 'Poppins', system-ui, sans-serif);
    }
    .wt-tooltip.active { opacity: 1; transform: translateY(0); }

    .wt-tooltip-title {
        font-size: 1rem; font-weight: 800; color: #1f2937;
        display: flex; align-items: center; gap: 8px;
        margin-bottom: 6px;
    }
    .wt-tooltip-title i { font-size: 1.15rem; color: #6366f1; }

    .wt-tooltip-body {
        font-size: 0.8125rem; color: #6b7280; line-height: 1.5;
        margin-bottom: 1rem;
    }
    .wt-tooltip-footer {
        display: flex; align-items: center; justify-content: space-between;
        gap: 0.5rem;
    }
    .wt-progress { display: flex; gap: 4px; align-items: center; }
    .wt-dot {
        width: 8px; height: 8px; border-radius: 50%;
        background: #e5e7eb; transition: all 0.25s;
    }
    .wt-dot.active { background: #6366f1; width: 20px; border-radius: 4px; }

    .wt-btn {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 0.4rem 0.85rem; border-radius: 8px;
        font-weight: 600; font-size: 0.78rem; cursor: pointer;
        border: none; transition: all 0.2s;
    }
    .wt-btn-ghost { background: #f3f4f6; color: #374151; }
    .wt-btn-ghost:hover { background: #e5e7eb; }
    .wt-btn-primary { background: #4f46e5; color: #fff; }
    .wt-btn-primary:hover { background: #4338ca; box-shadow: 0 2px 8px rgba(79,70,229,.3); }
    .wt-btn-skip { background: transparent; color: #9ca3af; font-size: 0.75rem; }
    .wt-btn-skip:hover { color: #6b7280; }

    .wt-tooltip::before {
        content: ''; position: absolute;
        width: 14px; height: 14px;
        background: #fff; border-radius: 3px;
        transform: rotate(45deg);
        box-shadow: -2px -2px 4px rgba(0,0,0,.04);
    }
    .wt-tooltip.arrow-top::before    { top: -7px; left: 2rem; }
    .wt-tooltip.arrow-bottom::before { bottom: -7px; left: 2rem; }
    .wt-tooltip.arrow-left::before   { left: -7px; top: 1.5rem; }
</style>

<div class="wt-overlay" id="wt-overlay"></div>
<div class="wt-tooltip" id="wt-tooltip">
    <h4 class="wt-tooltip-title" id="wt-title"></h4>
    <p class="wt-tooltip-body" id="wt-body"></p>
    <div class="wt-tooltip-footer">
        <div class="wt-progress" id="wt-progress"></div>
        <div style="display:flex;gap:6px">
            <button class="wt-btn wt-btn-skip" id="wt-skip" onclick="endWalkthrough()">Skip</button>
            <button class="wt-btn wt-btn-ghost" id="wt-prev" onclick="wtPrev()"><i class="ri-arrow-left-s-line"></i> Back</button>
            <button class="wt-btn wt-btn-primary" id="wt-next" onclick="wtNext()">Next <i class="ri-arrow-right-s-line"></i></button>
        </div>
    </div>
</div>

<script>
(function() {
    var steps = @json($wtSteps);
    var storageKey = 'wt_seen_{{ $wtKey ?? "default" }}';
    var current = 0;
    var overlay = document.getElementById('wt-overlay');
    var tooltip = document.getElementById('wt-tooltip');
    var titleEl = document.getElementById('wt-title');
    var bodyEl = document.getElementById('wt-body');
    var progressEl = document.getElementById('wt-progress');
    var prevBtn = document.getElementById('wt-prev');
    var nextBtn = document.getElementById('wt-next');
    var skipBtn = document.getElementById('wt-skip');
    function wtFloatBtn() {
        return document.getElementById('wt-float-btn');
    }
    var lastHL = null;

    function showStep(idx) {
        current = idx;
        var s = steps[idx];
        var el = document.getElementById(s.target);
        if (!el) { if (idx < steps.length - 1) { showStep(idx + 1); } else { endWalkthrough(); } return; }

        if (lastHL) lastHL.classList.remove('wt-highlight');
        el.classList.add('wt-highlight');
        lastHL = el;

        el.scrollIntoView({ behavior: 'smooth', block: 'center' });

        titleEl.innerHTML = '<i class="' + s.icon + '"></i> ' + s.title;
        bodyEl.textContent = s.body;

        var dots = '';
        for (var i = 0; i < steps.length; i++) {
            dots += '<div class="wt-dot' + (i === idx ? ' active' : '') + '" style="cursor:pointer" onclick="goToStep(' + i + ')"></div>';
        }
        progressEl.innerHTML = dots;

        prevBtn.style.display = idx === 0 ? 'none' : '';
        if (idx === steps.length - 1) {
            nextBtn.innerHTML = 'Finish <i class="ri-check-line"></i>';
            skipBtn.style.display = 'none';
        } else {
            nextBtn.innerHTML = 'Next <i class="ri-arrow-right-s-line"></i>';
            skipBtn.style.display = '';
        }

        setTimeout(function() { posTooltip(el, s.position); }, 350);
    }

    function posTooltip(el, pos) {
        tooltip.className = 'wt-tooltip active';
        var r = el.getBoundingClientRect(), tw = tooltip.offsetWidth, th = tooltip.offsetHeight, g = 16;
        var left, top;

        if (pos === 'bottom') { left = r.left + r.width/2 - tw/2; top = r.bottom + g; tooltip.classList.add('arrow-top'); }
        else if (pos === 'top') { left = r.left + r.width/2 - tw/2; top = r.top - th - g; tooltip.classList.add('arrow-bottom'); }
        else if (pos === 'left') { left = r.left - tw - g; top = r.top + r.height/2 - th/2; tooltip.classList.add('arrow-left'); }
        else { left = r.right + g; top = r.top + r.height/2 - th/2; }

        if (left < 12) left = 12;
        if (left + tw > window.innerWidth - 12) left = window.innerWidth - tw - 12;
        if (top < 12) top = 12;
        if (top + th > window.innerHeight - 12) top = window.innerHeight - th - 12;

        tooltip.style.left = left + 'px';
        tooltip.style.top = top + 'px';
    }

    window.startWalkthrough = function() {
        if (!steps || steps.length === 0) return;
        var fb = wtFloatBtn();
        if (fb) fb.classList.add('hidden');
        overlay.classList.add('active');
        showStep(0);
        try { localStorage.setItem(storageKey, '1'); } catch(e) {}
    };

    window.endWalkthrough = function() {
        overlay.classList.remove('active');
        tooltip.classList.remove('active');
        if (lastHL) lastHL.classList.remove('wt-highlight');
        lastHL = null;
        var fb = wtFloatBtn();
        if (fb) fb.classList.remove('hidden');
    };

    window.wtNext = function() {
        if (current >= steps.length - 1) endWalkthrough();
        else { tooltip.classList.remove('active'); setTimeout(function() { showStep(current + 1); }, 150); }
    };

    window.wtPrev = function() {
        if (current > 0) { tooltip.classList.remove('active'); setTimeout(function() { showStep(current - 1); }, 150); }
    };

    window.goToStep = function(idx) {
        tooltip.classList.remove('active');
        setTimeout(function() { showStep(idx); }, 150);
    };

    overlay.addEventListener('click', function() { endWalkthrough(); });
    document.addEventListener('keydown', function(e) { if (e.key === 'Escape') endWalkthrough(); });

    // Auto-show for first-time visitors - DISABLED per user request
    /*
    try {
        if (!localStorage.getItem(storageKey)) {
            setTimeout(function() { startWalkthrough(); }, 1200);
        }
    } catch(e) {}
    */
})();
</script>
