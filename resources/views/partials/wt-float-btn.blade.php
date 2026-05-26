{{-- Global floating “Take a Tour” trigger (pages with @include walkthrough supply startWalkthrough + overlay). --}}
<style>
    .wt-float-btn {
        position: fixed;
        bottom: 28px;
        right: 28px;
        z-index: 9990;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, #f59e0b, #f97316);
        color: #fff;
        border: 2px solid rgba(255, 255, 255, 0.35);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        font-weight: 800;
        cursor: grab;
        box-shadow: 0 4px 20px rgba(245, 158, 11, 0.4);
        transition: box-shadow 0.2s ease;
        animation: wtFloatPulse 2.5s ease-in-out infinite;
        font-family: var(--cd-font, 'Poppins', system-ui, sans-serif);
        user-select: none;
        -webkit-user-select: none;
        touch-action: none;
        padding: 0;
        margin: 0;
    }
    .wt-float-btn:hover {
        box-shadow: 0 6px 28px rgba(245, 158, 11, 0.55);
        animation: none;
    }
    .wt-float-btn.dragging {
        cursor: grabbing;
        animation: none;
        box-shadow: 0 8px 32px rgba(245, 158, 11, 0.6);
        transform: scale(1.08);
    }
    .wt-float-btn.hidden,
    .wt-float-btn.wt-float-retracted-for-ai {
        display: none !important;
    }
    @keyframes wtFloatPulse {
        0%,
        100% {
            box-shadow: 0 4px 20px rgba(245, 158, 11, 0.4);
        }
        50% {
            box-shadow: 0 4px 20px rgba(245, 158, 11, 0.15), 0 0 0 8px rgba(245, 158, 11, 0.12);
        }
    }
    @media (prefers-reduced-motion: reduce) {
        .wt-float-btn {
            animation: none;
        }
    }
    .cd-tour-btn {
        display: none !important;
    }
</style>

<button type="button" class="wt-float-btn" id="wt-float-btn" title="Take a Tour" aria-label="Take a tour">
    <i class="ri-question-mark" aria-hidden="true"></i>
</button>

<script>
    (function () {
        var floatBtn = document.getElementById('wt-float-btn');
        if (!floatBtn) return;

        var isDragging = false;
        var wasDragged = false;
        var startX, startY, btnX, btnY;
        var posKey = 'wt_float_pos';
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
            if (typeof window.startWalkthrough === 'function') {
                window.startWalkthrough();
            } else {
                // Tour not available on this page
                console.warn('Tour guide not available on this page');
                // Optional: Show feedback that tour isn't available
                floatBtn.style.opacity = '0.6';
                setTimeout(() => { floatBtn.style.opacity = '1'; }, 300);
            }
        });

        var aiDrawer = document.getElementById('hs-overlay-right');
        function syncTourBtnForAiDrawer() {
            if (!aiDrawer) return;
            floatBtn.classList.toggle('wt-float-retracted-for-ai', aiDrawer.classList.contains('open'));
        }
        if (aiDrawer) {
            syncTourBtnForAiDrawer();
            try {
                new MutationObserver(syncTourBtnForAiDrawer).observe(aiDrawer, {
                    attributes: true,
                    attributeFilter: ['class'],
                });
            } catch (e) {}
        }
    })();
</script>
