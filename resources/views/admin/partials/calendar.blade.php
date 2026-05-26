<style>
    /* FullCalendar minimal Tailwind-like cosmetics */
    .fc {
        --fc-border-color: rgb(229 231 235);
        font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Inter, Arial;
        color: #111827;
    }

    .fc a {
        color: inherit;
    }

    .fc .fc-toolbar-title {
        font-size: 1rem;
        font-weight: 600;
    }

    .fc .fc-button {
        background: #fff;
        border: 1px solid #d1d5db;
        color: #374151;
        font-size: .875rem;
        border-radius: .375rem;
        padding: .375rem .75rem;
        transition: background .15s ease;
    }

    .fc .fc-button:hover {
        background: #f9fafb;
    }

    .fc .fc-button.fc-button-primary {
        background: #2563eb;
        color: #fff;
        border-color: #2563eb;
    }

    .fc .fc-button.fc-button-primary:hover {
        background: #1d4ed8;
    }

    .fc .fc-daygrid-day.fc-day-today {
        background: rgba(59, 130, 246, .08);
    }

    .fc .fc-timegrid-now-indicator-line {
        border-color: #ef4444;
    }

    .fc .fc-timegrid-now-indicator-arrow {
        border-top-color: #ef4444;
    }

    .fc .fc-toolbar {
        display: none !important;
    }

    /* Soft color classes for events */
    .status-pending {
        background-color: #FEF3C7 !important;
        border-color: #FDE68A !important;
        color: #111827 !important;
    }

    .status-done {
        background-color: #DCFCE7 !important;
        border-color: #BBF7D0 !important;
        color: #065F46 !important;
    }

    .status-close {
        background-color: #DBEAFE !important;
        border-color: #BFDBFE !important;
        color: #1E3A8A !important;
    }

    .status-unattended {
        background-color: #FFE4E6 !important;
        border-color: #FECDD3 !important;
        color: #7F1D1D !important;
    }

    /* Make month events look like soft pills */
    .fc-daygrid-event {
        border-width: 1px !important;
        border-style: solid !important;
        border-radius: 8px !important;
        padding: 2px 6px !important;
        font-weight: bold;
        line-height: 1.2;
    }

    .fc .fc-col-header-cell-cushion {
        padding: 4px 2px;
        font-size: 0.75rem;
        display: block;
        width: 100%;
        text-align: center;
    }

    @media (min-width: 640px) {
        .fc .fc-col-header-cell-cushion {
            padding: 8px 4px;
            font-size: 0.875rem;
        }
    }

    /* Custom toolbar */
    .fc-tailwind-toolbar {
        display: grid;
        grid-template-columns: 1fr auto 1fr;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
    }

    @media (max-width: 768px) {
        .fc-tailwind-toolbar {
            display: flex;
            flex-direction: column;
            gap: 12px;
            text-align: center;
        }

        .toolbar-left, .toolbar-right {
            justify-content: center;
            width: 100%;
        }

        .toolbar-center {
            order: -1; /* Move title to top on mobile */
        }
    }

    .toolbar-left {
        justify-self: start;
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .toolbar-center {
        justify-self: center;
    }

    .toolbar-right {
        justify-self: end;
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .tw-btn {
        border: 1px solid #d1d5db;
        background: #fff;
        padding: .375rem .75rem;
        border-radius: .375rem;
        font-size: .875rem;
        line-height: 1.25rem;
        color: #374151;
        box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .375rem;
        cursor: pointer;
        white-space: nowrap;
    }

    @media (max-width: 480px) {
        .tw-btn {
            padding: .25rem .5rem;
            font-size: .75rem;
        }
    }

    .tw-btn:hover {
        background: #f9fafb
    }

    .tw-toggle[aria-pressed="true"] {
        background: #2563eb;
        border-color: #2563eb;
        color: #fff;
    }

    .tw-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 1.25rem;
        height: 1.25rem;
        padding: 0 .25rem;
        margin-left: .375rem;
        background: #111827;
        color: #fff;
        border-radius: 9999px;
        font-size: .75rem;
        line-height: 1rem;
    }

    /* Legend */
    .status-legend {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        justify-content: flex-end;
        align-items: center;
        margin-top: 10px;
        color: #111827;
    }

    .status-legend .item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: .875rem;
    }

    .status-legend .swatch {
        width: 16px;
        height: 12px;
        border-radius: 4px;
        border: 1px solid transparent;
        display: inline-block;
    }

    .status-legend .swatch.pending { background: #FEF3C7; border-color: #FDE68A; }
    .status-legend .swatch.done { background: #DCFCE7; border-color: #BBF7D0; }
    .status-legend .swatch.close { background: #DBEAFE; border-color: #BFDBFE; }
    .status-legend .swatch.unattended { background: #FFE4E6; border-color: #FECDD3; }

    /* Dark mode overrides */
    :is([data-theme-mode="dark"], .dark) .fc {
        --fc-border-color: rgba(255, 255, 255, 0.1);
        color: #e5e7eb;
    }

    :is([data-theme-mode="dark"], .dark) .fc .fc-daygrid-day-number,
    :is([data-theme-mode="dark"], .dark) .fc .fc-col-header-cell-cushion {
        color: #e5e7eb !important;
    }

    :is([data-theme-mode="dark"], .dark) .fc .fc-col-header-cell {
        background: #1e3a5f !important;
    }

    :is([data-theme-mode="dark"], .dark) .fc .fc-col-header-cell-cushion {
        color: #fff !important;
        font-weight: 600;
    }

    :is([data-theme-mode="dark"], .dark) .fc .fc-daygrid-day.fc-day-today {
        background: rgba(59, 130, 246, 0.15) !important;
    }

    :is([data-theme-mode="dark"], .dark) .fc th,
    :is([data-theme-mode="dark"], .dark) .fc td {
        border-color: rgba(255, 255, 255, 0.1) !important;
    }

    :is([data-theme-mode="dark"], .dark) .fc .fc-scrollgrid {
        border-color: rgba(255, 255, 255, 0.1) !important;
    }

    :is([data-theme-mode="dark"], .dark) .tw-btn {
        background: rgba(255, 255, 255, 0.05);
        border-color: rgba(255, 255, 255, 0.15);
        color: #e5e7eb;
    }

    :is([data-theme-mode="dark"], .dark) .tw-btn:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    :is([data-theme-mode="dark"], .dark) .tw-toggle[aria-pressed="true"] {
        background: #2563eb;
        border-color: #2563eb;
        color: #fff;
    }

    :is([data-theme-mode="dark"], .dark) .cal-title,
    :is([data-theme-mode="dark"], .dark) #adminCalTitle {
        color: #f3f4f6 !important;
    }

    :is([data-theme-mode="dark"], .dark) .status-legend {
        color: #e5e7eb;
    }

    :is([data-theme-mode="dark"], .dark) .fc .fc-list-day-cushion {
        background: rgba(255, 255, 255, 0.05) !important;
        color: #e5e7eb !important;
    }

    :is([data-theme-mode="dark"], .dark) .fc .fc-list-event:hover td {
        background: rgba(255, 255, 255, 0.05) !important;
    }
</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

<div class="box border">
    <div class="box-body">
        <!-- Custom Toolbar -->
        <div id="adminCalendarToolbar" class="fc-tailwind-toolbar">
            <div class="toolbar-left">
                <button data-act="prev" class="tw-btn">« Prev</button>
                <button data-act="today" class="tw-btn">Today</button>
                <button data-act="next" class="tw-btn">Next »</button>
            </div>

            <div class="toolbar-center">
                <strong id="adminCalTitle" class="cal-title text-2xl text-dark"></strong>
            </div>

            <div class="toolbar-right">
                <button data-view="dayGridMonth" class="tw-btn tw-toggle" aria-pressed="true">Month</button>
                <button data-view="listWeek" class="tw-btn tw-toggle">Weekly</button>
                <button data-view="listDay" class="tw-btn tw-toggle">Today</button>
            </div>
        </div>

        <div id="adminCalendarHost"></div>

        <!-- Status Legend -->
        <div class="status-legend">
            <span class="item">
                <span class="swatch pending"></span>
                Pending
            </span>
            <span class="item">
                <span class="swatch done"></span>
                Done
            </span>
            <span class="item">
                <span class="swatch close"></span>
                Close
            </span>
            <span class="item">
                <span class="swatch unattended"></span>
                Unattended
            </span>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarHost = document.getElementById('adminCalendarHost');
    if (!calendarHost) return;

    const calendar = new FullCalendar.Calendar(calendarHost, {
        height: 'auto',
        initialView: 'dayGridMonth',
        nowIndicator: true,
        navLinks: true,
        selectable: true,
        editable: false,
        dayMaxEventRows: true,
        firstDay: 1,
        dayHeaderFormat: { weekday: 'narrow' },
        breakpoints: {
            480: {
                dayHeaderFormat: { weekday: 'short' }
            },
            768: {
                dayHeaderFormat: { weekday: 'short' }
            }
        },
        views: {
            dayGridMonth: { displayEventTime: false },
            listWeek: {
                eventTimeFormat: { hour: 'numeric', minute: '2-digit', hour12: true, meridiem: 'long' }
            },
            listDay: {
                eventTimeFormat: { hour: 'numeric', minute: '2-digit', hour12: true, meridiem: 'long' }
            }
        },
        events: [],
        datesSet: function(info) {
            document.getElementById('adminCalTitle').textContent = info.view.title;
        },
        dateClick: function(info) {
            // Placeholder for future event creation
            console.log('Date clicked:', info.dateStr);
        }
    });

    calendar.render();

    // Update title
    document.getElementById('adminCalTitle').textContent = calendar.view.title;

    // Toolbar buttons
    const toolbar = document.getElementById('adminCalendarToolbar');
    toolbar.querySelectorAll('[data-act]').forEach(btn => {
        btn.addEventListener('click', () => {
            const act = btn.dataset.act;
            if (act === 'prev') calendar.prev();
            else if (act === 'next') calendar.next();
            else if (act === 'today') calendar.today();
        });
    });

    toolbar.querySelectorAll('[data-view]').forEach(btn => {
        btn.addEventListener('click', () => {
            const view = btn.dataset.view;
            calendar.changeView(view);
            toolbar.querySelectorAll('[data-view]').forEach(b => b.setAttribute('aria-pressed', 'false'));
            btn.setAttribute('aria-pressed', 'true');
        });
    });
});
</script>
