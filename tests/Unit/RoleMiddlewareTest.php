<?php

use App\Http\Middleware\RoleMiddleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

test('role middleware normalizes spaces and letter casing', function () {
    $request = Request::create('/dashboard', 'GET');
    $request->setUserResolver(fn () => (object) [
        'role' => ' admin ',
        'aktif' => 1,
    ]);

    $response = (new RoleMiddleware)->handle(
        $request,
        fn () => new Response('allowed'),
        'ADMIN'
    );

    expect($response->getStatusCode())->toBe(200);
    expect($response->getContent())->toBe('allowed');
});
