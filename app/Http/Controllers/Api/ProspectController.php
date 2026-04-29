<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prospect;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class ProspectController extends Controller
{
    private function baseScope()
    {
        return Prospect::query()->with(['cabang', 'creator']);
    }

    private function normalizeCode($code)
    {
        $code = strtoupper(trim((string) $code));

        if ($code === '') {
            return '';
        }

        $code = preg_replace('/\s+/', '', $code);

        if (preg_match('/^[A-Z]+-(.+)$/', $code, $m)) {
            return strtoupper(trim($m[1]));
        }

        return $code;
    }

    private function buildUserMap()
    {
        $map = [];

        $users = User::query()
            ->select([
                'id',
                'name',
                'kode',
                'employee_id',
                'nama_lengkap',
            ])
            ->get();

        foreach ($users as $u) {
            $displayName = trim((string) $u->nama_lengkap);
            if ($displayName === '') {
                $displayName = trim((string) $u->name);
            }

            if ($displayName === '') {
                continue;
            }

            $keys = [
                (string) $u->name,
                (string) $u->kode,
                (string) $u->employee_id,
                $this->normalizeCode($u->name),
                $this->normalizeCode($u->kode),
                $this->normalizeCode($u->employee_id),
            ];

            foreach ($keys as $key) {
                $key = strtoupper(trim((string) $key));
                if ($key !== '') {
                    $map[$key] = $displayName;
                }
            }
        }

        return $map;
    }

    private function resolveAoPenugasanName($diambilOleh, array $userMap)
    {
        $raw = strtoupper(trim((string) $diambilOleh));

        if ($raw === '') {
            return null;
        }

        $normalized = $this->normalizeCode($raw);

        if (isset($userMap[$raw]) && trim((string) $userMap[$raw]) !== '') {
            return $userMap[$raw];
        }

        if (isset($userMap[$normalized]) && trim((string) $userMap[$normalized]) !== '') {
            return $userMap[$normalized];
        }

        return $diambilOleh;
    }

    private function transformProspectItem($item, array $userMap)
    {
        $row = $item->toArray();

        $diambilOleh = $row['diambil_oleh'] ?? null;

        $row['ao_penugasan_kode'] = $diambilOleh;
        $row['ao_penugasan_nama'] = $this->resolveAoPenugasanName($diambilOleh, $userMap);

        return $row;
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
                  ->orWhere('status', 'like', $s)
                  ->orWhere('diambil_oleh', 'like', $s);
            });
        }

        if ($r->filled('cabang_id')) {
            $q->where('cabang_id', (int) $r->query('cabang_id'));
        }

        $items = $q->orderByDesc('tanggal_prospek')
                   ->orderByDesc('id')
                   ->get();

        $userMap = $this->buildUserMap();

        $items = $items->map(function ($item) use ($userMap) {
            return $this->transformProspectItem($item, $userMap);
        })->values();

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

        $userMap = $this->buildUserMap();
        $item = $this->transformProspectItem($item, $userMap);

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
            'diambil_oleh'     => ['nullable', 'string', 'max:50'],
        ]);

        if (empty($data['status'])) {
            $data['status'] = 'OPEN';
        }

        $p = new Prospect();
        $p->input_by = $u ? (int) $u->id : null;
        $p->fill($data);
        $p->save();

        $userMap = $this->buildUserMap();

        return response()->json([
            'ok'   => true,
            'item' => $this->transformProspectItem($p->fresh(['cabang', 'creator']), $userMap)
        ], 201);
    }

    public function update(Request $r, $id)
    {
        $q = Prospect::query()->with(['cabang', 'creator']);

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
            'diambil_oleh'     => ['nullable', 'string', 'max:50'],
        ]);

        if (empty($data['status'])) {
            $data['status'] = $p->status ?: 'OPEN';
        }

        $p->fill($data);
        $p->save();

        $userMap = $this->buildUserMap();

        return response()->json([
            'ok'   => true,
            'item' => $this->transformProspectItem($p->fresh(['cabang', 'creator']), $userMap)
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
