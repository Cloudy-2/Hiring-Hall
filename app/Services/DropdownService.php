<?php

namespace App\Services;

use App\Models\DropdownOption;

class DropdownService
{
    /**
     * Get all dropdown options for job posting form
     */
    public static function getJobPostingOptions(): array
    {
        return [
            'categories' => DropdownOption::getOptions(DropdownOption::TYPE_JOB_CATEGORY),
            'employmentTypes' => DropdownOption::getOptions(DropdownOption::TYPE_EMPLOYMENT_TYPE),
            'remoteTypes' => DropdownOption::getOptions(DropdownOption::TYPE_REMOTE_TYPE),
            'currencies' => DropdownOption::getOptions(DropdownOption::TYPE_CURRENCY),
            'workSetups' => DropdownOption::getOptions(DropdownOption::TYPE_WORK_SETUP),
            'shiftSchedules' => DropdownOption::getOptions(DropdownOption::TYPE_SHIFT_SCHEDULE),
            'industryTypes' => DropdownOption::getOptions(DropdownOption::TYPE_INDUSTRY_TYPE),
            'recruiterTypes' => DropdownOption::getOptions(DropdownOption::TYPE_RECRUITER_TYPE),
            'locations' => DropdownOption::getOptions(DropdownOption::TYPE_LOCATION),
            'expertiseCategories' => DropdownOption::getOptions(DropdownOption::TYPE_EXPERTISE_CATEGORY),
        ];
    }

    /**
     * Get all dropdown options for applicant profile form
     */
    public static function getApplicantProfileOptions(): array
    {
        return [
            'titles' => DropdownOption::getOptions(DropdownOption::TYPE_APPLICANT_TITLE),
            'workModes' => DropdownOption::getOptions(DropdownOption::TYPE_WORK_MODE),
            'availabilities' => DropdownOption::getOptions(DropdownOption::TYPE_AVAILABILITY),
            'jobTypes' => DropdownOption::getOptions(DropdownOption::TYPE_JOB_TYPE),
            'expertiseCategories' => DropdownOption::getOptions(DropdownOption::TYPE_EXPERTISE_CATEGORY),
            'languages' => DropdownOption::getOptions(DropdownOption::TYPE_LANGUAGE),
            'currencies' => DropdownOption::getOptions(DropdownOption::TYPE_CURRENCY),
        ];
    }

    /**
     * Get dropdown options for applicant search/browse filters
     * Returns collections instead of key-value arrays for checkbox rendering
     */
    public static function getApplicantFilterOptions(): array
    {
        return [
            'applicant_title' => DropdownOption::getOptionsCollection(DropdownOption::TYPE_APPLICANT_TITLE),
            'work_mode' => DropdownOption::getOptionsCollection(DropdownOption::TYPE_WORK_MODE),
            'availability' => DropdownOption::getOptionsCollection(DropdownOption::TYPE_AVAILABILITY),
            'job_type' => DropdownOption::getOptionsCollection(DropdownOption::TYPE_JOB_TYPE),
            'expertise_category' => DropdownOption::getOptionsCollection(DropdownOption::TYPE_EXPERTISE_CATEGORY),
            'language' => DropdownOption::getOptionsCollection(DropdownOption::TYPE_LANGUAGE),
        ];
    }

    /**
     * Get options for a specific type
     */
    public static function getOptions(string $type): array
    {
        return DropdownOption::getOptions($type);
    }

    /**
     * Get dropdown options for job search/browse filters
     * Returns collections instead of key-value arrays for checkbox rendering
     */
    public static function getJobSearchFilterOptions(): array
    {
        return [
            'industry_type' => DropdownOption::getOptionsCollection(DropdownOption::TYPE_INDUSTRY_TYPE),
            'job_category' => DropdownOption::getOptionsCollection(DropdownOption::TYPE_JOB_CATEGORY),
            'employment_type' => DropdownOption::getOptionsCollection(DropdownOption::TYPE_EMPLOYMENT_TYPE),
            'recruiter_type' => DropdownOption::getOptionsCollection(DropdownOption::TYPE_RECRUITER_TYPE),
            'location' => DropdownOption::getOptionsCollection(DropdownOption::TYPE_LOCATION),
            'expertise_category' => DropdownOption::getOptionsCollection(DropdownOption::TYPE_EXPERTISE_CATEGORY),
        ];
    }
}
