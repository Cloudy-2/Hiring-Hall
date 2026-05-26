@php
    $heroClockId = $heroClockId ?? 'jfHeroClock';
    $heroClockTimezone = $heroClockTimezone ?? 'America/Los_Angeles';
    $heroClockDateId = $heroClockId.'Date';
    $heroClockHourId = $heroClockId.'HH';
    $heroClockMinuteId = $heroClockId.'MM';
    $heroClockSecondId = $heroClockId.'SS';
    $heroClockMeridiemId = $heroClockId.'Mer';
    $heroClockTzId = $heroClockId.'Tz';
@endphp

<style>
    .jf-header-side {
        position: absolute;
        top: 0.85rem;
        right: 0.85rem;
        min-width: 260px;
        z-index: 2;
    }

    .jf-header-clock {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.55rem 0.8rem;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 0.875rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        color: #6b7280;
        font-size: 0.8125rem;
        font-weight: 600;
        line-height: 1;
        white-space: nowrap;
    }
    .jf-header-clock-date {
        text-transform: capitalize;
        letter-spacing: -0.01em;
    }
    .jf-header-clock-divider {
        opacity: 0.35;
    }
    .jf-header-clock-time {
        display: inline-flex;
        align-items: baseline;
        gap: 0.15rem;
        color: #374151;
        font-weight: 800;
    }
    .jf-header-clock-segment {
        min-width: 1.25ch;
        text-align: center;
    }
    .jf-header-clock-meridiem {
        margin-left: 0.15rem;
        font-size: 0.625rem;
        letter-spacing: 0.08em;
    }
    .jf-header-clock-tz {
        padding: 0.2rem 0.45rem;
        border-radius: 0.45rem;
        background: #eff6ff;
        border: 1px solid #cfe3ff;
        color: #2563eb;
        font-size: 0.625rem;
        font-weight: 800;
        letter-spacing: 0.08em;
    }

    :is([data-theme-mode="dark"], .dark) .jf-header-clock {
        background: rgba(15, 23, 42, 0.72);
        border-color: rgba(255, 255, 255, 0.08);
        color: #cbd5e1;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
    }
    :is([data-theme-mode="dark"], .dark) .jf-header-clock-time {
        color: #f8fafc;
    }
    :is([data-theme-mode="dark"], .dark) .jf-header-clock-tz {
        background: rgba(59, 130, 246, 0.18);
        border-color: rgba(59, 130, 246, 0.28);
        color: #93c5fd;
    }

    @media (max-width: 768px) {
        .jf-header-side {
            position: static;
            width: 100%;
            min-width: 0;
        }

        .jf-header-clock {
            width: 100%;
            justify-content: flex-start;
            flex-wrap: wrap;
            white-space: normal;
        }
    }
</style>

<div class="jf-header-side" aria-hidden="true">
    <div class="jf-header-clock" id="{{ $heroClockId }}" data-timezone="{{ $heroClockTimezone }}" aria-label="Current date and time">
        <span class="jf-header-clock-date" id="{{ $heroClockDateId }}">{{ now($heroClockTimezone)->format('F j Y, D') }}</span>
        <span class="jf-header-clock-divider">|</span>
        <span class="jf-header-clock-time" aria-hidden="true">
            <span class="jf-header-clock-segment" id="{{ $heroClockHourId }}">{{ now($heroClockTimezone)->format('h') }}</span>
            <span>:</span>
            <span class="jf-header-clock-segment" id="{{ $heroClockMinuteId }}">{{ now($heroClockTimezone)->format('i') }}</span>
            <span>:</span>
            <span class="jf-header-clock-segment" id="{{ $heroClockSecondId }}">{{ now($heroClockTimezone)->format('s') }}</span>
            <span class="jf-header-clock-meridiem" id="{{ $heroClockMeridiemId }}">{{ now($heroClockTimezone)->format('A') }}</span>
        </span>
        <span class="jf-header-clock-tz" id="{{ $heroClockTzId }}">PDT</span>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const clockRoot = document.getElementById(@json($heroClockId));
        if (!clockRoot) return;

        const timezone = clockRoot.dataset.timezone || @json($heroClockTimezone);
        const elements = {
            date: document.getElementById(@json($heroClockDateId)),
            hh: document.getElementById(@json($heroClockHourId)),
            mm: document.getElementById(@json($heroClockMinuteId)),
            ss: document.getElementById(@json($heroClockSecondId)),
            mer: document.getElementById(@json($heroClockMeridiemId)),
            tz: document.getElementById(@json($heroClockTzId)),
        };

        const pad = value => String(value).padStart(2, '0');

        function getTimezoneAbbr(date) {
            try {
                const parts = new Intl.DateTimeFormat('en-US', {
                    timeZone: timezone,
                    timeZoneName: 'short',
                }).formatToParts(date);
                return (parts.find(part => part.type === 'timeZoneName')?.value || 'PDT').toUpperCase();
            } catch (error) {
                return 'PDT';
            }
        }

        function tick() {
            const nowDate = new Date();

            try {
                const timeParts = new Intl.DateTimeFormat('en-US', {
                    timeZone: timezone,
                    hour: 'numeric',
                    minute: 'numeric',
                    second: 'numeric',
                    hour12: true,
                }).formatToParts(nowDate);

                const part = type => timeParts.find(item => item.type === type)?.value || '';

                if (elements.hh) elements.hh.textContent = pad(part('hour'));
                if (elements.mm) elements.mm.textContent = pad(part('minute'));
                if (elements.ss) elements.ss.textContent = pad(part('second'));
                if (elements.mer) elements.mer.textContent = (part('dayPeriod') || '').toUpperCase();

                if (elements.date) {
                    elements.date.textContent = new Intl.DateTimeFormat('en-US', {
                        timeZone: timezone,
                        month: 'long',
                        day: 'numeric',
                        year: 'numeric',
                        weekday: 'short',
                    }).format(nowDate);
                }

                if (elements.tz) {
                    elements.tz.textContent = getTimezoneAbbr(nowDate);
                }
            } catch (error) {
                console.error('Hero clock tick error:', error);
            }
        }

        tick();
        setInterval(tick, 1000);
    });
</script>
