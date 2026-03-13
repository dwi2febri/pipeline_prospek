<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $cabangName = '-';
        if ($user && $user->cabang_id) {
            $cabang = Cabang::find($user->cabang_id);
            $cabangName = $cabang ? ($cabang->kode_cabang . ' - ' . $cabang->nama_cabang) : '-';
        }

        return view('profile.index', compact('cabangName'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'current_password.required' => 'Password lama wajib diisi.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Password lama tidak sesuai.',
            ])->withInput();
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('ok', 'Password berhasil diperbarui.');
    }
}
