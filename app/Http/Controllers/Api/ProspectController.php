<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prospect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProspectController extends Controller
{
    private function baseScope()
    {
        return Prospect::query()
            ->leftJoin('cabangs', 'cabangs.id', '=', 'prospects.cabang_id')
            ->leftJoin('users as user_ao', 'user_ao.employee_id', '=', 'prospects.diambil_oleh')
            ->leftJoin('users as user_pengaju', 'user_pengaju.employee_id', '=', 'prospects.referral_user_id')
            ->select([
                'prospects.*',
                'cabangs.nama_cabang as nama_cabang',
                'user_ao.nama_lengkap as nama_ao',
                'user_pengaju.nama_lengkap as nama_pengaju',
            ]);
    }

    private function transformRow($row)
    {
        if (is_array($row)) {
            return $row;
        }

        return (array) $row;
    }

    public function summary(Request $r)
    {
        $q = Prospect::query();

        if (Schema::hasColumn('prospects', 'deleted_at')) {
            $q->whereNull('deleted_at');
        }

        $data = [
            'total'      => (clone $q)->count(),
            'OPEN'       => (clone $q)->where('status', 'OPEN')->count(),
            'FOLLOW_UP'  => (clone $q)->where('status', 'FOLLOW UP')->count(),
            'CLOSING'    => (clone $q)->where('status', 'CLOSING')->count(),
            'REJECTED'   => (clone $q)->where('status', 'REJECTED')->count(),
        ];

        return response()->json([
            'ok' => true,
            'summary' => $data
        ]);
    }

    public function index(Request $r)
    {
        $q = $this->baseScope();

        if (Schema::hasColumn('prospects', 'deleted_at')) {
            $q->whereNull('prospects.deleted_at');
        }

        if ($r->filled('status')) {
            $q->where('prospects.status', $r->query('status'));
        }

        if ($r->filled('search')) {
            $s = '%' . trim($r->query('search')) . '%';

            $q->where(function ($w) use ($s) {
                $w->where('prospects.nama', 'like', $s)
                  ->orWhere('prospects.no_hp', 'like', $s)
                  ->orWhere('prospects.nik', 'like', $s)
                  ->orWhere('prospects.alamat', 'like', $s)
                  ->orWhere('prospects.jenis_produk', 'like', $s)
                  ->orWhere('prospects.status', 'like', $s)
                  ->orWhere('prospects.diambil_oleh', 'like', $s)
                  ->orWhere('prospects.referral_user_id', 'like', $s)
                  ->orWhere('cabangs.nama_cabang', 'like', $s)
                  ->orWhere('user_ao.nama_lengkap', 'like', $s)
                  ->orWhere('user_pengaju.nama_lengkap', 'like', $s);
            });
        }

        if ($r->filled('cabang_id')) {
            $q->where('prospects.cabang_id', (int) $r->query('cabang_id'));
        }

        if ($r->filled('diambil_oleh')) {
            $q->where('prospects.diambil_oleh', $r->query('diambil_oleh'));
        }

        if ($r->filled('referral_user_id')) {
            $q->where('prospects.referral_user_id', $r->query('referral_user_id'));
        }

        $items = $q->orderByDesc('prospects.tanggal_prospek')
                   ->orderByDesc('prospects.id')
                   ->get()
                   ->map(function ($item) {
                       return $this->transformRow($item);
                   })
                   ->values();

        return response()->json([
            'ok'    => true,
            'total' => $items->count(),
            'items' => $items,
        ]);
    }

    public function show(Request $r, $id)
    {
        $q = $this->baseScope();

        if (Schema::hasColumn('prospects', 'deleted_at')) {
            $q->whereNull('prospects.deleted_at');
        }

        $item = $q->where('prospects.id', (int) $id)->firstOrFail();

        return response()->json([
            'ok'   => true,
            'item' => $this->transformRow($item)
        ]);
    }

    public function store(Request $r)
    {
        $u = $r->user();

        $data = $r->validate([
            'tanggal_prospek'   => ['required', 'date'],
            'nama'              => ['required', 'string', 'max:150'],
            'nik'               => ['nullable', 'string', 'max:30'],
            'no_hp'             => ['nullable', 'string', 'max:30'],
            'alamat'            => ['nullable', 'string'],
            'kab_kota'          => ['nullable', 'string', 'max:100'],
            'kecamatan'         => ['nullable', 'string', 'max:100'],
            'desa'              => ['nullable', 'string', 'max:100'],
            'kode_provinsi'     => ['nullable', 'string', 'max:10'],
            'kode_kab_kota'     => ['nullable', 'string', 'max:20'],
            'kode_kecamatan'    => ['nullable', 'string', 'max:20'],
            'kode_desa'         => ['nullable', 'string', 'max:30'],
            'keterangan_usaha'  => ['nullable', 'string'],
            'jenis_usaha'       => ['nullable', 'string', 'max:60'],
            'lokasi_lat'        => ['nullable', 'numeric'],
            'lokasi_lng'        => ['nullable', 'numeric'],
            'jenis_produk'      => ['required', 'in:TABUNGAN,DEPOSITO,KREDIT,ASET'],
            'status'            => ['nullable', 'in:OPEN,FOLLOW UP,CLOSING,REJECTED'],
            'is_diambil'        => ['nullable', 'integer'],
            'diambil_oleh'      => ['nullable', 'string', 'max:50'],
            'no_rekening'       => ['nullable', 'string', 'max:50'],
            'cabang_id'         => ['required', 'integer', 'exists:cabangs,id'],
            'referral_user_id'  => ['nullable', 'string', 'max:50'],
            'catatan'           => ['nullable', 'string'],
        ]);

        if (empty($data['status'])) {
            $data['status'] = 'OPEN';
        }

        if (!isset($data['is_diambil'])) {
            $data['is_diambil'] = 0;
        }

        $p = new Prospect();
        $p->input_by = $u ? (int) $u->id : null;
        $p->fill($data);
        $p->save();

        $item = $this->baseScope()
            ->where('prospects.id', (int) $p->id)
            ->first();

        return response()->json([
            'ok'   => true,
            'item' => $this->transformRow($item)
        ], 201);
    }

    public function update(Request $r, $id)
    {
        $q = Prospect::query();

        if (Schema::hasColumn('prospects', 'deleted_at')) {
            $q->whereNull('deleted_at');
        }

        $p = $q->where('id', (int) $id)->firstOrFail();

        $data = $r->validate([
            'tanggal_prospek'   => ['required', 'date'],
            'nama'              => ['required', 'string', 'max:150'],
            'nik'               => ['nullable', 'string', 'max:30'],
            'no_hp'             => ['nullable', 'string', 'max:30'],
            'alamat'            => ['nullable', 'string'],
            'kab_kota'          => ['nullable', 'string', 'max:100'],
            'kecamatan'         => ['nullable', 'string', 'max:100'],
            'desa'              => ['nullable', 'string', 'max:100'],
            'kode_provinsi'     => ['nullable', 'string', 'max:10'],
            'kode_kab_kota'     => ['nullable', 'string', 'max:20'],
            'kode_kecamatan'    => ['nullable', 'string', 'max:20'],
            'kode_desa'         => ['nullable', 'string', 'max:30'],
            'keterangan_usaha'  => ['nullable', 'string'],
            'jenis_usaha'       => ['nullable', 'string', 'max:60'],
            'lokasi_lat'        => ['nullable', 'numeric'],
            'lokasi_lng'        => ['nullable', 'numeric'],
            'jenis_produk'      => ['required', 'in:TABUNGAN,DEPOSITO,KREDIT,ASET'],
            'status'            => ['nullable', 'in:OPEN,FOLLOW UP,CLOSING,REJECTED'],
            'is_diambil'        => ['nullable', 'integer'],
            'diambil_oleh'      => ['nullable', 'string', 'max:50'],
            'no_rekening'       => ['nullable', 'string', 'max:50'],
            'cabang_id'         => ['required', 'integer', 'exists:cabangs,id'],
            'referral_user_id'  => ['nullable', 'string', 'max:50'],
            'catatan'           => ['nullable', 'string'],
        ]);

        if (empty($data['status'])) {
            $data['status'] = $p->status ?: 'OPEN';
        }

        $p->fill($data);
        $p->save();

        $item = $this->baseScope()
            ->where('prospects.id', (int) $p->id)
            ->first();

        return response()->json([
            'ok'   => true,
            'item' => $this->transformRow($item)
        ]);
    }

    public function updateStatus(Request $r, $id)
    {
        $q = Prospect::query();

        if (Schema::hasColumn('prospects', 'deleted_at')) {
            $q->whereNull('deleted_at');
        }

        $p = $q->where('id', (int) $id)->first();

        if (!$p) {
            return response()->json([
                'ok' => false,
                'message' => 'Prospect tidak ditemukan',
            ], 404);
        }

        $data = $r->validate([
            'status' => ['required', 'in:OPEN,FOLLOW UP,CLOSING,REJECTED'],
        ]);

        $p->status = $data['status'];
        $p->save();

        return response()->json([
            'ok' => true,
            'message' => 'Status prospect berhasil diupdate',
            'item' => [
                'id' => (int) $p->id,
                'nama' => $p->nama,
                'status' => $p->status,
            ],
        ]);
    }

    public function destroy(Request $r, $id)
    {
        $q = Prospect::query();

        if (Schema::hasColumn('prospects', 'deleted_at')) {
            $q->whereNull('deleted_at');
        }

        $p = $q->where('id', (int) $id)->firstOrFail();
        $p->delete();

        return response()->json([
            'ok' => true
        ]);
    }

    public function restore(Request $r, $id)
    {
        if (!Schema::hasColumn('prospects', 'deleted_at')) {
            return response()->json([
                'ok' => false,
                'message' => 'Restore tidak tersedia karena tabel prospects tidak menggunakan soft delete.'
            ], 400);
        }

        $p = Prospect::onlyTrashed()->where('id', (int) $id)->firstOrFail();
        $p->restore();

        return response()->json([
            'ok' => true
        ]);
    }
}
