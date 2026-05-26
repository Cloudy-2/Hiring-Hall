<?php

namespace App\Http\Controllers\Moderator;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    protected function ensureAdmin(Request $request)
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

        $action = $request->input('action');
        $userId = $request->input('user_id');
        $modelType = $request->input('model_type');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $query = ActivityLog::with('user');

        if ($action) {
            $query->action($action);
        }

        if ($userId) {
            $query->forUser($userId);
        }

        if ($modelType) {
            $query->forModel($modelType);
        }

        if ($dateFrom && $dateTo) {
            $query->dateRange($dateFrom, $dateTo);
        } elseif ($dateFrom) {
            $query->where('created_at', '>=', $dateFrom);
        } elseif ($dateTo) {
            $query->where('created_at', '<=', $dateTo);
        }

        $logs = $query->latest()->paginate(50)->withQueryString();

        $actions = ActivityLog::distinct()->pluck('action');
        $modelTypes = ActivityLog::distinct()->whereNotNull('model_type')->pluck('model_type');
        $users = User::orderBy('name')->get(['id', 'name', 'email']);

        return view('moderator.activity-logs.index', compact(
            'logs',
            'actions',
            'modelTypes',
            'users',
            'action',
            'userId',
            'modelType',
            'dateFrom',
            'dateTo'
        ));
    }

    public function show(Request $request, ActivityLog $activityLog)
    {
        $this->ensureAdmin($request);

        $activityLog->load('user');

        return view('moderator.activity-logs.show', compact('activityLog'));
    }

    public function export(Request $request)
    {
        $this->ensureAdmin($request);

        $action = $request->input('action');
        $userId = $request->input('user_id');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $query = ActivityLog::with('user');

        if ($action) {
            $query->action($action);
        }
        if ($userId) {
            $query->forUser($userId);
        }
        if ($dateFrom && $dateTo) {
            $query->dateRange($dateFrom, $dateTo);
        }

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="activity_logs_'.date('Y-m-d').'.csv"',
        ];

        $callback = function () use ($query) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'User', 'Action', 'Model', 'Description', 'IP Address', 'Date']);

            $query->orderBy('created_at', 'desc')->chunk(100, function ($logs) use ($file) {
                foreach ($logs as $log) {
                    fputcsv($file, [
                        $log->id,
                        $log->user ? $log->user->name : 'Guest',
                        $log->action,
                        $log->model_type ? class_basename($log->model_type).' #'.$log->model_id : '-',
                        $log->description,
                        $log->ip_address,
                        $log->created_at->format('Y-m-d H:i:s'),
                    ]);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
