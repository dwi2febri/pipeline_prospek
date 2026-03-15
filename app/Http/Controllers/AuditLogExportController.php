<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogExportController extends Controller
{
    public function export(Request $request)
    {
        $search = trim((string) $request->get('search', ''));

        $q = AuditLog::query()->with('user')->latest('id');

        if ($search !== '') {
            $s = '%' . $search . '%';
            $q->where(function ($w) use ($s) {
                $w->where('action', 'like', $s)
                  ->orWhere('type', 'like', $s)
                  ->orWhere('model_id', 'like', $s)
                  ->orWhere('ip', 'like', $s)
                  ->orWhere('ip_address', 'like', $s)
                  ->orWhere('actor_name', 'like', $s)
                  ->orWhere('auditable_type', 'like', $s)
                  ->orWhere('auditable_id', 'like', $s)
                  ->orWhere('meta', 'like', $s);
            });
        }

        $filename = 'audit_logs_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($q) {
            $handle = fopen('php://output', 'w');

            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'Waktu',
                'Action',
                'Actor',
                'Actor ID',
                'Type',
                'Auditable Type',
                'Auditable ID',
                'Model ID',
                'IP',
                'User Agent',
                'Old Values',
                'New Values',
                'Meta',
            ]);

            $q->chunk(500, function ($rows) use ($handle) {
                foreach ($rows as $l) {
                    fputcsv($handle, [
                        optional($l->created_at)->format('d/m/Y H:i:s'),
                        $l->action,
                        $l->actor_name ?? ($l->user->name ?? '-'),
                        $l->actor_id,
                        $l->type,
                        $l->auditable_type,
                        $l->auditable_id,
                        $l->model_id,
                        $l->ip ?? $l->ip_address,
                        $l->user_agent,
                        $l->old_values,
                        $l->new_values,
                        $l->meta,
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
