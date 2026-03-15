<?php

namespace App\Support;

use App\Models\AuditLog;

class AuditLogger
{
    public static function log(
        string $action,
        ?string $type = null,
        $modelId = null,
        $meta = null,
        $oldValues = null,
        $newValues = null,
        ?string $auditableType = null,
        $auditableId = null
    ): void {
        try {
            $user = auth()->user();

            AuditLog::create([
                'actor_id'       => $user?->id,
                'actor_name'     => $user?->name ?? 'guest',
                'action'         => $action,
                'type'           => $type,
                'auditable_type' => $auditableType,
                'auditable_id'   => $auditableId !== null ? (string) $auditableId : null,
                'old_values'     => is_array($oldValues) || is_object($oldValues)
                    ? json_encode($oldValues, JSON_UNESCAPED_UNICODE)
                    : ($oldValues !== null ? (string) $oldValues : null),
                'new_values'     => is_array($newValues) || is_object($newValues)
                    ? json_encode($newValues, JSON_UNESCAPED_UNICODE)
                    : ($newValues !== null ? (string) $newValues : null),
                'ip_address'     => request()->ip(),
                'user_agent'     => request()->userAgent(),
                'meta'           => is_array($meta) || is_object($meta)
                    ? json_encode($meta, JSON_UNESCAPED_UNICODE)
                    : ($meta !== null ? (string) $meta : null),
                'model_id'       => $modelId !== null ? (string) $modelId : null,
                'ip'             => request()->ip(),
            ]);
        } catch (\Throwable $e) {
            // biar audit gagal tidak bikin aplikasi error
        }
    }
}
