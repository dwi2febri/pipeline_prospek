<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = auth()->user();
        $role = strtoupper(trim((string) ($user->role ?? '')));

        if (in_array($role, ['ADMIN', 'MANAJEMEN', 'SUPERVISOR'])) {
            return redirect()->route('dashboard');
        }

        if (in_array($role, ['AO', 'AO_KREDIT', 'AO_DANA', 'AO_REMEDIAL'])) {
            return redirect()->route('prospects.submissions');
        }

        if ($role === 'PEGAWAI') {
            return redirect()->route('prospects.index');
        }

        return redirect()->route('prospects.index');
    }
}
