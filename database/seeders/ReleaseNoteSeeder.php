<?php

namespace Database\Seeders;

use App\Models\ReleaseNote;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ReleaseNoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $createdBy = User::whereIn('role', ['moderator', 'admin', 'super_admin'])->first()?->id;

        $notes = [
            [
                'version' => '1.0.0',
                'title' => 'Release notes, moderator tools, and FAQ',
                'body' => "This release includes:\n\n• Release notes: View release notes from the app; moderators can create and manage them.\n• Impersonation logs: Moderators can see when staff impersonate users for support.\n• Chat reports: New moderator area to review and manage chat reports.\n• Settings: Moderators can manage app settings from the dashboard.\n• FAQ: Updated FAQ content and categories; new seeder for consistent help content.\n• Moderator area: Improved navigation, applicant and company verification, job moderation, and dropdown options.\n• UI updates: Refinements to moderator applicant/company/job pages, saved candidates, and sidemenu for candidates and employers.",
                'released_at' => Carbon::parse('2026-02-20'),
                'is_published' => true,
            ],
            [
                'version' => '1.1.0',
                'title' => 'Terms agreements, company contact details, and onboarding updates',
                'body' => "This release includes:\n\n• Terms and agreements: Companies and applicants can agree to terms when registering or applying; agreement timestamps are stored for companies, job applications, and user data-sharing consent.\n• Company contact: Contact person email and phone fields added to company profiles for clearer communication.\n• Moderator: New agreement view for companies so moderators can see when and how companies agreed to terms.\n• Onboarding: Applicant and employer onboarding flows updated with agreement steps and modals (company creation, onboarding, and applicant flows).\n• Agency config: New configuration for agency-related settings.",
                'released_at' => Carbon::parse('2026-02-21'),
                'is_published' => true,
            ],
            [
                'version' => '1.1.1',
                'title' => 'Minor fixes: Saved Applicants page and agreement modals',
                'body' => "This release includes:\n\n• Saved Applicants: Fixed error on the employer Saved Applicants page so the list loads correctly.\n• Agreement modals: Checkbox now shows a visible check mark when agreed; improved accessibility (Escape to close, focus management, loading state on submit).",
                'released_at' => Carbon::parse('2026-02-21'),
                'is_published' => true,
            ],
            [
                'version' => '1.2.0',
                'title' => 'Guest browsing, moderator header, and terms agreement refinements',
                'body' => "This release includes:\n\n• Browse jobs as guest: UI improvements when viewing jobs without logging in; fixes and polish for the guest experience.\n• Moderator header: Added header to the moderator account area for clearer navigation.\n• Terms and agreements: Refactored agreement checkboxes and flows; terms agreement implemented for onboarding and registration.\n• Migrations: Prevented duplicate table creation in migrations; migration fixes for a cleaner schema.",
                'released_at' => Carbon::parse('2026-02-21'),
                'is_published' => true,
            ],
            [
                'version' => '1.3.0',
                'title' => 'Moderator KPIs, job duplicate prevention, and email verification',
                'body' => "This release includes:\n\n• Moderator dashboard: KPI cards enhanced with colors and clickable navigation for quick access to key areas.\n• Job duplicate prevention: Employers can no longer create duplicate active job postings from the same template; must close existing job first.\n• Agreement and terms: Modal UI enhancements for a clearer consent experience.\n• Guest apply flow: Applying as guest now redirects to login when terms are required, for a consistent flow.\n• Email verification: Verify-email page no longer shows Edit Profile or update-email links; profile page requires verified email (direct URL access blocked until verified).",
                'released_at' => Carbon::parse('2026-02-22'),
                'is_published' => true,
            ],
            [
                'version' => '1.4.0',
                'title' => 'Guest mode blur, activity on job, and chat emoji fix',
                'body' => "This release includes:\n\n• Guest mode: Company name and details are blurred when browsing jobs as a guest to encourage sign-up.\n• Activity on this job: New indicator on job listings showing when there is recent activity on a job.\n• Job highlights: Fixed data text display for job highlight fields.\n• Chat: Emoji display fix for chat messages.\n• Release notes: Clarified release dates for versions 1.0.0 through 1.3.0.",
                'released_at' => Carbon::parse('2026-02-22'),
                'is_published' => true,
            ],
            [
                'version' => '1.5.0',
                'title' => 'Job postings soft delete, employer jobs redesign, and applicant photos',
                'body' => "This release includes:\n\n• Job postings soft delete: Deleting a job now moves it to trash instead of removing it permanently; new Deleted tab on Job Postings lets you restore or permanently delete jobs.\n• Employer Job Postings page: Redesigned to match the All Applications page (hero, section, toolbar, table, and filters). Company logo shown next to each job.\n• Duplicate job: Removed the duplicate-job action from the employer jobs list.\n• Job delete: Fixed job delete so it works correctly; delete uses the proper route and error handling.\n• Employer Applications: Applicant profile photo is now shown in the applications table when available (with initials fallback).",
                'released_at' => now(),
                'is_published' => true,
            ],
        ];

        foreach ($notes as $note) {
            ReleaseNote::updateOrCreate(
                ['version' => $note['version']],
                [
                    'title' => $note['title'],
                    'body' => $note['body'],
                    'released_at' => $note['released_at'],
                    'is_published' => $note['is_published'],
                    'created_by' => $createdBy,
                ]
            );
        }
    }
}
