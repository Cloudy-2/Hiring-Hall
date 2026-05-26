import { Head, Link } from '@inertiajs/react';
import CandidateLayout from '@/Layouts/CandidateLayout';

export default function JobAlertsShow({ auth, alert, jobs, slugs = [] }) {
    const formatEmploymentType = (type) => {
        return type ? type.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()) : '';
    };

    return (
        <CandidateLayout user={auth.user}>
            <Head title={`${alert.name} - Matching Jobs`} />

            <div className="py-6">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {/* Header */}
                    <div className="mb-6">
                        <Link
                            href="/candidate/job-alerts"
                            className="inline-flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 hover:text-emerald-600 mb-4"
                        >
                            <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to Job Alerts
                        </Link>
                        
                        <div className="bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-600 rounded-xl p-6 shadow-lg">
                            <div className="flex flex-wrap items-center justify-between gap-4">
                                <div className="flex-1">
                                    <h1 className="text-2xl font-bold text-white mb-2 flex items-center gap-2">
                                        <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                                        </svg>
                                        {alert.name}
                                    </h1>
                                    
                                    {/* Criteria Display */}
                                    <div className="flex flex-wrap gap-2 text-sm">
                                        {alert.keywords && (
                                            <span className="px-3 py-1 bg-white/20 text-white rounded-full backdrop-blur-sm">
                                                <i className="ri-search-line mr-1"></i>
                                                {alert.keywords}
                                            </span>
                                        )}
                                        {alert.location && (
                                            <span className="px-3 py-1 bg-white/20 text-white rounded-full backdrop-blur-sm">
                                                <i className="ri-map-pin-line mr-1"></i>
                                                {alert.location}
                                            </span>
                                        )}
                                        {alert.category && (
                                            <span className="px-3 py-1 bg-white/20 text-white rounded-full backdrop-blur-sm">
                                                <i className="ri-briefcase-line mr-1"></i>
                                                {alert.category}
                                            </span>
                                        )}
                                        {alert.remote_type && (
                                            <span className="px-3 py-1 bg-white/20 text-white rounded-full backdrop-blur-sm">
                                                <i className="ri-home-wifi-line mr-1"></i>
                                                {alert.remote_type}
                                            </span>
                                        )}
                                        {alert.employment_type && (
                                            <span className="px-3 py-1 bg-white/20 text-white rounded-full backdrop-blur-sm">
                                                <i className="ri-time-line mr-1"></i>
                                                {alert.employment_type}
                                            </span>
                                        )}
                                    </div>
                                </div>
                                
                                <Link
                                    href={`/candidate/job-alerts/${alert.id}/edit`}
                                    className="inline-flex items-center gap-2 px-4 py-2 bg-white text-emerald-700 rounded-lg font-semibold text-sm hover:bg-emerald-50 transition"
                                >
                                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit Alert
                                </Link>
                            </div>
                        </div>
                    </div>

                    {/* Matching Jobs */}
                    <div className="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <div className="flex items-center justify-between mb-4 pb-3 border-b border-gray-100 dark:border-gray-700">
                            <h2 className="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                <svg className="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fillRule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clipRule="evenodd" />
                                    <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z" />
                                </svg>
                                Matching Jobs
                            </h2>
                            <span className="text-xs text-gray-500">{jobs.total} found</span>
                        </div>

                        {jobs.data.length === 0 ? (
                            <div className="text-center py-12">
                                <svg className="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <p className="text-gray-500 mb-4">
                                    No jobs match your alert criteria yet.
                                </p>
                                <p className="text-sm text-gray-400 mb-4">
                                    We'll notify you when new matching jobs are posted.
                                </p>
                                <Link
                                    href="/jobs"
                                    className="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700"
                                >
                                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    Browse All Jobs
                                </Link>
                            </div>
                        ) : (
                            <>
                                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    {jobs.data.map((job) => (
                                        <Link
                                            key={job.id}
                                            href={`/jobs/${job.slug}`}
                                            className="block p-5 border border-gray-200 dark:border-gray-700 rounded-xl hover:border-emerald-300 dark:hover:border-emerald-700 hover:shadow-lg transition-all"
                                        >
                                            {/* Company Logo */}
                                            <div className="flex items-start gap-3 mb-3">
                                                <div className="w-12 h-12 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center flex-shrink-0 overflow-hidden">
                                                    {job.company?.logo_url ? (
                                                        <img
                                                            src={job.company.logo_url}
                                                            alt={job.company.name}
                                                            className="w-full h-full object-cover"
                                                        />
                                                    ) : (
                                                        <span className="text-emerald-600 dark:text-emerald-400 font-bold text-sm">
                                                            {(job.company?.name || 'C').substring(0, 2).toUpperCase()}
                                                        </span>
                                                    )}
                                                </div>
                                                <div className="flex-1 min-w-0">
                                                    <p className="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                                        {job.company?.name || 'Company'}
                                                    </p>
                                                    <p className="text-xs text-gray-500">
                                                        {job.location || 'Remote'}
                                                    </p>
                                                </div>
                                            </div>

                                            {/* Job Title */}
                                            <h3 className="text-base font-bold text-gray-900 dark:text-white mb-2 line-clamp-2">
                                                {job.title}
                                            </h3>

                                            {/* Job Details */}
                                            <div className="flex flex-wrap gap-2 mb-3">
                                                {job.employment_type && (
                                                    <span className="px-2 py-1 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded text-xs font-semibold">
                                                        {formatEmploymentType(job.employment_type)}
                                                    </span>
                                                )}
                                                {job.remote_type && (
                                                    <span className="px-2 py-1 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded text-xs font-semibold">
                                                        {job.remote_type}
                                                    </span>
                                                )}
                                            </div>

                                            {/* Summary */}
                                            {job.summary && (
                                                <p className="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mb-3">
                                                    {job.summary}
                                                </p>
                                            )}

                                            {/* Posted Date */}
                                            <div className="text-xs text-gray-500">
                                                Posted {new Date(job.posted_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })}
                                            </div>
                                        </Link>
                                    ))}
                                </div>

                                {/* Pagination */}
                                {jobs.links && jobs.links.length > 3 && (
                                    <div className="mt-6 flex items-center justify-between text-sm text-gray-600 dark:text-gray-400">
                                        <div>
                                            Showing {jobs.from} to {jobs.to} of {jobs.total} results
                                        </div>
                                        <div className="flex gap-2">
                                            {jobs.links.map((link, idx) => (
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
