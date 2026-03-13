<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $r)
    {
        $r->validate([
            'email' => ['required','email'],
            'password' => ['required','string'],
            'device_name' => ['nullable','string','max:120'],
        ]);

        $user = User::where('email', $r->email)->first();

        if (!$user || !Hash::check($r->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }

        // kalau ada kolom aktif, pastikan aktif
        if (property_exists($user, 'aktif') || isset($user->aktif)) {
            if ((int)$user->aktif !== 1) {
                throw ValidationException::withMessages([
                    'email' => ['Akun tidak aktif. Hubungi admin.'],
                ]);
            }
        }

        $device = $r->device_name ?: ('device-' . substr(md5($r->ip().microtime(true)),0,8));
        $token = $user->createToken($device)->plainTextToken;

        return response()->json([
            'ok' => true,
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'nama_lengkap' => $user->nama_lengkap ?? null,
                'email' => $user->email,
                'role' => $user->role ?? null,
                'cabang_id' => $user->cabang_id ?? null,
                'aktif' => $user->aktif ?? 1,
            ],
        ]);
    }

    public function me(Request $r)
    {
        $u = $r->user();
        return response()->json([
            'ok' => true,
            'user' => [
                'id' => $u->id,
                'name' => $u->name,
                'nama_lengkap' => $u->nama_lengkap ?? null,
                'email' => $u->email,
                'role' => $u->role ?? null,
                'cabang_id' => $u->cabang_id ?? null,
                'aktif' => $u->aktif ?? 1,
            ],
        ]);
    }

    public function logout(Request $r)
    {
        $u = $r->user();
        $u->currentAccessToken()?->delete();

        return response()->json(['ok' => true]);
    }
}
