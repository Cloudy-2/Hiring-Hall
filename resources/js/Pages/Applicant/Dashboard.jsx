import { Head, Link } from "@inertiajs/react";
import CandidateLayout from "@/Layouts/CandidateLayout";

export default function Dashboard({
    auth,
    user,
    profile,
    applications,
    recommendedJobs,
    appliedCount,
    underReviewCount,
    interviewingCount,
    offeredCount,
    hiredCount,
    declinedCount,
    savedJobs,
    savedJobIds,
}) {
    const completionItems = {
        photo: !!user.profile_photo_path,
        title: !!(profile?.job_title || profile?.title),
        experience: !!profile?.years_experience,
        skills: !!profile?.expertise_categories,
        salary: !!profile?.expected_salary_min,
    };

    const completedCount =
        Object.values(completionItems).filter(Boolean).length;
    const totalItems = Object.keys(completionItems).length;
    const percentage = Math.round((completedCount / totalItems) * 100);

    const statusColors = {
        applied: { bg: "#eef2ff", text: "#4f46e5" },
        submitted: { bg: "#eef2ff", text: "#4f46e5" },
        under_review: { bg: "#fefce8", text: "#ca8a04" },
        shortlisted: { bg: "#fefce8", text: "#ca8a04" },
        interviewed: { bg: "#f5f3ff", text: "#7c3aed" },
        offered: { bg: "#fdf4ff", text: "#a21caf" },
        hired: { bg: "#f0fdf4", text: "#16a34a" },
        accepted: { bg: "#f0fdf4", text: "#16a34a" },
        rejected: { bg: "#fef2f2", text: "#dc2626" },
        declined: { bg: "#fef2f2", text: "#dc2626" },
    };

    return (
        <CandidateLayout user={auth.user}>
            <Head title="Dashboard" />

            <div className="py-6">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {/* Hero Section */}
                    <div className="bg-gradient-to-r from-indigo-900 via-indigo-700 to-indigo-600 rounded-xl p-6 mb-6 shadow-lg">
                        <div className="flex flex-wrap items-center justify-between gap-4">
                            <div>
                                <h1 className="text-2xl font-bold text-white mb-1">
                                    Welcome back,{" "}
                                    {profile?.display_name || user.name}!
                                </h1>
                                <p className="text-indigo-200 text-sm">
                                    Here's what's happening with your job search
                                </p>
                            </div>
                            <div className="flex flex-wrap gap-2">
                                <Link
                                    href="/jobs"
                                    className="inline-flex items-center gap-2 px-4 py-2 bg-white text-indigo-700 rounded-lg font-semibold text-sm hover:bg-indigo-50 transition"
                                >
                                    <svg
                                        className="w-4 h-4"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            strokeWidth={2}
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                        />
                                    </svg>
                                    Search Jobs
                                </Link>
                                <Link
                                    href="/candidate/applications"
                                    className="inline-flex items-center gap-2 px-4 py-2 bg-white/20 text-white rounded-lg font-semibold text-sm hover:bg-white/30 transition backdrop-blur-sm"
                                >
                                    <svg
                                        className="w-4 h-4"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            strokeWidth={2}
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                        />
                                    </svg>
                                    Applications
                                </Link>
                                {percentage < 100 && (
                                    <Link
                                        href="/candidate/profile"
                                        className="inline-flex items-center gap-2 px-4 py-2 bg-white/20 text-white rounded-lg font-semibold text-sm hover:bg-white/30 transition backdrop-blur-sm"
                                    >
                                        <svg
                                            className="w-4 h-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                strokeLinecap="round"
                                                strokeLinejoin="round"
                                                strokeWidth={2}
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                            />
                                        </svg>
                                        Complete Profile
                                    </Link>
                                )}
                            </div>
                        </div>
                    </div>

                    {/* Application Tracking */}
                    <div className="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                        <div className="flex items-center justify-between mb-4 pb-3 border-b border-gray-100 dark:border-gray-700">
                            <h2 className="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                <svg
                                    className="w-5 h-5 text-indigo-600"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
                                </svg>
                                Application Tracking
                            </h2>
                            <Link
                                href="/candidate/applications"
                                className="text-sm font-semibold text-indigo-600 hover:text-indigo-700"
                            >
                                View All →
                            </Link>
                        </div>
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            {[
                                {
                                    label: "Applied",
                                    count: appliedCount,
                                    icon: "📝",
                                    color: "#4f46e5",
                                    bg: "#eef2ff",
                                },
                                {
                                    label: "Interviewing",
                                    count: interviewingCount,
                                    icon: "👥",
                                    color: "#ca8a04",
                                    bg: "#fefce8",
                                },
                                {
                                    label: "Offer Received",
                                    count: offeredCount,
                                    icon: "✉️",
                                    color: "#7c3aed",
                                    bg: "#f5f3ff",
                                },
                                {
                                    label: "Hired",
                                    count: hiredCount,
                                    icon: "🏆",
                                    color: "#16a34a",
                                    bg: "#f0fdf4",
                                },
                            ].map((stat, idx) => (
                                <div
                                    key={idx}
                                    className="text-center p-5 rounded-xl border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-all hover:-translate-y-1"
                                    style={{
                                        backgroundColor: stat.bg,
                                        borderTopColor: stat.color,
                                        borderTopWidth: "3px",
                                    }}
                                >
                                    <div className="text-3xl mb-2">
                                        {stat.icon}
                                    </div>
                                    <div
                                        className="text-3xl font-black mb-1"
                                        style={{ color: stat.color }}
                                    >
                                        {stat.count}
                                    </div>
                                    <div className="text-sm font-semibold text-gray-700">
                                        {stat.label}
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>

                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        {/* Recommended Jobs */}
                        <div className="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                            <div className="flex items-center justify-between mb-4 pb-3 border-b border-gray-100 dark:border-gray-700">
                                <h2 className="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                    <svg
                                        className="w-5 h-5 text-indigo-600"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fillRule="evenodd"
                                            d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z"
                                            clipRule="evenodd"
                                        />
                                        <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z" />
                                    </svg>
                                    Recommended Jobs
                                </h2>
                            </div>
                            {recommendedJobs && recommendedJobs.length > 0 ? (
                                <div className="space-y-3">
                                    {recommendedJobs.slice(0, 4).map((job) => (
                                        <Link
                                            key={job.id}
                                            href={`/jobs/${job.slug}`}
                                            className="block p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:border-indigo-500 hover:shadow-md transition"
                                        >
                                            <div className="flex items-start gap-3">
                                                <div className="w-12 h-12 rounded-lg bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center flex-shrink-0">
                                                    {job.company?.logo_url ? (
                                                        <img
                                                            src={
                                                                job.company
                                                                    .logo_url
                                                            }
                                                            alt=""
                                                            className="w-8 h-8 rounded object-cover"
                                                        />
                                                    ) : (
                                                        <span className="text-indigo-600 dark:text-indigo-400 font-bold text-sm">
                                                            {(
                                                                job.company
                                                                    ?.name ||
                                                                "C"
                                                            )
                                                                .substring(0, 2)
                                                                .toUpperCase()}
                                                        </span>
                                                    )}
                                                </div>
                                                <div className="flex-1 min-w-0">
                                                    <h3 className="font-semibold text-gray-900 dark:text-white truncate">
                                                        {job.title}
                                                    </h3>
                                                    <p className="text-sm text-gray-600 dark:text-gray-400">
                                                        {job.company?.name ||
                                                            "Company"}
                                                    </p>
                                                    <div className="flex items-center gap-2 mt-1 text-xs text-gray-500">
                                                        <span>
                                                            📍{" "}
                                                            {job.location ||
                                                                "Remote"}
                                                        </span>
                                                        {job.employment_type && (
                                                            <span className="px-2 py-0.5 bg-indigo-100 text-indigo-700 rounded-full">
                                                                {job.employment_type.replace(
                                                                    "_",
                                                                    " ",
                                                                )}
                                                            </span>
                                                        )}
                                                    </div>
                                                </div>
                                            </div>
                                        </Link>
                                    ))}
                                </div>
                            ) : (
                                <div className="text-center py-8">
                                    <p className="text-gray-500 mb-3">
                                        No recommended jobs right now
                                    </p>
                                    <Link
                                        href="/jobs"
                                        className="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700"
                                    >
                                        Find Jobs
                                    </Link>
                                </div>
                            )}
                        </div>

                        {/* Sidebar */}
                        <div className="space-y-6">
                            {/* Profile Completion */}
                            <div className="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                                <div className="flex items-center justify-between mb-4">
                                    <h3 className="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                        <svg
                                            className="w-5 h-5 text-indigo-600"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                        >
                                            <path
                                                fillRule="evenodd"
                                                d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                clipRule="evenodd"
                                            />
                                        </svg>
                                        Profile
                                    </h3>
                                    <span
                                        className={`text-sm font-bold ${percentage >= 80 ? "text-green-600" : percentage >= 50 ? "text-yellow-600" : "text-red-600"}`}
                                    >
                                        {percentage}%
                                    </span>
                                </div>
                                <div className="mb-4">
                                    <div className="flex items-center justify-between text-sm mb-2">
                                        <span className="text-gray-600 dark:text-gray-400">
                                            Completion
                                        </span>
                                        <span className="font-semibold text-gray-900 dark:text-white">
                                            {completedCount}/{totalItems}
                                        </span>
                                    </div>
                                    <div className="h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                        <div
                                            className={`h-full transition-all ${percentage >= 80 ? "bg-green-500" : percentage >= 50 ? "bg-yellow-500" : "bg-red-500"}`}
                                            style={{ width: `${percentage}%` }}
                                        />
                                    </div>
                                </div>
                                <Link
                                    href="/candidate/profile"
                                    className="block w-full text-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-semibold text-sm"
                                >
                                    {percentage < 100
                                        ? "Complete Profile"
                                        : "View Profile"}
                                </Link>
                            </div>

                            {/* Quick Stats */}
                            <div className="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                                <h3 className="text-lg font-bold text-gray-900 dark:text-white mb-4">
                                    Quick Stats
                                </h3>
                                <div className="space-y-3">
                                    <div className="flex items-center justify-between">
                                        <span className="text-sm text-gray-600 dark:text-gray-400">
                                            Saved Jobs
                                        </span>
                                        <span className="font-bold text-gray-900 dark:text-white">
                                            {savedJobs || 0}
                                        </span>
                                    </div>
                                    <div className="flex items-center justify-between">
                                        <span className="text-sm text-gray-600 dark:text-gray-400">
                                            Declined
                                        </span>
                                        <span className="font-bold text-gray-900 dark:text-white">
                                            {declinedCount || 0}
                                        </span>
                                    </div>
                                    <div className="flex items-center justify-between">
                                        <span className="text-sm text-gray-600 dark:text-gray-400">
                                            Under Review
                                        </span>
                                        <span className="font-bold text-gray-900 dark:text-white">
                                            {underReviewCount || 0}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Recent Applications */}
                    {applications && applications.length > 0 && (
                        <div className="mt-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                            <div className="flex items-center justify-between mb-4 pb-3 border-b border-gray-100 dark:border-gray-700">
                                <h2 className="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                    <svg
                                        className="w-5 h-5 text-indigo-600"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fillRule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                            clipRule="evenodd"
                                        />
                                    </svg>
                                    Recent Applications
                                </h2>
                                <Link
                                    href="/candidate/applications"
                                    className="text-sm font-semibold text-indigo-600 hover:text-indigo-700"
                                >
                                    View All →
                                </Link>
                            </div>
                            <div className="space-y-2">
                                {applications.slice(0, 5).map((app) => {
                                    const statusStyle = statusColors[
                                        app.status
                                    ] || { bg: "#f9fafb", text: "#6b7280" };
                                    return (
                                        <div
                                            key={app.id}
                                            className="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition"
                                        >
                                            <div className="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center flex-shrink-0">
                                                {app.job_posting?.company
                                                    ?.logo_url ? (
                                                    <img
                                                        src={
                                                            app.job_posting
                                                                .company
                                                                .logo_url
                                                        }
                                                        alt=""
                                                        className="w-6 h-6 rounded object-cover"
                                                    />
                                                ) : (
                                                    <span className="text-indigo-600 dark:text-indigo-400 font-bold text-xs">
                                                        {(
                                                            app.job_posting
                                                                ?.company
                                                                ?.name || "C"
                                                        )
                                                            .substring(0, 2)
                                                            .toUpperCase()}
                                                    </span>
                                                )}
                                            </div>
                                            <div className="flex-1 min-w-0">
                                                <Link
                                                    href={`/jobs/${app.job_posting?.slug}`}
                                                    className="font-semibold text-gray-900 dark:text-white hover:text-indigo-600 truncate block"
                                                >
                                                    {app.job_posting?.title}
                                                </Link>
                                                <p className="text-sm text-gray-600 dark:text-gray-400 truncate">
                                                    {
                                                        app.job_posting?.company
                                                            ?.name
                                                    }
                                                </p>
                                            </div>
                                            <span
                                                className="px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap"
                                                style={{
                                                    backgroundColor:
                                                        statusStyle.bg,
                                                    color: statusStyle.text,
                                                }}
                                            >
                                                {app.status.replace("_", " ")}
                                            </span>
                                        </div>
                                    );
                                })}
                            </div>
                        </div>
                    )}
                </div>
            </div>
        </CandidateLayout>
    );
}
