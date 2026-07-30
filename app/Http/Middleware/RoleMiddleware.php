<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401);
        }

        $role = strtoupper(trim((string) ($user->role ?? '')));

        // User nonaktif tidak boleh tertahan pada halaman error 403.
        if ((int) ($user->aktif ?? 1) !== 1) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->withErrors(['email' => 'Akun Anda sudah tidak aktif.']);
        }

        // kalau tidak ada parameter role, anggap lolos
        if ($roles === []) {
            return $next($request);
        }

        $allowed = array_map(
            fn ($allowedRole) => strtoupper(trim((string) $allowedRole)),
            $roles
        );

        if (! in_array($role, $allowed, true)) {
            abort(403);
        }

        return $next($request);
    }
}
