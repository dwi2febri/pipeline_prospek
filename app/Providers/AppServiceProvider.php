<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use App\Support\AuditLogger;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Event::listen(Login::class, function (Login $event) {
            AuditLogger::log(
                action: 'LOGIN',
                type: 'AUTH',
                modelId: $event->user?->id,
                meta: [
                    'username' => $event->user?->name,
                    'role' => $event->user?->role,
                ],
                newValues: [
                    'username' => $event->user?->name,
                    'role' => $event->user?->role,
                ],
                auditableType: 'User',
                auditableId: $event->user?->id
            );
        });

        Event::listen(Logout::class, function (Logout $event) {
            AuditLogger::log(
                action: 'LOGOUT',
                type: 'AUTH',
                modelId: $event->user?->id,
                meta: [
                    'username' => $event->user?->name,
                    'role' => $event->user?->role,
                ],
                oldValues: [
                    'username' => $event->user?->name,
                    'role' => $event->user?->role,
                ],
                auditableType: 'User',
                auditableId: $event->user?->id
            );
        });
    }
}
