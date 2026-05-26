import { useState, useEffect } from 'react';
import { Head, useForm, router } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function ProfileEdit({ auth, user, profile, dropdownOptions }) {
    const [currentStep, setCurrentStep] = useState(1);
    const [cvFileName, setCvFileName] = useState('');

    const { data, setData, post, processing, errors } = useForm({
        display_name: profile.display_name || '',
        job_title: profile.job_title || profile.title || '',
        location: profile.location || '',
        work_mode: profile.work_mode || '',
        degree: profile.degree || '',
        years_experience: profile.years_experience || 0,
        availability: profile.availability || '',
        job_type: profile.job_type || '',
        expected_salary_min: profile.expected_salary_min || '',
        expected_salary_max: profile.expected_salary_max || '',
        salary_currency: profile.salary_currency || 'USD',
        headline: profile.headline || '',
        about: profile.about || '',
        career_objective: profile.career_objective || '',
        cv_file: null,
        remove_cv: '0',
        education: parseJsonField(profile.education_details) || [{ course: '', school: '', location: '', dates: '' }],
        certifications: parseJsonField(profile.certifications) || [{ title: '', provider: '' }],
        achievements: parseJsonField(profile.key_achievements) || [''],
        activities: parseJsonField(profile.activities_interests) || [''],
        skills: parseJsonField(profile.skills) || [''],
        tools_used: parseJsonField(profile.tools_used) || [''],
        languages: parseJsonField(profile.languages) || [''],
        expertise_categories: parseJsonField(profile.expertise_categories) || [],
        experience_position: '',
        experience_company: '',
        experience_location: '',
        experience_start: '',
        experience_end: '',
        experience_responsibilities: [''],
    });

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

    const steps = [
        { num: 1, label: 'Overview', desc: 'Basic info, summary, and Resume' },
        { num: 2, label: 'Profile Info', desc: 'Education, skills, and more' },
        { num: 3, label: 'Experience', desc: 'Work history and achievements' },
    ];

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('candidate.profile.update'), {
            forceFormData: true,
            onSuccess: () => {
                alert('Profile updated successfully!');
            },
        });
    };

    const handleFileChange = (e) => {
        const file = e.target.files[0];
        if (file) {
            setData('cv_file', file);
            setCvFileName(file.name);
        }
    };

    const addEducationRow = () => {
        setData('education', [...data.education, { course: '', school: '', location: '', dates: '' }]);
    };

    const removeEducationRow = (index) => {
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
            setData('expertise_categories', current.filter(v => v !== value));
        } else {
            setData('expertise_categories', [...current, value]);
        }
    };

    const progressWidth = ((currentStep - 1) / (steps.length - 1)) * 100;

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Edit Profile" />

            <div className="max-w-4xl mx-auto px-4 sm:px-6 py-6">
                <div className="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    
                    {/* Step Indicator */}
                    <div className="mb-6">
                        <h2 className="text-xl font-semibold text-gray-900 dark:text-white mb-1">
                            Step {currentStep} of {steps.length} — {steps[currentStep - 1].label}
                        </h2>
                        <p className="text-sm text-gray-500 dark:text-gray-400">{steps[currentStep - 1].desc}</p>
                    </div>

                    {/* Progress Bar */}
                    <div className="mb-8">
                        <div className="relative w-full max-w-md mx-auto">
                            <div className="absolute left-0 right-0 top-5 h-0.5 rounded-full bg-gray-200 dark:bg-gray-700 overflow-hidden">
                                <div 
                                    className="absolute left-0 top-0 h-full bg-indigo-600 rounded-full transition-all duration-500"
                                    style={{ width: `${progressWidth}%` }}
                                ></div>
                            </div>
                            <div className="relative flex justify-between gap-2">
                                {steps.map((step) => (
                                    <button
                                        key={step.num}
                                        type="button"
                                        onClick={() => setCurrentStep(step.num)}
                                        className="flex flex-col items-center gap-2 flex-1 min-w-0 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-xl py-2 transition-all"
                                    >
                                        <span className={`flex items-center justify-center w-10 h-10 rounded-full border-2 text-sm font-semibold shadow-sm ring-2 ring-white dark:ring-gray-800 ${
                                            currentStep === step.num
                                                ? 'border-indigo-600 bg-white dark:bg-gray-800 text-indigo-600'
                                                : 'border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400'
                                        }`}>
                                            {step.num}
                                        </span>
                                        <span className={`text-xs font-medium truncate w-full text-center ${
                                            currentStep === step.num ? 'text-indigo-600' : 'text-gray-500 dark:text-gray-400'
                                        }`}>
                                            {step.label}
                                        </span>
                                    </button>
                                ))}
                            </div>
                        </div>
                    </div>

                    <form onSubmit={handleSubmit}>
                        {/* STEP 1: Overview */}
                        {currentStep === 1 && (
                            <div className="space-y-6">
                                {/* Basic Information */}
                                <div className="rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 p-6">
                                    <h5 className="font-semibold text-sm text-gray-700 dark:text-white mb-4 flex items-center gap-2">
                                        <i className="ri-user-smile-line text-indigo-600"></i> Basic Information
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
                                            {errors.display_name && <p className="text-red-600 text-xs mt-1">{errors.display_name}</p>}
                                        </div>
