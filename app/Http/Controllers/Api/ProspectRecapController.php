<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use App\Models\Prospect;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProspectRecapController extends Controller
{
    protected array $korwilRanges = [
        '100' => [1, 7],
        '200' => [8, 14],
        '300' => [15, 21],
        '400' => [22, 28],
    ];

    protected function getCabangFilterMeta(Request $request): array
    {
        $filterCabang = trim((string) $request->query('cabang_id', ''));

        if ($filterCabang === '' || !is_numeric($filterCabang)) {
            return [
                'type' => 'all',
                'id' => null,
                'kode' => null,
                'range' => null,
            ];
        }

        $selected = Cabang::query()->find((int) $filterCabang);

        if (!$selected) {
            return [
                'type' => 'all',
                'id' => null,
                'kode' => null,
                'range' => null,
            ];
        }

        $kode = trim((string) $selected->kode_cabang);

        if (isset($this->korwilRanges[$kode])) {
            return [
                'type' => 'korwil',
                'id' => (int) $selected->id,
                'kode' => $kode,
                'range' => $this->korwilRanges[$kode],
            ];
        }

        return [
            'type' => 'cabang',
            'id' => (int) $selected->id,
            'kode' => $kode,
            'range' => null,
        ];
    }

    protected function applyCabangFilterToCabangQuery($query, array $meta, string $alias = 'cabangs')
    {
        if ($meta['type'] === 'all') {
            return $query;
        }

        if ($meta['type'] === 'korwil' && is_array($meta['range'])) {
            [$start, $end] = $meta['range'];

            return $query->whereRaw(
                "CAST({$alias}.kode_cabang AS UNSIGNED) BETWEEN ? AND ?",
                [$start, $end]
            );
        }

        return $query->where("{$alias}.id", $meta['id']);
    }

    protected function applyCabangFilterToUserCabangQuery($query, array $meta, string $alias = 'cabangs')
    {
        if ($meta['type'] === 'all') {
            return $query;
        }

        if ($meta['type'] === 'korwil' && is_array($meta['range'])) {
            [$start, $end] = $meta['range'];

            return $query->whereRaw(
                "CAST({$alias}.kode_cabang AS UNSIGNED) BETWEEN ? AND ?",
                [$start, $end]
            );
        }

        return $query->where('users.cabang_id', $meta['id']);
    }

    protected function getMonth(Request $request): int
    {
        $bulan = (int) $request->query('bulan', now()->month);
        return $bulan >= 1 && $bulan <= 12 ? $bulan : (int) now()->month;
    }

    protected function getYear(Request $request): int
    {
        $tahun = (int) $request->query('tahun', now()->year);
        return $tahun > 2000 ? $tahun : (int) now()->year;
    }

    protected function getSearch(Request $request): string
    {
        return trim((string) $request->query('search', ''));
    }

    protected function getKcBaseQuery(Request $request)
    {
        $bulan = $this->getMonth($request);
        $tahun = $this->getYear($request);
        $search = $this->getSearch($request);
        $meta = $this->getCabangFilterMeta($request);

        $query = Cabang::query()
            ->leftJoin('prospects', function ($join) use ($bulan, $tahun) {
                $join->on('prospects.cabang_id', '=', 'cabangs.id')
                    ->whereMonth('prospects.tanggal_prospek', $bulan)
                    ->whereYear('prospects.tanggal_prospek', $tahun)
                    ->whereNull('prospects.deleted_at');
            })
            ->where('cabangs.aktif', 1)
            ->whereRaw("CAST(cabangs.kode_cabang AS UNSIGNED) BETWEEN 1 AND 28")
            ->when($search !== '', function ($q) use ($search) {
                $s = '%' . $search . '%';
                $q->where(function ($w) use ($s) {
                    $w->where('cabangs.kode_cabang', 'like', $s)
                      ->orWhere('cabangs.nama_cabang', 'like', $s);
                });
            });

        $query = $this->applyCabangFilterToCabangQuery($query, $meta, 'cabangs');

        return $query
            ->groupBy('cabangs.id', 'cabangs.kode_cabang', 'cabangs.nama_cabang')
            ->select([
                'cabangs.id',
                'cabangs.kode_cabang',
                'cabangs.nama_cabang',
                DB::raw('COUNT(prospects.id) as total_pengajuan'),
                DB::raw("SUM(CASE WHEN prospects.status = 'OPEN' THEN 1 ELSE 0 END) as total_open"),
                DB::raw("SUM(CASE WHEN prospects.status = 'FOLLOW UP' THEN 1 ELSE 0 END) as total_follow_up"),
                DB::raw("SUM(CASE WHEN prospects.status = 'CLOSING' THEN 1 ELSE 0 END) as total_closing"),
                DB::raw("SUM(CASE WHEN prospects.status = 'REJECTED' THEN 1 ELSE 0 END) as total_rejected"),
            ]);
    }

    protected function getPengajuBaseQuery(Request $request)
    {
        $bulan = $this->getMonth($request);
        $tahun = $this->getYear($request);
        $search = $this->getSearch($request);
        $meta = $this->getCabangFilterMeta($request);

        $query = Cabang::query()
            ->leftJoin('users', function ($join) {
                $join->on('users.cabang_id', '=', 'cabangs.id')
                    ->whereIn('users.role', ['PEGAWAI', 'AO', 'AO_KREDIT', 'AO_DANA', 'AO_REMEDIAL']);
            })
            ->leftJoin('prospects', function ($join) use ($bulan, $tahun) {
                $join->on('prospects.input_by', '=', 'users.id')
                    ->whereMonth('prospects.tanggal_prospek', $bulan)
                    ->whereYear('prospects.tanggal_prospek', $tahun)
                    ->whereNull('prospects.deleted_at');
            })
            ->where('cabangs.aktif', 1)
            ->where(function ($q) {
                $q->where('cabangs.kode_cabang', '000')
                  ->orWhereRaw("CAST(cabangs.kode_cabang AS UNSIGNED) BETWEEN 1 AND 28");
            })
            ->when($search !== '', function ($q) use ($search) {
                $s = '%' . $search . '%';
                $q->where(function ($w) use ($s) {
                    $w->where('cabangs.kode_cabang', 'like', $s)
                      ->orWhere('cabangs.nama_cabang', 'like', $s);
                });
            });

        $query = $this->applyCabangFilterToCabangQuery($query, $meta, 'cabangs');

        return $query
            ->groupBy('cabangs.id', 'cabangs.kode_cabang', 'cabangs.nama_cabang')
            ->select([
                'cabangs.id',
                'cabangs.kode_cabang',
                'cabangs.nama_cabang',
                DB::raw('COUNT(prospects.id) as total_pengajuan'),
                DB::raw("SUM(CASE WHEN prospects.status = 'OPEN' THEN 1 ELSE 0 END) as total_open"),
                DB::raw("SUM(CASE WHEN prospects.status = 'FOLLOW UP' THEN 1 ELSE 0 END) as total_follow_up"),
                DB::raw("SUM(CASE WHEN prospects.status = 'CLOSING' THEN 1 ELSE 0 END) as total_closing"),
                DB::raw("SUM(CASE WHEN prospects.status = 'REJECTED' THEN 1 ELSE 0 END) as total_rejected"),
            ]);
    }

    protected function getPegawaiBaseQuery(Request $request)
    {
        $bulan = $this->getMonth($request);
        $tahun = $this->getYear($request);
        $search = $this->getSearch($request);
        $meta = $this->getCabangFilterMeta($request);

        $query = User::query()
            ->leftJoin('cabangs', 'cabangs.id', '=', 'users.cabang_id')
            ->leftJoin('prospects', function ($join) use ($bulan, $tahun) {
                $join->on('prospects.input_by', '=', 'users.id')
                    ->whereMonth('prospects.tanggal_prospek', $bulan)
                    ->whereYear('prospects.tanggal_prospek', $tahun)
                    ->whereNull('prospects.deleted_at');
            })
            ->whereIn('users.role', ['PEGAWAI', 'AO', 'AO_KREDIT', 'AO_DANA', 'AO_REMEDIAL'])
            ->when($search !== '', function ($q) use ($search) {
                $s = '%' . $search . '%';
                $q->where(function ($w) use ($s) {
                    $w->where('users.name', 'like', $s)
                      ->orWhere('users.nama_lengkap', 'like', $s)
                      ->orWhere('users.job_position', 'like', $s)
                      ->orWhere('users.role', 'like', $s)
                      ->orWhere('cabangs.nama_cabang', 'like', $s)
                      ->orWhere('cabangs.kode_cabang', 'like', $s);
                });
            });

        $query = $this->applyCabangFilterToUserCabangQuery($query, $meta, 'cabangs');

        return $query
            ->groupBy(
                'users.id',
                'users.name',
                'users.nama_lengkap',
                'users.job_position',
                'users.role',
                'users.cabang_id',
                'cabangs.kode_cabang',
                'cabangs.nama_cabang'
            )
            ->select([
                'users.id',
                'users.name',
                'users.nama_lengkap',
                'users.job_position',
                'users.role',
                'users.cabang_id',
                'cabangs.kode_cabang',
                'cabangs.nama_cabang',
                DB::raw('COUNT(prospects.id) as total_pengajuan'),
                DB::raw("SUM(CASE WHEN prospects.status = 'OPEN' THEN 1 ELSE 0 END) as total_open"),
                DB::raw("SUM(CASE WHEN prospects.status = 'FOLLOW UP' THEN 1 ELSE 0 END) as total_follow_up"),
                DB::raw("SUM(CASE WHEN prospects.status = 'CLOSING' THEN 1 ELSE 0 END) as total_closing"),
                DB::raw("SUM(CASE WHEN prospects.status = 'REJECTED' THEN 1 ELSE 0 END) as total_rejected"),
            ]);
    }

    protected function getSortDirection(Request $request): string
    {
        return strtolower((string) $request->query('direction', 'desc')) === 'asc' ? 'asc' : 'desc';
    }

    public function perKc(Request $request)
    {
        $allowed = [
            'kode_cabang',
            'nama_cabang',
            'total_pengajuan',
            'total_open',
            'total_follow_up',
            'total_closing',
            'total_rejected',
        ];

        $sort = (string) $request->query('sort', 'kode_cabang');
        if (!in_array($sort, $allowed, true)) {
            $sort = 'kode_cabang';
        }

        $direction = $this->getSortDirection($request);

        $items = $this->getKcBaseQuery($request)
            ->orderBy($sort, $direction)
            ->orderByRaw("CAST(cabangs.kode_cabang AS UNSIGNED) ASC")
            ->get();

        return response()->json([
            'ok' => true,
            'tab' => 'kc',
            'filters' => [
                'bulan' => $this->getMonth($request),
                'tahun' => $this->getYear($request),
                'cabang_id' => $request->query('cabang_id'),
                'search' => $this->getSearch($request),
                'sort' => $sort,
                'direction' => $direction,
            ],
            'total' => $items->count(),
            'items' => $items,
        ]);
    }

    public function pengaju(Request $request)
    {
        $allowed = [
            'kode_cabang',
            'nama_cabang',
            'total_pengajuan',
            'total_open',
            'total_follow_up',
            'total_closing',
            'total_rejected',
        ];

        $sort = (string) $request->query('sort', 'total_pengajuan');
        if (!in_array($sort, $allowed, true)) {
            $sort = 'total_pengajuan';
        }

        $direction = $this->getSortDirection($request);

        $items = $this->getPengajuBaseQuery($request)
            ->orderByRaw("CASE WHEN cabangs.kode_cabang = '000' THEN 0 ELSE 1 END ASC")
            ->orderBy($sort, $direction)
            ->orderByRaw("CASE WHEN cabangs.kode_cabang = '000' THEN -1 ELSE CAST(cabangs.kode_cabang AS UNSIGNED) END ASC")
            ->get();

        return response()->json([
            'ok' => true,
            'tab' => 'pengaju',
            'filters' => [
                'bulan' => $this->getMonth($request),
                'tahun' => $this->getYear($request),
                'cabang_id' => $request->query('cabang_id'),
                'search' => $this->getSearch($request),
                'sort' => $sort,
                'direction' => $direction,
            ],
            'total' => $items->count(),
            'items' => $items,
        ]);
    }

    public function perPegawai(Request $request)
    {
        $allowed = [
            'name',
            'nama_lengkap',
            'role',
            'job_position',
            'kode_cabang',
            'total_pengajuan',
            'total_open',
            'total_follow_up',
            'total_closing',
            'total_rejected',
        ];

        $sort = (string) $request->query('sort', 'total_pengajuan');
        if (!in_array($sort, $allowed, true)) {
            $sort = 'total_pengajuan';
        }

        $direction = $this->getSortDirection($request);

        $items = $this->getPegawaiBaseQuery($request)
            ->orderBy($sort, $direction)
            ->orderBy('users.id', 'desc')
            ->get();

        return response()->json([
            'ok' => true,
            'tab' => 'pegawai',
            'filters' => [
                'bulan' => $this->getMonth($request),
                'tahun' => $this->getYear($request),
                'cabang_id' => $request->query('cabang_id'),
                'search' => $this->getSearch($request),
                'sort' => $sort,
                'direction' => $direction,
            ],
            'total' => $items->count(),
            'items' => $items,
        ]);
    }

    public function detailPegawai(Request $request, $userId)
    {
        $bulan = $this->getMonth($request);
        $tahun = $this->getYear($request);

        $pegawai = User::query()
            ->leftJoin('cabangs', 'cabangs.id', '=', 'users.cabang_id')
            ->where('users.id', (int) $userId)
            ->select([
                'users.id',
                'users.name',
                'users.nama_lengkap',
                'users.role',
                'users.job_position',
                'cabangs.kode_cabang',
                'cabangs.nama_cabang',
            ])
            ->first();

        if (!$pegawai) {
            return response()->json([
                'ok' => false,
                'message' => 'Pegawai tidak ditemukan',
            ], 404);
        }

        $items = Prospect::query()
            ->where('input_by', (int) $userId)
            ->whereNull('deleted_at')
            ->whereMonth('tanggal_prospek', $bulan)
            ->whereYear('tanggal_prospek', $tahun)
            ->latest('tanggal_prospek')
            ->latest('id')
            ->select([
                'id',
                'tanggal_prospek',
                'nama',
                'jenis_produk',
                'jenis_usaha',
                'status',
            ])
            ->get();

        return response()->json([
            'ok' => true,
            'pegawai' => $pegawai,
            'filters' => [
                'bulan' => $bulan,
                'tahun' => $tahun,
            ],
            'total' => $items->count(),
            'items' => $items,
        ]);
    }
}
