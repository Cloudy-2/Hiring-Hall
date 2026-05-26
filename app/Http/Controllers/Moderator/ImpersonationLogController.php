<?php

namespace App\Http\Controllers\Moderator;

use App\Http\Controllers\Controller;
use App\Models\ImpersonationLog;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ImpersonationLogController extends Controller
{
    protected function ensureAdmin(Request $request): \App\Models\User
    {
        $user = $request->user();
        if (! $user || ! in_array($user->role, ['admin', 'super_admin'])) {
            abort(403);
        }

        return $user;
    }

    public function index(Request $request)
    {
        $this->ensureAdmin($request);

        $query = ImpersonationLog::with(['impersonator', 'targetUser'])->latest('started_at');
        $logs = $query->paginate(30)->withQueryString();

        return view('moderator.impersonation-logs.index', compact('logs'));
    }

    public function export(Request $request): StreamedResponse|Response
    {
        $this->ensureAdmin($request);

        $logs = ImpersonationLog::with(['impersonator', 'targetUser'])
            ->orderBy('started_at')
            ->get();

        $filename = 'impersonation-logs-'.now()->format('Y-m-d-His').'.csv';

        return response()->streamDownload(function () use ($logs) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Started', 'Ended', 'Impersonator (email)', 'Target user (email)', 'Target name']);
            foreach ($logs as $log) {
                fputcsv($out, [
                    $log->started_at->toIso8601String(),
                    $log->ended_at?->toIso8601String() ?? '',
                    $log->impersonator->email ?? '',
                    $log->targetUser->email ?? '',
                    $log->targetUser->name ?? '',
                ]);
            }
            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
