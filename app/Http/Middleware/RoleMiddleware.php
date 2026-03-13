<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(401);
        }

        $role = strtoupper((string)($user->role ?? ''));

        // kalau tidak ada parameter role, anggap lolos
        if (count($roles) === 0) {
            return $next($request);
        }

        $allowed = array_map(function ($r) {
            return strtoupper((string)$r);
        }, $roles);

        if (!in_array($role, $allowed, true)) {
            abort(403);
        }

        // optional: block user nonaktif
        if ((int)($user->aktif ?? 1) !== 1) {
            abort(403, 'User nonaktif.');
        }

        return $next($request);
    }
}
