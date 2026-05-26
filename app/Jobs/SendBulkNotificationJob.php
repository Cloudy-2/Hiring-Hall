<?php

namespace App\Jobs;

use App\Models\BulkNotification;
use App\Notifications\BulkMessageNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendBulkNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 600;

    public function __construct(
        public BulkNotification $bulkNotification
    ) {}

    public function handle(): void
    {
        $this->bulkNotification->update(['status' => BulkNotification::STATUS_SENDING]);

        $users = $this->bulkNotification->getTargetUsersQuery()->get();

        $this->bulkNotification->update(['recipient_count' => $users->count()]);

        $sentCount = 0;
        $failedCount = 0;

        foreach ($users as $user) {
            try {
                $user->notify(new BulkMessageNotification(
                    $this->bulkNotification->subject,
                    $this->bulkNotification->message,
                    $this->bulkNotification->notification_type
                ));

                $sentCount++;
            } catch (\Exception $e) {
                $failedCount++;
                \Log::error('Failed to send bulk notification to user '.$user->id.': '.$e->getMessage());
            }
        }

        $this->bulkNotification->update([
            'status' => BulkNotification::STATUS_SENT,
            'sent_at' => now(),
            'sent_count' => $sentCount,
            'failed_count' => $failedCount,
        ]);
    }

    public function failed(\Throwable $exception): void
    {
        $this->bulkNotification->update([
            'status' => BulkNotification::STATUS_FAILED,
        ]);

        \Log::error('Bulk notification job failed: '.$exception->getMessage());
    }
}
