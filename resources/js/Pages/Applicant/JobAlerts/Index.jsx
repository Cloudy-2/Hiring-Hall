import { Head, Link, router } from '@inertiajs/react';
import { useState } from 'react';
import CandidateLayout from '@/Layouts/CandidateLayout';

export default function JobAlertsIndex({ auth, alerts, dropdownOptions }) {
    const [deletingId, setDeletingId] = useState(null);

    const handleDelete = (id, name) => {
        if (!confirm(`Delete job alert "${name}"?`)) return;

        setDeletingId(id);
        router.delete(`/candidate/job-alerts/${id}`, {
            onFinish: () => setDeletingId(null),
        });
    };

    const handleToggleActive = (alert) => {
        router.put(
            `/candidate/job-alerts/${alert.id}`,
            {
                ...alert,
                is_active: !alert.is_active,
            },
            {
                preserveScroll: true,
            }
        );
    };

    const getFrequencyBadge = (frequency) => {
        const config = {
            daily: { bg: '#eef2ff', text: '#4f46e5', label: 'Daily' },
            weekly: { bg: '#f0fdf4', text: '#16a34a', label: 'Weekly' },
        };
        return config[frequency] || config.daily;
    };

    return (
        <CandidateLayout user={auth.user}>
            <Head title="Job Alerts" />

            <div className="py-6">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {/* Hero */}
                    <div className="bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-600 rounded-xl p-6 mb-6 shadow-lg">
                        <div className="flex flex-wrap items-center justify-between gap-4">
                            <div>
                                <h1 className="text-2xl font-bold text-white mb-1 flex items-center gap-2">
                                    <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                                    </svg>
                                    Job Alerts
                                </h1>
                                <p className="text-emerald-100 text-sm">
                                    Get notified when new jobs match your criteria
                                </p>
                            </div>
                            <div className="flex flex-wrap gap-2">
                                <Link
                                    href="/candidate/job-alerts/create"
                                    className="inline-flex items-center gap-2 px-4 py-2 bg-white text-emerald-700 rounded-lg font-semibold text-sm hover:bg-emerald-50 transition"
                                >
                                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4v16m8-8H4" />
                                    </svg>
                                    Create Alert
                                </Link>
                                <Link
                                    href="/candidate/dashboard"
                                    className="inline-flex items-center gap-2 px-4 py-2 bg-white/20 text-white rounded-lg font-semibold text-sm hover:bg-white/30 transition backdrop-blur-sm"
                                >
                                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                    Dashboard
                                </Link>
                            </div>
                        </div>
                    </div>

                    {/* Main Content */}
                    <div className="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <div className="flex items-center justify-between mb-4 pb-3 border-b border-gray-100 dark:border-gray-700">
                            <h2 className="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                <svg className="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                                </svg>
                                Your Job Alerts
                            </h2>
                            <span className="text-xs text-gray-500">{alerts.total} total</span>
                        </div>

                        {alerts.data.length === 0 ? (
                            <div className="text-center py-12">
                                <svg className="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <p className="text-gray-500 mb-4">
                                    You haven't created any job alerts yet.
                                </p>
                                <Link
                                    href="/candidate/job-alerts/create"
                                    className="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700"
                                >
                                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4v16m8-8H4" />
                                    </svg>
                                    Create Your First Alert
                                </Link>
                            </div>
                        ) : (
                            <>
                                <div className="space-y-3">
                                    {alerts.data.map((alert) => {
                                        const freqBadge = getFrequencyBadge(alert.frequency);
                                        return (
                                            <div
                                                key={alert.id}
                                                className="flex flex-col sm:flex-row items-start gap-4 p-4 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-emerald-300 dark:hover:border-emerald-700 hover:shadow-md transition-all"
                                            >
                                                {/* Toggle Switch */}
                                                <div className="flex-shrink-0">
                                                    <button
                                                        onClick={() => handleToggleActive(alert)}
                                                        className={`relative inline-flex h-6 w-11 items-center rounded-full transition-colors ${
                                                            alert.is_active ? 'bg-emerald-600' : 'bg-gray-300 dark:bg-gray-600'
                                                        }`}
                                                        title={alert.is_active ? 'Active' : 'Inactive'}
                                                    >
                                                        <span
                                                            className={`inline-block h-4 w-4 transform rounded-full bg-white transition-transform ${
                                                                alert.is_active ? 'translate-x-6' : 'translate-x-1'
                                                            }`}
                                                        />
                                                    </button>
                                                </div>

                                                {/* Content */}
                                                <div className="flex-1 min-w-0">
                                                    <div className="flex items-start justify-between gap-2 mb-2">
                                                        <h3 className="text-lg font-bold text-gray-900 dark:text-white">
                                                            {alert.name}
                                                        </h3>
                                                        <div className="flex items-center gap-2">
                                                            <span
                                                                className="px-2 py-1 rounded-full text-xs font-bold"
                                                                style={{ backgroundColor: freqBadge.bg, color: freqBadge.text }}
                                                            >
                                                                {freqBadge.label}
                                                            </span>
                                                            {alert.email_enabled && (
                                                                <span className="px-2 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-600">
                                                                    <i className="ri-mail-line"></i> Email
                                                                </span>
                                                            )}
                                                        </div>
                                                    </div>

                                                    {/* Criteria */}
                                                    <div className="flex flex-wrap gap-2 mb-3 text-xs">
                                                        {alert.keywords && (
                                                            <span className="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded">
                                                                <i className="ri-search-line mr-1"></i>
                                                                {alert.keywords}
                                                            </span>
                                                        )}
                                                        {alert.location && (
                                                            <span className="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded">
                                                                <i className="ri-map-pin-line mr-1"></i>
                                                                {alert.location}
                                                            </span>
                                                        )}
                                                        {alert.category && (
                                                            <span className="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded">
                                                                <i className="ri-briefcase-line mr-1"></i>
                                                                {alert.category}
                                                            </span>
                                                        )}
                                                        {alert.remote_type && (
                                                            <span className="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded">
                                                                <i className="ri-home-wifi-line mr-1"></i>
                                                                {alert.remote_type}
                                                            </span>
                                                        )}
                                                        {alert.employment_type && (
                                                            <span className="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded">
                                                                <i className="ri-time-line mr-1"></i>
                                                                {alert.employment_type}
                                                            </span>
                                                        )}
                                                    </div>

                                                    {/* Meta */}
                                                    <div className="text-xs text-gray-500">
                                                        Created {new Date(alert.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}
                                                    </div>
                                                </div>

                                                {/* Actions */}
                                                <div className="flex gap-2 flex-shrink-0">
                                                    <Link
                                                        href={`/candidate/job-alerts/${alert.id}`}
                                                        className="p-2 text-emerald-600 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition"
                                                        title="View matching jobs"
                                                    >
                                                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                    </Link>
                                                    <Link
                                                        href={`/candidate/job-alerts/${alert.id}/edit`}
                                                        className="p-2 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition"
                                                        title="Edit"
                                                    >
                                                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </Link>
                                                    <button
                                                        onClick={() => handleDelete(alert.id, alert.name)}
                                                        disabled={deletingId === alert.id}
                                                        className="p-2 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition disabled:opacity-50"
                                                        title="Delete"
                                                    >
                                                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        );
                                    })}
                                </div>

                                {/* Pagination */}
                                {alerts.links && alerts.links.length > 3 && (
                                    <div className="mt-6 flex items-center justify-between text-sm text-gray-600 dark:text-gray-400">
                                        <div>
                                            Showing {alerts.from} to {alerts.to} of {alerts.total} results
                                        </div>
                                        <div className="flex gap-2">
                                            {alerts.links.map((link, idx) => (
                                                <Link
                                                    key={idx}
                                                    href={link.url || '#'}
                                                    className={`px-3 py-1 rounded-lg ${
                                                        link.active
                                                            ? 'bg-emerald-600 text-white'
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
