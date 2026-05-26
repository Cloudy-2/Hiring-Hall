<?php

namespace App\Http\Controllers\Api\V1\Shared;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Resources\Api\AnnouncementResource;
use App\Models\Announcement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnnouncementController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $query = Announcement::active()
            ->forRole($request->user()?->role)
            ->orderBy('is_pinned', 'desc')
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc');

        $paginator = $query->paginate($this->perPage($request, 20));

        return $this->paginated(
            $paginator,
            AnnouncementResource::collection($paginator->items())
        );
    }

    public function markRead(Request $request): JsonResponse
    {
        $request->user()->forceFill([
            'announcements_last_read_at' => now(),
        ])->save();

        return $this->success([
            'announcements_last_read_at' => $request->user()->announcements_last_read_at?->toIso8601String(),
        ], 'Announcements marked as read.');
    }
}
