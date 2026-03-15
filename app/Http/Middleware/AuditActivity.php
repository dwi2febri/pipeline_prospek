<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Support\AuditLogger;

class AuditActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        try {
            if (!auth()->check()) {
                return $response;
            }

            $path = ltrim($request->path(), '/');

            $skipPrefixes = [
                '_debugbar',
                'livewire',
                'livewire/update',
                'livewire/upload-file',
                'vendor/livewire',
                'build',
                'storage',
                'favicon.ico',
            ];

            foreach ($skipPrefixes as $prefix) {
                if (str_starts_with($path, $prefix)) {
                    return $response;
                }
            }

            $routeName = $request->route()?->getName();

            $payload = $request->except([
                '_token',
                'password',
                'password_confirmation',
                'current_password',
            ]);

            AuditLogger::log(
                action: 'ACCESS ' . strtoupper($request->method()) . ' ' . $request->path(),
                type: 'REQUEST',
                modelId: null,
                meta: [
                    'route' => $routeName,
                    'query' => $request->query(),
                    'payload' => $payload,
                    'status_code' => $response->getStatusCode(),
                ],
                auditableType: 'REQUEST',
                auditableId: null
            );
        } catch (\Throwable $e) {
        }

        return $response;
    }
}
