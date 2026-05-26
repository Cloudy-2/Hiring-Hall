import { Head, Link, router } from "@inertiajs/react";
import { useState } from "react";

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

    const handleSaveJob = () => {
        if (!auth.user) {
            router.visit("/login");
            return;
        }

        setSaving(true);
        router.post(
            `/jobs/${job.slug}/save`,
            {},
            {
                preserveScroll: true,
                onFinish: () => setSaving(false),
            },
        );
    };

    const handleApply = () => {
        if (!auth.user) {
            router.visit("/login");
            return;
        }

        setApplying(true);
        router.post(
            `/jobs/${job.slug}/apply`,
            {},
            {
                onFinish: () => setApplying(false),
            },
        );
    };

    const formatSalary = (min, max, currency) => {
        if (!min && !max) return "Competitive";
        const formatter = new Intl.NumberFormat("en-US", {
            style: "currency",
            currency: currency || "USD",
            minimumFractionDigits: 0,
        });

        if (min && max) {
            return `${formatter.format(min)} - ${formatter.format(max)}`;
        }
        return min
            ? `From ${formatter.format(min)}`
            : `Up to ${formatter.format(max)}`;
    };

    return (
        <>
            <Head title={job.title} />

            <div className="min-h-screen bg-gray-50">
                {/* Navigation */}
                <nav className="bg-white shadow-sm border-b">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="flex justify-between h-16">
                            <div className="flex items-center">
                                <Link
                                    href="/"
                                    className="text-2xl font-bold text-indigo-600"
                                >
                                    HillBCS Hire
                                </Link>
                            </div>
                            <div className="flex items-center space-x-4">
                                <Link
                                    href="/jobs"
                                    className="text-gray-700 hover:text-indigo-600"
                                >
                                    Browse Jobs
                                </Link>
                                {auth.user ? (
                                    <>
                                        {isCandidate && (
                                            <Link
                                                href="/candidate/dashboard"
                                                className="text-gray-700 hover:text-indigo-600"
                                            >
                                                Dashboard
                                            </Link>
                                        )}
                                        <span className="text-gray-700">
                                            {auth.user.name}
                                        </span>
                                    </>
                                ) : (
                                    <>
                                        <Link
                                            href="/login"
                                            className="text-gray-700 hover:text-indigo-600"
                                        >
                                            Login
                                        </Link>
                                        <Link
                                            href="/register"
                                            className="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700"
                                        >
                                            Sign Up
                                        </Link>
                                    </>
                                )}
                            </div>
                        </div>
                    </div>
                </nav>

                {/* Main Content */}
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        {/* Left Column - Job Details */}
                        <div className="lg:col-span-2 space-y-6">
                            {/* Job Header */}
                            <div className="bg-white rounded-lg shadow-sm p-6">
                                <div className="flex items-start justify-between">
                                    <div className="flex items-start space-x-4">
                                        {job.company?.logo_url && (
                                            <img
                                                src={job.company.logo_url}
                                                alt={job.company.name}
                                                className="w-16 h-16 rounded-lg object-cover"
                                            />
                                        )}
                                        <div>
                                            <h1 className="text-3xl font-bold text-gray-900">
                                                {job.title}
                                            </h1>
                                            <div className="mt-2 flex items-center space-x-4 text-gray-600">
                                                <span className="flex items-center">
                                                    <svg
                                                        className="w-5 h-5 mr-1"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <path
                                                            strokeLinecap="round"
                                                            strokeLinejoin="round"
                                                            strokeWidth={2}
                                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                                                        />
                                                    </svg>
                                                    {job.company?.name ||
                                                        "Company"}
                                                </span>
                                                {job.company?.verified && (
                                                    <span className="flex items-center text-green-600">
                                                        <svg
                                                            className="w-5 h-5 mr-1"
                                                            fill="currentColor"
                                                            viewBox="0 0 20 20"
                                                        >
                                                            <path
                                                                fillRule="evenodd"
                                                                d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                clipRule="evenodd"
                                                            />
                                                        </svg>
                                                        Verified
                                                    </span>
                                                )}
                                                <span className="flex items-center">
                                                    <svg
                                                        className="w-5 h-5 mr-1"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <path
                                                            strokeLinecap="round"
                                                            strokeLinejoin="round"
                                                            strokeWidth={2}
                                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                                        />
                                                        <path
                                                            strokeLinecap="round"
                                                            strokeLinejoin="round"
                                                            strokeWidth={2}
                                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                                        />
                                                    </svg>
                                                    {job.location || "Remote"}
                                                </span>
                                            </div>
                                            <div className="mt-3 flex items-center space-x-2">
                                                <span className="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm font-medium">
                                                    {job.employment_type
                                                        ?.replace("_", " ")
                                                        .toUpperCase() ||
                                                        "Full Time"}
                                                </span>
                                                {job.remote_type && (
                                                    <span className="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                                        {job.remote_type.replace(
                                                            "_",
                                                            " ",
                                                        )}
                                                    </span>
                                                )}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {/* Action Buttons */}
                                {isCandidate && !isJobClosed && (
                                    <div className="mt-6 flex space-x-4">
                                        {!hasApplied ? (
                                            <button
                                                onClick={handleApply}
                                                disabled={applying}
                                                className="flex-1 bg-indigo-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-indigo-700 disabled:opacity-50"
                                            >
                                                {applying
                                                    ? "Applying..."
                                                    : "Apply Now"}
                                            </button>
                                        ) : (
                                            <div className="flex-1 bg-green-100 text-green-800 px-6 py-3 rounded-lg font-semibold text-center">
                                                ✓ Already Applied
                                            </div>
                                        )}
                                        <button
                                            onClick={handleSaveJob}
                                            disabled={saving}
                                            className={`px-6 py-3 rounded-lg font-semibold border-2 ${
                                                isSaved
                                                    ? "border-indigo-600 text-indigo-600 bg-indigo-50"
                                                    : "border-gray-300 text-gray-700 hover:border-indigo-600"
                                            }`}
                                        >
                                            {saving
                                                ? "..."
                                                : isSaved
                                                  ? "★ Saved"
                                                  : "☆ Save"}
                                        </button>
                                    </div>
                                )}

                                {isJobClosed && (
                                    <div className="mt-6 bg-red-50 border border-red-200 rounded-lg p-4 text-red-800">
                                        <strong>
                                            This position is closed.
                                        </strong>{" "}
                                        Applications are no longer being
                                        accepted.
                                    </div>
                                )}
                            </div>

                            {/* Job Description */}
                            <div className="bg-white rounded-lg shadow-sm p-6">
                                <h2 className="text-xl font-bold text-gray-900 mb-4">
                                    Job Description
                                </h2>
                                <div className="prose max-w-none text-gray-700 whitespace-pre-wrap">
                                    {job.description ||
                                        "No description provided."}
                                </div>
                            </div>

                            {/* Requirements */}
                            {job.requirements && (
                                <div className="bg-white rounded-lg shadow-sm p-6">
                                    <h2 className="text-xl font-bold text-gray-900 mb-4">
                                        Requirements
                                    </h2>
                                    <ul className="space-y-2 text-gray-700">
                                        {job.requirements
                                            .split("\n")
                                            .filter((r) => r.trim())
                                            .map((req, idx) => (
                                                <li
                                                    key={idx}
                                                    className="flex items-start"
                                                >
                                                    <svg
                                                        className="w-5 h-5 text-indigo-600 mr-2 mt-0.5 flex-shrink-0"
                                                        fill="currentColor"
                                                        viewBox="0 0 20 20"
                                                    >
                                                        <path
                                                            fillRule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                            clipRule="evenodd"
                                                        />
                                                    </svg>
                                                    <span>{req}</span>
                                                </li>
                                            ))}
                                    </ul>
                                </div>
                            )}

                            {/* Responsibilities */}
                            {job.responsibilities && (
                                <div className="bg-white rounded-lg shadow-sm p-6">
                                    <h2 className="text-xl font-bold text-gray-900 mb-4">
                                        Responsibilities
                                    </h2>
                                    <ul className="space-y-2 text-gray-700">
                                        {job.responsibilities
                                            .split("\n")
                                            .filter((r) => r.trim())
                                            .map((resp, idx) => (
                                                <li
                                                    key={idx}
                                                    className="flex items-start"
                                                >
                                                    <svg
                                                        className="w-5 h-5 text-indigo-600 mr-2 mt-0.5 flex-shrink-0"
                                                        fill="currentColor"
                                                        viewBox="0 0 20 20"
                                                    >
                                                        <path
                                                            fillRule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                            clipRule="evenodd"
                                                        />
                                                    </svg>
                                                    <span>{resp}</span>
                                                </li>
                                            ))}
                                    </ul>
                                </div>
                            )}
                        </div>

                        {/* Right Column - Job Info & Related Jobs */}
                        <div className="space-y-6">
                            {/* Job Info Card */}
                            <div className="bg-white rounded-lg shadow-sm p-6">
                                <h3 className="text-lg font-bold text-gray-900 mb-4">
                                    Job Information
                                </h3>
                                <div className="space-y-4">
                                    <div>
                                        <div className="text-sm text-gray-500">
                                            Salary
                                        </div>
                                        <div className="text-lg font-semibold text-gray-900">
                                            {formatSalary(
                                                job.salary_min,
                                                job.salary_max,
                                                job.salary_currency,
                                            )}
                                        </div>
                                    </div>
                                    {job.vacancies && (
                                        <div>
                                            <div className="text-sm text-gray-500">
                                                Vacancies
                                            </div>
                                            <div className="text-lg font-semibold text-gray-900">
                                                {job.vacancies}
                                            </div>
                                        </div>
                                    )}
                                    {(job.experience_min_years ||
                                        job.experience_max_years) && (
                                        <div>
                                            <div className="text-sm text-gray-500">
                                                Experience
                                            </div>
                                            <div className="text-lg font-semibold text-gray-900">
                                                {job.experience_min_years &&
                                                job.experience_max_years
                                                    ? `${job.experience_min_years}-${job.experience_max_years} years`
                                                    : job.experience_min_years
                                                      ? `${job.experience_min_years}+ years`
                                                      : `Up to ${job.experience_max_years} years`}
                                            </div>
                                        </div>
                                    )}
                                    {job.category && (
                                        <div>
                                            <div className="text-sm text-gray-500">
                                                Category
                                            </div>
                                            <div className="text-lg font-semibold text-gray-900">
                                                {job.category}
                                            </div>
                                        </div>
                                    )}
                                    {job.posted_at && (
                                        <div>
                                            <div className="text-sm text-gray-500">
                                                Posted
                                            </div>
                                            <div className="text-lg font-semibold text-gray-900">
                                                {new Date(
                                                    job.posted_at,
                                                ).toLocaleDateString()}
                                            </div>
                                        </div>
                                    )}
                                    {job.closes_at && !deadlinePassed && (
                                        <div>
                                            <div className="text-sm text-gray-500">
                                                Deadline
                                            </div>
                                            <div className="text-lg font-semibold text-gray-900">
                                                {daysLeft > 0
                                                    ? `${daysLeft} days left`
                                                    : "Today"}
                                            </div>
                                        </div>
                                    )}
                                </div>
                            </div>

                            {/* Related Jobs */}
                            {relatedJobs && relatedJobs.length > 0 && (
                                <div className="bg-white rounded-lg shadow-sm p-6">
                                    <h3 className="text-lg font-bold text-gray-900 mb-4">
                                        Related Jobs
                                    </h3>
                                    <div className="space-y-4">
                                        {relatedJobs.map((relJob) => (
                                            <Link
                                                key={relJob.id}
                                                href={`/jobs/${relJob.slug}`}
                                                className="block p-4 border border-gray-200 rounded-lg hover:border-indigo-600 hover:shadow-md transition"
                                            >
                                                <div className="font-semibold text-gray-900 hover:text-indigo-600">
                                                    {relJob.title}
                                                </div>
                                                <div className="text-sm text-gray-600 mt-1">
                                                    {relJob.company?.name}
                                                </div>
                                                <div className="text-sm text-gray-500 mt-1">
                                                    {relJob.location ||
                                                        "Remote"}
                                                </div>
                                            </Link>
                                        ))}
                                    </div>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}
