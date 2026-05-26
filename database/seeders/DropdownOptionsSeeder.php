<?php

namespace Database\Seeders;

use App\Models\DropdownOption;
use Illuminate\Database\Seeder;

class DropdownOptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $options = [
            // Job Categories (for Post a Job)
            ['type' => 'job_category', 'value' => 'operations_va', 'label' => 'Operations VA', 'sort_order' => 1],
            ['type' => 'job_category', 'value' => 'admin_support', 'label' => 'Admin Support', 'sort_order' => 2],
            ['type' => 'job_category', 'value' => 'scheduling_dispatch', 'label' => 'Scheduling / Dispatch', 'sort_order' => 3],
            ['type' => 'job_category', 'value' => 'client_success', 'label' => 'Client Success', 'sort_order' => 4],
            ['type' => 'job_category', 'value' => 'back_office', 'label' => 'Back Office', 'sort_order' => 5],

            // Employment Types
            ['type' => 'employment_type', 'value' => 'full_time', 'label' => 'Full Time', 'sort_order' => 1],
            ['type' => 'employment_type', 'value' => 'part_time', 'label' => 'Part Time', 'sort_order' => 2],
            ['type' => 'employment_type', 'value' => 'contract', 'label' => 'Contract', 'sort_order' => 3],
            ['type' => 'employment_type', 'value' => 'freelance', 'label' => 'Freelance', 'sort_order' => 4],
            ['type' => 'employment_type', 'value' => 'internship', 'label' => 'Internship', 'sort_order' => 5],

            // Remote Types
            ['type' => 'remote_type', 'value' => 'remote', 'label' => 'Remote', 'sort_order' => 1],
            ['type' => 'remote_type', 'value' => 'on_site', 'label' => 'On-site', 'sort_order' => 2],
            ['type' => 'remote_type', 'value' => 'hybrid', 'label' => 'Hybrid', 'sort_order' => 3],
            ['type' => 'remote_type', 'value' => 'flexible', 'label' => 'Flexible', 'sort_order' => 4],
            ['type' => 'remote_type', 'value' => 'work_from_home', 'label' => 'Work From Home', 'sort_order' => 5],

            // Currencies
            ['type' => 'currency', 'value' => 'USD', 'label' => 'USD - US Dollar', 'sort_order' => 1],
            ['type' => 'currency', 'value' => 'PHP', 'label' => 'PHP - Philippine Peso', 'sort_order' => 2],
            ['type' => 'currency', 'value' => 'EUR', 'label' => 'EUR - Euro', 'sort_order' => 3],
            ['type' => 'currency', 'value' => 'GBP', 'label' => 'GBP - British Pound', 'sort_order' => 4],
            ['type' => 'currency', 'value' => 'AUD', 'label' => 'AUD - Australian Dollar', 'sort_order' => 5],

            // Work Setup (for job highlights)
            ['type' => 'work_setup', 'value' => 'work_from_home', 'label' => 'Work From Home', 'sort_order' => 1],
            ['type' => 'work_setup', 'value' => 'office_based', 'label' => 'Office Based', 'sort_order' => 2],
            ['type' => 'work_setup', 'value' => 'hybrid', 'label' => 'Hybrid', 'sort_order' => 3],
            ['type' => 'work_setup', 'value' => 'field_work', 'label' => 'Field Work', 'sort_order' => 4],
            ['type' => 'work_setup', 'value' => 'client_site', 'label' => 'Client Site', 'sort_order' => 5],

            // Shift Schedules
            ['type' => 'shift_schedule', 'value' => 'day_shift', 'label' => 'Day Shift', 'sort_order' => 1],
            ['type' => 'shift_schedule', 'value' => 'night_shift', 'label' => 'Night Shift', 'sort_order' => 2],
            ['type' => 'shift_schedule', 'value' => 'mid_shift', 'label' => 'Mid Shift', 'sort_order' => 3],
            ['type' => 'shift_schedule', 'value' => 'rotating_shift', 'label' => 'Rotating Shift', 'sort_order' => 4],
            ['type' => 'shift_schedule', 'value' => 'flexible_hours', 'label' => 'Flexible Hours', 'sort_order' => 5],

            // Applicant Title/Role
            ['type' => 'applicant_title', 'value' => 'virtual_assistant', 'label' => 'Virtual Assistant', 'sort_order' => 1],
            ['type' => 'applicant_title', 'value' => 'operations_manager', 'label' => 'Operations Manager', 'sort_order' => 2],
            ['type' => 'applicant_title', 'value' => 'admin_assistant', 'label' => 'Administrative Assistant', 'sort_order' => 3],
            ['type' => 'applicant_title', 'value' => 'customer_service', 'label' => 'Customer Service Representative', 'sort_order' => 4],
            ['type' => 'applicant_title', 'value' => 'project_coordinator', 'label' => 'Project Coordinator', 'sort_order' => 5],

            // Work Mode (for candidate profile)
            ['type' => 'work_mode', 'value' => 'remote', 'label' => 'Remote', 'sort_order' => 1],
            ['type' => 'work_mode', 'value' => 'on_site', 'label' => 'On-site', 'sort_order' => 2],
            ['type' => 'work_mode', 'value' => 'hybrid', 'label' => 'Hybrid', 'sort_order' => 3],
            ['type' => 'work_mode', 'value' => 'flexible', 'label' => 'Flexible', 'sort_order' => 4],
            ['type' => 'work_mode', 'value' => 'any', 'label' => 'Any', 'sort_order' => 5],

            // Availability
            ['type' => 'availability', 'value' => 'immediate', 'label' => 'Immediate', 'sort_order' => 1],
            ['type' => 'availability', 'value' => '1_week', 'label' => '1 Week Notice', 'sort_order' => 2],
            ['type' => 'availability', 'value' => '2_weeks', 'label' => '2 Weeks Notice', 'sort_order' => 3],
            ['type' => 'availability', 'value' => '1_month', 'label' => '1 Month Notice', 'sort_order' => 4],
            ['type' => 'availability', 'value' => 'negotiable', 'label' => 'Negotiable', 'sort_order' => 5],

            // Job Type (for candidate preference)
            ['type' => 'job_type', 'value' => 'full_time', 'label' => 'Full Time', 'sort_order' => 1],
            ['type' => 'job_type', 'value' => 'part_time', 'label' => 'Part Time', 'sort_order' => 2],
            ['type' => 'job_type', 'value' => 'contract', 'label' => 'Contract', 'sort_order' => 3],
            ['type' => 'job_type', 'value' => 'freelance', 'label' => 'Freelance', 'sort_order' => 4],
            ['type' => 'job_type', 'value' => 'any', 'label' => 'Any', 'sort_order' => 5],

            // Expertise Categories - Administrative & Operations
            ['type' => 'expertise_category', 'value' => 'Administrative Assistant', 'label' => 'Administrative Assistant', 'sort_order' => 1],
            ['type' => 'expertise_category', 'value' => 'Executive Assistant', 'label' => 'Executive Assistant', 'sort_order' => 2],
            ['type' => 'expertise_category', 'value' => 'Operations Manager', 'label' => 'Operations Manager', 'sort_order' => 3],
            ['type' => 'expertise_category', 'value' => 'Operations Coordinator', 'label' => 'Operations Coordinator', 'sort_order' => 4],
            ['type' => 'expertise_category', 'value' => 'Office Manager', 'label' => 'Office Manager', 'sort_order' => 5],
            ['type' => 'expertise_category', 'value' => 'Virtual Assistant', 'label' => 'Virtual Assistant', 'sort_order' => 6],
            ['type' => 'expertise_category', 'value' => 'Data Entry Specialist', 'label' => 'Data Entry Specialist', 'sort_order' => 7],
            ['type' => 'expertise_category', 'value' => 'Document Controller', 'label' => 'Document Controller', 'sort_order' => 8],

            // Expertise Categories - Finance & Accounting
            ['type' => 'expertise_category', 'value' => 'accountant', 'label' => 'Accountant', 'sort_order' => 9],
            ['type' => 'expertise_category', 'value' => 'bookkeeper', 'label' => 'Bookkeeper', 'sort_order' => 10],
            ['type' => 'expertise_category', 'value' => 'financial_analyst', 'label' => 'Financial Analyst', 'sort_order' => 11],
            ['type' => 'expertise_category', 'value' => 'ap_specialist', 'label' => 'Accounts Payable Specialist', 'sort_order' => 12],
            ['type' => 'expertise_category', 'value' => 'ar_specialist', 'label' => 'Accounts Receivable Specialist', 'sort_order' => 13],
            ['type' => 'expertise_category', 'value' => 'payroll_specialist', 'label' => 'Payroll Specialist', 'sort_order' => 14],
            ['type' => 'expertise_category', 'value' => 'audit_assistant', 'label' => 'Audit Assistant', 'sort_order' => 15],
            ['type' => 'expertise_category', 'value' => 'tax_specialist', 'label' => 'Tax Specialist', 'sort_order' => 16],

            // Expertise Categories - Customer Support / Service
            ['type' => 'expertise_category', 'value' => 'csr', 'label' => 'Customer Service Representative', 'sort_order' => 17],
            ['type' => 'expertise_category', 'value' => 'support_specialist', 'label' => 'Customer Support Specialist', 'sort_order' => 18],
            ['type' => 'expertise_category', 'value' => 'tech_support', 'label' => 'Technical Support Representative', 'sort_order' => 19],
            ['type' => 'expertise_category', 'value' => 'helpdesk', 'label' => 'Help Desk Agent', 'sort_order' => 20],
            ['type' => 'expertise_category', 'value' => 'client_success', 'label' => 'Client Success Manager', 'sort_order' => 21],
            ['type' => 'expertise_category', 'value' => 'call_center', 'label' => 'Call Center Agent', 'sort_order' => 22],

            // Expertise Categories - Sales & Marketing
            ['type' => 'expertise_category', 'value' => 'sales_rep', 'label' => 'Sales Representative', 'sort_order' => 23],
            ['type' => 'expertise_category', 'value' => 'sales_exec', 'label' => 'Sales Executive', 'sort_order' => 24],
            ['type' => 'expertise_category', 'value' => 'account_mgr', 'label' => 'Account Manager', 'sort_order' => 25],
            ['type' => 'expertise_category', 'value' => 'bdr', 'label' => 'Business Development Representative', 'sort_order' => 26],
            ['type' => 'expertise_category', 'value' => 'digital_marketing', 'label' => 'Digital Marketing Specialist', 'sort_order' => 27],
            ['type' => 'expertise_category', 'value' => 'social_media', 'label' => 'Social Media Manager', 'sort_order' => 28],
            ['type' => 'expertise_category', 'value' => 'content_writer', 'label' => 'Content Writer', 'sort_order' => 29],
            ['type' => 'expertise_category', 'value' => 'seo_specialist', 'label' => 'SEO Specialist', 'sort_order' => 30],

            // Expertise Categories - Human Resources
            ['type' => 'expertise_category', 'value' => 'hr_assistant', 'label' => 'HR Assistant', 'sort_order' => 31],
            ['type' => 'expertise_category', 'value' => 'hr_generalist', 'label' => 'HR Generalist', 'sort_order' => 32],
            ['type' => 'expertise_category', 'value' => 'recruiter', 'label' => 'Recruitment Specialist', 'sort_order' => 33],
            ['type' => 'expertise_category', 'value' => 'talent_acq', 'label' => 'Talent Acquisition Specialist', 'sort_order' => 34],
            ['type' => 'expertise_category', 'value' => 'training_coord', 'label' => 'Training Coordinator', 'sort_order' => 35],

            // Expertise Categories - IT & Technical
            ['type' => 'expertise_category', 'value' => 'Software Developer', 'label' => 'Software Developer', 'sort_order' => 36],
            ['type' => 'expertise_category', 'value' => 'Web Developer', 'label' => 'Web Developer', 'sort_order' => 37],
            ['type' => 'expertise_category', 'value' => 'Frontend Developer', 'label' => 'Frontend Developer', 'sort_order' => 38],
            ['type' => 'expertise_category', 'value' => 'Backend Developer', 'label' => 'Backend Developer', 'sort_order' => 39],
            ['type' => 'expertise_category', 'value' => 'Full Stack Developer', 'label' => 'Full Stack Developer', 'sort_order' => 40],
            ['type' => 'expertise_category', 'value' => 'QA Tester', 'label' => 'QA Tester', 'sort_order' => 41],
            ['type' => 'expertise_category', 'value' => 'IT Support Specialist', 'label' => 'IT Support Specialist', 'sort_order' => 42],
            ['type' => 'expertise_category', 'value' => 'System Administrator', 'label' => 'System Administrator', 'sort_order' => 43],

            // Expertise Categories - Project & Coordination
            ['type' => 'expertise_category', 'value' => 'project_coord', 'label' => 'Project Coordinator', 'sort_order' => 44],
            ['type' => 'expertise_category', 'value' => 'project_mgr', 'label' => 'Project Manager', 'sort_order' => 45],
            ['type' => 'expertise_category', 'value' => 'scrum_master', 'label' => 'Scrum Master', 'sort_order' => 46],
            ['type' => 'expertise_category', 'value' => 'product_mgr', 'label' => 'Product Manager', 'sort_order' => 47],

            // Expertise Categories - Creative & Design
            ['type' => 'expertise_category', 'value' => 'Graphic Designer', 'label' => 'Graphic Designer', 'sort_order' => 48],
            ['type' => 'expertise_category', 'value' => 'UI/UX Designer', 'label' => 'UI/UX Designer', 'sort_order' => 49],
            ['type' => 'expertise_category', 'value' => 'Video Editor', 'label' => 'Video Editor', 'sort_order' => 50],
            ['type' => 'expertise_category', 'value' => 'Multimedia Specialist', 'label' => 'Multimedia Specialist', 'sort_order' => 51],

            // Expertise Categories - E-commerce & Admin Support
            ['type' => 'expertise_category', 'value' => 'ecommerce', 'label' => 'E-commerce Specialist', 'sort_order' => 52],
            ['type' => 'expertise_category', 'value' => 'shopify_mgr', 'label' => 'Shopify Manager', 'sort_order' => 53],
            ['type' => 'expertise_category', 'value' => 'product_listing', 'label' => 'Product Listing Specialist', 'sort_order' => 54],
            ['type' => 'expertise_category', 'value' => 'inventory_coord', 'label' => 'Inventory Coordinator', 'sort_order' => 55],

            // Expertise Categories - Specialized Virtual Roles
            ['type' => 'expertise_category', 'value' => 're_va', 'label' => 'Real Estate Virtual Assistant', 'sort_order' => 56],
            ['type' => 'expertise_category', 'value' => 'medical_va', 'label' => 'Medical Virtual Assistant', 'sort_order' => 57],
            ['type' => 'expertise_category', 'value' => 'legal_va', 'label' => 'Legal Virtual Assistant', 'sort_order' => 58],
            ['type' => 'expertise_category', 'value' => 'exec_va', 'label' => 'Executive Virtual Assistant', 'sort_order' => 59],
            ['type' => 'expertise_category', 'value' => 'marketing_va', 'label' => 'Marketing Virtual Assistant', 'sort_order' => 60],

            // ======================================
            // DEPRECATED: Old broad expertise categories (kept for backward compatibility with existing profiles)
            // These are marked inactive to hide from new forms, but old profile data still uses these values
            // ======================================
            ['type' => 'expertise_category', 'value' => 'administrative', 'label' => 'Administrative (Deprecated)', 'sort_order' => 999, 'is_active' => false],
            ['type' => 'expertise_category', 'value' => 'customer_service', 'label' => 'Customer Service (Deprecated)', 'sort_order' => 1000, 'is_active' => false],
            ['type' => 'expertise_category', 'value' => 'operations', 'label' => 'Operations (Deprecated)', 'sort_order' => 1001, 'is_active' => false],
            ['type' => 'expertise_category', 'value' => 'scheduling', 'label' => 'Scheduling & Dispatch (Deprecated)', 'sort_order' => 1002, 'is_active' => false],
            ['type' => 'expertise_category', 'value' => 'data_entry', 'label' => 'Data Entry (Deprecated)', 'sort_order' => 1003, 'is_active' => false],
            ['type' => 'language', 'value' => 'english', 'label' => 'English', 'sort_order' => 1],
            ['type' => 'language', 'value' => 'spanish', 'label' => 'Spanish', 'sort_order' => 2],
            ['type' => 'language', 'value' => 'filipino', 'label' => 'Filipino', 'sort_order' => 3],
            ['type' => 'language', 'value' => 'mandarin', 'label' => 'Mandarin', 'sort_order' => 4],
            ['type' => 'language', 'value' => 'french', 'label' => 'French', 'sort_order' => 5],

            // Industry Types (for Post a Job and Search Jobs filter)
            ['type' => 'industry_type', 'value' => 'research_development', 'label' => 'Research & Development', 'sort_order' => 1],
            ['type' => 'industry_type', 'value' => 'accounting', 'label' => 'Accounting', 'sort_order' => 2],
            ['type' => 'industry_type', 'value' => 'business_process', 'label' => 'Business Process', 'sort_order' => 3],
            ['type' => 'industry_type', 'value' => 'consulting', 'label' => 'Consulting', 'sort_order' => 4],
            ['type' => 'industry_type', 'value' => 'administrative_support', 'label' => 'Administrative Support', 'sort_order' => 5],
            ['type' => 'industry_type', 'value' => 'human_resources', 'label' => 'Human Resources', 'sort_order' => 6],
            ['type' => 'industry_type', 'value' => 'marketing', 'label' => 'Marketing', 'sort_order' => 7],

            // Recruiter Types (for Post a Job and Search Jobs filter)
            ['type' => 'recruiter_type', 'value' => 'direct', 'label' => 'Direct Company', 'sort_order' => 1],
            ['type' => 'recruiter_type', 'value' => 'agency', 'label' => 'Agency', 'sort_order' => 2],
            ['type' => 'recruiter_type', 'value' => 'staffing', 'label' => 'Staffing Firm', 'sort_order' => 3],
            ['type' => 'recruiter_type', 'value' => 'headhunter', 'label' => 'Headhunter', 'sort_order' => 4],
            ['type' => 'recruiter_type', 'value' => 'freelance', 'label' => 'Freelance Recruiter', 'sort_order' => 5],

            // Locations (for Post a Job and Search Jobs filter)
            ['type' => 'location', 'value' => 'usa', 'label' => 'USA (All)', 'sort_order' => 1],
            ['type' => 'location', 'value' => 'philippines', 'label' => 'Philippines', 'sort_order' => 2],
            ['type' => 'location', 'value' => 'uk', 'label' => 'United Kingdom', 'sort_order' => 3],
            ['type' => 'location', 'value' => 'australia', 'label' => 'Australia', 'sort_order' => 4],
            ['type' => 'location', 'value' => 'canada', 'label' => 'Canada', 'sort_order' => 5],
        ];

        foreach ($options as $option) {
            DropdownOption::updateOrCreate(
                ['type' => $option['type'], 'value' => $option['value']],
                [
                    'label' => $option['label'],
                    'sort_order' => $option['sort_order'],
                    'is_active' => $option['is_active'] ?? true,
                ]
            );
        }
    }
}
