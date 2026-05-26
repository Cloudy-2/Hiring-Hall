<nav class="breadcrumb-nav flex items-center justify-between p-4 w-full bg-white dark:bg-slate-900/40 border-b border-slate-100 dark:border-white/5 rounded-md border border-gray-200"
    style="transition: none !important;">
    <style>
        html.dark .breadcrumb-nav {
            border-bottom-color: rgba(255, 255, 255, 0.05) !important;
        }

        /* Hide breadcrumbs on mobile screens (less than 768px) */
        @media (max-width: 767px) {
            .breadcrumb-nav {
                display: none !important;
            }
        }
    </style>

    <!-- Left: Refined Age-Friendly Breadcrumbs (Modern & High-Density) -->
    <div class="flex items-center text-slate-800 dark:text-slate-100">
        <ol class="flex items-center gap-2.5 text-[14px] font-medium tracking-tight">
            <!-- Modern Home Icon & Main Link -->
            <li class="flex items-center gap-2">
                <i class="ri-home-4-line"></i>
                <a href="/"
                    class="text-dark dark:text-indigo-400 hover:text-indigo-600 transition-none font-bold">Main</a>
            </li>

            <!-- Dynamic breadcrumbs -->
            @if (!empty($breadcrumbs) && is_array($breadcrumbs))
                @foreach ($breadcrumbs as $crumb)
                    <li class="flex items-center gap-2.5">
                        <span class="text-slate-300 dark:text-slate-700 mx-2">›</span>
                        @if (!empty($crumb['url']))
                            <a href="{{ $crumb['url'] }}"
                                class="text-primary dark:text-indigo-400 hover:text-indigo-600 transition-none font-bold">{{ $crumb['label'] }}</a>
                        @else
                            <span class="text-slate-500 dark:text-slate-400 font-medium">{{ $crumb['label'] }}</span>
                        @endif
                    </li>
                @endforeach
            @endif

            <!-- Current Page -->
            @if (!empty($active))
                <li class="flex items-center gap-2.5">
                    <span class="text-slate-300 dark:text-slate-700 mx-2">›</span>
                    <span
                        class="text-slate-800 dark:text-slate-100 font-bold capitalize tracking-tight">{{ strtolower($active) }}</span>
                </li>
            @endif
        </ol>
    </div>

    <!-- Right: High-Legibility System Clock -->
    <div class="flex items-center gap-5 text-[13px] font-medium text-slate-500 dark:text-slate-400 tracking-tight"
        id="bcClock">
        <div class="flex items-center gap-2">
            <span id="bcDate" class="uppercase tracking-tight">{{ now()->format('D, M d, Y') }}</span>
            <span class="opacity-30">|</span>
            <div class="flex items-baseline gap-1 font-bold text-slate-600 dark:text-slate-300">
                <span id="bcHH">--</span>
                <span>:</span>
                <span id="bcMM">--</span>
                <span>:</span>
                <span id="bcSS">--</span>
                <span id="bcMer" class="ml-0.5 text-[10px] opacity-70"></span>
            </div>
        </div>
    </div>
</nav>

<style>
    /* Clean sidebar hover and layout without transitions for instant UI */
    #bcClock * {
        transition: none !important;
    }
</style>

<script>
    (function () {
        const els = {
            root: document.getElementById('bcClock'),
            hh: document.getElementById('bcHH'),
            mm: document.getElementById('bcMM'),
            ss: document.getElementById('bcSS'),
            mer: document.getElementById('bcMer'),
            date: document.getElementById('bcDate'),
            abbr: document.getElementById('bcTZAbbr'),
        };

        function getActiveTZ() {
            const forced = els.root?.dataset?.timezone?.trim();
            return forced || Intl.DateTimeFormat().resolvedOptions().timeZone || 'UTC';
        }
        const pad = n => String(n).padStart(2, '0');

        function tzName(date, tz, style) {
            try {
                const parts = new Intl.DateTimeFormat('en-US', { timeZone: tz, timeZoneName: style }).formatToParts(date);
                return parts.find(p => p.type === 'timeZoneName')?.value || '';
            } catch (e) { return ''; }
        }

        function tick() {
            const tz = getActiveTZ();
            const now = new Date();

            try {
                const tf = new Intl.DateTimeFormat('en-US', {
                    timeZone: tz, hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: true
                }).formatToParts(now);

                const part = t => tf.find(p => p.type === t)?.value || '';

                if (els.hh) els.hh.textContent = pad(part('hour'));
                if (els.mm) els.mm.textContent = pad(part('minute'));
                if (els.ss) els.ss.textContent = pad(part('second'));
                if (els.mer) els.mer.textContent = (part('dayPeriod') || '').toUpperCase();

                // Format date for the sub-bar
                if (els.date) {
                    els.date.textContent = new Intl.DateTimeFormat('en-US', {
                        timeZone: tz, weekday: 'short', month: 'short', day: '2-digit', year: 'numeric'
                    }).format(now);
                }

                // Sync TZ Abbreviation
                if (els.abbr) {
                    let abbr = tzName(now, tz, 'short');
                    els.abbr.textContent = (abbr || 'PDT').toUpperCase();
                }
            } catch (e) { console.error("Clock tick error:", e); }
        }

        tick();
        setInterval(tick, 1000);
    })();
</script>