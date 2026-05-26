<?php

namespace App\Notifications;

use App\Models\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CompanyVerificationNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Company $company,
        protected string $decision,
        protected ?string $reason = null,
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $approved = $this->decision === 'approved';

        return [
            'title' => $approved ? 'Company Approved ✅' : 'Company Rejected ❌',
            'message' => $approved
                ? "Your company \"{$this->company->name}\" has been verified! You can now post jobs."
                : "Your company \"{$this->company->name}\" was not approved. Reason: {$this->reason}",
            'action_url' => route('employer.companies.index'),
            'company_id' => $this->company->id,
            'decision' => $this->decision,
        ];
    }
}
