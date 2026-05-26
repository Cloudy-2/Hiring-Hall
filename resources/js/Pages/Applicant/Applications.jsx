import { Head, Link, router } from "@inertiajs/react";
import { useState } from "react";
import CandidateLayout from "@/Layouts/CandidateLayout";

export default function Applications({
    auth,
    applications,
    totalApplied,
    appliedCount,
    underReviewCount,
    viewedCount,
    acceptedCount,
    declinedCount,
    closedCount,
    statusFilter,
    search,
}) {
    const [selectedIds, setSelectedIds] = useState([]);
    const [searchQuery, setSearchQuery] = useState(search || "");
    const [bulkAction, setBulkAction] = useState("");

    const statusColors = {
        applied: { bg: "#eef2ff", text: "#4f46e5", label: "Applied" },
        submitted: { bg: "#eef2ff", text: "#4f46e5", label: "Applied" },
        under_review: { bg: "#fefce8", text: "#ca8a04", label: "Review" },
        application_viewed: { bg: "#e0f2fe", text: "#0284c7", label: "Viewed" },
        viewed: { bg: "#e0f2fe", text: "#0284c7", label: "Viewed" },
        accepted: { bg: "#f0fdf4", text: "#16a34a", label: "Accept" },
        hired: { bg: "#f0fdf4", text: "#16a34a", label: "Hired" },
        not_selected: { bg: "#fef2f2", text: "#dc2626", label: "Declined" },
        no_longer_under_consideration: {
            bg: "#fef2f2",
            text: "#dc2626",
            label: "Declined",
        },
        declined: { bg: "#fef2f2", text: "#dc2626", label: "Declined" },
        rejected: { bg: "#fef2f2", text: "#dc2626", label: "Declined" },
        closed: { bg: "#f9fafb", text: "#6b7280", label: "Closed" },
    };

    const handleSelectAll = (e) => {
        if (e.target.checked) {
            setSelectedIds(applications.data.map((app) => app.id));
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

    const handleBulkAction = () => {
        if (!bulkAction) {
            alert("Please select a bulk action first.");
            return;
        }
        if (selectedIds.length === 0) {
            alert("Please select at least one application.");
            return;
        }

        if (
            confirm(
                `Are you sure you want to ${bulkAction} ${selectedIds.length} application(s)?`,
            )
        ) {
            const url =
                bulkAction === "withdraw"
                    ? "/candidate/applications/bulk-withdraw"
                    : "/candidate/applications/bulk-delete";

            router.post(
                url,
                { ids: selectedIds },
                {
                    onSuccess: () => {
                        setSelectedIds([]);
                        setBulkAction("");
                    },
                },
            );
        }
    };

    const handleSearch = (e) => {
        e.preventDefault();
        router.get(
            "/candidate/applications",
            { q: searchQuery },
            { preserveState: true },
        );
    };

    const handleDelete = (id, title) => {
        if (confirm(`Cancel your application for "${title}"?`)) {
            router.delete(`/candidate/applications/${id}`);
        }
    };

    return (
        <CandidateLayout user={auth.user}>
            <Head title="My Applications" />

            <div className="py-6">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {/* Hero */}
                    <div className="bg-gradient-to-r from-indigo-900 via-indigo-700 to-indigo-600 rounded-xl p-6 mb-6 shadow-lg">
                        <div className="flex flex-wrap items-center justify-between gap-4">
                            <div>
                                <h1 className="text-2xl font-bold text-white mb-1 flex items-center gap-2">
                                    <svg
                                        className="w-6 h-6"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                        <path
                                            fillRule="evenodd"
                                            d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                                            clipRule="evenodd"
                                        />
                                    </svg>
                                    My Applications
                                </h1>
                                <p className="text-indigo-200 text-sm">
                                    Track and manage all your job applications
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
                                    Find Jobs
                                </Link>
                                <Link
                                    href="/candidate/applications/history"
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
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                        />
                                    </svg>
                                    View History
                                </Link>
                            </div>
                        </div>
                    </div>

                    {/* Stats */}
                    <div className="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                        <div className="flex items-center mb-4 pb-3 border-b border-gray-100 dark:border-gray-700">
                            <h2 className="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                <svg
                                    className="w-5 h-5 text-indigo-600"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
                                </svg>
                                Overview
                            </h2>
                        </div>
                        <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
                            {[
                                {
                                    label: "Total Applied",
                                    count: totalApplied,
                                    icon: "📝",
                                    color: "#4f46e5",
                                    bg: "#eef2ff",
                                },
                                {
                                    label: "Review",
                                    count: underReviewCount,
                                    icon: "⏱️",
                                    color: "#ca8a04",
                                    bg: "#fefce8",
                                },
                                {
                                    label: "Viewed",
                                    count: viewedCount,
                                    icon: "👁️",
                                    color: "#0284c7",
                                    bg: "#e0f2fe",
                                },
                                {
                                    label: "Accept",
                                    count: acceptedCount,
                                    icon: "✅",
                                    color: "#16a34a",
                                    bg: "#f0fdf4",
                                },
                                {
                                    label: "Declined",
                                    count: declinedCount,
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
                                    className="text-center p-4 rounded-xl"
                                    style={{ backgroundColor: stat.bg }}
                                >
                                    <div className="text-2xl mb-1">
                                        {stat.icon}
                                    </div>
                                    <div
                                        className="text-2xl font-black mb-1"
                                        style={{ color: stat.color }}
                                    >
                                        {stat.count}
                                    </div>
                                    <div className="text-xs font-semibold text-gray-700">
                                        {stat.label}
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>

                    {/* Applications Table */}
                    <div className="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <div className="flex items-center justify-between mb-4 pb-3 border-b border-gray-100 dark:border-gray-700">
                            <h2 className="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                <svg
                                    className="w-5 h-5 text-indigo-600"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fillRule="evenodd"
                                        d="M5 4a3 3 0 00-3 3v6a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3H5zm-1 9v-1h5v2H5a1 1 0 01-1-1zm7 1h4a1 1 0 001-1v-1h-5v2zm0-4h5V8h-5v2zM9 8H4v2h5V8z"
                                        clipRule="evenodd"
                                    />
                                </svg>
                                All Applications
                            </h2>
                            <span className="text-xs text-gray-500">
                                {applications.total} total
                            </span>
                        </div>

                        {/* Toolbar */}
                        <div className="flex flex-wrap items-center gap-3 mb-4 pb-4 border-b border-gray-100 dark:border-gray-700">
                            <select
                                value={bulkAction}
                                onChange={(e) => setBulkAction(e.target.value)}
                                className="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                            >
                                <option value="">Bulk action</option>
                                <option value="withdraw">
                                    Withdraw Selected
                                </option>
                                <option value="delete">Delete Selected</option>
                            </select>
                            <button
                                onClick={handleBulkAction}
                                className="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 transition"
                            >
                                Apply
                            </button>
                            <form
                                onSubmit={handleSearch}
                                className="flex-1 max-w-md"
                            >
                                <div className="relative">
                                    <input
                                        type="text"
                                        value={searchQuery}
                                        onChange={(e) =>
                                            setSearchQuery(e.target.value)
                                        }
                                        placeholder="Search applications..."
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
                            {selectedIds.length > 0 && (
                                <span className="text-xs text-gray-500 ml-auto">
                                    {selectedIds.length} selected
                                </span>
                            )}
                        </div>

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
                                    You have not applied to any jobs yet.
                                </p>
                                <Link
                                    href="/jobs"
                                    className="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700"
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
                                    Browse Jobs
                                </Link>
                            </div>
                        ) : (
                            <>
                                <div className="overflow-x-auto">
                                    <table className="w-full">
                                        <thead className="bg-gray-50 dark:bg-gray-700/50">
                                            <tr>
                                                <th className="px-4 py-3 text-left">
                                                    <input
                                                        type="checkbox"
                                                        checked={
                                                            selectedIds.length ===
                                                            applications.data
                                                                .length
                                                        }
                                                        onChange={
                                                            handleSelectAll
                                                        }
                                                        className="rounded border-gray-300"
                                                    />
                                                </th>
                                                <th className="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                                    Job Title & Company
                                                </th>
                                                <th className="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                                    Date Applied
                                                </th>
                                                <th className="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                                    Status
                                                </th>
                                                <th className="px-4 py-3 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                                    Actions
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody className="divide-y divide-gray-200 dark:divide-gray-700">
                                            {applications.data.map((app) => {
                                                const statusStyle =
                                                    statusColors[
                                                        app.status
                                                    ] || {
                                                        bg: "#f9fafb",
                                                        text: "#6b7280",
                                                        label: app.status,
                                                    };
                                                return (
                                                    <tr
                                                        key={app.id}
                                                        className="hover:bg-gray-50 dark:hover:bg-gray-700/50"
                                                    >
                                                        <td className="px-4 py-3">
                                                            <input
                                                                type="checkbox"
                                                                checked={selectedIds.includes(
                                                                    app.id,
                                                                )}
                                                                onChange={() =>
                                                                    handleSelectOne(
                                                                        app.id,
                                                                    )
                                                                }
                                                                className="rounded border-gray-300"
                                                            />
                                                        </td>
                                                        <td className="px-4 py-3">
                                                            <Link
                                                                href={`/jobs/${app.job_posting?.slug}`}
                                                                className="font-semibold text-gray-900 dark:text-white hover:text-indigo-600 block"
                                                            >
                                                                {
                                                                    app
                                                                        .job_posting
                                                                        ?.title
                                                                }
                                                            </Link>
                                                            <div className="text-xs text-gray-500">
                                                                {app.job_posting
                                                                    ?.company
                                                                    ?.name ||
                                                                    "Company"}
                                                            </div>
                                                        </td>
                                                        <td className="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
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
                                                        </td>
                                                        <td className="px-4 py-3">
                                                            <span
                                                                className="px-3 py-1 rounded-full text-xs font-semibold"
                                                                style={{
                                                                    backgroundColor:
                                                                        statusStyle.bg,
                                                                    color: statusStyle.text,
                                                                }}
                                                            >
                                                                {
                                                                    statusStyle.label
                                                                }
                                                            </span>
                                                        </td>
                                                        <td className="px-4 py-3 text-right">
                                                            <div className="inline-flex gap-2">
                                                                <Link
                                                                    href={`/jobs/${app.job_posting?.slug}`}
                                                                    className="p-2 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition"
                                                                    title="View"
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
                                                                            strokeWidth={
                                                                                2
                                                                            }
                                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                                                        />
                                                                        <path
                                                                            strokeLinecap="round"
                                                                            strokeLinejoin="round"
                                                                            strokeWidth={
                                                                                2
                                                                            }
                                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                                                        />
                                                                    </svg>
                                                                </Link>
                                                                <button
                                                                    onClick={() =>
                                                                        handleDelete(
                                                                            app.id,
                                                                            app
                                                                                .job_posting
                                                                                ?.title,
                                                                        )
                                                                    }
                                                                    className="p-2 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition"
                                                                    title="Cancel"
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
                                                                            strokeWidth={
                                                                                2
                                                                            }
                                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                                        />
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                );
                                            })}
                                        </tbody>
                                    </table>
                                </div>

                                {/* Pagination */}
                                <div className="mt-4 flex items-center justify-between text-sm text-gray-600 dark:text-gray-400">
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
                                                        ? "bg-indigo-600 text-white"
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
        </CandidateLayout>
    );
}
