<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\User;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds. Keep in sync with resources/markdown/hiring_hall_faq_rag.md.
     */
    public function run(): void
    {
        $createdBy = User::whereIn('role', ['moderator', 'admin', 'super_admin'])->first()?->id;

        $faqs = [
            // Getting Started
            [
                'question' => 'What is Hiring Hall / this platform?',
                'answer' => 'Hiring Hall is HillBCS’s hiring and recruitment platform. It connects job seekers (applicants) with employers. Applicants browse jobs, apply, and manage their profile and applications. Employers post jobs, search applicants, manage applications, and schedule interviews.',
                'category' => 'Getting Started',
                'sort_order' => 0,
            ],
            [
                'question' => 'What is HillBCS?',
                'answer' => 'Hill Business Consulting Services (HillBCS) powers Hiring Hall. The platform supports a professional hiring workflow: verified employers, structured applications, and help through the Help Desk.',
                'category' => 'Getting Started',
                'sort_order' => 1,
            ],
            [
                'question' => 'How do I create an account?',
                'answer' => 'Click Register and choose Applicant (job seeker) or Employer. Enter your email, name, and password. Verify your email using the link we send. Then complete your profile or company details as guided.',
                'category' => 'Getting Started',
                'sort_order' => 2,
            ],
            [
                'question' => 'What is the difference between an Applicant and an Employer account?',
                'answer' => 'Applicants search for jobs, apply, and manage their resume and profile. Employers post jobs (after company setup and verification), search and save applicants, and manage hiring. Register with the role that matches how you will use the platform.',
                'category' => 'Getting Started',
                'sort_order' => 3,
            ],
            [
                'question' => 'What is SSO? How do I sign in with my company or link my account?',
                'answer' => 'SSO (single sign-on) lets some organizations sign into Hiring Hall with their company identity provider. If your employer uses it, use the SSO or company sign-in option from the login screen—your IT or HR contact usually shares how that works. Linking an extra identity to an account you already have follows the on-screen prompts after you start sign-in; you don’t need to type internal web paths by hand. If something fails, contact Help Desk with your work email so they can see whether SSO is enabled for your org.',
                'category' => 'Getting Started',
                'sort_order' => 4,
            ],

            // For Applicants
            [
                'question' => 'How do I apply for a job?',
                'answer' => 'Open the Jobs section, choose a job, and use Apply. You must be logged in as an applicant. Some jobs require a complete profile or an uploaded resume first. Track applications from your Applications dashboard.',
                'category' => 'For Applicants',
                'sort_order' => 10,
            ],
            [
                'question' => 'How do I edit my applicant profile or resume?',
                'answer' => 'From your dashboard, open Profile or Edit Profile. Update your headline, about, experience, education, skills, and upload or replace your CV. A complete profile helps employers find you in search.',
                'category' => 'For Applicants',
                'sort_order' => 11,
            ],
            [
                'question' => 'Can I save jobs to apply later?',
                'answer' => 'Yes. On job listings or job details, use the save (bookmark or heart) control. View saved jobs from your dashboard and apply when ready.',
                'category' => 'For Applicants',
                'sort_order' => 12,
            ],
            [
                'question' => 'How do I know if my application was viewed or if I have an interview?',
                'answer' => 'Check application status in your Applications area. Interview invites appear under Interviews and in notifications. Keep your email and in-app notifications enabled.',
                'category' => 'For Applicants',
                'sort_order' => 13,
            ],
            [
                'question' => 'Can I withdraw or delete an application?',
                'answer' => 'Yes. From Applications you can withdraw an active application. Withdrawn items may appear in application history; you may be able to remove some records from history depending on the product options available to you.',
                'category' => 'For Applicants',
                'sort_order' => 14,
            ],
            [
                'question' => 'What are recommended jobs?',
                'answer' => 'The platform may suggest jobs that match your profile or activity. Review them on your Recommended Jobs area when that feature is available on your account.',
                'category' => 'For Applicants',
                'sort_order' => 15,
            ],

            // For Employers
            [
                'question' => 'How do I post a job?',
                'answer' => 'Your company should be registered and verified. From the employer dashboard, use Create Job or Post a Job. Complete title, location, category, employment type, description, and other required fields. Submit for review; when approved, the job goes live for applicants.',
                'category' => 'For Employers',
                'sort_order' => 20,
            ],
            [
                'question' => 'How do I search and view applicants?',
                'answer' => 'From the employer dashboard, open Applicants or Search Applicants. Use filters, open profiles to see full details and resumes, save candidates you like, and use rating tools where provided.',
                'category' => 'For Employers',
                'sort_order' => 21,
            ],
            [
                'question' => 'How does company verification work?',
                'answer' => 'After you add your company, the team reviews your information. When verified, you can use full posting and hiring features. While pending, some actions may be limited.',
                'category' => 'For Employers',
                'sort_order' => 22,
            ],
            [
                'question' => 'Can I schedule interviews through the platform?',
                'answer' => 'Yes. From applications or your pipeline, open an application and use the interview scheduling option. Set date and time; the applicant is notified and can see it in their dashboard.',
                'category' => 'For Employers',
                'sort_order' => 23,
            ],
            [
                'question' => 'What is the applications pipeline?',
                'answer' => 'Employers often manage candidates through stages (for example new, reviewed, interview, hired). Use your pipeline or applications view to move and track applicants through those stages.',
                'category' => 'For Employers',
                'sort_order' => 24,
            ],

            // Jobs & Applications
            [
                'question' => 'What job categories and types are available?',
                'answer' => 'Jobs use categories (role families) and employment types (full-time, part-time, contract, etc.). Remote, hybrid, and on-site may be available. Use filters on the Jobs page to narrow results.',
                'category' => 'Jobs & Applications',
                'sort_order' => 30,
            ],
            [
                'question' => 'How long does job approval take?',
                'answer' => 'Submitted jobs are reviewed for guidelines compliance. Timing varies; often within a few business days. You are notified when a job is approved and live.',
                'category' => 'Jobs & Applications',
                'sort_order' => 31,
            ],
            [
                'question' => 'Why was my job posting not approved?',
                'answer' => 'Jobs must meet posting guidelines: clear role, legitimate company, non-discriminatory content, and accurate details. If rejected, you should receive a reason or may contact support for more information.',
                'category' => 'Jobs & Applications',
                'sort_order' => 32,
            ],

            // Account & Security
            [
                'question' => 'How do I reset my password?',
                'answer' => 'On the login page, use Forgot password, enter your email, and follow the reset link. Check spam if you do not see the message. Contact support if you remain locked out.',
                'category' => 'Account & Security',
                'sort_order' => 40,
            ],
            [
                'question' => 'How do I enable two-factor authentication (2FA)?',
                'answer' => 'In account or security settings after login, enable Two-Factor Authentication and follow setup (often with an authenticator app). This adds a second step at sign-in.',
                'category' => 'Account & Security',
                'sort_order' => 41,
            ],
            [
                'question' => 'Who can see my profile or resume?',
                'answer' => 'Applicant profiles and resumes are visible to authenticated employers using the platform for hiring, not as a public marketing site. Use is governed by the privacy policy and terms.',
                'category' => 'Account & Security',
                'sort_order' => 42,
            ],
            [
                'question' => 'How do I change my email or name?',
                'answer' => 'Update profile and account settings from your user menu or profile area. Some changes may require re-verification of your email.',
                'category' => 'Account & Security',
                'sort_order' => 43,
            ],

            // Support & Help
            [
                'question' => 'How do I contact support?',
                'answer' => 'Use Help Desk or Support in the app to open a ticket. Describe your issue clearly. You can also read the FAQ page at /FAQ.',
                'category' => 'Support & Help',
                'sort_order' => 50,
            ],
            [
                'question' => 'Where is the FAQ?',
                'answer' => 'Open Frequently Asked Questions from the help or support menu, or go to /FAQ. Answers are grouped by category.',
                'category' => 'Support & Help',
                'sort_order' => 51,
            ],
            [
                'question' => 'What is Ask Hill AI?',
                'answer' => 'Ask Hill AI is an in-app assistant that answers from the published FAQ knowledge base. It does not access your private data, other users’ data, or live job databases. If something is not in the FAQ, it will direct you to /FAQ or Help Desk.',
                'category' => 'Support & Help',
                'sort_order' => 52,
            ],
            [
                'question' => 'Can Hill AI see my applications or messages?',
                'answer' => 'No. The FAQ assistant does not read your account, applications, chats, or employer records. It only summarizes public FAQ content.',
                'category' => 'Support & Help',
                'sort_order' => 53,
            ],
            [
                'question' => 'I found a bug or want a feature. Where do I report it?',
                'answer' => 'Submit a support ticket with steps to reproduce (for bugs) or a clear description (for ideas). Screenshots help the team respond faster.',
                'category' => 'Support & Help',
                'sort_order' => 54,
            ],
            [
                'question' => 'Are chats with Ask Hill AI saved?',
                'answer' => 'Yes. Your questions and the assistant’s replies are stored so support can improve the FAQ and spot gaps. The assistant still does not read your private account data—only the text you type in chat and the FAQ-based reply.',
                'category' => 'Support & Help',
                'sort_order' => 55,
            ],
            [
                'question' => 'What happens if the chatbot cannot answer my question?',
                'answer' => 'You will get a clear message to use /FAQ or Help Desk. Your question is kept (marked as a training candidate when the FAQ match was weak) so the team can add better FAQ entries later.',
                'category' => 'Support & Help',
                'sort_order' => 56,
            ],

            // Interviews & scheduling
            [
                'question' => 'How do interview invites work for applicants?',
                'answer' => 'When an employer schedules an interview, you receive a notification. Open Interviews in your dashboard to see date, time, and any notes the employer provided. Keep notifications enabled so you do not miss updates.',
                'category' => 'Interviews & scheduling',
                'sort_order' => 70,
            ],
            [
                'question' => 'Can I reschedule or cancel an interview?',
                'answer' => 'Use the interview detail in your dashboard if the product exposes reschedule or cancel actions. If not, message the employer through the platform’s messaging tools if available, or contact Help Desk for help coordinating changes.',
                'category' => 'Interviews & scheduling',
                'sort_order' => 71,
            ],
            [
                'question' => 'How do employers set up interview slots?',
                'answer' => 'From the application or pipeline, choose the candidate and use the interview scheduling flow. Enter date, time, timezone if applicable, and instructions. The applicant is notified automatically when the system sends the invite.',
                'category' => 'Interviews & scheduling',
                'sort_order' => 72,
            ],

            // Profiles, resumes & search
            [
                'question' => 'What file types can I upload for a resume?',
                'answer' => 'Common formats are PDF and Word (DOC/DOCX) where the upload control allows them. Use a clear file name. If upload fails, check file size limits and try PDF for best compatibility.',
                'category' => 'Profiles, resumes & search',
                'sort_order' => 80,
            ],
            [
                'question' => 'Why am I not showing up in employer search?',
                'answer' => 'Your profile may be incomplete, visibility settings may limit discovery, or employers may be using filters that exclude your profile. Complete your headline, skills, and resume, then try again. If you believe there is a technical issue, open a ticket.',
                'category' => 'Profiles, resumes & search',
                'sort_order' => 81,
            ],
            [
                'question' => 'Can employers download my resume?',
                'answer' => 'Employers who can view your profile can typically download or open the resume you attached, consistent with how the product implements applicant profiles. This is intended for hiring purposes under the terms of use.',
                'category' => 'Profiles, resumes & search',
                'sort_order' => 82,
            ],

            // Jobs, salaries & fairness
            [
                'question' => 'Are salaries shown on every job?',
                'answer' => 'Not always. Employers choose whether to display salary or range. When shown, it helps you compare roles. When hidden, you may need to discuss compensation during screening or interview.',
                'category' => 'Jobs, salaries & fairness',
                'sort_order' => 90,
            ],
            [
                'question' => 'Does Hiring Hall allow discriminatory job posts?',
                'answer' => 'No. Listings must comply with applicable laws and platform guidelines. Posts must not discriminate on protected characteristics. Report concerns via Help Desk or moderation channels if you see problematic content.',
                'category' => 'Jobs, salaries & fairness',
                'sort_order' => 91,
            ],
            [
                'question' => 'Can I report a job or employer?',
                'answer' => 'Yes. Use report or support options associated with the listing or company if available, or describe the situation in a support ticket so the team can review it.',
                'category' => 'Jobs, salaries & fairness',
                'sort_order' => 92,
            ],

            // Privacy & data
            [
                'question' => 'Where can I read the privacy policy?',
                'answer' => 'Use the Privacy Policy link in the site footer or account/legal section. It explains how personal data is collected, used, and shared in connection with Hiring Hall.',
                'category' => 'Privacy & data',
                'sort_order' => 100,
            ],
            [
                'question' => 'Can I delete my account?',
                'answer' => 'Account deletion or deactivation, if offered, is managed through account settings or by requesting Help Desk assistance. Some records may be retained where required for legal or fraud-prevention reasons.',
                'category' => 'Privacy & data',
                'sort_order' => 101,
            ],
            [
                'question' => 'Is my data sold to advertisers?',
                'answer' => 'Hiring Hall is built for hiring workflows, not ad targeting. Refer to the privacy policy for the official statement on sharing and sales of personal information.',
                'category' => 'Privacy & data',
                'sort_order' => 102,
            ],

            // Notifications & Platform
            [
                'question' => 'How do notifications work?',
                'answer' => 'The header may show notifications for application updates, interviews, messages, and system items. Open the notification panel to read and mark items read where supported.',
                'category' => 'Notifications & Platform',
                'sort_order' => 60,
            ],
            [
                'question' => 'What browsers are supported?',
                'answer' => 'Use a current version of Chrome, Edge, Firefox, or Safari. Keep JavaScript enabled. Clear cache if the layout behaves oddly after an update.',
                'category' => 'Notifications & Platform',
                'sort_order' => 61,
            ],
            [
                'question' => 'Can I use Hiring Hall on my phone?',
                'answer' => 'Yes. The site is responsive in mobile browsers. Install shortcuts or use “Add to Home Screen” if your browser supports it for quicker access.',
                'category' => 'Notifications & Platform',
                'sort_order' => 62,
            ],
            [
                'question' => 'The page looks broken after an update. What should I do?',
                'answer' => 'Hard-refresh the page (Ctrl+F5 or Cmd+Shift+R), clear site cache, try another browser, and ensure you are not on a restricted network blocking scripts. If it persists, contact Help Desk with your browser name and version.',
                'category' => 'Notifications & Platform',
                'sort_order' => 63,
            ],
            [
                'question' => 'What does “verified employer” mean?',
                'answer' => 'It means the company record passed review so job posts and hiring tools can be used reliably. Verification reduces fraudulent listings and helps applicants trust who they apply to.',
                'category' => 'Notifications & Platform',
                'sort_order' => 64,
            ],
            [
                'question' => 'I am locked out after too many login attempts. What now?',
                'answer' => 'Wait for any cooldown period shown, reset your password from the login page, and contact Help Desk if you still cannot access your account. Do not share passwords with anyone claiming to be support.',
                'category' => 'Notifications & Platform',
                'sort_order' => 65,
            ],
        ];

        foreach ($faqs as $item) {
            Faq::updateOrCreate(
                ['question' => $item['question']],
                [
                    'answer' => $item['answer'],
                    'category' => $item['category'],
                    'sort_order' => $item['sort_order'],
                    'is_active' => true,
                    'created_by' => $createdBy,
                ]
            );
        }
    }
}
