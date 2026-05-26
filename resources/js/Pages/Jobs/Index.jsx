import { Head, Link, router } from "@inertiajs/react";
import { useState } from "react";
import CandidateLayout from "@/Layouts/CandidateLayout";

export default function JobsIndex({ jobs, filters, filterOptions, hasMore, total, auth }) {
    const [searchKeyword, setSearchKeyword] = useState(filters.keyword || "");
    const [searchLocation, setSearchLocation] = useState(filters.location || "");

    const isCandidate = auth?.user?.role === 'applicant' || auth?.user?.role === 'candidate';

    const handleSearch = (e) => {
        e.preventDefault();
        router.get("/jobs", { keyword: searchKeyword, location: searchLocation }, { preserveState: true, preserveScroll: true });
    };

    const formatSalary = (job) => {
        if (job.salary_min && job.salary_max) return `${job.salary_currency} ${job.salary_min.toLocaleString()} - ${job.salary_max.toLocaleString()}`;
        if (job.salary_min) return `${job.salary_currency} ${job.salary_min.toLocaleString()}+`;
        return "Competitive";
    };

    const formatEmploymentType = (type) => {
        const types = { full_time: "Full Time", part_time: "Part Time", contract: "Contract", freelance: "Freelance" };
        return types[type] || type;
    };

    const pageContent = (
        <div className="min-h-screen bg-gray-50 dark:bg-gray-900">
            {/* Standalone navbar only for guests/non-candidates */}
            {!isCandidate && (
                <nav className="bg-white border-b border-gray-200 sticky top-0 z-50">
                    <div className="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">
                        <Link href="/" className="flex items-center gap-3">
                            <div className="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center">
                                <span className="text-white font-bold text-lg">H</span>
                            </div>
                            <span className="text-xl font-bold text-gray-900">Hiring Hall</span>
                        </Link>
                        <div className="flex items-center gap-2">
                            {auth?.user ? (
                                <Link href="/dashboard" className="px-5 py-2 bg-gray-900 text-white rounded-lg font-semibold hover:bg-gray-800 transition text-sm">Dashboard</Link>
                            ) : (
                                <>
                                    <Link href="/login" className="px-5 py-2 text-gray-700 hover:text-gray-900 font-semibold transition text-sm">Sign In</Link>
                                    <Link href="/register" className="px-5 py-2 bg-gray-900 text-white rounded-lg font-semibold hover:bg-gray-800 transition text-sm">Get Started</Link>
                                </>
                            )}
                        </div>
                    </div>
                </nav>
            )}

            {/* Hero / Search */}
            <div className="bg-gradient-to-br from-gray-900 to-gray-800 text-white py-12">
                <div className="max-w-7xl mx-auto px-6">
                    <h1 className="text-4xl font-extrabold mb-2">Browse Jobs</h1>
                    <p className="text-xl text-gray-300 mb-8">
                        Find your next opportunity from {total} open positions
                    </p>
                    <form onSubmit={handleSearch} className="bg-white rounded-xl p-4 shadow-lg">
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <input
                                type="text"
                                placeholder="Job title, keywords, or company"
                                value={searchKeyword}
                                onChange={(e) => setSearchKeyword(e.target.value)}
                                className="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent text-gray-900"
                            />
                            <input
                                type="text"
                                placeholder="Location"
                                value={searchLocation}
                                onChange={(e) => setSearchLocation(e.target.value)}
                                className="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent text-gray-900"
                            />
                            <button type="submit" className="px-6 py-3 bg-yellow-500 text-gray-900 rounded-lg font-bold hover:bg-yellow-400 transition">
                                Search Jobs
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {/* Jobs Grid */}
            <div className="max-w-7xl mx-auto px-6 py-10">
                {jobs.data.length === 0 ? (
                    <div className="text-center py-16">
                        <div className="text-6xl mb-4">🔍</div>
                        <h2 className="text-2xl font-bold text-gray-900 dark:text-white mb-2">No jobs found</h2>
                        <p className="text-gray-600 dark:text-gray-400">Try adjusting your search criteria</p>
                    </div>
                ) : (
                    <>
                        <div className="mb-6 text-sm text-gray-600 dark:text-gray-400">
                            Showing {jobs.data.length} of {total} jobs
                        </div>
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-5">
                            {jobs.data.map((job) => (
                                <Link
                                    key={job.id}
                                    href={`/jobs/${job.slug}`}
                                    className="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm hover:shadow-md transition border border-gray-200 dark:border-gray-700 group"
                                >
                                    <div className="flex items-start gap-4">
                                        <div className="w-14 h-14 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center flex-shrink-0 overflow-hidden">
                                            {job.logo ? (
                                                <img src={job.logo} alt={job.company} className="w-full h-full object-cover" />
                                            ) : (
                                                <span className="text-xl font-bold text-gray-400">{job.company.charAt(0)}</span>
                                            )}
                                        </div>
                                        <div className="flex-1 min-w-0">
                                            <h3 className="text-lg font-bold text-gray-900 dark:text-white mb-1 group-hover:text-yellow-600 transition truncate">
                                                {job.role}
                                            </h3>
                                            <div className="flex items-center gap-2 mb-2">
                                                <span className="text-sm text-gray-700 dark:text-gray-300 font-medium">{job.company}</span>
                                                {job.verified && <span className="text-blue-500 text-xs" title="Verified">✓</span>}
                                            </div>
                                            <div className="flex flex-wrap gap-2 text-xs text-gray-500 dark:text-gray-400 mb-3">
                                                <span>📍 {job.location}</span>
                                                <span>💼 {formatEmploymentType(job.employment_type)}</span>
                                                <span>💰 {formatSalary(job)}</span>
                                            </div>
                                            <div className="flex flex-wrap gap-2">
                                                {job.category && (
                                                    <span className="px-2 py-0.5 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">{job.category}</span>
                                                )}
                                                {job.vacancies > 1 && (
                                                    <span className="px-2 py-0.5 bg-green-100 text-green-800 rounded-full text-xs font-medium">{job.vacancies} openings</span>
                                                )}
                                            </div>
                                        </div>
                                        {isCandidate && (
                                            <button
                                                type="button"
                                                onClick={(e) => e.preventDefault()}
                                                className={`p-2 rounded-lg transition flex-shrink-0 ${job.is_saved ? "text-yellow-500 bg-yellow-50" : "text-gray-400 hover:text-yellow-500 hover:bg-yellow-50"}`}
                                                title={job.is_saved ? "Saved" : "Save job"}
                                            >
                                                {job.is_saved ? "★" : "☆"}
                                            </button>
                                        )}
                                    </div>
                                </Link>
                            ))}
                        </div>

                        {/* Pagination */}
                        {jobs.links && jobs.links.length > 3 && (
                            <div className="mt-8 flex justify-center gap-2 flex-wrap">
                                {jobs.links.map((link, index) => (
                                    <Link
                                        key={index}
                                        href={link.url || "#"}
                                        preserveState
                                        preserveScroll
                                        className={`px-4 py-2 rounded-lg font-medium transition text-sm ${
                                            link.active
                                                ? "bg-yellow-500 text-gray-900"
                                                : link.url
                                                  ? "bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 border border-gray-300 dark:border-gray-600"
                                                  : "bg-gray-100 dark:bg-gray-700 text-gray-400 cursor-not-allowed"
                                        }`}
                                        dangerouslySetInnerHTML={{ __html: link.label }}
                                    />
                                ))}
                            </div>
                        )}
                    </>
                )}
            </div>

            {!isCandidate && (
                <footer className="bg-gray-100 dark:bg-gray-800 py-10 mt-10">
                    <div className="max-w-7xl mx-auto px-6 text-center text-gray-600 dark:text-gray-400 text-sm">
                        <p>&copy; 2024 Hiring Hall by Hill Business Consulting Services. All rights reserved.</p>
                    </div>
                </footer>
            )}
        </div>
    );

    if (isCandidate) {
        return (
            <CandidateLayout user={auth.user}>
                <Head title="Browse Jobs — Hiring Hall" />
                {pageContent}
            </CandidateLayout>
        );
    }

    return (
        <>
            <Head title="Browse Jobs — Hiring Hall" />
            {pageContent}
        </>
    );
}
