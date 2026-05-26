import { Head, Link } from '@inertiajs/react';
import CandidateLayout from '@/Layouts/CandidateLayout';

export default function Interviews({
    auth,
    interviews,
    filter = 'upcoming',
    upcomingCount,
    todayCount,
    totalCount,
}) {
    const statusConfig = {
        scheduled: { bg: '#eef2ff', text: '#4f46e5', icon: 'ri-calendar-check-line', label: 'Scheduled' },
        completed: { bg: '#f0fdf4', text: '#16a34a', icon: 'ri-checkbox-circle-line', label: 'Completed' },
        cancelled: { bg: '#fef2f2', text: '#dc2626', icon: 'ri-close-circle-line', label: 'Cancelled' },
        rescheduled: { bg: '#fefce8', text: '#ca8a04', icon: 'ri-refresh-line', label: 'Rescheduled' },
        no_show: { bg: '#f9fafb', text: '#6b7280', icon: 'ri-user-unfollow-line', label: 'No Show' },
    };

    const typeIcons = {
        phone: 'ri-phone-line',
        video: 'ri-video-chat-line',
        in_person: 'ri-map-pin-user-line',
    };

    const getTypeLabel = (type) => {
        const labels = {
            phone: 'Phone Call',
            video: 'Video Call',
            in_person: 'In Person',
        };
        return labels[type] || type;
    };

    const formatDate = (dateString) => {
        const date = new Date(dateString);
        return {
            day: date.getDate(),
            month: date.toLocaleDateString('en-US', { month: 'short' }),
            dayName: date.toLocaleDateString('en-US', { weekday: 'short' }),
            time: date.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' }),
            isToday: date.toDateString() === new Date().toDateString(),
            isPast: date < new Date(),
        };
    };

    const handleDownloadCalendar = (interviewId) => {
        window.location.href = `/candidate/interviews/${interviewId}/calendar`;
    };

    return (
        <CandidateLayout user={auth.user}>
            <Head title="My Interviews" />

            <div className="py-6">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {/* Hero */}
                    <div className="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 rounded-xl p-6 mb-6 shadow-lg">
                        <div className="flex flex-wrap items-center justify-between gap-4">
                            <div>
                                <h1 className="text-2xl font-bold text-white mb-1 flex items-center gap-2">
                                    <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path fillRule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clipRule="evenodd" />
                                    </svg>
                                    My Interviews
                                </h1>
                                <p className="text-purple-100 text-sm">
                                    View and manage your scheduled interviews - Good luck! 🚀
                                </p>
                            </div>
                            <Link
                                href="/candidate/dashboard"
                                className="inline-flex items-center gap-2 px-4 py-2 bg-white text-purple-700 rounded-lg font-semibold text-sm hover:bg-purple-50 transition"
                            >
                                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                Dashboard
                            </Link>
                        </div>
                    </div>

                    {/* Stats */}
                    <div className="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                        <div className="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl p-5 border border-blue-100 dark:border-blue-800 hover:shadow-lg transition-all">
                            <div className="flex items-center justify-between mb-2">
                                <span className="text-xs font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider">Upcoming</span>
                                <div className="w-10 h-10 rounded-lg bg-white dark:bg-blue-900/50 flex items-center justify-center text-blue-600 dark:text-blue-400 shadow-sm">
                                    <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fillRule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clipRule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <div className="text-3xl font-black text-gray-900 dark:text-white mb-1">{upcomingCount}</div>
                            <p className="text-xs text-gray-600 dark:text-gray-400">Next session scheduled soon</p>
                        </div>

                        <div className="bg-gradient-to-br from-orange-50 to-amber-50 dark:from-orange-900/20 dark:to-amber-900/20 rounded-xl p-5 border border-orange-100 dark:border-orange-800 hover:shadow-lg transition-all">
                            <div className="flex items-center justify-between mb-2">
                                <span className="text-xs font-bold text-orange-600 dark:text-orange-400 uppercase tracking-wider">Today</span>
                                <div className="w-10 h-10 rounded-lg bg-white dark:bg-orange-900/50 flex items-center justify-center text-orange-600 dark:text-orange-400 shadow-sm">
                                    <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clipRule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <div className="text-3xl font-black text-gray-900 dark:text-white mb-1">{todayCount}</div>
                            <p className="text-xs text-gray-600 dark:text-gray-400">Activities for today</p>
                        </div>

                        <div className="bg-gradient-to-br from-gray-50 to-slate-50 dark:from-gray-900/20 dark:to-slate-900/20 rounded-xl p-5 border border-gray-100 dark:border-gray-800 hover:shadow-lg transition-all">
                            <div className="flex items-center justify-between mb-2">
                                <span className="text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Total</span>
                                <div className="w-10 h-10 rounded-lg bg-white dark:bg-gray-900/50 flex items-center justify-center text-gray-600 dark:text-gray-400 shadow-sm">
                                    <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                        <path fillRule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clipRule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <div className="text-3xl font-black text-gray-900 dark:text-white mb-1">{totalCount}</div>
                            <p className="text-xs text-gray-600 dark:text-gray-400">Complete meeting history</p>
                        </div>
                    </div>

                    {/* Main Content */}
                    <div className="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <div className="flex items-center justify-between mb-4 pb-3 border-b border-gray-100 dark:border-gray-700">
                            <h2 className="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                <svg className="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fillRule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clipRule="evenodd" />
                                </svg>
                                Scheduled Interviews
                            </h2>
                            <span className="text-xs text-gray-500">{interviews.total} total</span>
                        </div>

                        {/* Filter Tabs */}
                        <div className="flex flex-wrap gap-2 mb-6">
                            <Link
                                href="/candidate/interviews"
                                className={`px-4 py-2 rounded-xl font-semibold text-sm transition-all ${
                                    !filter || filter === 'upcoming'
                                        ? 'bg-indigo-600 text-white shadow-md'
                                        : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'
                                }`}
                            >
                                <i className="ri-calendar-check-line mr-1"></i> Upcoming
                            </Link>
                            <Link
                                href="/candidate/interviews?filter=today"
                                className={`px-4 py-2 rounded-xl font-semibold text-sm transition-all ${
                                    filter === 'today'
                                        ? 'bg-green-600 text-white shadow-md'
                                        : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'
                                }`}
                            >
                                <i className="ri-calendar-todo-line mr-1"></i> Today
                            </Link>
                            <Link
                                href="/candidate/interviews?filter=past"
                                className={`px-4 py-2 rounded-xl font-semibold text-sm transition-all ${
                                    filter === 'past'
                                        ? 'bg-gray-600 text-white shadow-md'
                                        : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'
                                }`}
                            >
                                <i className="ri-history-line mr-1"></i> Past
                            </Link>
                            <Link
                                href="/candidate/interviews?filter=all"
                                className={`px-4 py-2 rounded-xl font-semibold text-sm transition-all ${
                                    filter === 'all'
                                        ? 'bg-blue-600 text-white shadow-md'
                                        : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'
                                }`}
                            >
                                <i className="ri-apps-line mr-1"></i> All
                            </Link>
                        </div>

                        {/* Interviews List */}
                        {interviews.data.length === 0 ? (
                            <div className="text-center py-12">
                                <svg className="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <h3 className="text-xl font-bold text-gray-900 dark:text-white mb-2">
                                    {filter === 'upcoming' && 'No upcoming interviews scheduled'}
                                    {filter === 'today' && 'No interviews scheduled for today'}
                                    {(filter === 'past' || filter === 'all' || !filter) && 'No interviews found'}
                                </h3>
                                <p className="text-gray-500 dark:text-gray-400 text-sm mb-4">
                                    When an employer schedules an interview with you, it will appear here.
                                </p>
                                <Link
                                    href="/candidate/dashboard"
                                    className="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700"
                                >
                                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                    Back to Dashboard
                                </Link>
                            </div>
                        ) : (
                            <>
                                <div className="space-y-4">
                                    {interviews.data.map((interview) => {
                                        const dateInfo = formatDate(interview.scheduled_at);
                                        const status = statusConfig[interview.status] || statusConfig.scheduled;
                                        const typeIcon = typeIcons[interview.interview_type] || 'ri-question-line';

                                        return (
                                            <div
                                                key={interview.id}
                                                className="flex flex-col sm:flex-row gap-4 p-4 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-indigo-300 dark:hover:border-indigo-700 hover:shadow-lg transition-all bg-white dark:bg-gray-800"
                                            >
                                                {/* Calendar Block */}
                                                <div className="flex-shrink-0">
                                                    <div className="w-20 h-24 rounded-xl overflow-hidden shadow-sm border border-gray-200 dark:border-gray-700">
                                                        <div
                                                            className={`h-6 flex items-center justify-center text-white text-xs font-bold uppercase ${
                                                                dateInfo.isToday
                                                                    ? 'bg-orange-500'
                                                                    : dateInfo.isPast
                                                                      ? 'bg-gray-400'
                                                                      : 'bg-indigo-600'
                                                            }`}
                                                        >
                                                            {dateInfo.dayName}
                                                        </div>
                                                        <div className="flex-1 flex flex-col items-center justify-center bg-white dark:bg-gray-700 py-2">
                                                            <div className="text-3xl font-black text-gray-900 dark:text-white leading-none">
                                                                {dateInfo.day}
                                                            </div>
                                                            <div className="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mt-1">
                                                                {dateInfo.month}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {/* Details */}
                                                <div className="flex-1 min-w-0">
                                                    <div className="flex flex-col sm:flex-row sm:items-start justify-between gap-2 mb-3">
                                                        <div className="flex-1 min-w-0">
                                                            <h4 className="text-lg font-bold text-gray-900 dark:text-white truncate mb-1">
                                                                {interview.title}
                                                            </h4>
                                                            <div className="flex items-center gap-2 text-sm flex-wrap">
                                                                <span className="font-semibold text-gray-700 dark:text-gray-300">
                                                                    {interview.job_application?.job_posting?.title || 'Unknown Position'}
                                                                </span>
                                                                <span className="text-gray-400">@</span>
                                                                <span className="font-semibold text-indigo-600 dark:text-indigo-400">
                                                                    {interview.job_application?.job_posting?.company?.name || 'Unknown Company'}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <span
                                                            className="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide flex items-center gap-1.5 whitespace-nowrap"
                                                            style={{ backgroundColor: status.bg, color: status.text }}
                                                        >
                                                            <i className={status.icon}></i>
                                                            {status.label}
                                                        </span>
                                                    </div>

                                                    {/* Metadata */}
                                                    <div className="flex flex-wrap gap-2 mb-4">
                                                        <div className="flex items-center gap-1.5 px-3 py-1.5 bg-gray-50 dark:bg-gray-700 rounded-lg text-xs font-semibold text-gray-700 dark:text-gray-300">
                                                            <i className="ri-time-line text-gray-500"></i>
                                                            {dateInfo.time}
                                                        </div>
                                                        <div className="flex items-center gap-1.5 px-3 py-1.5 bg-gray-50 dark:bg-gray-700 rounded-lg text-xs font-semibold text-gray-700 dark:text-gray-300">
                                                            <i className="ri-refresh-line text-gray-500"></i>
                                                            {interview.duration_minutes} min
                                                        </div>
                                                        <div className="flex items-center gap-1.5 px-3 py-1.5 bg-gray-50 dark:bg-gray-700 rounded-lg text-xs font-semibold text-gray-700 dark:text-gray-300">
                                                            <i className={`${typeIcon} text-gray-500`}></i>
                                                            {getTypeLabel(interview.interview_type)}
                                                        </div>
                                                        {interview.location && (
                                                            <div className="flex items-center gap-1.5 px-3 py-1.5 bg-gray-50 dark:bg-gray-700 rounded-lg text-xs font-semibold text-gray-700 dark:text-gray-300">
                                                                <i className="ri-map-pin-line text-gray-500"></i>
                                                                {interview.location}
                                                            </div>
                                                        )}
                                                    </div>

                                                    {/* Description */}
                                                    {interview.description && (
                                                        <p className="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
                                                            {interview.description}
                                                        </p>
                                                    )}

                                                    {/* Actions */}
                                                    <div className="flex flex-wrap gap-2 pt-3 border-t border-gray-100 dark:border-gray-700">
                                                        {interview.meeting_link && !dateInfo.isPast && (
                                                            <a
                                                                href={interview.meeting_link}
                                                                target="_blank"
                                                                rel="noopener noreferrer"
                                                                className="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg font-semibold text-sm hover:bg-indigo-700 transition"
                                                            >
                                                                <i className="ri-video-chat-line"></i>
                                                                Join Meeting
                                                            </a>
                                                        )}
                                                        <button
                                                            onClick={() => handleDownloadCalendar(interview.id)}
                                                            className="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg font-semibold text-sm hover:bg-gray-200 dark:hover:bg-gray-600 transition"
                                                        >
                                                            <i className="ri-calendar-download-line"></i>
                                                            Add to Calendar
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        );
                                    })}
                                </div>

                                {/* Pagination */}
                                {interviews.links && interviews.links.length > 3 && (
                                                    <div className="mt-6 flex items-center justify-between text-sm text-gray-600 dark:text-gray-400">
                                        <div>
                                            Showing {interviews.from} to {interviews.to} of {interviews.total} results
                                        </div>
                                        <div className="flex gap-2">
                                            {interviews.links.map((link, idx) => (
                                                <Link
                                                    key={idx}
                                                    href={link.url || '#'}
                                                    className={`px-3 py-1 rounded-lg ${
                                                        link.active
                                                            ? 'bg-indigo-600 text-white'
                                                            : link.url
                                                              ? 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200'
                                                              : 'bg-gray-100 dark:bg-gray-700 text-gray-400 cursor-not-allowed'
                                                    }`}
                                                    dangerouslySetInnerHTML={{ __html: link.label }}
                                                />
                                            ))}
                                        </div>
                                    </div>
                                )}
                            </>
                        )}
                    </div>
                </div>
            </div>
        </CandidateLayout>
    );
}
