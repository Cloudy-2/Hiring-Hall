import { Head, Link, router } from "@inertiajs/react";
import { useState } from "react";

export default function JobsIndex({
    jobs,
    filters,
    filterOptions,
    hasMore,
    total,
    auth,
}) {
    const [searchKeyword, setSearchKeyword] = useState(filters.keyword || "");
    const [searchLocation, setSearchLocation] = useState(
        filters.location || "",
    );

    const handleSearch = (e) => {
        e.preventDefault();
        router.get(
            "/jobs",
            {
                keyword: searchKeyword,
                location: searchLocation,
            },
            {
                preserveState: true,
                preserveScroll: true,
            },
        );
    };

    const formatSalary = (job) => {
        if (job.salary_min && job.salary_max) {
            return `${job.salary_currency} ${job.salary_min.toLocaleString()} - ${job.salary_max.toLocaleString()}`;
        } else if (job.salary_min) {
            return `${job.salary_currency} ${job.salary_min.toLocaleString()}+`;
        }
        return "Competitive";
    };

    const formatEmploymentType = (type) => {
        const types = {
            full_time: "Full Time",
            part_time: "Part Time",
            contract: "Contract",
            freelance: "Freelance",
        };
        return types[type] || type;
    };

    return (
        <>
            <Head title="Browse Jobs — Hiring Hall" />

            <div className="min-h-screen bg-gray-50">
                {/* Navbar */}
                <nav className="bg-white border-b border-gray-200 sticky top-0 z-50">
                    <div className="max-w-7xl mx-auto px-6 h-18 flex items-center justify-between">
                        <Link href="/" className="flex items-center gap-3">
                            <div className="w-11 h-11 bg-yellow-500 rounded-lg flex items-center justify-center">
                                <span className="text-white font-bold text-xl">
                                    H
                                </span>
                            </div>
                            <span className="text-xl font-bold text-gray-900">
                                Hiring Hall
                            </span>
                        </Link>

                        <div className="flex items-center gap-2">
                            {auth?.user ? (
                                <Link
                                    href="/dashboard"
                                    className="px-6 py-2.5 bg-gray-900 text-white rounded-lg font-semibold hover:bg-gray-800 transition"
                                >
                                    Dashboard
                                </Link>
                            ) : (
                                <>
                                    <Link
                                        href="/login"
                                        className="px-6 py-2.5 text-gray-700 hover:text-gray-900 font-semibold transition"
                                    >
                                        Sign In
                                    </Link>
                                    <Link
                                        href="/register"
                                        className="px-6 py-2.5 bg-gray-900 text-white rounded-lg font-semibold hover:bg-gray-800 transition"
                                    >
                                        Get Started
                                    </Link>
                                </>
                            )}
                        </div>
                    </div>
                </nav>

                {/* Header */}
                <div className="bg-gradient-to-br from-gray-900 to-gray-800 text-white py-12">
                    <div className="max-w-7xl mx-auto px-6">
                        <h1 className="text-4xl font-extrabold mb-4">
                            Browse Jobs
                        </h1>
                        <p className="text-xl text-gray-300 mb-8">
                            Find your next opportunity from {total} open
                            positions
                        </p>

                        {/* Search Form */}
                        <form
                            onSubmit={handleSearch}
                            className="bg-white rounded-xl p-4 shadow-lg"
                        >
                            <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <input
                                    type="text"
                                    placeholder="Job title, keywords, or company"
                                    value={searchKeyword}
                                    onChange={(e) =>
                                        setSearchKeyword(e.target.value)
                                    }
                                    className="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent text-gray-900"
                                />
                                <input
                                    type="text"
                                    placeholder="Location"
                                    value={searchLocation}
                                    onChange={(e) =>
                                        setSearchLocation(e.target.value)
                                    }
                                    className="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent text-gray-900"
                                />
                                <button
                                    type="submit"
                                    className="px-6 py-3 bg-yellow-500 text-gray-900 rounded-lg font-bold hover:bg-yellow-400 transition"
                                >
                                    Search Jobs
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {/* Jobs Grid */}
                <div className="max-w-7xl mx-auto px-6 py-12">
                    {jobs.data.length === 0 ? (
                        <div className="text-center py-16">
                            <div className="text-6xl mb-4">🔍</div>
                            <h2 className="text-2xl font-bold text-gray-900 mb-2">
                                No jobs found
                            </h2>
                            <p className="text-gray-600">
                                Try adjusting your search criteria
                            </p>
                        </div>
                    ) : (
                        <>
                            <div className="mb-6 text-gray-600">
                                Showing {jobs.data.length} of {total} jobs
                            </div>

                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {jobs.data.map((job) => (
                                    <Link
                                        key={job.id}
                                        href={`/jobs/${job.slug}`}
                                        className="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition border border-gray-200 group"
                                    >
                                        <div className="flex items-start gap-4">
                                            {/* Company Logo */}
                                            <div className="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                {job.logo ? (
                                                    <img
                                                        src={job.logo}
                                                        alt={job.company}
                                                        className="w-full h-full object-cover rounded-lg"
                                                    />
                                                ) : (
                                                    <span className="text-2xl font-bold text-gray-400">
                                                        {job.company.charAt(0)}
                                                    </span>
                                                )}
                                            </div>

                                            <div className="flex-1 min-w-0">
                                                {/* Job Title */}
                                                <h3 className="text-xl font-bold text-gray-900 mb-1 group-hover:text-yellow-600 transition">
                                                    {job.role}
                                                </h3>

                                                {/* Company Name */}
                                                <div className="flex items-center gap-2 mb-3">
                                                    <span className="text-gray-700 font-medium">
                                                        {job.company}
                                                    </span>
                                                    {job.verified && (
                                                        <span
                                                            className="text-blue-500"
                                                            title="Verified Company"
                                                        >
                                                            ✓
                                                        </span>
                                                    )}
                                                </div>

                                                {/* Job Details */}
                                                <div className="flex flex-wrap gap-3 text-sm text-gray-600 mb-3">
                                                    <span className="flex items-center gap-1">
                                                        📍 {job.location}
                                                    </span>
                                                    <span className="flex items-center gap-1">
                                                        💼{" "}
                                                        {formatEmploymentType(
                                                            job.employment_type,
                                                        )}
                                                    </span>
                                                    <span className="flex items-center gap-1">
                                                        💰 {formatSalary(job)}
                                                    </span>
                                                </div>

                                                {/* Tags */}
                                                <div className="flex flex-wrap gap-2">
                                                    {job.category && (
                                                        <span className="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">
                                                            {job.category}
                                                        </span>
                                                    )}
                                                    {job.vacancies > 1 && (
                                                        <span className="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                                            {job.vacancies}{" "}
                                                            openings
                                                        </span>
                                                    )}
                                                </div>
                                            </div>

                                            {/* Save Button (if authenticated as applicant) */}
                                            {auth?.user?.role ===
                                                "applicant" && (
                                                <button
                                                    type="button"
                                                    className={`p-2 rounded-lg transition ${
                                                        job.is_saved
                                                            ? "text-yellow-500 bg-yellow-50"
                                                            : "text-gray-400 hover:text-yellow-500 hover:bg-yellow-50"
                                                    }`}
                                                    title={
                                                        job.is_saved
                                                            ? "Saved"
                                                            : "Save job"
                                                    }
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
                                <div className="mt-8 flex justify-center gap-2">
                                    {jobs.links.map((link, index) => (
                                        <Link
                                            key={index}
                                            href={link.url || "#"}
                                            preserveState
                                            preserveScroll
                                            className={`px-4 py-2 rounded-lg font-medium transition ${
                                                link.active
                                                    ? "bg-yellow-500 text-gray-900"
                                                    : link.url
                                                      ? "bg-white text-gray-700 hover:bg-gray-100 border border-gray-300"
                                                      : "bg-gray-100 text-gray-400 cursor-not-allowed"
                                            }`}
                                            dangerouslySetInnerHTML={{
                                                __html: link.label,
                                            }}
                                        />
                                    ))}
                                </div>
                            )}
                        </>
                    )}
                </div>

                {/* Footer */}
                <footer className="bg-gray-100 py-12 mt-12">
                    <div className="max-w-7xl mx-auto px-6 text-center text-gray-600">
                        <p>
                            &copy; 2024 Hiring Hall by Hill Business Consulting
                            Services. All rights reserved.
                        </p>
                    </div>
                </footer>
            </div>
        </>
    );
}
