<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prospect;
use Illuminate\Support\Facades\Schema;

class ProspectController extends Controller
{
    private function baseScope()
    {
        return Prospect::query()->with(['cabang', 'creator']);
    }

    public function summary(Request $r)
    {
        $q = $this->baseScope();

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
            $q->whereNull('deleted_at');
        }

        if ($r->filled('status')) {
            $q->where('status', $r->query('status'));
        }

        if ($r->filled('search')) {
            $s = '%' . trim($r->query('search')) . '%';
            $q->where(function ($w) use ($s) {
                $w->where('nama', 'like', $s)
                  ->orWhere('no_hp', 'like', $s)
                  ->orWhere('nik', 'like', $s)
                  ->orWhere('alamat', 'like', $s)
                  ->orWhere('jenis_produk', 'like', $s)
                  ->orWhere('status', 'like', $s);
            });
        }

        if ($r->filled('cabang_id')) {
            $q->where('cabang_id', (int) $r->query('cabang_id'));
        }

        $items = $q->orderByDesc('tanggal_prospek')
                   ->orderByDesc('id')
                   ->get();

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
            $q->whereNull('deleted_at');
        }

        $item = $q->where('id', (int) $id)->firstOrFail();

        return response()->json([
            'ok'   => true,
            'item' => $item
        ]);
    }

    public function store(Request $r)
    {
        $u = $r->user();

        $data = $r->validate([
            'tanggal_prospek'  => ['required', 'date'],
            'nama'             => ['required', 'string', 'max:150'],
            'nik'              => ['nullable', 'string', 'max:30'],
            'no_hp'            => ['nullable', 'string', 'max:30'],
            'alamat'           => ['nullable', 'string', 'max:255'],
            'lokasi_lat'       => ['nullable', 'numeric'],
            'lokasi_lng'       => ['nullable', 'numeric'],
            'jenis_usaha'      => ['nullable', 'string', 'max:60'],
            'keterangan_usaha' => ['nullable', 'string'],
            'jenis_produk'     => ['required', 'in:TABUNGAN,DEPOSITO,KREDIT,ASET'],
            'status'           => ['nullable', 'in:OPEN,FOLLOW UP,CLOSING,REJECTED'],
            'catatan'          => ['nullable', 'string'],
            'cabang_id'        => ['required', 'integer', 'exists:cabangs,id'],
        ]);

        if (empty($data['status'])) {
            $data['status'] = 'OPEN';
        }

        $p = new Prospect();
        $p->input_by = $u ? (int) $u->id : null;
        $p->fill($data);
        $p->save();

        return response()->json([
            'ok'   => true,
            'item' => $p
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
            'tanggal_prospek'  => ['required', 'date'],
            'nama'             => ['required', 'string', 'max:150'],
            'nik'              => ['nullable', 'string', 'max:30'],
            'no_hp'            => ['nullable', 'string', 'max:30'],
            'alamat'           => ['nullable', 'string', 'max:255'],
            'lokasi_lat'       => ['nullable', 'numeric'],
            'lokasi_lng'       => ['nullable', 'numeric'],
            'jenis_usaha'      => ['nullable', 'string', 'max:60'],
            'keterangan_usaha' => ['nullable', 'string'],
            'jenis_produk'     => ['required', 'in:TABUNGAN,DEPOSITO,KREDIT,ASET'],
            'status'           => ['nullable', 'in:OPEN,FOLLOW UP,CLOSING,REJECTED'],
            'catatan'          => ['nullable', 'string'],
            'cabang_id'        => ['required', 'integer', 'exists:cabangs,id'],
        ]);

        if (empty($data['status'])) {
            $data['status'] = $p->status ?: 'OPEN';
        }

        $p->fill($data);
        $p->save();

        return response()->json([
            'ok'   => true,
            'item' => $p->fresh()
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
