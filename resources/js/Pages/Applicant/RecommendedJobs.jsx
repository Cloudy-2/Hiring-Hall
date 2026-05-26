import { Head, Link } from '@inertiajs/react';
import { useEffect, useMemo, useState } from 'react';
import CandidateLayout from '@/Layouts/CandidateLayout';

export default function RecommendedJobs({
    auth,
    recommendedJobs = [],
    savedJobs,
    applicationsByJobId = {},
    savedJobIds = [],
    allLocations = [],
    allJobTypes = [],
}) {
    const [searchQuery, setSearchQuery] = useState('');
    const [locationFilter, setLocationFilter] = useState('');
    const [typeFilter, setTypeFilter] = useState('');
    const [sortBy, setSortBy] = useState('default');

    const [savedSearch, setSavedSearch] = useState('');
    const [selectedSavedIds, setSelectedSavedIds] = useState([]);
    const [bulkAction, setBulkAction] = useState('');
    const [savedList, setSavedList] = useState(savedJobs?.data || []);
    const [savedIds, setSavedIds] = useState(savedJobIds || []);
    const [appliedByJobId, setAppliedByJobId] = useState(
        applicationsByJobId || {},
    );
    const [bulkSubmitting, setBulkSubmitting] = useState(false);

    useEffect(() => {
        setSavedList(savedJobs?.data || []);
    }, [savedJobs?.data]);

    useEffect(() => {
        setSavedIds(savedJobIds || []);
    }, [savedJobIds]);

    useEffect(() => {
        setAppliedByJobId(applicationsByJobId || {});
    }, [applicationsByJobId]);

    const jobLocations = useMemo(() => {
        if (allLocations && allLocations.length) {
            return allLocations;
        }
        const list = recommendedJobs
            .map((job) => job.location)
            .filter(Boolean);
        return Array.from(new Set(list)).sort();
    }, [allLocations, recommendedJobs]);

    const jobTypes = useMemo(() => {
        if (allJobTypes && allJobTypes.length) {
            return allJobTypes;
        }
        const list = recommendedJobs
            .map((job) => job.employment_type)
            .filter(Boolean);
        return Array.from(new Set(list)).sort();
    }, [allJobTypes, recommendedJobs]);

    const filteredJobs = useMemo(() => {
        let list = [...recommendedJobs];
        const query = searchQuery.trim().toLowerCase();

        if (query) {
            list = list.filter((job) => {
                const title = (job.title || '').toLowerCase();
                const company = (job.company?.name || '').toLowerCase();
                return title.includes(query) || company.includes(query);
            });
        }

        if (locationFilter) {
            list = list.filter(
                (job) =>
                    (job.location || '').toLowerCase() ===
                    locationFilter.toLowerCase(),
            );
        }

        if (typeFilter) {
            list = list.filter(
                (job) =>
                    (job.employment_type || '').toLowerCase() ===
                    typeFilter.toLowerCase(),
            );
        }

        if (sortBy === 'newest') {
            list.sort(
                (a, b) =>
                    new Date(b.posted_at || 0) -
                    new Date(a.posted_at || 0),
            );
        } else if (sortBy === 'applicants') {
            list.sort(
                (a, b) =>
                    (b.applications_count || 0) -
                    (a.applications_count || 0),
            );
        }

        return list;
    }, [
        recommendedJobs,
        searchQuery,
        locationFilter,
        typeFilter,
        sortBy,
    ]);

    const savedItems = useMemo(() => {
        const query = savedSearch.trim().toLowerCase();
        if (!query) return savedList;
        return savedList.filter((saved) => {
            const job = getSavedJob(saved);
            if (!job) return false;
            const title = (job.title || '').toLowerCase();
            const company = (job.company?.name || '').toLowerCase();
            return title.includes(query) || company.includes(query);
        });
    }, [savedList, savedSearch]);

    const handleSelectAllSaved = (checked) => {
        if (!checked) {
            setSelectedSavedIds([]);
            return;
        }
        const ids = savedItems
            .map((saved) => getSavedJob(saved)?.id)
            .filter(Boolean);
        setSelectedSavedIds(ids);
    };

    const toggleSavedSelection = (id) => {
        if (selectedSavedIds.includes(id)) {
            setSelectedSavedIds(selectedSavedIds.filter((item) => item !== id));
        } else {
            setSelectedSavedIds([...selectedSavedIds, id]);
        }
    };

    const postJson = async (url, payload) => {
        if (window.axios) {
            const response = await window.axios.post(url, payload);
            return response.data;
        }
        const token = document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute('content');
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
        if (selectedSavedIds.length === 0) {
            alert('Please select at least one job.');
            return;
        }

        if (bulkAction === 'remove') {
            const confirmed = window.confirm(
                `Remove ${selectedSavedIds.length} saved job(s)?`,
            );
            if (!confirmed) return;
            setBulkSubmitting(true);
            try {
                await postJson(route('candidate.saved-jobs.bulk-remove'), {
                    ids: selectedSavedIds,
                });
                setSavedList((prev) =>
                    prev.filter(
                        (saved) =>
                            !selectedSavedIds.includes(getSavedJob(saved)?.id),
                    ),
                );
                setSavedIds((prev) =>
                    prev.filter((id) => !selectedSavedIds.includes(id)),
                );
                setSelectedSavedIds([]);
                setBulkAction('');
            } finally {
                setBulkSubmitting(false);
            }
            return;
        }

        if (bulkAction === 'apply') {
            const applyIds = selectedSavedIds.filter(
                (id) => !appliedByJobId[id],
            );
            if (applyIds.length === 0) {
                alert('You already applied to all selected jobs.');
                return;
            }
            const confirmed = window.confirm(
                `Apply to ${applyIds.length} job(s)?`,
            );
            if (!confirmed) return;
            setBulkSubmitting(true);
            try {
                await postJson(route('candidate.saved-jobs.bulk-apply'), {
                    ids: applyIds,
                });
                setAppliedByJobId((prev) => {
                    const updated = { ...prev };
                    applyIds.forEach((id) => {
                        updated[id] = 'applied';
                    });
                    return updated;
                });
                setSelectedSavedIds([]);
                setBulkAction('');
            } finally {
                setBulkSubmitting(false);
            }
        }
    };

    const handleRemoveSaved = async (jobId, jobSlug, jobTitle) => {
        const confirmed = window.confirm(
            `Remove "${jobTitle}" from saved jobs?`,
        );
        if (!confirmed) return;
        await postJson(`/jobs/${jobSlug}/save`, { redirect: 'none' });
        setSavedList((prev) =>
            prev.filter((saved) => getSavedJob(saved)?.id !== jobId),
        );
        setSavedIds((prev) => prev.filter((id) => id !== jobId));
        setSelectedSavedIds((prev) => prev.filter((id) => id !== jobId));
    };

    return (
        <CandidateLayout user={auth.user}>
            <Head title="Recommended Jobs" />

            <div className="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 grid grid-cols-12 gap-5">
                <div className="col-span-12">
                    <div className="bg-gradient-to-r from-indigo-600 via-indigo-500 to-purple-600 rounded-xl p-6 flex flex-wrap items-center justify-between gap-4 text-white">
                        <div>
                            <h1 className="text-2xl font-bold mb-1">
                                Recommended Jobs
                            </h1>
                            <p className="text-sm text-indigo-100">
                                Personalized picks based on your profile and
                                activity.
                            </p>
                        </div>
                        <div className="flex flex-wrap gap-2">
                            <Link
                                href="/candidate/dashboard"
                                className="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white/20 text-white text-sm font-semibold hover:bg-white/30"
                            >
                                Dashboard
                            </Link>
                            <Link
                                href="/candidate/applications"
                                className="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white/20 text-white text-sm font-semibold hover:bg-white/30"
                            >
                                My Applications
                            </Link>
                        </div>
                    </div>
                </div>

                <div className="col-span-12">
                    <div className="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-4 flex flex-wrap gap-3">
                        <div className="relative flex-1 min-w-[200px]">
                            <input
                                type="text"
                                value={searchQuery}
                                onChange={(e) => setSearchQuery(e.target.value)}
                                placeholder="Search jobs..."
                                className="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                            />
                            <span className="absolute left-3 top-2.5 text-gray-400 text-sm">
                                <i className="ri-search-line" />
                            </span>
                        </div>
                        <select
                            value={locationFilter}
                            onChange={(e) => setLocationFilter(e.target.value)}
                            className="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                        >
                            <option value="">All Locations</option>
                            {jobLocations.map((loc) => (
                                <option key={loc} value={loc}>
                                    {loc}
                                </option>
                            ))}
                        </select>
                        <select
                            value={typeFilter}
                            onChange={(e) => setTypeFilter(e.target.value)}
                            className="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                        >
                            <option value="">All Job Types</option>
                            {jobTypes.map((type) => (
                                <option key={type} value={type}>
                                    {formatEmploymentType(type)}
                                </option>
                            ))}
                        </select>
                        <select
                            value={sortBy}
                            onChange={(e) => setSortBy(e.target.value)}
                            className="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                        >
                            <option value="default">Sort: Recommended</option>
                            <option value="newest">Sort: Newest</option>
                            <option value="applicants">
                                Sort: Most Applicants
                            </option>
                        </select>
                    </div>
                </div>

                <div className="col-span-12">
                    {filteredJobs.length === 0 ? (
                        <div className="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-10 text-center">
                            <p className="text-gray-500 mb-2">
                                No recommended jobs found.
                            </p>
                            <button
                                type="button"
                                onClick={() => {
                                    setSearchQuery('');
                                    setLocationFilter('');
                                    setTypeFilter('');
                                    setSortBy('default');
                                }}
                                className="text-indigo-600 text-sm font-semibold"
                            >
                                Clear filters
                            </button>
                        </div>
                    ) : (
                        <div className="grid grid-cols-12 gap-5">
                            {filteredJobs.map((job, index) => {
                                const postedAt = job.posted_at
                                    ? new Date(job.posted_at)
                                    : null;
                                const isNew = postedAt
                                    ? Date.now() - postedAt.getTime() <
                                      1000 * 60 * 60 * 24 * 3
                                    : false;
                                const isHot = index < 3;
                                const applicantCount =
                                    job.applications_count || 0;
                                const isHighMatch =
                                    !isHot && !isNew && applicantCount > 5;
                                const initials = getCompanyInitials(
                                    job.company?.name,
                                );
                                return (
                                    <div
                                        key={job.id}
                                        className="xl:col-span-3 md:col-span-6 col-span-12"
                                    >
                                        <div className="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5 h-full flex flex-col hover:shadow-lg transition">
                                            {(isHot || isNew || isHighMatch) && (
                                                <span className="text-xs font-semibold px-2 py-1 rounded-full bg-indigo-50 text-indigo-600 self-start mb-3">
                                                    {isHot
                                                        ? 'Recommended'
                                                        : isNew
                                                          ? 'New'
                                                          : 'High Match'}
                                                </span>
                                            )}
                                            <div className="flex items-start gap-3 mb-3">
                                                <div className="w-11 h-11 rounded-lg bg-indigo-100 flex items-center justify-center overflow-hidden">
                                                    {job.company?.logo_url ? (
                                                        <img
                                                            src={
                                                                job.company
                                                                    ?.logo_url
                                                            }
                                                            alt=""
                                                            className="w-full h-full object-cover"
                                                        />
                                                    ) : (
                                                        <span className="text-indigo-700 font-bold text-sm">
                                                            {initials}
                                                        </span>
                                                    )}
                                                </div>
                                                <div className="min-w-0">
                                                    <p className="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                                        {job.company?.name ||
                                                            'Company'}
                                                    </p>
                                                    <div className="text-xs text-gray-500 flex flex-wrap gap-2">
                                                        {job.location && (
                                                            <span>
                                                                <i className="ri-map-pin-2-line" />{' '}
                                                                {job.location}
                                                            </span>
                                                        )}
                                                    </div>
                                                </div>
                                            </div>
                                            <h5 className="text-sm font-bold text-gray-900 dark:text-white mb-2">
                                                <Link
                                                    href={`/jobs/${job.slug}`}
                                                    className="hover:text-indigo-600"
                                                >
                                                    {job.title}
                                                </Link>
                                            </h5>
                                            <div className="text-xs text-gray-500 flex flex-wrap gap-2 mb-3">
                                                {applicantCount > 0 && (
                                                    <span>
                                                        {applicantCount} applicant
                                                        {applicantCount !== 1
                                                            ? 's'
                                                            : ''}
                                                    </span>
                                                )}
                                                {postedAt && (
                                                    <span>
                                                        Posted{' '}
                                                        {formatRelativeTime(
                                                            postedAt,
                                                        )}
                                                    </span>
                                                )}
                                            </div>
                                            <div className="flex flex-wrap gap-2 mb-4">
                                                {job.employment_type && (
                                                    <span className="text-xs px-2 py-1 rounded-full bg-indigo-50 text-indigo-600">
                                                        {formatEmploymentType(
                                                            job.employment_type,
                                                        )}
                                                    </span>
                                                )}
                                                {job.category && (
                                                    <span className="text-xs px-2 py-1 rounded-full bg-emerald-50 text-emerald-600">
                                                        {formatEmploymentType(
                                                            job.category,
                                                        )}
                                                    </span>
                                                )}
                                            </div>
                                            <div className="mt-auto">
                                                <Link
                                                    href={`/jobs/${job.slug}`}
                                                    className="inline-flex items-center justify-center w-full px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700"
                                                >
                                                    View and Apply
                                                </Link>
                                            </div>
                                        </div>
                                    </div>
                                );
                            })}
                        </div>
                    )}
                </div>

                <div className="col-span-12 mt-4">
                    <div className="flex items-center gap-3">
                        <div className="flex-1 h-px bg-gray-200" />
                        <span className="text-xs font-semibold text-gray-400 uppercase tracking-wider">
                            Saved Jobs
                        </span>
                        <div className="flex-1 h-px bg-gray-200" />
                    </div>
                </div>

                <div className="col-span-12">
                    <div className="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
                        <div className="flex items-center justify-between mb-4">
                            <div>
                                <h3 className="text-lg font-bold text-gray-900 dark:text-white">
                                    Saved Jobs
                                </h3>
                                <p className="text-sm text-gray-500">
                                    Jobs you bookmarked for later.
                                </p>
                            </div>
                        </div>

                        {savedItems.length === 0 ? (
                            <div className="text-center py-8">
                                <p className="text-gray-500 mb-3">
                                    No saved jobs yet.
                                </p>
                                <Link
                                    href="/jobs"
                                    className="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700"
                                >
                                    Browse Jobs
                                </Link>
                            </div>
                        ) : (
                            <>
                                <div className="flex flex-wrap items-center gap-3 mb-4">
                                    <label className="inline-flex items-center gap-2 text-sm text-gray-600">
                                        <input
                                            type="checkbox"
                                            className="h-4 w-4 rounded border-gray-300"
                                            checked={
                                                selectedSavedIds.length > 0 &&
                                                selectedSavedIds.length ===
                                                    savedItems.length
                                            }
                                            onChange={(e) =>
                                                handleSelectAllSaved(
                                                    e.target.checked,
                                                )
                                            }
                                        />
                                        Select All
                                    </label>
                                    <select
                                        value={bulkAction}
                                        onChange={(e) =>
                                            setBulkAction(e.target.value)
                                        }
                                        className="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                    >
                                        <option value="">Bulk action</option>
                                        <option value="remove">
                                            Remove Selected
                                        </option>
                                        <option value="apply">
                                            Apply to Selected
                                        </option>
                                    </select>
                                    <button
                                        type="button"
                                        onClick={handleBulkAction}
                                        disabled={bulkSubmitting}
                                        className="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 disabled:opacity-60"
                                    >
                                        {bulkSubmitting
                                            ? 'Working...'
                                            : 'Apply Action'}
                                    </button>
                                    <div className="relative flex-1 min-w-[200px] max-w-xs">
                                        <input
                                            type="text"
                                            value={savedSearch}
                                            onChange={(e) =>
                                                setSavedSearch(e.target.value)
                                            }
                                            placeholder="Search saved jobs..."
                                            className="w-full pl-9 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                        />
                                        <span className="absolute left-3 top-2.5 text-gray-400 text-sm">
                                            <i className="ri-search-line" />
                                        </span>
                                    </div>
                                    {selectedSavedIds.length > 0 && (
                                        <span className="text-xs text-indigo-600 font-semibold">
                                            {selectedSavedIds.length} selected
                                        </span>
                                    )}
                                </div>

                                <div className="space-y-3">
                                    {savedItems.map((saved) => {
                                        const job = getSavedJob(saved);
                                        if (!job) return null;
                                        const status = appliedByJobId[job.id];
                                        return (
                                            <div
                                                key={`saved-${job.id}`}
                                                className="flex items-center gap-3 p-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50"
                                            >
                                                <input
                                                    type="checkbox"
                                                    className="h-4 w-4 rounded border-gray-300"
                                                    checked={selectedSavedIds.includes(
                                                        job.id,
                                                    )}
                                                    onChange={() =>
                                                        toggleSavedSelection(job.id)
                                                    }
                                                />
                                                <div className="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center overflow-hidden">
                                                    {job.company?.logo_url ? (
                                                        <img
                                                            src={
                                                                job.company
                                                                    ?.logo_url
                                                            }
                                                            alt=""
                                                            className="w-full h-full object-cover"
                                                        />
                                                    ) : (
                                                        <span className="text-indigo-700 font-bold text-xs">
                                                            {getCompanyInitials(
                                                                job.company
                                                                    ?.name,
                                                            )}
                                                        </span>
                                                    )}
                                                </div>
                                                <div className="flex-1 min-w-0">
                                                    <Link
                                                        href={`/jobs/${job.slug}`}
                                                        className="font-semibold text-gray-900 dark:text-white hover:text-indigo-600 block truncate"
                                                    >
                                                        {job.title}
                                                    </Link>
                                                    <div className="text-xs text-gray-500 flex flex-wrap gap-2">
                                                        <span>
                                                            {job.company?.name ||
                                                                'Company'}
                                                        </span>
                                                        {job.location && (
                                                            <span>
                                                                <i className="ri-map-pin-2-line" />{' '}
                                                                {job.location}
                                                            </span>
                                                        )}
                                                        {status && (
                                                            <span className="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-600 text-[11px] font-semibold">
                                                                {formatEmploymentType(
                                                                    status,
                                                                )}
                                                            </span>
                                                        )}
                                                    </div>
                                                </div>
                                                <div className="flex items-center gap-2">
                                                    {status ? (
                                                        <Link
                                                            href="/candidate/applications"
                                                            className="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-gray-200 text-indigo-600"
                                                            title="View application"
                                                        >
                                                            <i className="ri-eye-line" />
                                                        </Link>
                                                    ) : (
                                                        <Link
                                                            href={`/jobs/${job.slug}`}
                                                            className="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-600 text-white"
                                                            title="Apply now"
                                                        >
                                                            <i className="ri-briefcase-line" />
                                                        </Link>
                                                    )}
                                                    <button
                                                        type="button"
                                                        onClick={() =>
                                                            handleRemoveSaved(
                                                                job.id,
                                                                job.slug,
                                                                job.title,
                                                            )
                                                        }
                                                        className="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-red-200 bg-red-50 text-red-600"
                                                        title="Remove"
                                                    >
                                                        <i className="ri-delete-bin-6-line" />
                                                    </button>
                                                </div>
                                            </div>
                                        );
                                    })}
                                </div>

                                {savedJobs?.links && (
                                    <div className="mt-4 flex items-center justify-between text-sm text-gray-600 dark:text-gray-400">
                                        <div>
                                            Showing {savedJobs.from} to {savedJobs.to}{' '}
                                            of {savedJobs.total} results
                                        </div>
                                        <div className="flex gap-2">
                                            {savedJobs.links.map((link, idx) => (
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
                                                    dangerouslySetInnerHTML={{
                                                        __html: link.label,
                                                    }}
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

function formatEmploymentType(value) {
    if (!value) return '';
    return value
        .toString()
        .replace(/_/g, ' ')
        .replace(/\b\w/g, (c) => c.toUpperCase());
}

function getCompanyInitials(name) {
    if (!name) return 'C';
    const parts = name.split(' ').filter(Boolean);
    const first = parts[0]?.[0] || 'C';
    const second = parts[1]?.[0] || '';
    return `${first}${second}`.toUpperCase();
}

function getSavedJob(saved) {
    if (!saved) return null;
    return saved.job_posting || saved.jobPosting || saved.job || null;
}

function formatRelativeTime(date) {
    if (!date) return '';
    const diffMs = Date.now() - date.getTime();
    const diffMinutes = Math.floor(diffMs / 60000);
    if (diffMinutes < 60) return `${diffMinutes}m ago`;
    const diffHours = Math.floor(diffMinutes / 60);
    if (diffHours < 24) return `${diffHours}h ago`;
    const diffDays = Math.floor(diffHours / 24);
    if (diffDays < 7) return `${diffDays}d ago`;
    const diffWeeks = Math.floor(diffDays / 7);
    if (diffWeeks < 4) return `${diffWeeks}w ago`;
    const diffMonths = Math.floor(diffDays / 30);
    if (diffMonths < 12) return `${diffMonths}mo ago`;
    const diffYears = Math.floor(diffDays / 365);
    return `${diffYears}y ago`;
}
