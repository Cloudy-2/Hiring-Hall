import { Head, Link, router } from "@inertiajs/react";
import { useState } from "react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";

export default function ApplicationsHistory({
    auth,
    applications,
    search,
    withdrawnCount,
    notSelectedCount,
    closedCount,
}) {
    const [searchQuery, setSearchQuery] = useState(search || "");

    const handleSearch = (e) => {
        e.preventDefault();
        router.get(
            "/candidate/applications/history",
            { q: searchQuery },
            { preserveState: true },
        );
    };

    const handleDelete = (id, title) => {
        if (
            confirm(
                `Permanently delete this application history for "${title}"?`,
            )
        ) {
            router.delete(`/candidate/applications/history/${id}`);
        }
    };

    const statusColors = {
        withdrawn: { bg: "#f9fafb", text: "#6b7280", label: "Withdrawn" },
        cancelled: { bg: "#f9fafb", text: "#6b7280", label: "Cancelled" },
        declined: { bg: "#fef2f2", text: "#dc2626", label: "Declined" },
        rejected: { bg: "#fef2f2", text: "#dc2626", label: "Rejected" },
        not_selected: { bg: "#fef2f2", text: "#dc2626", label: "Not Selected" },
        closed: { bg: "#f9fafb", text: "#6b7280", label: "Closed" },
        expired: { bg: "#f9fafb", text: "#6b7280", label: "Expired" },
    };

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Application History" />

            <div className="py-6">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {/* Hero */}
                    <div className="bg-gradient-to-r from-gray-700 via-gray-600 to-gray-500 rounded-xl p-6 mb-6 shadow-lg">
                        <div className="flex flex-wrap items-center justify-between gap-4">
                            <div>
                                <h1 className="text-2xl font-bold text-white mb-1 flex items-center gap-2">
                                    <svg
                                        className="w-6 h-6"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fillRule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                            clipRule="evenodd"
                                        />
                                    </svg>
                                    Application History
                                </h1>
                                <p className="text-gray-200 text-sm">
                                    View your past and archived applications
                                </p>
                            </div>
                            <div className="flex flex-wrap gap-2">
                                <Link
                                    href="/candidate/applications"
                                    className="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 rounded-lg font-semibold text-sm hover:bg-gray-50 transition"
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
                                            d="M10 19l-7-7m0 0l7-7m-7 7h18"
                                        />
                                    </svg>
                                    Back to Applications
                                </Link>
                            </div>
                        </div>
                    </div>

                    {/* Stats */}
                    <div className="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                        <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            {[
                                {
                                    label: "Withdrawn",
                                    count: withdrawnCount,
                                    icon: "🔙",
                                    color: "#6b7280",
                                    bg: "#f9fafb",
                                },
                                {
                                    label: "Not Selected",
                                    count: notSelectedCount,
                                    icon: "❌",
                                    color: "#dc2626",
                                    bg: "#fef2f2",
                                },
                                {
                                    label: "Closed",
                                    count: closedCount,
                                    icon: "🔒",
                                    color: "#6b7280",
                                    bg: "#f9fafb",
                                },
                            ].map((stat, idx) => (
                                <div
                                    key={idx}
                                    className="text-center p-5 rounded-xl border border-gray-200 dark:border-gray-700"
                                    style={{ backgroundColor: stat.bg }}
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

                    {/* History Table */}
                    <div className="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <div className="flex items-center justify-between mb-4 pb-3 border-b border-gray-100 dark:border-gray-700">
                            <h2 className="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                <svg
                                    className="w-5 h-5 text-gray-600"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fillRule="evenodd"
                                        d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z"
                                        clipRule="evenodd"
                                    />
                                </svg>
                                Archived Applications
                            </h2>
                            <span className="text-xs text-gray-500">
                                {applications.total} total
                            </span>
                        </div>

                        {/* Search */}
                        <form onSubmit={handleSearch} className="mb-4">
                            <div className="relative max-w-md">
                                <input
                                    type="text"
                                    value={searchQuery}
                                    onChange={(e) =>
                                        setSearchQuery(e.target.value)
                                    }
                                    placeholder="Search history..."
                                    className="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                />
                                <svg
                                    className="w-5 h-5 text-gray-400 absolute left-3 top-2.5"
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
                            </div>
                        </form>

                        {applications.data.length === 0 ? (
                            <div className="text-center py-12">
                                <svg
                                    className="w-16 h-16 text-gray-300 mx-auto mb-4"
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
                                <p className="text-gray-500 mb-4">
                                    No application history found.
                                </p>
                                <Link
                                    href="/candidate/applications"
                                    className="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700"
                                >
                                    View Active Applications
                                </Link>
                            </div>
                        ) : (
                            <>
                                <div className="space-y-3">
                                    {applications.data.map((app) => {
                                        const statusStyle = statusColors[
                                            app.status
                                        ] || {
                                            bg: "#f9fafb",
                                            text: "#6b7280",
                                            label: app.status,
                                        };
                                        return (
                                            <div
                                                key={app.id}
                                                className="flex items-center gap-4 p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:border-gray-300 dark:hover:border-gray-600 transition"
                                            >
                                                <div className="w-12 h-12 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center flex-shrink-0">
                                                    {app.job_posting?.company
                                                        ?.logo_url ? (
                                                        <img
                                                            src={
                                                                app.job_posting
                                                                    .company
                                                                    .logo_url
                                                            }
                                                            alt=""
                                                            className="w-8 h-8 rounded object-cover"
                                                        />
                                                    ) : (
                                                        <span className="text-gray-600 dark:text-gray-400 font-bold text-sm">
                                                            {(
                                                                app.job_posting
                                                                    ?.company
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
                                                        {app.job_posting
                                                            ?.title ||
                                                            "Job Title"}
                                                    </h3>
                                                    <p className="text-sm text-gray-600 dark:text-gray-400 truncate">
                                                        {app.job_posting
                                                            ?.company?.name ||
                                                            "Company"}
                                                    </p>
                                                    <p className="text-xs text-gray-500 mt-1">
                                                        Applied:{" "}
                                                        {app.applied_at
                                                            ? new Date(
                                                                  app.applied_at,
                                                              ).toLocaleDateString(
                                                                  "en-US",
                                                                  {
                                                                      month: "short",
                                                                      day: "numeric",
                                                                      year: "numeric",
                                                                  },
                                                              )
                                                            : "-"}
                                                    </p>
                                                </div>
                                                <div className="flex items-center gap-3">
                                                    <span
                                                        className="px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap"
                                                        style={{
                                                            backgroundColor:
                                                                statusStyle.bg,
                                                            color: statusStyle.text,
                                                        }}
                                                    >
                                                        {statusStyle.label}
                                                    </span>
                                                    <button
                                                        onClick={() =>
                                                            handleDelete(
                                                                app.id,
                                                                app.job_posting
                                                                    ?.title,
                                                            )
                                                        }
                                                        className="p-2 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition"
                                                        title="Delete"
                                                    >
                                                        <svg
                                                            className="w-5 h-5"
                                                            fill="none"
                                                            stroke="currentColor"
                                                            viewBox="0 0 24 24"
                                                        >
                                                            <path
                                                                strokeLinecap="round"
                                                                strokeLinejoin="round"
                                                                strokeWidth={2}
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                            />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        );
                                    })}
                                </div>

                                {/* Pagination */}
                                <div className="mt-6 flex items-center justify-between text-sm text-gray-600 dark:text-gray-400">
                                    <div>
                                        Showing {applications.from} to{" "}
                                        {applications.to} of{" "}
                                        {applications.total} results
                                    </div>
                                    <div className="flex gap-2">
                                        {applications.links.map((link, idx) => (
                                            <Link
                                                key={idx}
                                                href={link.url || "#"}
                                                className={`px-3 py-1 rounded-lg ${
                                                    link.active
                                                        ? "bg-gray-600 text-white"
                                                        : link.url
                                                          ? "bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200"
                                                          : "bg-gray-100 dark:bg-gray-700 text-gray-400 cursor-not-allowed"
                                                }`}
                                                dangerouslySetInnerHTML={{
                                                    __html: link.label,
                                                }}
                                            />
                                        ))}
                                    </div>
                                </div>
                            </>
                        )}
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
