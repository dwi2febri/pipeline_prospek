<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\Cabang;
use App\Support\Role;

class CabangController extends Controller
{
    private function mustAdmin(Request $r): void
    {
        if (!Role::isAdmin($r->user())) abort(403, 'Admin only');
    }

    public function index(Request $r)
    {
        $q = Cabang::query();

        if ($r->filled('search')) {
            $s = '%'.$r->query('search').'%';
            $q->where(function($w) use ($s){
                $w->where('kode_cabang','like',$s)
                  ->orWhere('nama_cabang','like',$s)
                  ->orWhere('alamat','like',$s);
            });
        }

        // ✅ urut kode_cabang numerik/string aman
        $q->orderByRaw("LPAD(kode_cabang, 10, '0') ASC");

        $items = $q->paginate((int)($r->query('per_page', 50)));

        return response()->json(['ok'=>true,'items'=>$items]);
    }

    public function store(Request $r)
    {
        $this->mustAdmin($r);

        $data = $r->validate([
            'kode_cabang' => ['required','string','max:10'],
            'nama_cabang' => ['required','string','max:120'],
            'alamat'      => ['nullable','string','max:255'],
            'aktif'       => ['nullable','in:0,1'],
        ]);

        $c = new Cabang();
        $c->kode_cabang = trim($data['kode_cabang']);
        $c->nama_cabang = trim($data['nama_cabang']);
        $c->alamat = $data['alamat'] ?? null;
        $c->aktif = isset($data['aktif']) ? (int)$data['aktif'] : 1;
        $c->save();

        return response()->json(['ok'=>true,'item'=>$c], 201);
    }

    public function update(Request $r, $id)
    {
        $this->mustAdmin($r);

        $c = Cabang::findOrFail((int)$id);

        $data = $r->validate([
            'kode_cabang' => ['required','string','max:10'],
            'nama_cabang' => ['required','string','max:120'],
            'alamat'      => ['nullable','string','max:255'],
            'aktif'       => ['nullable','in:0,1'],
        ]);

        $c->kode_cabang = trim($data['kode_cabang']);
        $c->nama_cabang = trim($data['nama_cabang']);
        $c->alamat = $data['alamat'] ?? null;
        if (isset($data['aktif'])) $c->aktif = (int)$data['aktif'];
        $c->save();

        return response()->json(['ok'=>true,'item'=>$c]);
    }

    public function toggle(Request $r, $id)
    {
        $this->mustAdmin($r);

        $c = Cabang::findOrFail((int)$id);
        $c->aktif = $c->aktif ? 0 : 1;
        $c->save();

        return response()->json(['ok'=>true,'aktif'=>$c->aktif]);
    }

    public function downloadTemplate(Request $r)
    {
        $this->mustAdmin($r);

        // Template sesuai import: kode_cabang,nama_cabang,alamat,aktif
        $csv = "kode_cabang,nama_cabang,alamat,aktif\n";
        $csv .= "001,KC Utama,Semarang,1\n";
        $csv .= "002,KC Rembang,Rembang,1\n";

        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template_cabangs.csv"',
        ]);
    }

    public function import(Request $r)
    {
        $this->mustAdmin($r);

        $r->validate([
            'file' => ['required','file','mimes:csv,txt','max:5120'],
        ]);

        $path = $r->file('file')->getRealPath();
        $rows = array_map('str_getcsv', file($path));

        if (!$rows || count($rows) < 2) {
            return response()->json(['ok'=>false,'message'=>'File kosong / format salah'], 422);
        }

        $header = array_map(function($h){
            return strtolower(trim((string)$h));
        }, $rows[0]);

        // wajib ada kode_cabang dan nama_cabang
        $idxKode = array_search('kode_cabang', $header);
        $idxNama = array_search('nama_cabang', $header);
        $idxAlamat = array_search('alamat', $header);
        $idxAktif = array_search('aktif', $header);

        if ($idxKode === false || $idxNama === false) {
            return response()->json(['ok'=>false,'message'=>'Header harus ada: kode_cabang,nama_cabang,alamat,aktif'], 422);
        }

        $inserted = 0;
        $updated = 0;
        $skipped = 0;

        for ($i=1; $i<count($rows); $i++) {
            $r0 = $rows[$i];
            if (!$r0 || count($r0) < 2) { $skipped++; continue; }

            $kode = trim((string)($r0[$idxKode] ?? ''));
            $nama = trim((string)($r0[$idxNama] ?? ''));
            $alamat = $idxAlamat !== false ? trim((string)($r0[$idxAlamat] ?? '')) : '';
            $aktif = $idxAktif !== false ? trim((string)($r0[$idxAktif] ?? '')) : '1';

            if ($kode === '' || $nama === '') { $skipped++; continue; }

            $aktifVal = ((string)$aktif === '0') ? 0 : 1;

            // ✅ UPSERT: kalau kode_cabang ATAU nama_cabang sama → update
            $c = Cabang::where('kode_cabang', $kode)
                ->orWhere('nama_cabang', $nama)
                ->first();

            if ($c) {
                $c->kode_cabang = $kode;
                $c->nama_cabang = $nama;
                $c->alamat = $alamat !== '' ? $alamat : $c->alamat;
                $c->aktif = $aktifVal;
                $c->save();
                $updated++;
            } else {
                Cabang::create([
                    'kode_cabang' => $kode,
                    'nama_cabang' => $nama,
                    'alamat' => $alamat !== '' ? $alamat : null,
                    'aktif' => $aktifVal,
                ]);
                $inserted++;
            }
        }

        return response()->json([
            'ok'=>true,
            'inserted'=>$inserted,
            'updated'=>$updated,
            'skipped'=>$skipped,
        ]);
    }
}
