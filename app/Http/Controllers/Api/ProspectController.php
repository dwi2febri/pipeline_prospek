<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prospect;
use App\Support\Role;

class ProspectController extends Controller
{
    private function baseScope(Request $r)
    {
        $u = $r->user();
        $q = Prospect::query()->with(['cabang','creator']);

        // Akses:
        if (Role::isCabang($u)) {
            $q->where('cabang_id', (int)$u->cabang_id);
        } elseif (Role::isPegawaiOrAO($u)) {
            $q->where('input_by', (int)$u->id);
        }
        return $q;
    }

    public function summary(Request $r)
    {
        $q = $this->baseScope($r);

        // periode optional
        $periode = $r->query('periode', 'bulan_ini');
        if ($periode === 'hari_ini') {
            $q->whereDate('tanggal_prospek', now()->toDateString());
        } elseif ($periode === 'bulan_ini') {
            $q->whereMonth('tanggal_prospek', now()->month)
              ->whereYear('tanggal_prospek', now()->year);
        }

        $clone = clone $q;
        $data = [
            'BELUM_BERMINAT' => (clone $clone)->where('status','BELUM_BERMINAT')->count(),
            'BERMINAT'       => (clone $clone)->where('status','BERMINAT')->count(),
            'TIDAK_BERMINAT' => (clone $clone)->where('status','TIDAK_BERMINAT')->count(),
            'CLOSING'        => (clone $clone)->where('status','CLOSING')->count(),
        ];

        return response()->json(['ok'=>true,'summary'=>$data]);
    }

    public function index(Request $r)
    {
        $q = $this->baseScope($r);

        // filter status
        if ($r->filled('status')) {
            $q->where('status', $r->query('status'));
        }

        // periode
        $periode = $r->query('periode', 'bulan_ini');
        if ($periode === 'hari_ini') {
            $q->whereDate('tanggal_prospek', now()->toDateString());
        } elseif ($periode === 'bulan_ini') {
            $q->whereMonth('tanggal_prospek', now()->month)
              ->whereYear('tanggal_prospek', now()->year);
        }

        // search
        if ($r->filled('search')) {
            $s = '%' . $r->query('search') . '%';
            $q->where(function($w) use ($s){
                $w->where('nama','like',$s)
                  ->orWhere('no_hp','like',$s)
                  ->orWhere('nik','like',$s);
            });
        }

        // pagination
        $perPage = (int)($r->query('per_page', 10));
        if ($perPage < 1) $perPage = 10;
        if ($perPage > 50) $perPage = 50;

        $items = $q->latest('tanggal_prospek')->paginate($perPage);

        return response()->json(['ok'=>true,'items'=>$items]);
    }

    public function show(Request $r, $id)
    {
        $p = $this->baseScope($r)->where('id', (int)$id)->firstOrFail();
        return response()->json(['ok'=>true,'item'=>$p]);
    }

    public function store(Request $r)
    {
        $u = $r->user();

        $data = $r->validate([
            'tanggal_prospek' => ['required','date'],
            'nama'            => ['required','string','max:150'],
            'nik'             => ['nullable','string','max:30'],
            'no_hp'           => ['nullable','string','max:30'],
            'alamat'          => ['nullable','string','max:255'],
            'lokasi_lat'      => ['nullable','numeric'],
            'lokasi_lng'      => ['nullable','numeric'],
            'jenis_usaha'     => ['nullable','string','max:60'],
            'keterangan_usaha'=> ['nullable','string'],
            'jenis_produk'    => ['required','in:TABUNGAN,DEPOSITO,KREDIT,ASET'],
            'status'          => ['nullable','in:BELUM_BERMINAT,BERMINAT,TIDAK_BERMINAT,CLOSING'],
            'catatan'         => ['nullable','string'],
            'cabang_id'       => ['required','integer','exists:cabangs,id'],
        ]);

        // default status bila UI disembunyikan
        if (empty($data['status'])) $data['status'] = 'BELUM_BERMINAT';

        $p = new Prospect();
        $p->input_by = (int)$u->id;
        $p->fill($data);
        $p->save();

        return response()->json(['ok'=>true,'item'=>$p], 201);
    }

    public function update(Request $r, $id)
    {
        $p = $this->baseScope($r)->where('id',(int)$id)->firstOrFail();

        $data = $r->validate([
            'tanggal_prospek' => ['required','date'],
            'nama'            => ['required','string','max:150'],
            'nik'             => ['nullable','string','max:30'],
            'no_hp'           => ['nullable','string','max:30'],
            'alamat'          => ['nullable','string','max:255'],
            'lokasi_lat'      => ['nullable','numeric'],
            'lokasi_lng'      => ['nullable','numeric'],
            'jenis_usaha'     => ['nullable','string','max:60'],
            'keterangan_usaha'=> ['nullable','string'],
            'jenis_produk'    => ['required','in:TABUNGAN,DEPOSITO,KREDIT,ASET'],
            'status'          => ['nullable','in:BELUM_BERMINAT,BERMINAT,TIDAK_BERMINAT,CLOSING'],
            'catatan'         => ['nullable','string'],
            'cabang_id'       => ['required','integer','exists:cabangs,id'],
        ]);

        if (empty($data['status'])) $data['status'] = $p->status ?: 'BELUM_BERMINAT';

        $p->fill($data);
        $p->save();

        return response()->json(['ok'=>true,'item'=>$p]);
    }

    public function destroy(Request $r, $id)
    {
        $p = $this->baseScope($r)->where('id',(int)$id)->firstOrFail();
        $p->delete();
        return response()->json(['ok'=>true]);
    }

    public function restore(Request $r, $id)
    {
        // restore but still must follow scope: admin restore all, cabang restore their, pegawai/ao restore theirs
        $q = Prospect::onlyTrashed()->with(['cabang','creator']);
        $u = $r->user();

        if (Role::isCabang($u)) {
            $q->where('cabang_id', (int)$u->cabang_id);
        } elseif (Role::isPegawaiOrAO($u)) {
            $q->where('input_by', (int)$u->id);
        }

        $p = $q->where('id',(int)$id)->firstOrFail();
        $p->restore();

        return response()->json(['ok'=>true]);
    }
}
