<?php

namespace App\Http\Controllers\Moderator;

use App\Http\Controllers\Controller;
use App\Models\Chats\MessageReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChatReportController extends Controller
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
        $query = MessageReport::with([
            'message' => fn ($q) => $q->withTrashed(),
            'message.user',
            'reporter',
            'conversation',
            'resolver',
        ])
            ->orderByDesc('created_at');

        if (in_array($status, ['pending', 'resolved', 'dismissed'], true)) {
            $query->where('status', $status);
        }

        $reports = $query->paginate(20)->withQueryString();

        $counts = [
            'all' => MessageReport::count(),
            'pending' => MessageReport::where('status', 'pending')->count(),
            'resolved' => MessageReport::where('status', 'resolved')->count(),
            'dismissed' => MessageReport::where('status', 'dismissed')->count(),
        ];

        return view('moderator.chat-reports.index', compact('reports', 'status', 'counts'));
    }

    public function show(Request $request, MessageReport $report): View
    {
        $this->ensureModerator($request);

        $report->load([
            'message' => fn ($q) => $q->withTrashed(),
            'message.user',
            'reporter',
            'conversation',
            'resolver',
        ]);

        return view('moderator.chat-reports.show', compact('report'));
    }

    public function resolve(Request $request, MessageReport $report): RedirectResponse
    {
        $this->ensureModerator($request);

        $report->update([
            'status' => 'resolved',
            'resolved_by' => $request->user()->id,
            'resolved_at' => now(),
        ]);

        return redirect()
            ->route('moderator.chat-reports.show', $report)
            ->with('success', 'Report marked as resolved.');
    }

    public function dismiss(Request $request, MessageReport $report): RedirectResponse
    {
        $this->ensureModerator($request);

        $report->update([
            'status' => 'dismissed',
            'resolved_by' => $request->user()->id,
            'resolved_at' => now(),
        ]);

        return redirect()
            ->route('moderator.chat-reports.show', $report)
            ->with('success', 'Report dismissed.');
    }
}
