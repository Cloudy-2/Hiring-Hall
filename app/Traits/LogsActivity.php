<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait LogsActivity
{
    protected static function bootLogsActivity(): void
    {
        static::created(function ($model) {
            if (static::shouldLogActivity()) {
                ActivityLog::log(
                    'created',
                    $model,
                    null,
                    $model->getAttributes(),
                    static::getActivityDescription('created', $model)
                );
            }
        });

        static::updated(function ($model) {
            if (static::shouldLogActivity()) {
                $changes = $model->getChanges();
                if (! empty($changes)) {
                    ActivityLog::log(
                        'updated',
                        $model,
                        $model->getOriginal(),
                        $model->getAttributes(),
                        static::getActivityDescription('updated', $model)
                    );
                }
            }
        });

        static::deleted(function ($model) {
            if (static::shouldLogActivity()) {
                ActivityLog::log(
                    'deleted',
                    $model,
                    $model->getAttributes(),
                    null,
                    static::getActivityDescription('deleted', $model)
                );
            }
        });
    }

    protected static function shouldLogActivity(): bool
    {
        return true;
    }

    protected static function getActivityDescription(string $action, $model): ?string
    {
        $modelName = class_basename($model);

        return "{$modelName} #{$model->id} was {$action}";
    }

    public function getActivityLogs()
    {
        return ActivityLog::where('model_type', get_class($this))
            ->where('model_id', $this->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
