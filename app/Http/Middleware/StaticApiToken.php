<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StaticApiToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $authHeader = $request->header('Authorization', '');

        if (!$authHeader) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Token tidak ditemukan.',
            ], 401);
        }

        if (!preg_match('/Bearer\s+(.+)/i', $authHeader, $matches)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Format token harus Bearer TOKEN.',
            ], 401);
        }

        $token = trim($matches[1]);
        $validToken = env('PRIVATE_API_TOKEN');

        if (!$validToken || !hash_equals($validToken, $token)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Token tidak valid.',
            ], 401);
        }

        return $next($request);
    }
}
