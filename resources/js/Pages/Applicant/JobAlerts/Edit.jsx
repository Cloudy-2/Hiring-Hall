import { Head, Link, useForm } from '@inertiajs/react';
import { useState, useEffect } from 'react';
import CandidateLayout from '@/Layouts/CandidateLayout';

export default function JobAlertsEdit({ auth, alert, dropdownOptions }) {
    const [keywordInput, setKeywordInput] = useState('');

    // Parse keywords from comma-separated string
    const initialKeywords = alert.keywords 
        ? alert.keywords.split(',').map(k => k.trim()).filter(Boolean)
        : [];

    const { data, setData, put, processing, errors } = useForm({
        name: alert.name || '',
        keywords: initialKeywords,
        location: alert.location || '',
        category: alert.category || '',
        remote_type: alert.remote_type || '',
        employment_type: alert.employment_type || '',
        frequency: alert.frequency || 'daily',
        email_enabled: alert.email_enabled ?? true,
        is_active: alert.is_active ?? true,
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        put(route('candidate.job-alerts.update', alert.id));
    };

    const addKeyword = () => {
        const keyword = keywordInput.trim();
        if (keyword && !data.keywords.includes(keyword)) {
            setData('keywords', [...data.keywords, keyword]);
            setKeywordInput('');
        }
    };

    const removeKeyword = (index) => {
        setData('keywords', data.keywords.filter((_, i) => i !== index));
    };

    const handleKeywordKeyPress = (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            addKeyword();
        }
    };

    return (
        <CandidateLayout user={auth.user}>
            <Head title="Edit Job Alert" />

            <div className="py-6">
                <div className="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
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
                        <h1 className="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                            Edit Job Alert
                        </h1>
                        <p className="text-gray-600 dark:text-gray-400">
                            Update your alert criteria and notification preferences
                        </p>
                    </div>

                    {/* Form */}
                    <form onSubmit={handleSubmit} className="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        {/* Alert Name */}
                        <div className="mb-6">
                            <label className="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Alert Name <span className="text-red-600">*</span>
                            </label>
                            <input
                                type="text"
                                value={data.name}
                                onChange={(e) => setData('name', e.target.value)}
                                className="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 dark:bg-gray-700 dark:text-white"
                                placeholder="e.g., Senior Developer Jobs in Manila"
                                required
                            />
                            {errors.name && <p className="text-red-600 text-xs mt-1">{errors.name}</p>}
                        </div>

                        {/* Keywords */}
                        <div className="mb-6">
                            <label className="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Keywords
                            </label>
                            <div className="flex gap-2 mb-2">
                                <input
                                    type="text"
                                    value={keywordInput}
                                    onChange={(e) => setKeywordInput(e.target.value)}
                                    onKeyPress={handleKeywordKeyPress}
                                    className="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 dark:bg-gray-700 dark:text-white"
                                    placeholder="e.g., React, Node.js, Python"
                                />
                                <button
                                    type="button"
                                    onClick={addKeyword}
                                    className="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition"
                                >
                                    Add
                                </button>
                            </div>
                            {data.keywords.length > 0 && (
                                <div className="flex flex-wrap gap-2">
                                    {data.keywords.map((keyword, index) => (
                                        <span
                                            key={index}
                                            className="inline-flex items-center gap-2 px-3 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 rounded-full text-sm"
                                        >
                                            {keyword}
                                            <button
                                                type="button"
                                                onClick={() => removeKeyword(index)}
                                                className="hover:text-emerald-900 dark:hover:text-emerald-200"
                                            >
                                                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </span>
                                    ))}
                                </div>
                            )}
                            <p className="text-xs text-gray-500 mt-1">Press Enter or click Add to add keywords</p>
                        </div>

                        {/* Location */}
                        <div className="mb-6">
                            <label className="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Location
                            </label>
                            <select
                                value={data.location}
                                onChange={(e) => setData('location', e.target.value)}
                                className="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 dark:bg-gray-700 dark:text-white"
                            >
                                <option value="">Any Location</option>
                                {dropdownOptions.locations && Object.entries(dropdownOptions.locations).map(([value, label]) => (
                                    <option key={value} value={value}>{label}</option>
                                ))}
                            </select>
                        </div>

                        {/* Category */}
                        <div className="mb-6">
                            <label className="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Job Category
                            </label>
                            <select
                                value={data.category}
                                onChange={(e) => setData('category', e.target.value)}
                                className="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 dark:bg-gray-700 dark:text-white"
                            >
                                <option value="">Any Category</option>
                                {dropdownOptions.categories && Object.entries(dropdownOptions.categories).map(([value, label]) => (
                                    <option key={value} value={value}>{label}</option>
                                ))}
                            </select>
                        </div>

                        {/* Remote Type */}
                        <div className="mb-6">
                            <label className="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Work Mode
                            </label>
                            <select
                                value={data.remote_type}
                                onChange={(e) => setData('remote_type', e.target.value)}
                                className="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 dark:bg-gray-700 dark:text-white"
                            >
                                <option value="">Any Work Mode</option>
                                {dropdownOptions.remote_types && Object.entries(dropdownOptions.remote_types).map(([value, label]) => (
                                    <option key={value} value={value}>{label}</option>
                                ))}
                            </select>
                        </div>

                        {/* Employment Type */}
                        <div className="mb-6">
                            <label className="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Employment Type
                            </label>
                            <select
                                value={data.employment_type}
                                onChange={(e) => setData('employment_type', e.target.value)}
                                className="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 dark:bg-gray-700 dark:text-white"
                            >
                                <option value="">Any Type</option>
                                {dropdownOptions.employment_types && Object.entries(dropdownOptions.employment_types).map(([value, label]) => (
                                    <option key={value} value={value}>{label}</option>
                                ))}
                            </select>
                        </div>

                        {/* Frequency */}
                        <div className="mb-6">
                            <label className="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Notification Frequency <span className="text-red-600">*</span>
                            </label>
                            <div className="flex gap-4">
                                <label className="flex items-center gap-2 cursor-pointer">
                                    <input
                                        type="radio"
                                        value="daily"
                                        checked={data.frequency === 'daily'}
                                        onChange={(e) => setData('frequency', e.target.value)}
                                        className="w-4 h-4 text-emerald-600 focus:ring-emerald-500"
                                    />
                                    <span className="text-sm text-gray-700 dark:text-gray-300">Daily</span>
                                </label>
                                <label className="flex items-center gap-2 cursor-pointer">
                                    <input
                                        type="radio"
                                        value="weekly"
                                        checked={data.frequency === 'weekly'}
                                        onChange={(e) => setData('frequency', e.target.value)}
                                        className="w-4 h-4 text-emerald-600 focus:ring-emerald-500"
                                    />
                                    <span className="text-sm text-gray-700 dark:text-gray-300">Weekly</span>
                                </label>
                            </div>
                        </div>

                        {/* Email Enabled */}
                        <div className="mb-6">
                            <label className="flex items-center gap-2 cursor-pointer">
                                <input
                                    type="checkbox"
                                    checked={data.email_enabled}
                                    onChange={(e) => setData('email_enabled', e.target.checked)}
                                    className="w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500"
                                />
                                <span className="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    Send email notifications
                                </span>
                            </label>
                            <p className="text-xs text-gray-500 ml-6">Receive matching jobs via email</p>
                        </div>

                        {/* Is Active */}
                        <div className="mb-6">
                            <label className="flex items-center gap-2 cursor-pointer">
                                <input
                                    type="checkbox"
                                    checked={data.is_active}
                                    onChange={(e) => setData('is_active', e.target.checked)}
                                    className="w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500"
                                />
                                <span className="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    Alert is active
                                </span>
                            </label>
                            <p className="text-xs text-gray-500 ml-6">Inactive alerts won't send notifications</p>
                        </div>

                        {/* Actions */}
                        <div className="flex gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <button
                                type="submit"
                                disabled={processing}
                                className="flex-1 px-6 py-3 bg-emerald-600 text-white rounded-lg font-semibold hover:bg-emerald-700 transition disabled:opacity-50"
                            >
                                {processing ? 'Saving...' : 'Save Changes'}
                            </button>
                            <Link
                                href="/candidate/job-alerts"
                                className="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg font-semibold hover:bg-gray-200 dark:hover:bg-gray-600 transition"
                            >
                                Cancel
                            </Link>
                        </div>
                    </form>
                </div>
            </div>
        </CandidateLayout>
    );
}
