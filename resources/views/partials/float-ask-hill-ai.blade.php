{{-- Floating Ask Hill AI — same interaction pattern as walkthrough float; opens #hs-overlay-right (see nav-top). --}}
<style>
    .hill-ai-float-btn {
        position: fixed;
        bottom: 96px;
        right: 28px;
        z-index: 9989;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 45%, #06b6d4 100%);
        color: #fff;
        border: 2px solid rgba(255, 255, 255, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        cursor: grab;
        box-shadow: 0 4px 22px rgba(99, 102, 241, 0.45);
        transition: box-shadow 0.2s ease;
        animation: hillAiFloatPulse 2.6s ease-in-out infinite;
        font-family: var(--cd-font, 'Poppins', system-ui, sans-serif);
        user-select: none;
        -webkit-user-select: none;
        touch-action: none;
        padding: 0;
        margin: 0;
    }

    .hill-ai-float-btn i {
        filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.15));
    }

    .hill-ai-float-btn:hover {
        box-shadow: 0 6px 28px rgba(99, 102, 241, 0.55), 0 0 0 1px rgba(255, 255, 255, 0.2);
        animation: none;
    }

    .hill-ai-float-btn.dragging {
        cursor: grabbing;
        animation: none;
        box-shadow: 0 8px 36px rgba(99, 102, 241, 0.6);
        transform: scale(1.08);
    }

    .hill-ai-float-btn.hidden,
    .hill-ai-float-btn.retracted-for-drawer {
        display: none !important;
    }

    html.dark .hill-ai-float-btn {
        border-color: rgba(125, 211, 252, 0.35);
        box-shadow: 0 4px 22px rgba(99, 102, 241, 0.35), 0 0 0 1px rgba(125, 211, 252, 0.12);
    }

    html.dark .hill-ai-float-btn:hover {
        box-shadow: 0 6px 28px rgba(129, 140, 248, 0.45), 0 0 0 1px rgba(125, 211, 252, 0.2);
    }

    @keyframes hillAiFloatPulse {
        0%,
        100% {
            box-shadow: 0 4px 22px rgba(99, 102, 241, 0.45);
        }
        50% {
            box-shadow:
                0 4px 22px rgba(99, 102, 241, 0.25),
                0 0 0 8px rgba(56, 189, 248, 0.14);
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .hill-ai-float-btn {
            animation: none;
        }
    }
</style>

<button type="button"
    class="hill-ai-float-btn"
    id="hill-ai-float-btn"
    title="Ask Hill AI"
    aria-label="Ask Hill AI"
    data-hs-overlay="#hs-overlay-right">
    <i class="ri-sparkling-2-line" aria-hidden="true"></i>
</button>

<script>
    (function () {
        var floatBtn = document.getElementById('hill-ai-float-btn');
        var overlayEl = document.getElementById('hs-overlay-right');
        if (!floatBtn || !overlayEl) return;

        var isDragging = false;
        var wasDragged = false;
        var startX, startY, btnX, btnY;
        var posKey = 'hill_ai_float_pos';
        var size = 56;

        try {
            var saved = JSON.parse(localStorage.getItem(posKey));
            if (saved && saved.x != null) {
                floatBtn.style.bottom = 'auto';
                floatBtn.style.right = 'auto';
                floatBtn.style.left = Math.min(saved.x, window.innerWidth - size) + 'px';
                floatBtn.style.top = Math.min(saved.y, window.innerHeight - size) + 'px';
            }
        } catch (e) {}

        function onStart(ex, ey) {
            isDragging = true;
            wasDragged = false;
            var r = floatBtn.getBoundingClientRect();
            startX = ex;
            startY = ey;
            btnX = r.left;
            btnY = r.top;
            floatBtn.classList.add('dragging');
        }

        function onMove(ex, ey) {
            if (!isDragging) return;
            var dx = ex - startX,
                dy = ey - startY;
            if (Math.abs(dx) > 3 || Math.abs(dy) > 3) wasDragged = true;
            var nx = btnX + dx,
                ny = btnY + dy;
            nx = Math.max(0, Math.min(nx, window.innerWidth - size));
            ny = Math.max(0, Math.min(ny, window.innerHeight - size));
            floatBtn.style.bottom = 'auto';
            floatBtn.style.right = 'auto';
            floatBtn.style.left = nx + 'px';
            floatBtn.style.top = ny + 'px';
        }

        function onEnd() {
            if (!isDragging) return;
            isDragging = false;
            floatBtn.classList.remove('dragging');
            try {
                localStorage.setItem(
                    posKey,
                    JSON.stringify({
                        x: parseInt(floatBtn.style.left, 10),
                        y: parseInt(floatBtn.style.top, 10),
                    })
                );
            } catch (e) {}
        }

        floatBtn.addEventListener('mousedown', function (e) {
            e.preventDefault();
            onStart(e.clientX, e.clientY);
        });
        document.addEventListener('mousemove', function (e) {
            onMove(e.clientX, e.clientY);
        });
        document.addEventListener('mouseup', onEnd);

        floatBtn.addEventListener(
            'touchstart',
            function (e) {
                var t = e.touches[0];
                onStart(t.clientX, t.clientY);
            },
            { passive: true }
        );
        document.addEventListener(
            'touchmove',
            function (e) {
                if (isDragging) {
                    var t = e.touches[0];
                    onMove(t.clientX, t.clientY);
                }
            },
            { passive: false }
        );
        document.addEventListener('touchend', onEnd);

        floatBtn.addEventListener('click', function (e) {
            if (wasDragged) {
                e.preventDefault();
                e.stopPropagation();
                return;
            }
            overlayEl.click();
        });

        function syncFloatForDrawer() {
            var drawerOpen = overlayEl.classList.contains('open');
            floatBtn.classList.toggle('retracted-for-drawer', drawerOpen);
        }

        syncFloatForDrawer();
        try {
            var drawerMo = new MutationObserver(syncFloatForDrawer);
            drawerMo.observe(overlayEl, { attributes: true, attributeFilter: ['class'] });
        } catch (e) {}
    })();
</script>
