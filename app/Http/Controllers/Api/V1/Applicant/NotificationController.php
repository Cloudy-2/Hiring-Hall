<?php

namespace App\Http\Controllers\Api\V1\Applicant;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Resources\Api\NotificationResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends ApiController
{
    /**
     * GET /api/v1/applicant/notifications
     *
     * Lists notifications for the applicant (all or unread-only).
     */
    public function index(Request $request): JsonResponse
    {
        $query = $request->user()->notifications();

        if ($request->boolean('unread_only')) {
            $query = $request->user()->unreadNotifications();
        }

        $paginator = $query->latest()->paginate($this->perPage($request, 20));

        return $this->paginated(
            $paginator,
            NotificationResource::collection($paginator->items())
        );
    }

    /**
     * PUT /api/v1/applicant/notifications/{id}/read
     *
     * Marks a single notification as read.
     */
    public function markRead(Request $request, string $id): JsonResponse
    {
        $notification = $request->user()->notifications()->where('id', $id)->first();

        if (! $notification) {
            return $this->notFound('Notification not found.');
        }

        $notification->markAsRead();

        return $this->success(null, 'Notification marked as read.');
    }

    /**
     * POST /api/v1/applicant/notifications/read-all
     *
     * Marks all unread notifications as read.
     */
    public function markAllRead(Request $request): JsonResponse
    {
        $request->user()->unreadNotifications()->update(['read_at' => now()]);

        return $this->success(null, 'All notifications marked as read.');
    }
}
