<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'static.api.token' => \App\Http\Middleware\StaticApiToken::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\AuditActivity::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->respond(function (Response $response, \Throwable $exception, Request $request) {
            if ($response->getStatusCode() === 419 && $request->is('logout')) {
                return redirect()
                    ->route('login')
                    ->with('status', 'Sesi Anda sudah berakhir. Silakan masuk kembali.');
            }

            if (
                $response->getStatusCode() === 403
                && $request->isMethod('GET')
                && ! $request->expectsJson()
                && ! $request->is('livewire/*')
                && $request->user()
            ) {
                $role = strtoupper(trim((string) ($request->user()->role ?? '')));
                $target = match (true) {
                    in_array($role, ['ADMIN', 'MANAJEMEN', 'MANAJEMEN KANWIL', 'SUPERVISOR'], true) => '/dashboard',
                    in_array($role, ['AO', 'AO_KREDIT', 'AO_DANA', 'AO_REMEDIAL'], true) => '/prospects-diajukan',
                    $role === 'PEGAWAI' => '/prospects',
                    default => null,
                };

                if ($target !== null && '/'.ltrim($request->path(), '/') !== $target) {
                    return redirect($target)
                        ->with('warning', 'Anda diarahkan ke halaman yang sesuai dengan hak akses akun.');
                }

                if ($target === null) {
                    Auth::guard('web')->logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return redirect()
                        ->route('login')
                        ->withErrors(['email' => 'Role akun tidak dikenali. Silakan hubungi administrator.']);
                }
            }

            return $response;
        });
    })
    ->create();
