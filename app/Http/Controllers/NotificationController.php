<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $status = $request->input('status', 'all');
        $search = $request->input('q');

        $query = $user->notifications();

        // Status filter
        if ($status === 'unread') {
            $query->whereNull('read_at');
        } elseif ($status === 'read') {
            $query->whereNotNull('read_at');
        }

        // Search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('data->title', 'like', '%'.$search.'%')
                    ->orWhere('data->message', 'like', '%'.$search.'%')
                    ->orWhere('data->subject', 'like', '%'.$search.'%');
            });
        }

        $notifications = $query->paginate(20)->withQueryString();
        $unreadCount = $user->unreadNotifications()->count();

        return view('notifications.index', [
            'notifications' => $notifications,
            'status' => $status,
            'search' => $search,
            'unreadCount' => $unreadCount,
        ]);
    }

    public function readAll(Request $request)
    {
        $request->user()->unreadNotifications()->update(['read_at' => now()]);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'All notifications marked as read']);
        }

        return back()->with('status', 'All notifications marked as read');
    }

    public function markAsRead(Request $request, string $id)
    {
        $notification = DatabaseNotification::where('id', $id)
            ->where('notifiable_id', $request->user()->id)
            ->where('notifiable_type', get_class($request->user()))
            ->firstOrFail();

        $notification->markAsRead();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Notification marked as read']);
        }

        return response()->noContent();
    }

    public function clearAll(Request $request)
    {
        $request->user()->notifications()->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'All notifications cleared']);
        }

        return back()->with('status', 'All notifications cleared');
    }

    public function destroy(Request $request, string $id)
    {
        $notification = DatabaseNotification::where('id', $id)
            ->where('notifiable_id', $request->user()->id)
            ->where('notifiable_type', get_class($request->user()))
            ->firstOrFail();

        $notification->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Notification deleted']);
        }

        return back()->with('status', 'Notification deleted');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'string']);

        DatabaseNotification::whereIn('id', $request->ids)
            ->where('notifiable_id', $request->user()->id)
            ->where('notifiable_type', get_class($request->user()))
            ->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Selected notifications deleted']);
        }

        return back()->with('status', 'Selected notifications deleted');
    }
}
