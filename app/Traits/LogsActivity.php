<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    public static function bootLogsActivity()
    {
        foreach (['created','updated','deleted'] as $ev) {
            static::$ev(function ($model) use ($ev) {
                $user = Auth::user();
                $changes = null;
                if ($ev === 'updated') {
                    $changes = [
                        'old' => array_intersect_key($model->getOriginal(), $model->getChanges()),
                        'new' => $model->getChanges()
                    ];
                } elseif ($ev === 'created') {
                    $changes = ['new' => $model->getAttributes()];
                } elseif ($ev === 'deleted') {
                    $changes = ['old' => $model->getAttributes()];
                }
                AuditLog::create([
                    'user_id' => $user?->id,
                    'action' => $ev,
                    'model_type' => get_class($model),
                    'model_id' => $model->getKey(),
                    'changes' => $changes,
                    'ip' => request()->ip() ?? null,
                    'ua' => request()->header('User-Agent')
                ]);
            });
        }
    }
}
