import { useEffect, useMemo, useState } from 'react';
import { Head, useForm } from '@inertiajs/react';
import CandidateLayout from '@/Layouts/CandidateLayout';

export default function ProfileEdit({ auth, profile, dropdownOptions }) {
    const [currentStep, setCurrentStep] = useState(1);
    const [cvFileName, setCvFileName] = useState('');

    function parseJsonField(field) {
        if (!field) return null;
        if (typeof field === 'string') {
            try {
                return JSON.parse(field);
            } catch (e) {
                return null;
            }
        }
        return field;
    }

    function ensureArray(value, fallback) {
        if (Array.isArray(value) && value.length > 0) {
            return value;
        }
        return fallback;
    }

    const experience = useMemo(() => {
        const parsed = parseJsonField(profile?.experience_overview);
        return parsed && typeof parsed === 'object' ? parsed : {};
    }, [profile?.experience_overview]);

    const experienceResponsibilities = ensureArray(
        Array.isArray(experience?.responsibilities) ? experience.responsibilities : null,
        [''],
    );

    const { data, setData, post, processing, errors } = useForm({
        display_name: profile?.display_name || '',
        job_title: profile?.job_title || profile?.title || '',
        location: profile?.location || '',
        work_mode: profile?.work_mode || '',
        degree: profile?.degree || '',
        years_experience: profile?.years_experience || 0,
        availability: profile?.availability || '',
        job_type: profile?.job_type || '',
        expected_salary_min: profile?.expected_salary_min || '',
        expected_salary_max: profile?.expected_salary_max || '',
        salary_currency: profile?.salary_currency || 'USD',
        headline: profile?.headline || '',
        about: profile?.about || '',
        career_objective: profile?.career_objective || '',
        cv_file: null,
        remove_cv: '0',
        education: ensureArray(parseJsonField(profile?.education_details), [
            { course: '', school: '', location: '', dates: '' },
        ]),
        certifications: ensureArray(parseJsonField(profile?.certifications), [
            { title: '', provider: '' },
        ]),
        achievements: ensureArray(parseJsonField(profile?.key_achievements), ['']),
        activities: ensureArray(parseJsonField(profile?.activities_interests), ['']),
        skills: ensureArray(parseJsonField(profile?.skills), ['']),
        tools_used: ensureArray(parseJsonField(profile?.tools_used), ['']),
        languages: ensureArray(parseJsonField(profile?.languages), ['']),
        expertise_categories: ensureArray(parseJsonField(profile?.expertise_categories), []),
        references: ensureArray(parseJsonField(profile?.references_block), [
            {
                name: '',
                designation: '',
                company: '',
                mobile: '',
                email: '',
                location: '',
            },
        ]),
        experience_position: experience?.position || '',
        experience_company: experience?.company || '',
        experience_location: experience?.location || '',
        experience_start: experience?.start_date || '',
        experience_end: experience?.end_date || '',
        experience_current: !experience?.end_date && (experience?.position || experience?.company || experience?.location) ? true : false,
        experience_responsibilities: experienceResponsibilities,
    });

    useEffect(() => {
        if (profile?.cv_path && !cvFileName) {
            setCvFileName('Resume uploaded');
        }
    }, [profile?.cv_path, cvFileName]);

    const steps = [
        { num: 1, label: 'Overview', desc: 'Basic info, summary, and resume' },
        { num: 2, label: 'Profile Info', desc: 'Education, skills, and more' },
        { num: 3, label: 'Experience', desc: 'Work history and achievements' },
    ];

    const progressWidth = ((currentStep - 1) / (steps.length - 1)) * 100;

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('candidate.profile.update'), {
            forceFormData: true,
        });
    };

    const handleFileChange = (e) => {
        const file = e.target.files[0];
        if (file) {
            setData('cv_file', file);
            setCvFileName(file.name);
            setData('remove_cv', '0');
        }
    };

    const handleRemoveCv = () => {
        setData('cv_file', null);
        setData('remove_cv', '1');
        setCvFileName('');
    };

    const addEducationRow = () => {
        setData('education', [...data.education, { course: '', school: '', location: '', dates: '' }]);
    };

    const removeEducationRow = (index) => {
        if (data.education.length <= 1) return;
        setData('education', data.education.filter((_, i) => i !== index));
    };

    const updateEducation = (index, field, value) => {
        const updated = [...data.education];
        updated[index][field] = value;
        setData('education', updated);
    };

    const addCertification = () => {
        setData('certifications', [...data.certifications, { title: '', provider: '' }]);
    };

    const removeCertification = (index) => {
        if (data.certifications.length <= 1) return;
        setData('certifications', data.certifications.filter((_, i) => i !== index));
    };

    const updateCertification = (index, field, value) => {
        const updated = [...data.certifications];
        updated[index][field] = value;
        setData('certifications', updated);
    };

    const addArrayItem = (field) => {
        setData(field, [...data[field], '']);
    };

    const removeArrayItem = (field, index) => {
        if (data[field].length <= 1) return;
        setData(field, data[field].filter((_, i) => i !== index));
    };

    const updateArrayItem = (field, index, value) => {
        const updated = [...data[field]];
        updated[index] = value;
        setData(field, updated);
    };

    const toggleExpertise = (value) => {
        const current = data.expertise_categories || [];
        if (current.includes(value)) {
            setData('expertise_categories', current.filter((v) => v !== value));
        } else {
            setData('expertise_categories', [...current, value]);
        }
    };

    const addReference = () => {
        setData('references', [
            ...data.references,
            {
                name: '',
                designation: '',
                company: '',
                mobile: '',
                email: '',
                location: '',
            },
        ]);
    };

    const removeReference = (index) => {
        if (data.references.length <= 1) return;
        setData('references', data.references.filter((_, i) => i !== index));
    };

    const updateReference = (index, field, value) => {
        const updated = [...data.references];
        updated[index][field] = value;
        setData('references', updated);
    };

    const addExperienceResponsibility = () => {
        setData('experience_responsibilities', [...data.experience_responsibilities, '']);
    };

    const removeExperienceResponsibility = (index) => {
        if (data.experience_responsibilities.length <= 1) return;
        setData(
            'experience_responsibilities',
            data.experience_responsibilities.filter((_, i) => i !== index),
        );
    };

    const updateExperienceResponsibility = (index, value) => {
        const updated = [...data.experience_responsibilities];
        updated[index] = value;
        setData('experience_responsibilities', updated);
    };

    const workModeOptions = dropdownOptions?.workModes || {};
    const availabilityOptions = dropdownOptions?.availabilities || {};
    const jobTypeOptions = dropdownOptions?.jobTypes || {};
    const currencyOptions = dropdownOptions?.currencies || {};
    const expertiseOptions = dropdownOptions?.expertiseCategories || {};

    return (
        <CandidateLayout user={auth.user}>
            <Head title="Edit Profile" />

            <div className="max-w-4xl mx-auto px-4 sm:px-6 py-6">
                <div className="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div className="mb-6">
                        <h2 className="text-xl font-semibold text-gray-900 dark:text-white mb-1">
                            Step {currentStep} of {steps.length} — {steps[currentStep - 1].label}
                        </h2>
                        <p className="text-sm text-gray-500 dark:text-gray-400">
                            {steps[currentStep - 1].desc}
                        </p>
                    </div>

                    <div className="mb-8">
                        <div className="relative w-full max-w-md mx-auto">
                            <div className="absolute left-0 right-0 top-5 h-0.5 rounded-full bg-gray-200 dark:bg-gray-700 overflow-hidden">
                                <div
                                    className="absolute left-0 top-0 h-full bg-indigo-600 rounded-full transition-all duration-500"
                                    style={{ width: `${progressWidth}%` }}
                                />
                            </div>
                            <div className="relative flex justify-between gap-2">
                                {steps.map((step) => (
                                    <button
                                        key={step.num}
                                        type="button"
                                        onClick={() => setCurrentStep(step.num)}
                                        className="flex flex-col items-center gap-2 flex-1 min-w-0 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-xl py-2 transition-all"
                                    >
                                        <span
                                            className={`flex items-center justify-center w-10 h-10 rounded-full border-2 text-sm font-semibold shadow-sm ring-2 ring-white dark:ring-gray-800 ${
                                                currentStep >= step.num
                                                    ? 'border-indigo-600 bg-white dark:bg-gray-800 text-indigo-600'
                                                    : 'border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400'
                                            }`}
                                        >
                                            {step.num}
                                        </span>
                                        <span
                                            className={`text-xs font-medium truncate w-full text-center ${
                                                currentStep >= step.num
                                                    ? 'text-indigo-600'
                                                    : 'text-gray-500 dark:text-gray-400'
                                            }`}
                                        >
                                            {step.label}
                                        </span>
                                    </button>
                                ))}
                            </div>
                        </div>
                    </div>

                    <form onSubmit={handleSubmit}>
                        {currentStep === 1 && (
                            <div className="space-y-6">
                                <div className="rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 p-6">
                                    <h5 className="font-semibold text-sm text-gray-700 dark:text-white mb-4 flex items-center gap-2">
                                        <i className="ri-user-smile-line text-indigo-600" /> Basic Information
                                    </h5>
                                    <div className="grid grid-cols-12 gap-4">
                                        <div className="col-span-12 md:col-span-6">
                                            <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                Display Name <span className="text-red-600">*</span>
                                            </label>
                                            <input
                                                type="text"
                                                value={data.display_name}
                                                onChange={(e) => setData('display_name', e.target.value)}
                                                className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white"
                                                required
                                            />
                                            {errors.display_name && (
                                                <p className="text-red-600 text-xs mt-1">{errors.display_name}</p>
                                            )}
                                        </div>
                                        <div className="col-span-12 md:col-span-6">
                                            <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                Job Title <span className="text-red-600">*</span>
                                            </label>
                                            <input
                                                type="text"
                                                value={data.job_title}
                                                onChange={(e) => setData('job_title', e.target.value)}
                                                className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white"
                                                required
                                            />
                                        </div>
                                        <div className="col-span-12 md:col-span-6">
                                            <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                Location <span className="text-red-600">*</span>
                                            </label>
                                            <input
                                                type="text"
                                                value={data.location}
                                                onChange={(e) => setData('location', e.target.value)}
                                                className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white"
                                                required
                                            />
                                        </div>
                                        <div className="col-span-12 md:col-span-6">
                                            <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                Work Mode <span className="text-red-600">*</span>
                                            </label>
                                            <select
                                                value={data.work_mode}
                                                onChange={(e) => setData('work_mode', e.target.value)}
                                                className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white"
                                                required
                                            >
                                                <option value="" disabled>
                                                    Select work mode
                                                </option>
                                                {Object.entries(workModeOptions).map(([value, label]) => (
                                                    <option key={value} value={value}>
                                                        {label}
                                                    </option>
                                                ))}
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div className="rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 p-6">
                                    <h5 className="font-semibold text-sm text-gray-700 dark:text-white mb-4 flex items-center gap-2">
                                        <i className="ri-briefcase-line text-indigo-600" /> Qualifications & Preferences
                                    </h5>
                                    <div className="grid grid-cols-12 gap-4">
                                        <div className="col-span-12 md:col-span-8">
                                            <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                Degree / Qualification <span className="text-red-600">*</span>
                                            </label>
                                            <input
                                                type="text"
                                                value={data.degree}
                                                onChange={(e) => setData('degree', e.target.value)}
                                                className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white"
                                                required
                                            />
                                        </div>
                                        <div className="col-span-12 md:col-span-4">
                                            <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                Years of Exp. <span className="text-red-600">*</span>
                                            </label>
                                            <input
                                                type="number"
                                                min="0"
                                                value={data.years_experience}
                                                onChange={(e) => setData('years_experience', e.target.value)}
                                                className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white"
                                                required
                                            />
                                        </div>
                                        <div className="col-span-12 md:col-span-6">
                                            <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                Availability <span className="text-red-600">*</span>
                                            </label>
                                            <select
                                                value={data.availability}
                                                onChange={(e) => setData('availability', e.target.value)}
                                                className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white"
                                                required
                                            >
                                                <option value="" disabled>
                                                    When can you start?
                                                </option>
                                                {Object.entries(availabilityOptions).map(([value, label]) => (
                                                    <option key={value} value={value}>
                                                        {label}
                                                    </option>
                                                ))}
                                            </select>
                                        </div>
                                        <div className="col-span-12 md:col-span-6">
                                            <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                Job Type <span className="text-red-600">*</span>
                                            </label>
                                            <select
                                                value={data.job_type}
                                                onChange={(e) => setData('job_type', e.target.value)}
                                                className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white"
                                                required
                                            >
                                                <option value="" disabled>
                                                    Preferred job type
                                                </option>
                                                {Object.entries(jobTypeOptions).map(([value, label]) => (
                                                    <option key={value} value={value}>
                                                        {label}
                                                    </option>
                                                ))}
                                            </select>
                                        </div>
                                        <div className="col-span-12">
                                            <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Expected Salary Scale <span className="text-red-600">*</span>
                                            </label>
                                            <div className="flex flex-wrap gap-2">
                                                <select
                                                    value={data.salary_currency}
                                                    onChange={(e) => setData('salary_currency', e.target.value)}
                                                    className="w-full sm:w-32 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white"
                                                    required
                                                >
                                                    {Object.entries(currencyOptions).map(([value, label]) => (
                                                        <option key={value} value={value}>
                                                            {label}
                                                        </option>
                                                    ))}
                                                </select>
                                                <input
                                                    type="number"
                                                    step="0.01"
                                                    placeholder="Minimum"
                                                    value={data.expected_salary_min}
                                                    onChange={(e) => setData('expected_salary_min', e.target.value)}
                                                    className="flex-1 min-w-[120px] px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white"
                                                    required
                                                />
                                                <input
                                                    type="number"
                                                    step="0.01"
                                                    placeholder="Maximum"
                                                    value={data.expected_salary_max}
                                                    onChange={(e) => setData('expected_salary_max', e.target.value)}
                                                    className="flex-1 min-w-[120px] px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white"
                                                    required
                                                />
                                            </div>
                                            <p className="mt-1.5 text-xs text-gray-500">
                                                Specify your desired monthly or yearly salary range in selected currency.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div className="rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 p-6">
                                    <h5 className="font-semibold text-sm text-gray-700 dark:text-white mb-4 flex items-center gap-2">
                                        <i className="ri-quote-text text-indigo-600" /> Summary
                                    </h5>
                                    <div className="mb-4">
                                        <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Headline <span className="text-red-600">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            value={data.headline}
                                            onChange={(e) => setData('headline', e.target.value)}
                                            className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white"
                                            required
                                        />
                                    </div>
                                    <div>
                                        <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            About Me <span className="text-red-600">*</span>
                                        </label>
                                        <textarea
                                            rows="4"
                                            value={data.about}
                                            onChange={(e) => setData('about', e.target.value)}
                                            className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white"
                                            required
                                        />
                                    </div>
                                </div>

                                <div className="rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 p-6">
                                    <h5 className="font-semibold text-sm text-gray-700 dark:text-white mb-4 flex items-center gap-2">
                                        <i className="ri-file-upload-line text-indigo-600" /> Resume
                                    </h5>
                                    <div className="flex flex-col sm:flex-row sm:items-center gap-4">
                                        <div className="flex-1">
                                            <input
                                                type="file"
                                                accept=".pdf,.doc,.docx"
                                                onChange={handleFileChange}
                                                className="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                            />
                                            {cvFileName && (
                                                <p className="text-xs text-indigo-600 mt-2 truncate">{cvFileName}</p>
                                            )}
                                        </div>
                                        {profile?.cv_path && (
                                            <button
                                                type="button"
                                                onClick={handleRemoveCv}
                                                className="inline-flex items-center gap-2 px-3 py-2 text-sm rounded-lg border border-red-200 text-red-600 hover:bg-red-50"
                                            >
                                                Remove
                                            </button>
                                        )}
                                    </div>
                                </div>
                            </div>
                        )}

                        {currentStep === 2 && (
                            <div className="space-y-6">
                                <div className="rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 p-6">
                                    <h5 className="font-semibold text-sm text-gray-700 dark:text-white mb-3 flex items-center gap-2">
                                        <i className="ri-target-line text-indigo-600" /> Career Objective
                                    </h5>
                                    <textarea
                                        rows="4"
                                        value={data.career_objective}
                                        onChange={(e) => setData('career_objective', e.target.value)}
                                        className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white"
                                    />
                                </div>

                                <div className="rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 p-6">
                                    <div className="flex items-center justify-between mb-4">
                                        <h5 className="font-semibold text-sm text-gray-700 dark:text-white flex items-center gap-2">
                                            <i className="ri-graduation-cap-line text-indigo-600" /> Education
                                        </h5>
                                        <button
                                            type="button"
                                            onClick={addEducationRow}
                                            className="text-sm font-semibold text-indigo-600 hover:text-indigo-700"
                                        >
                                            Add Degree
                                        </button>
                                    </div>
                                    <div className="space-y-4">
                                        {data.education.map((edu, idx) => (
                                            <div
                                                key={`edu-${idx}`}
                                                className="p-4 border border-gray-200 dark:border-gray-700 rounded-lg"
                                            >
                                                <div className="flex justify-end">
                                                    <button
                                                        type="button"
                                                        onClick={() => removeEducationRow(idx)}
                                                        className="text-xs text-gray-500 hover:text-red-600"
                                                    >
                                                        Remove
                                                    </button>
                                                </div>
                                                <div className="grid grid-cols-12 gap-3">
                                                    <div className="col-span-12 md:col-span-6">
                                                        <label className="text-xs font-semibold text-gray-500">Course</label>
                                                        <input
                                                            type="text"
                                                            value={edu.course}
                                                            onChange={(e) => updateEducation(idx, 'course', e.target.value)}
                                                            className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg"
                                                        />
                                                    </div>
                                                    <div className="col-span-12 md:col-span-6">
                                                        <label className="text-xs font-semibold text-gray-500">School</label>
                                                        <input
                                                            type="text"
                                                            value={edu.school}
                                                            onChange={(e) => updateEducation(idx, 'school', e.target.value)}
                                                            className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg"
                                                        />
                                                    </div>
                                                    <div className="col-span-12 md:col-span-7">
                                                        <label className="text-xs font-semibold text-gray-500">Location</label>
                                                        <input
                                                            type="text"
                                                            value={edu.location}
                                                            onChange={(e) => updateEducation(idx, 'location', e.target.value)}
                                                            className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg"
                                                        />
                                                    </div>
                                                    <div className="col-span-12 md:col-span-5">
                                                        <label className="text-xs font-semibold text-gray-500">Dates</label>
                                                        <input
                                                            type="text"
                                                            value={edu.dates}
                                                            onChange={(e) => updateEducation(idx, 'dates', e.target.value)}
                                                            className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg"
                                                        />
                                                    </div>
                                                </div>
                                            </div>
                                        ))}
                                    </div>
                                </div>

                                <div className="rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 p-6">
                                    <h5 className="font-semibold text-sm text-gray-700 dark:text-white mb-4 flex items-center gap-2">
                                        <i className="ri-folder-settings-line text-indigo-600" /> Expertise / Categories
                                    </h5>
                                    <div className="grid grid-cols-12 gap-2">
                                        {Object.entries(expertiseOptions).map(([value, label]) => (
                                            <label
                                                key={value}
                                                className="col-span-12 sm:col-span-6 lg:col-span-4 inline-flex items-center gap-2 p-2 rounded-lg border border-transparent hover:border-indigo-200 hover:bg-indigo-50 cursor-pointer"
                                            >
                                                <input
                                                    type="checkbox"
                                                    checked={data.expertise_categories.includes(value)}
                                                    onChange={() => toggleExpertise(value)}
                                                />
                                                <span className="text-sm">{label}</span>
                                            </label>
                                        ))}
                                    </div>
                                </div>

                                <div className="rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 p-6">
                                    <div className="flex items-center justify-between mb-3">
                                        <h5 className="font-semibold text-sm text-gray-700 dark:text-white flex items-center gap-2">
                                            <i className="ri-lightbulb-line text-indigo-600" /> Skills
                                        </h5>
                                        <button
                                            type="button"
                                            onClick={() => addArrayItem('skills')}
                                            className="text-sm font-semibold text-indigo-600 hover:text-indigo-700"
                                        >
                                            Add Skill
                                        </button>
                                    </div>
                                    <div className="space-y-2">
                                        {data.skills.map((skill, idx) => (
                                            <div key={`skill-${idx}`} className="flex gap-2 items-center">
                                                <input
                                                    type="text"
                                                    value={skill}
                                                    onChange={(e) => updateArrayItem('skills', idx, e.target.value)}
                                                    className="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg"
                                                />
                                                <button
                                                    type="button"
                                                    onClick={() => removeArrayItem('skills', idx)}
                                                    className="text-xs text-gray-500 hover:text-red-600"
                                                >
                                                    Remove
                                                </button>
                                            </div>
                                        ))}
                                    </div>
                                </div>

                                <div className="rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 p-6">
                                    <div className="flex items-center justify-between mb-3">
                                        <h5 className="font-semibold text-sm text-gray-700 dark:text-white flex items-center gap-2">
                                            <i className="ri-tools-line text-indigo-600" /> Tools Used
                                        </h5>
                                        <button
                                            type="button"
                                            onClick={() => addArrayItem('tools_used')}
                                            className="text-sm font-semibold text-indigo-600 hover:text-indigo-700"
                                        >
                                            Add Tool
                                        </button>
                                    </div>
                                    <div className="space-y-2">
                                        {data.tools_used.map((tool, idx) => (
                                            <div key={`tool-${idx}`} className="flex gap-2 items-center">
                                                <input
                                                    type="text"
                                                    value={tool}
                                                    onChange={(e) => updateArrayItem('tools_used', idx, e.target.value)}
                                                    className="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg"
                                                />
                                                <button
                                                    type="button"
                                                    onClick={() => removeArrayItem('tools_used', idx)}
                                                    className="text-xs text-gray-500 hover:text-red-600"
                                                >
                                                    Remove
                                                </button>
                                            </div>
                                        ))}
                                    </div>
                                </div>

                                <div className="rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 p-6">
                                    <div className="flex items-center justify-between mb-3">
                                        <h5 className="font-semibold text-sm text-gray-700 dark:text-white flex items-center gap-2">
                                            <i className="ri-global-line text-indigo-600" /> Languages
                                        </h5>
                                        <button
                                            type="button"
                                            onClick={() => addArrayItem('languages')}
                                            className="text-sm font-semibold text-indigo-600 hover:text-indigo-700"
                                        >
                                            Add Language
                                        </button>
                                    </div>
                                    <div className="space-y-2">
                                        {data.languages.map((lang, idx) => (
                                            <div key={`lang-${idx}`} className="flex gap-2 items-center">
                                                <input
                                                    type="text"
                                                    value={lang}
                                                    onChange={(e) => updateArrayItem('languages', idx, e.target.value)}
                                                    className="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg"
                                                />
                                                <button
                                                    type="button"
                                                    onClick={() => removeArrayItem('languages', idx)}
                                                    className="text-xs text-gray-500 hover:text-red-600"
                                                >
                                                    Remove
                                                </button>
                                            </div>
                                        ))}
                                    </div>
                                </div>

                                <div className="rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 p-6">
                                    <div className="flex items-center justify-between mb-3">
                                        <h5 className="font-semibold text-sm text-gray-700 dark:text-white flex items-center gap-2">
                                            <i className="ri-award-line text-indigo-600" /> Certifications
                                        </h5>
                                        <button
                                            type="button"
                                            onClick={addCertification}
                                            className="text-sm font-semibold text-indigo-600 hover:text-indigo-700"
                                        >
                                            Add Certification
                                        </button>
                                    </div>
                                    <div className="space-y-3">
                                        {data.certifications.map((cert, idx) => (
                                            <div
                                                key={`cert-${idx}`}
                                                className="p-3 border border-gray-200 dark:border-gray-700 rounded-lg"
                                            >
                                                <div className="flex justify-end">
                                                    <button
                                                        type="button"
                                                        onClick={() => removeCertification(idx)}
                                                        className="text-xs text-gray-500 hover:text-red-600"
                                                    >
                                                        Remove
                                                    </button>
                                                </div>
                                                <div className="grid grid-cols-12 gap-3">
                                                    <div className="col-span-12 md:col-span-7">
                                                        <label className="text-xs font-semibold text-gray-500">Title</label>
                                                        <input
                                                            type="text"
                                                            value={cert.title}
                                                            onChange={(e) => updateCertification(idx, 'title', e.target.value)}
                                                            className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg"
                                                        />
                                                    </div>
                                                    <div className="col-span-12 md:col-span-5">
                                                        <label className="text-xs font-semibold text-gray-500">Provider</label>
                                                        <input
                                                            type="text"
                                                            value={cert.provider}
                                                            onChange={(e) => updateCertification(idx, 'provider', e.target.value)}
                                                            className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg"
                                                        />
                                                    </div>
                                                </div>
                                            </div>
                                        ))}
                                    </div>
                                </div>

                                <div className="rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 p-6">
                                    <div className="flex items-center justify-between mb-3">
                                        <h5 className="font-semibold text-sm text-gray-700 dark:text-white flex items-center gap-2">
                                            <i className="ri-trophy-line text-indigo-600" /> Key Achievements
                                        </h5>
                                        <button
                                            type="button"
                                            onClick={() => addArrayItem('achievements')}
                                            className="text-sm font-semibold text-indigo-600 hover:text-indigo-700"
                                        >
                                            Add Bullet
                                        </button>
                                    </div>
                                    <div className="space-y-2">
                                        {data.achievements.map((line, idx) => (
                                            <div key={`ach-${idx}`} className="flex gap-2 items-center">
                                                <input
                                                    type="text"
                                                    value={line}
                                                    onChange={(e) => updateArrayItem('achievements', idx, e.target.value)}
                                                    className="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg"
                                                />
                                                <button
                                                    type="button"
                                                    onClick={() => removeArrayItem('achievements', idx)}
                                                    className="text-xs text-gray-500 hover:text-red-600"
                                                >
                                                    Remove
                                                </button>
                                            </div>
                                        ))}
                                    </div>
                                </div>

                                <div className="rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 p-6">
                                    <div className="flex items-center justify-between mb-3">
                                        <h5 className="font-semibold text-sm text-gray-700 dark:text-white flex items-center gap-2">
                                            <i className="ri-heart-line text-indigo-600" /> Activities & Interests
                                        </h5>
                                        <button
                                            type="button"
                                            onClick={() => addArrayItem('activities')}
                                            className="text-sm font-semibold text-indigo-600 hover:text-indigo-700"
                                        >
                                            Add Bullet
                                        </button>
                                    </div>
                                    <div className="space-y-2">
                                        {data.activities.map((line, idx) => (
                                            <div key={`act-${idx}`} className="flex gap-2 items-center">
                                                <input
                                                    type="text"
                                                    value={line}
                                                    onChange={(e) => updateArrayItem('activities', idx, e.target.value)}
                                                    className="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg"
                                                />
                                                <button
                                                    type="button"
                                                    onClick={() => removeArrayItem('activities', idx)}
                                                    className="text-xs text-gray-500 hover:text-red-600"
                                                >
                                                    Remove
                                                </button>
                                            </div>
                                        ))}
                                    </div>
                                </div>

                                <div className="rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 p-6">
                                    <div className="flex items-center justify-between mb-3">
                                        <h5 className="font-semibold text-sm text-gray-700 dark:text-white flex items-center gap-2">
                                            <i className="ri-user-shared-line text-indigo-600" /> References
                                        </h5>
                                        <button
                                            type="button"
                                            onClick={addReference}
                                            className="text-sm font-semibold text-indigo-600 hover:text-indigo-700"
                                        >
                                            Add Reference
                                        </button>
                                    </div>
                                    <div className="space-y-4">
                                        {data.references.map((ref, idx) => (
                                            <div
                                                key={`ref-${idx}`}
                                                className="p-4 border border-gray-200 dark:border-gray-700 rounded-lg"
                                            >
                                                <div className="flex justify-end">
                                                    <button
                                                        type="button"
                                                        onClick={() => removeReference(idx)}
                                                        className="text-xs text-gray-500 hover:text-red-600"
                                                    >
                                                        Remove
                                                    </button>
                                                </div>
                                                <div className="grid grid-cols-12 gap-3">
                                                    <div className="col-span-12 md:col-span-6">
                                                        <label className="text-xs font-semibold text-gray-500">Name</label>
                                                        <input
                                                            type="text"
                                                            value={ref.name}
                                                            onChange={(e) => updateReference(idx, 'name', e.target.value)}
                                                            className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg"
                                                        />
                                                    </div>
                                                    <div className="col-span-12 md:col-span-6">
                                                        <label className="text-xs font-semibold text-gray-500">Designation</label>
                                                        <input
                                                            type="text"
                                                            value={ref.designation}
                                                            onChange={(e) => updateReference(idx, 'designation', e.target.value)}
                                                            className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg"
                                                        />
                                                    </div>
                                                    <div className="col-span-12 md:col-span-6">
                                                        <label className="text-xs font-semibold text-gray-500">Company</label>
                                                        <input
                                                            type="text"
                                                            value={ref.company}
                                                            onChange={(e) => updateReference(idx, 'company', e.target.value)}
                                                            className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg"
                                                        />
                                                    </div>
                                                    <div className="col-span-12 md:col-span-6">
                                                        <label className="text-xs font-semibold text-gray-500">Mobile</label>
                                                        <input
                                                            type="text"
                                                            value={ref.mobile}
                                                            onChange={(e) => updateReference(idx, 'mobile', e.target.value)}
                                                            className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg"
                                                        />
                                                    </div>
                                                    <div className="col-span-12 md:col-span-6">
                                                        <label className="text-xs font-semibold text-gray-500">Email</label>
                                                        <input
                                                            type="email"
                                                            value={ref.email}
                                                            onChange={(e) => updateReference(idx, 'email', e.target.value)}
                                                            className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg"
                                                        />
                                                    </div>
                                                    <div className="col-span-12 md:col-span-6">
                                                        <label className="text-xs font-semibold text-gray-500">Location</label>
                                                        <input
                                                            type="text"
                                                            value={ref.location}
                                                            onChange={(e) => updateReference(idx, 'location', e.target.value)}
                                                            className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg"
                                                        />
                                                    </div>
                                                </div>
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            </div>
                        )}

                        {currentStep === 3 && (
                            <div className="space-y-6">
                                <div className="rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 p-6">
                                    <h5 className="font-semibold text-sm text-gray-700 dark:text-white mb-4 flex items-center gap-2">
                                        <i className="ri-briefcase-4-line text-indigo-600" /> Experience Overview
                                    </h5>
                                    <div className="grid grid-cols-12 gap-4">
                                        <div className="col-span-12">
                                            <label className="text-xs font-semibold text-gray-500">Position / Title</label>
                                            <input
                                                type="text"
                                                value={data.experience_position}
                                                onChange={(e) => setData('experience_position', e.target.value)}
                                                className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg"
                                                required
                                            />
                                        </div>
                                        <div className="col-span-12 md:col-span-6">
                                            <label className="text-xs font-semibold text-gray-500">Company Name</label>
                                            <input
                                                type="text"
                                                value={data.experience_company}
                                                onChange={(e) => setData('experience_company', e.target.value)}
                                                className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg"
                                                required
                                            />
                                        </div>
                                        <div className="col-span-12 md:col-span-6">
                                            <label className="text-xs font-semibold text-gray-500">Location</label>
                                            <input
                                                type="text"
                                                value={data.experience_location}
                                                onChange={(e) => setData('experience_location', e.target.value)}
                                                className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg"
                                                required
                                            />
                                        </div>
                                        <div className="col-span-6">
                                            <label className="text-xs font-semibold text-gray-500">Start Date</label>
                                            <input
                                                type="date"
                                                value={data.experience_start}
                                                onChange={(e) => setData('experience_start', e.target.value)}
                                                className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg"
                                                required
                                            />
                                        </div>
                                        <div className="col-span-6">
                                            <label className="text-xs font-semibold text-gray-500">End Date</label>
                                            <input
                                                type="date"
                                                value={data.experience_end}
                                                onChange={(e) => setData('experience_end', e.target.value)}
                                                className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg"
                                                disabled={data.experience_current}
                                            />
                                        </div>
                                        <div className="col-span-12">
                                            <label className="inline-flex items-center gap-2 text-sm text-gray-500">
                                                <input
                                                    type="checkbox"
                                                    checked={data.experience_current}
                                                    onChange={(e) => {
                                                        setData('experience_current', e.target.checked);
                                                        if (e.target.checked) {
                                                            setData('experience_end', '');
                                                        }
                                                    }}
                                                />
                                                I currently work here
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div className="rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 p-6">
                                    <div className="flex items-center justify-between mb-3">
                                        <label className="text-xs font-semibold text-gray-500">Key Responsibilities</label>
                                        <button
                                            type="button"
                                            onClick={addExperienceResponsibility}
                                            className="text-sm font-semibold text-indigo-600 hover:text-indigo-700"
                                        >
                                            Add Bullet
                                        </button>
                                    </div>
                                    <div className="space-y-2">
                                        {data.experience_responsibilities.map((resp, idx) => (
                                            <div key={`resp-${idx}`} className="flex gap-2 items-center">
                                                <input
                                                    type="text"
                                                    value={resp}
                                                    onChange={(e) => updateExperienceResponsibility(idx, e.target.value)}
                                                    className="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg"
                                                    required
                                                />
                                                <button
                                                    type="button"
                                                    onClick={() => removeExperienceResponsibility(idx)}
                                                    className="text-xs text-gray-500 hover:text-red-600"
                                                >
                                                    Remove
                                                </button>
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            </div>
                        )}

                        <div className="flex flex-col-reverse sm:flex-row justify-between items-center gap-4 mt-10 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <div>
                                <button
                                    type="button"
                                    onClick={() => setCurrentStep((step) => Math.max(1, step - 1))}
                                    className="px-4 py-2 text-sm rounded-lg border border-gray-300 text-gray-600"
                                    disabled={currentStep === 1}
                                >
                                    Previous
                                </button>
                            </div>
                            <div className="flex gap-3">
                                {currentStep < steps.length && (
                                    <button
                                        type="button"
                                        onClick={() => setCurrentStep((step) => Math.min(steps.length, step + 1))}
                                        className="px-4 py-2 text-sm rounded-lg bg-indigo-600 text-white"
                                    >
                                        Next
                                    </button>
                                )}
                                {currentStep === steps.length && (
                                    <button
                                        type="submit"
                                        className="px-4 py-2 text-sm rounded-lg bg-indigo-600 text-white"
                                        disabled={processing}
                                    >
                                        {processing ? 'Saving...' : 'Save Profile'}
                                    </button>
                                )}
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </CandidateLayout>
    );
}
