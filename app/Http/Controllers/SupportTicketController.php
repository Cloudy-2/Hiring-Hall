<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSupportTicketReplyRequest;
use App\Http\Requests\StoreSupportTicketRequest;
use App\Models\SupportTicket;
use App\Models\SupportTicketAttachment;
use App\Models\SupportTicketReply;
use App\Models\User;
use App\Notifications\NewSupportTicketNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupportTicketController extends Controller
{
    public function index(Request $request): View
    {
        $tickets = SupportTicket::where('user_id', $request->user()->id)
            ->orderByDesc('updated_at')
            ->paginate(15);

        return view('modules.tech-support.index', compact('tickets'));
    }

    public function create(): View
    {
        return view('modules.tech-support.create');
    }

    public function store(StoreSupportTicketRequest $request): RedirectResponse
    {
        $ticket = SupportTicket::create([
            'user_id' => $request->user()->id,
            'subject' => $request->input('subject'),
            'message' => $request->input('message'),
            'status' => 'pending',
        ]);

        $this->storeAttachments($request, $ticket, null);

        $staff = User::whereIn('role', ['moderator', 'admin', 'super_admin'])->get();
        foreach ($staff as $user) {
            $user->notify(new NewSupportTicketNotification($ticket));
        }

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Support ticket submitted. We will get back to you soon.');
    }

    public function show(Request $request, SupportTicket $ticket): View|RedirectResponse
    {
        if (! $ticket->isOwnedBy($request->user())) {
            abort(403);
        }

        $ticket->load(['replies.user', 'replies.attachments', 'attachments']);

        return view('modules.tech-support.show', compact('ticket'));
    }

    public function reply(StoreSupportTicketReplyRequest $request, SupportTicket $ticket): RedirectResponse
    {
        if (! $ticket->isOwnedBy($request->user())) {
            abort(403);
        }

        $reply = $ticket->replies()->create([
            'user_id' => $request->user()->id,
            'message' => $request->input('message'),
            'is_staff' => false,
        ]);

        $this->storeAttachments($request, $ticket, $reply);

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Reply added.');
    }

    /**
     * @param  array<\Illuminate\Http\UploadedFile>|null  $files
     */
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
}
