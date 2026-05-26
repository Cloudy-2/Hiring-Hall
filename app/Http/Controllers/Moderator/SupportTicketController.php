<?php

namespace App\Http\Controllers\Moderator;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSupportTicketReplyRequest;
use App\Http\Requests\UpdateSupportTicketStatusRequest;
use App\Models\SupportTicket;
use App\Models\SupportTicketAttachment;
use App\Models\SupportTicketReply;
use App\Notifications\NewSupportTicketReplyNotification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupportTicketController extends Controller
{
    protected function ensureModerator(Request $request): \App\Models\User
    {
        $user = $request->user();
        if (! $user || ! $user->isModerator()) {
            abort(403);
        }

        return $user;
    }

    public function index(Request $request): View
    {
        $this->ensureModerator($request);

        $status = $request->input('status');
        $search = trim((string) $request->input('search', ''));
        $query = SupportTicket::with('user')
            ->withCount('replies')
            ->orderByDesc('updated_at');

        $this->applyIndexFilters($query, $status, $search);

        $tickets = $query->paginate(20)->withQueryString();

        $counts = [
            'all' => SupportTicket::count(),
            'pending' => SupportTicket::where('status', 'pending')->count(),
            'in_progress' => SupportTicket::where('status', 'in_progress')->count(),
            'resolved' => SupportTicket::where('status', 'resolved')->count(),
            'closed' => SupportTicket::where('status', 'closed')->count(),
        ];

        return view('moderator.tech-support.index', compact('tickets', 'status', 'counts', 'search'));
    }

    public function updates(Request $request): JsonResponse
    {
        $this->ensureModerator($request);

        $status = $request->input('status');
        $search = trim((string) $request->input('search', ''));
        $sinceInput = $request->input('since');

        $since = null;
        if (is_string($sinceInput) && $sinceInput !== '') {
            try {
                $since = Carbon::parse($sinceInput);
            } catch (\Throwable $e) {
                $since = null;
            }
        }

        $query = SupportTicket::query();
        $this->applyIndexFilters($query, $status, $search);

        if ($since) {
            $query->where('updated_at', '>', $since);
        }

        $updatedCount = (clone $query)->count();
        $latestUpdatedAt = (clone $query)->max('updated_at');

        return response()->json([
            'updated_count' => $updatedCount,
            'latest_updated_at' => $latestUpdatedAt,
            'server_time' => now()->toIso8601String(),
        ]);
    }

    public function show(Request $request, SupportTicket $ticket): View
    {
        $this->ensureModerator($request);

        $ticket->load(['user', 'replies.user', 'replies.attachments', 'attachments']);

        return view('moderator.tech-support.show', compact('ticket'));
    }

    public function updateStatus(UpdateSupportTicketStatusRequest $request, SupportTicket $ticket): RedirectResponse
    {
        $this->ensureModerator($request);

        $ticket->update(['status' => $request->input('status')]);

        return redirect()->route('moderator.tickets.show', $ticket)
            ->with('success', 'Ticket status updated.');
    }

    public function bulkUpdateStatus(UpdateSupportTicketStatusRequest $request): RedirectResponse
    {
        $this->ensureModerator($request);

        $data = $request->validate([
            'ticket_ids' => ['required', 'array', 'min:1'],
            'ticket_ids.*' => ['integer', 'exists:support_tickets,id'],
        ]);

        $status = $request->input('status');
        $ticketIds = array_values(array_unique($data['ticket_ids']));
        $filterStatus = $request->input('filter_status');

        $updated = SupportTicket::whereIn('id', $ticketIds)->update([
            'status' => $status,
            'updated_at' => now(),
        ]);

        return redirect()->route('moderator.tickets.index', array_filter([
            'status' => $filterStatus,
            'search' => $request->input('search'),
        ]))
            ->with('success', "Updated {$updated} ticket(s) to {$status}.");
    }

    public function reply(StoreSupportTicketReplyRequest $request, SupportTicket $ticket): RedirectResponse
    {
        $this->ensureModerator($request);

        $reply = $ticket->replies()->create([
            'user_id' => $request->user()->id,
            'message' => $request->input('message'),
            'is_staff' => true,
        ]);
        $this->storeAttachments($request, $ticket, $reply);

        $reply->load('user');

        $ticket->load('user');
        $ticket->user->notify(new NewSupportTicketReplyNotification($ticket, $reply));

        return redirect()->route('moderator.tickets.show', $ticket)
            ->with('success', 'Reply added as staff.');
    }

    private function storeAttachments(Request $request, SupportTicket $ticket, ?SupportTicketReply $reply): void
    {
        $files = $request->file('attachments');
        if (! is_array($files)) {
            return;
        }

        $basePath = 'support-tickets/'.$ticket->id;
        foreach ($files as $file) {
            if (! $file->isValid()) {
                continue;
            }
            $path = $file->store($basePath, 'local');
            SupportTicketAttachment::create([
                'support_ticket_id' => $ticket->id,
                'support_ticket_reply_id' => $reply?->id,
                'path' => $path,
                'name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
            ]);
        }
    }

    private function applyIndexFilters(Builder $query, ?string $status, string $search): void
    {
        if (in_array($status, ['pending', 'open', 'in_progress', 'resolved', 'closed'], true)) {
            $query->where('status', $status);
        }

        if ($search !== '') {
            $query->where(function (Builder $q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%")
                    ->orWhereHas('user', function (Builder $uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }
    }
}
