<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AuditLog;

class AuditActivity
{
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }

    public function terminate(Request $request, $response): void
    {
        // hanya log aksi yang mengubah data
        $method = strtoupper($request->method());
        $shouldLog = in_array($method, ['POST','PUT','PATCH','DELETE'], true);

        // Livewire update adalah POST, ini termasuk (bagus untuk audit)
        if (!$shouldLog) return;

        // jangan log endpoint API kalau kamu tidak mau (silakan hapus if ini kalau mau)
        if ($request->is('api/*')) return;

        $user = $request->user();

        $action = $method.' '.$request->path();
        $type = null;
        $modelId = null;
        $meta = null;

        // --- Deteksi Livewire call supaya lebih informatif ---
        if ($request->is('livewire/*') && $request->has('components')) {
            $components = $request->input('components', []);
            $c0 = $components[0] ?? [];

            $name = $c0['snapshot'] ?? null; // snapshot json string
            $calls = $c0['calls'] ?? [];

            $methodName = $calls[0]['method'] ?? null;
            $params = $calls[0]['params'] ?? [];

            // coba ambil nama komponen dari snapshot
            $componentName = null;
            if (is_string($name)) {
                $decoded = json_decode($name, true);
                $componentName = $decoded['memo']['name'] ?? null;
            }

            $action = 'LIVEWIRE '.($componentName ?: 'component').' '.($methodName ?: '-');

            // coba tebak "id" dari params pertama kalau numerik
            if (isset($params[0]) && (is_int($params[0]) || ctype_digit((string)$params[0]))) {
                $modelId = (string)$params[0];
            }

            $meta = json_encode([
                'component' => $componentName,
                'method' => $methodName,
                'params' => $params,
            ], JSON_UNESCAPED_UNICODE);
        } else {
            // non-livewire: simpan ringkas payload
            $payload = $request->except(['password','password_confirmation','_token']);
            $meta = json_encode([
                'route' => $request->path(),
                'payload' => $payload,
            ], JSON_UNESCAPED_UNICODE);
        }

        // simpan
        AuditLog::create([
            'user_id' => $user?->id,
            'actor_name' => $user?->name,
            'action' => $action,
            'type' => $type,
            'model_id' => $modelId,
            'ip' => $request->ip(),
            'user_agent' => substr((string)$request->userAgent(), 0, 500),
            'meta' => $meta,
        ]);
    }
}
