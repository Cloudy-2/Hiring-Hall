import { Head, Link, router } from '@inertiajs/react';
import { useState, useMemo } from 'react';
import CandidateLayout from '@/Layouts/CandidateLayout';

export default function SavedJobs({
    auth,
    savedJobs,
    applicationsByJobId = {},
}) {
    const [searchQuery, setSearchQuery] = useState('');
    const [selectedIds, setSelectedIds] = useState([]);
    const [bulkAction, setBulkAction] = useState('');
    const [processing, setProcessing] = useState(false);

    const filteredJobs = useMemo(() => {
        if (!searchQuery.trim()) return savedJobs.data;
        
        const query = searchQuery.toLowerCase();
        return savedJobs.data.filter((saved) => {
            const job = saved.job_posting;
            if (!job) return false;
            
            const title = (job.title || '').toLowerCase();
            const company = (job.company?.name || '').toLowerCase();
            return title.includes(query) || company.includes(query);
        });
    }, [savedJobs.data, searchQuery]);

    const handleSelectAll = (e) => {
        if (e.target.checked) {
            setSelectedIds(filteredJobs.map((saved) => saved.job_posting?.id).filter(Boolean));
        } else {
            setSelectedIds([]);
        }
    };

    const handleSelectOne = (id) => {
        if (selectedIds.includes(id)) {
            setSelectedIds(selectedIds.filter((i) => i !== id));
        } else {
            setSelectedIds([...selectedIds, id]);
        }
    };

    const postJson = async (url, payload) => {
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                ...(token ? { 'X-CSRF-TOKEN': token } : {}),
            },
            body: JSON.stringify(payload),
        });
        return response.json();
    };

    const handleBulkAction = async () => {
        if (!bulkAction) {
            alert('Please select a bulk action.');
            return;
        }
        if (selectedIds.length === 0) {
            alert('Please select at least one job.');
            return;
        }

        if (bulkAction === 'remove') {
            if (!confirm(`Remove ${selectedIds.length} saved job(s)?`)) return;
            
            setProcessing(true);
            try {
                await postJson(route('candidate.saved-jobs.bulk-remove'), { ids: selectedIds });
                router.reload({ only: ['savedJobs', 'applicationsByJobId'] });
                setSelectedIds([]);
                setBulkAction('');
            } catch (error) {
                alert('Failed to remove jobs.');
            } finally {
                setProcessing(false);
            }
        } else if (bulkAction === 'apply') {
            const applyableIds = selectedIds.filter((id) => !applicationsByJobId[id]);
            
            if (applyableIds.length === 0) {
                alert('You have already applied to all selected jobs.');
                return;
            }
            
            if (!confirm(`Apply to ${applyableIds.length} job(s)?`)) return;
            
            setProcessing(true);
            try {
                await postJson(route('candidate.saved-jobs.bulk-apply'), { ids: applyableIds });
                router.reload({ only: ['savedJobs', 'applicationsByJobId'] });
                setSelectedIds([]);
                setBulkAction('');
            } catch (error) {
                alert('Failed to submit applications.');
            } finally {
                setProcessing(false);
            }
        }
    };

    const handleRemoveSaved = async (jobSlug, jobTitle) => {
        if (!confirm(`Remove "${jobTitle}" from saved jobs?`)) return;
        
        router.post(
            `/jobs/${jobSlug}/save`,
            { redirect: 'saved-jobs' },
            {
                preserveScroll: true,
                onSuccess: () => {
                    setSelectedIds((prev) => prev.filter((id) => {
                        const saved = savedJobs.data.find((s) => s.job_posting?.slug === jobSlug);
                        return saved?.job_posting?.id !== id;
                    }));
                },
            }
        );
    };

    const logoBgs = ['#4f46e5', '#0d9488', '#dc2626', '#7c3aed', '#ea580c', '#0284c7'];

    return (
        <CandidateLayout user={auth.user}>
            <Head title="Saved Jobs" />

            <div className="py-6">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {/* Hero */}
                    <div className="bg-gradient-to-r from-purple-600 via-purple-500 to-indigo-600 rounded-xl p-6 mb-6 shadow-lg">
                        <div className="flex flex-wrap items-center justify-between gap-4">
                            <div>
                                <h1 className="text-2xl font-bold text-white mb-1 flex items-center gap-2">
                                    <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z" />
                                    </svg>
                                    Saved Jobs
                                </h1>
                                <p className="text-purple-100 text-sm">
                                    Jobs you've bookmarked for later review
                                </p>
                            </div>
                            <div className="flex flex-wrap gap-2">
                                <Link
                                    href="/jobs"
                                    className="inline-flex items-center gap-2 px-4 py-2 bg-white text-purple-700 rounded-lg font-semibold text-sm hover:bg-purple-50 transition"
                                >
                                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    Browse Jobs
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
                                <svg className="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z" />
                                </svg>
                                Your Saved Jobs
                            </h2>
                            <span className="text-xs text-gray-500">
                                {savedJobs.total} saved
                            </span>
                        </div>

                        {savedJobs.data.length === 0 ? (
                            <div className="text-center py-12">
                                <svg className="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                </svg>
                                <p className="text-gray-500 mb-4">
                                    You haven't saved any jobs yet.
                                </p>
                                <Link
                                    href="/jobs"
                                    className="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700"
                                >
                                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    Find Jobs
                                </Link>
                            </div>
                        ) : (
                            <>
                                {/* Toolbar */}
                                <div className="flex flex-wrap items-center gap-3 mb-4 pb-4 border-b border-gray-100 dark:border-gray-700">
                                    <label className="inline-flex items-center gap-2 text-sm text-gray-600">
                                        <input
                                            type="checkbox"
                                            className="h-4 w-4 rounded border-gray-300"
                                            checked={selectedIds.length > 0 && selectedIds.length === filteredJobs.length}
                                            onChange={handleSelectAll}
                                        />
                                        Select All
                                    </label>
                                    <select
                                        value={bulkAction}
                                        onChange={(e) => setBulkAction(e.target.value)}
                                        className="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                    >
                                        <option value="">Bulk action</option>
                                        <option value="remove">Remove Selected</option>
                                        <option value="apply">Apply to Selected</option>
                                    </select>
                                    <button
                                        type="button"
                                        onClick={handleBulkAction}
                                        disabled={processing}
                                        className="px-4 py-2 bg-purple-600 text-white rounded-lg text-sm font-semibold hover:bg-purple-700 transition disabled:opacity-60"
                                    >
                                        {processing ? 'Processing...' : 'Apply'}
                                    </button>
                                    <div className="relative flex-1 min-w-[200px] max-w-md">
                                        <input
                                            type="text"
                                            value={searchQuery}
                                            onChange={(e) => setSearchQuery(e.target.value)}
                                            placeholder="Search saved jobs..."
                                            className="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                        />
                                        <svg className="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                    {selectedIds.length > 0 && (
                                        <span className="text-xs text-purple-600 font-semibold ml-auto">
                                            {selectedIds.length} selected
                                        </span>
                                    )}
                                </div>

                                {/* Jobs List */}
                                <div className="space-y-2">
                                    {filteredJobs.map((saved, index) => {
                                        const job = saved.job_posting;
                                        if (!job) return null;

                                        const status = applicationsByJobId[job.id];
                                        const bgColor = logoBgs[index % logoBgs.length];

                                        return (
                                            <div
                                                key={saved.id}
                                                className="flex items-center gap-3 p-3 rounded-lg border border-gray-100 dark:border-gray-700 hover:border-purple-200 dark:hover:border-purple-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all"
                                            >
                                                <input
                                                    type="checkbox"
                                                    className="h-4 w-4 rounded border-gray-300"
                                                    checked={selectedIds.includes(job.id)}
                                                    onChange={() => handleSelectOne(job.id)}
                                                />

                                                <div
                                                    className="w-10 h-10 rounded-lg flex items-center justify-center text-white font-bold text-sm flex-shrink-0 overflow-hidden"
                                                    style={{ backgroundColor: bgColor }}
                                                >
                                                    {job.company?.logo_url ? (
                                                        <img
                                                            src={job.company.logo_url}
                                                            alt={job.company.name}
                                                            className="w-full h-full object-cover"
                                                        />
                                                    ) : (
                                                        <span>
                                                            {(job.company?.name || 'C').substring(0, 2).toUpperCase()}
                                                        </span>
                                                    )}
                                                </div>

                                                <div className="flex-1 min-w-0">
                                                    <Link
                                                        href={`/jobs/${job.slug}`}
                                                        className="text-sm font-semibold text-gray-900 dark:text-white hover:text-purple-600 truncate block"
                                                    >
                                                        {job.title}
                                                    </Link>
                                                    <div className="text-xs text-gray-500 flex items-center gap-1 flex-wrap">
                                                        <span>{job.company?.name || 'Company'}</span>
                                                        {job.location && (
                                                            <>
                                                                <span>·</span>
                                                                <span>{job.location}</span>
                                                            </>
                                                        )}
                                                        {saved.saved_at && (
                                                            <>
                                                                <span>·</span>
                                                                <span>
                                                                    Saved {new Date(saved.saved_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })}
                                                                </span>
                                                            </>
                                                        )}
                                                    </div>
                                                </div>

                                                {status && (
                                                    <span className="px-3 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-600">
                                                        {status.replace('_', ' ')}
                                                    </span>
                                                )}

                                                <div className="flex gap-2 flex-shrink-0">
                                                    {status ? (
                                                        <Link
                                                            href="/candidate/applications"
                                                            className="p-2 text-gray-600 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition"
                                                            title="View application"
                                                        >
                                                            <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                            </svg>
                                                        </Link>
                                                    ) : (
                                                        <Link
                                                            href={`/jobs/${job.slug}`}
                                                            className="p-2 text-white bg-purple-600 hover:bg-purple-700 rounded-lg transition"
                                                            title="Apply now"
                                                        >
                                                            <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                            </svg>
                                                        </Link>
                                                    )}
                                                    <button
                                                        type="button"
                                                        onClick={() => handleRemoveSaved(job.slug, job.title)}
                                                        className="p-2 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition"
                                                        title="Remove"
                                                    >
                                                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        );
                                    })}
                                </div>

                                {/* Pagination */}
                                {savedJobs.links && savedJobs.links.length > 3 && (
                                    <div className="mt-6 flex items-center justify-between text-sm text-gray-600 dark:text-gray-400">
                                        <div>
                                            Showing {savedJobs.from} to {savedJobs.to} of {savedJobs.total} results
                                        </div>
                                        <div className="flex gap-2">
                                            {savedJobs.links.map((link, idx) => (
                                                <Link
                                                    key={idx}
                                                    href={link.url || '#'}
                                                    className={`px-3 py-1 rounded-lg ${
                                                        link.active
                                                            ? 'bg-purple-600 text-white'
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
