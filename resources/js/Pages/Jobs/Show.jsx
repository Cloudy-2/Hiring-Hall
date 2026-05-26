import { Head, Link, router } from "@inertiajs/react";
import { useState } from "react";
import CandidateLayout from "@/Layouts/CandidateLayout";

export default function Show({
    auth,
    job,
    relatedJobs,
    isSaved,
    hasApplied,
    isCandidate,
    isEmployer,
    isJobClosed,
    deadlinePassed,
    daysLeft,
}) {
    const [saving, setSaving] = useState(false);
    const [applying, setApplying] = useState(false);
    const [saved, setSaved] = useState(isSaved);
    const [applied, setApplied] = useState(hasApplied);

    const handleSaveJob = () => {
        if (!auth.user) { router.visit("/login"); return; }
        setSaving(true);
        router.post(`/jobs/${job.slug}/save`, {}, {
            preserveScroll: true,
            onSuccess: () => setSaved(!saved),
            onFinish: () => setSaving(false),
        });
    };

    const handleApply = () => {
        if (!auth.user) { router.visit("/login"); return; }
        setApplying(true);
        router.post(`/jobs/${job.slug}/apply`, {}, {
            onSuccess: () => setApplied(true),
            onFinish: () => setApplying(false),
        });
    };

    const formatSalary = (min, max, currency) => {
        if (!min && !max) return "Competitive";
        const fmt = new Intl.NumberFormat("en-US", { style: "currency", currency: currency || "USD", minimumFractionDigits: 0 });
        if (min && max) return `${fmt.format(min)} – ${fmt.format(max)}`;
        return min ? `From ${fmt.format(min)}` : `Up to ${fmt.format(max)}`;
    };

    const pageContent = (
        <div className="min-h-screen bg-gray-50 dark:bg-gray-900">
            {/* Standalone nav only for guests */}
            {!isCandidate && !isEmployer && (
                <nav className="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 sticky top-0 z-40">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between h-16 items-center">
                        <Link href="/" className="text-xl font-bold text-indigo-600">Hiring Hall</Link>
                        <div className="flex items-center gap-4">
                            <Link href="/jobs" className="text-sm text-gray-600 dark:text-gray-300 hover:text-indigo-600">Browse Jobs</Link>
                            {auth?.user ? (
                                <Link href="/dashboard" className="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700">Dashboard</Link>
                            ) : (
                                <>
                                    <Link href="/login" className="text-sm text-gray-600 hover:text-indigo-600">Login</Link>
                                    <Link href="/register" className="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700">Sign Up</Link>
                                </>
                            )}
                        </div>
                    </div>
                </nav>
            )}

            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                {/* Breadcrumb */}
                <div className="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-6">
                    <Link href="/jobs" className="hover:text-indigo-600">Browse Jobs</Link>
                    <span>/</span>
                    <span className="text-gray-900 dark:text-white font-medium truncate">{job.title}</span>
                </div>

                <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {/* ── Left: Job Details ── */}
                    <div className="lg:col-span-2 space-y-6">
                        {/* Header Card */}
                        <div className="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                            <div className="flex items-start gap-4 mb-4">
                                <div className="w-16 h-16 rounded-xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center flex-shrink-0 overflow-hidden">
                                    {job.company?.logo_url ? (
                                        <img src={job.company.logo_url} alt={job.company.name} className="w-full h-full object-cover" />
                                    ) : (
                                        <span className="text-2xl font-bold text-gray-400">
                                            {(job.company?.name || 'C').charAt(0)}
                                        </span>
                                    )}
                                </div>
                                <div className="flex-1 min-w-0">
                                    <h1 className="text-2xl font-bold text-gray-900 dark:text-white mb-1">{job.title}</h1>
                                    <div className="flex items-center gap-2 text-gray-600 dark:text-gray-400 text-sm flex-wrap">
                                        <span className="font-medium">{job.company?.name || 'Company'}</span>
                                        {job.company?.verified && <span className="text-green-600 font-semibold">✓ Verified</span>}
                                        <span>·</span>
                                        <span>📍 {job.location || 'Remote'}</span>
                                    </div>
                                    <div className="flex flex-wrap gap-2 mt-3">
                                        <span className="px-3 py-1 bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 rounded-full text-xs font-semibold">
                                            {(job.employment_type || 'full_time').replace('_', ' ').toUpperCase()}
                                        </span>
                                        {job.remote_type && (
                                            <span className="px-3 py-1 bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300 rounded-full text-xs font-semibold">
                                                {job.remote_type.replace('_', ' ')}
                                            </span>
                                        )}
                                        {job.category && (
                                            <span className="px-3 py-1 bg-yellow-100 dark:bg-yellow-900/40 text-yellow-700 dark:text-yellow-300 rounded-full text-xs font-semibold">
                                                {job.category}
                                            </span>
                                        )}
                                    </div>
                                </div>
                            </div>

                            {/* Action Buttons */}
                            {isCandidate && !isJobClosed && (
                                <div className="flex gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                                    {!applied ? (
                                        <button
                                            onClick={handleApply}
                                            disabled={applying}
                                            className="flex-1 bg-indigo-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-indigo-700 disabled:opacity-50 transition"
                                        >
                                            {applying ? 'Applying...' : 'Apply Now'}
                                        </button>
                                    ) : (
                                        <div className="flex-1 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 px-6 py-3 rounded-lg font-semibold text-center border border-green-200 dark:border-green-800">
                                            ✓ Application Submitted
                                        </div>
                                    )}
                                    <button
                                        onClick={handleSaveJob}
                                        disabled={saving}
                                        className={`px-6 py-3 rounded-lg font-semibold border-2 transition ${
                                            saved
                                                ? 'border-indigo-600 text-indigo-600 bg-indigo-50 dark:bg-indigo-900/30'
                                                : 'border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:border-indigo-600'
                                        }`}
                                    >
                                        {saving ? '...' : saved ? '★ Saved' : '☆ Save'}
                                    </button>
                                </div>
                            )}

                            {!auth?.user && (
                                <div className="flex gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                                    <Link href="/login" className="flex-1 text-center bg-indigo-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-indigo-700 transition">
                                        Login to Apply
                                    </Link>
                                </div>
                            )}

                            {isJobClosed && (
                                <div className="mt-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 text-red-700 dark:text-red-400">
                                    <strong>This position is closed.</strong> Applications are no longer being accepted.
                                </div>
                            )}
                        </div>

                        {/* Description */}
                        {job.description && (
                            <div className="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                                <h2 className="text-lg font-bold text-gray-900 dark:text-white mb-4">Job Description</h2>
                                <div className="prose dark:prose-invert max-w-none text-gray-700 dark:text-gray-300 whitespace-pre-wrap text-sm leading-relaxed">
                                    {job.description}
                                </div>
                            </div>
                        )}

                        {/* Requirements */}
                        {job.requirements && (
                            <div className="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                                <h2 className="text-lg font-bold text-gray-900 dark:text-white mb-4">Requirements</h2>
                                <ul className="space-y-2">
                                    {job.requirements.split('\n').filter(r => r.trim()).map((req, idx) => (
                                        <li key={idx} className="flex items-start gap-2 text-sm text-gray-700 dark:text-gray-300">
                                            <svg className="w-4 h-4 text-indigo-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
                                            </svg>
                                            {req}
                                        </li>
                                    ))}
                                </ul>
                            </div>
                        )}

                        {/* Responsibilities */}
                        {job.responsibilities && (
                            <div className="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                                <h2 className="text-lg font-bold text-gray-900 dark:text-white mb-4">Responsibilities</h2>
                                <ul className="space-y-2">
                                    {job.responsibilities.split('\n').filter(r => r.trim()).map((resp, idx) => (
                                        <li key={idx} className="flex items-start gap-2 text-sm text-gray-700 dark:text-gray-300">
                                            <svg className="w-4 h-4 text-indigo-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
                                            </svg>
                                            {resp}
                                        </li>
                                    ))}
                                </ul>
                            </div>
                        )}
                    </div>

                    {/* ── Right: Sidebar ── */}
                    <div className="space-y-6">
                        {/* Job Info */}
                        <div className="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                            <h3 className="text-base font-bold text-gray-900 dark:text-white mb-4">Job Information</h3>
                            <dl className="space-y-3 text-sm">
                                <div>
                                    <dt className="text-gray-500 dark:text-gray-400">Salary</dt>
                                    <dd className="font-semibold text-gray-900 dark:text-white mt-0.5">
                                        {formatSalary(job.salary_min, job.salary_max, job.salary_currency)}
                                    </dd>
                                </div>
                                {job.vacancies && (
                                    <div>
                                        <dt className="text-gray-500 dark:text-gray-400">Vacancies</dt>
                                        <dd className="font-semibold text-gray-900 dark:text-white mt-0.5">{job.vacancies}</dd>
                                    </div>
                                )}
                                {(job.experience_min_years || job.experience_max_years) && (
                                    <div>
                                        <dt className="text-gray-500 dark:text-gray-400">Experience</dt>
                                        <dd className="font-semibold text-gray-900 dark:text-white mt-0.5">
                                            {job.experience_min_years && job.experience_max_years
                                                ? `${job.experience_min_years}–${job.experience_max_years} years`
                                                : job.experience_min_years
                                                  ? `${job.experience_min_years}+ years`
                                                  : `Up to ${job.experience_max_years} years`}
                                        </dd>
                                    </div>
                                )}
                                {job.posted_at && (
                                    <div>
                                        <dt className="text-gray-500 dark:text-gray-400">Posted</dt>
                                        <dd className="font-semibold text-gray-900 dark:text-white mt-0.5">
                                            {new Date(job.posted_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}
                                        </dd>
                                    </div>
                                )}
                                {job.closes_at && !deadlinePassed && (
                                    <div>
                                        <dt className="text-gray-500 dark:text-gray-400">Deadline</dt>
                                        <dd className="font-semibold text-orange-600 mt-0.5">
                                            {daysLeft > 0 ? `${daysLeft} days left` : 'Today'}
                                        </dd>
                                    </div>
                                )}
                            </dl>
                        </div>

                        {/* Related Jobs */}
                        {relatedJobs && relatedJobs.length > 0 && (
                            <div className="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                                <h3 className="text-base font-bold text-gray-900 dark:text-white mb-4">Related Jobs</h3>
                                <div className="space-y-3">
                                    {relatedJobs.map((relJob) => (
                                        <Link
                                            key={relJob.id}
                                            href={`/jobs/${relJob.slug}`}
                                            className="block p-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:border-indigo-400 hover:shadow-sm transition"
                                        >
                                            <div className="font-semibold text-sm text-gray-900 dark:text-white hover:text-indigo-600 truncate">{relJob.title}</div>
                                            <div className="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{relJob.company?.name}</div>
                                            <div className="text-xs text-gray-400 mt-0.5">📍 {relJob.location || 'Remote'}</div>
                                        </Link>
                                    ))}
                                </div>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </div>
    );

    if (isCandidate) {
        return (
            <CandidateLayout user={auth.user}>
                <Head title={`${job.title} — Hiring Hall`} />
                {pageContent}
            </CandidateLayout>
        );
    }

    return (
        <>
            <Head title={`${job.title} — Hiring Hall`} />
            {pageContent}
        </>
    );
}
