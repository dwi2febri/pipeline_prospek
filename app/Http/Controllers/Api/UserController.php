<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Support\Role;

class UserController extends Controller
{
    private function mustAdmin(Request $r): void
    {
        if (!Role::isAdmin($r->user())) abort(403, 'Admin only');
    }

    public function index(Request $r)
    {
        $this->mustAdmin($r);

        $q = User::query()->with('cabang')->latest('id');

        if ($r->filled('search')) {
            $s = '%'.$r->query('search').'%';
            $q->where(function($w) use ($s){
                $w->where('name','like',$s)
                  ->orWhere('nama_lengkap','like',$s)
                  ->orWhere('email','like',$s)
                  ->orWhere('role','like',$s);
            });
        }

        $items = $q->paginate((int)($r->query('per_page', 12)));
        return response()->json(['ok'=>true,'items'=>$items]);
    }

    public function store(Request $r)
    {
        $this->mustAdmin($r);

        $data = $r->validate([
            'name' => ['required','string','max:80'],
            'nama_lengkap' => ['nullable','string','max:150'],
            'email' => ['required','email','max:120','unique:users,email'],
            'role' => ['required','in:ADMIN,CABANG,PEGAWAI,AO_KREDIT,AO_DANA,AO_REMEDIAL'],
            'cabang_id' => ['nullable','integer','exists:cabangs,id'],
            'aktif' => ['nullable','in:0,1'],
            'password' => ['required','string','min:6'],
        ]);

        $u = new User();
        $u->name = $data['name'];
        $u->nama_lengkap = $data['nama_lengkap'] ?? null;
        $u->email = $data['email'];
        $u->role = $data['role'];
        $u->cabang_id = $data['cabang_id'] ?? null;
        $u->aktif = isset($data['aktif']) ? (int)$data['aktif'] : 1;
        $u->password = Hash::make($data['password']);
        $u->save();

        return response()->json(['ok'=>true,'item'=>$u], 201);
    }

    public function update(Request $r, $id)
    {
        $this->mustAdmin($r);

        $u = User::findOrFail((int)$id);

        $data = $r->validate([
            'name' => ['required','string','max:80'],
            'nama_lengkap' => ['nullable','string','max:150'],
            'email' => ['required','email','max:120','unique:users,email,'.$u->id],
            'role' => ['required','in:ADMIN,CABANG,PEGAWAI,AO_KREDIT,AO_DANA,AO_REMEDIAL'],
            'cabang_id' => ['nullable','integer','exists:cabangs,id'],
            'aktif' => ['nullable','in:0,1'],
            'password' => ['nullable','string','min:6'],
        ]);

        $u->name = $data['name'];
        $u->nama_lengkap = $data['nama_lengkap'] ?? null;
        $u->email = $data['email'];
        $u->role = $data['role'];
        $u->cabang_id = $data['cabang_id'] ?? null;
        if (isset($data['aktif'])) $u->aktif = (int)$data['aktif'];

        if (!empty($data['password'])) {
            $u->password = Hash::make($data['password']);
        }

        $u->save();

        return response()->json(['ok'=>true,'item'=>$u]);
    }

    public function toggle(Request $r, $id)
    {
        $this->mustAdmin($r);

        $me = (int)$r->user()->id;
        if ($me === (int)$id) {
            return response()->json(['ok'=>false,'message'=>'Tidak bisa menonaktifkan akun sendiri'], 422);
        }

        $u = User::findOrFail((int)$id);
        $u->aktif = $u->aktif ? 0 : 1;
        $u->save();

        return response()->json(['ok'=>true,'aktif'=>$u->aktif]);
    }
    public function show(Request $r, $id)
    {
        $this->mustAdmin($r);
        $u = User::with('cabang')->findOrFail((int)$id);
        return response()->json(['ok'=>true,'item'=>$u]);
    }
}
