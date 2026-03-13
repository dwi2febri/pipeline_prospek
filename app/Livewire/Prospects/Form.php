<?php

namespace App\Livewire\Prospects;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Prospect;
use App\Models\Cabang;
use App\Models\ProspectDocument;
use Illuminate\Support\Facades\Storage;

class Form extends Component
{
    use WithFileUploads;

    public ?int $id = null;

    public string $tanggal_prospek = '';
    public string $nama = '';
    public ?string $nik = null;
    public ?string $no_hp = null;

    public ?string $alamat = null;
    public ?string $lokasi_lat = null;
    public ?string $lokasi_lng = null;

    public string $jenis_usaha = '';
    public ?string $keterangan_usaha = null;

    public string $jenis_produk = 'KREDIT';

    public ?string $catatan = null;
    public ?int $cabang_id = null;

    public array $cabangOptions = [];

    public ?string $kab_kota = null;
    public ?string $kecamatan = null;
    public ?string $desa = null;

    public ?string $kode_provinsi = '33';
    public ?string $kode_kab_kota = null;
    public ?string $kode_kecamatan = null;
    public ?string $kode_desa = null;

    public string $status = 'FOLLOW UP';

    public array $photos = [];

    public bool $showDuplicateHpModal = false;
    public ?string $duplicateHp = null;

    public function mount($id = null)
    {
        $this->id = $id ? (int)$id : null;

        $this->cabangOptions = Cabang::query()
            ->where('aktif', 1)
            ->orderByRaw("LPAD(kode_cabang, 10, '0') ASC")
            ->get(['id', 'kode_cabang', 'nama_cabang'])
            ->map(fn($c) => [
                'id' => (int)$c->id,
                'text' => $c->kode_cabang . ' - ' . $c->nama_cabang
            ])
            ->toArray();

        if ($this->id) {
            $p = Prospect::findOrFail($this->id);

            $u = auth()->user();
            if ($u->role === 'CABANG' && (int)$p->cabang_id !== (int)$u->cabang_id) {
                abort(403);
            }
            if (($u->role === 'PEGAWAI' || str_starts_with((string)$u->role, 'AO_')) && (int)$p->input_by !== (int)$u->id) {
                abort(403);
            }

            $this->tanggal_prospek  = (string)$p->tanggal_prospek;
            $this->nama             = (string)$p->nama;
            $this->nik              = $p->nik;
            $this->no_hp            = $p->no_hp;

            $this->alamat           = $p->alamat;
            $this->kab_kota         = $p->kab_kota;
            $this->kecamatan        = $p->kecamatan;
            $this->desa             = $p->desa;

            $this->kode_provinsi    = $p->kode_provinsi ?: '33';
            $this->kode_kab_kota    = $p->kode_kab_kota;
            $this->kode_kecamatan   = $p->kode_kecamatan;
            $this->kode_desa        = $p->kode_desa;

            $this->lokasi_lat       = $p->lokasi_lat;
            $this->lokasi_lng       = $p->lokasi_lng;

            $this->jenis_usaha      = (string)($p->jenis_usaha ?? '');
            $this->keterangan_usaha = $p->keterangan_usaha;

            $this->jenis_produk     = (string)$p->jenis_produk;
            $this->catatan          = $p->catatan;

            $this->cabang_id        = $p->cabang_id ? (int)$p->cabang_id : null;
            $this->status           = $p->status ?: 'FOLLOW UP';
        } else {
            $this->tanggal_prospek = now()->toDateString();

            $u = auth()->user();
            $this->cabang_id = $u && $u->cabang_id ? (int)$u->cabang_id : null;

            $this->kode_provinsi = '33';
            $this->status = 'FOLLOW UP';
        }
    }

    protected function rules(): array
    {
        return [
            'tanggal_prospek'   => ['required', 'date'],
            'nama'              => ['required', 'string', 'max:150'],
            'nik'               => ['nullable', 'regex:/^[0-9]+$/', 'max:30'],
            'no_hp'             => ['required', 'regex:/^[0-9]+$/', 'max:30'],

            'alamat'            => ['nullable', 'string', 'max:255'],
            'kab_kota'          => ['nullable', 'string', 'max:255'],
            'kecamatan'         => ['nullable', 'string', 'max:255'],
            'desa'              => ['nullable', 'string', 'max:255'],

            'kode_provinsi'     => ['nullable', 'string', 'max:10'],
            'kode_kab_kota'     => ['nullable', 'string', 'max:20'],
            'kode_kecamatan'    => ['nullable', 'string', 'max:20'],
            'kode_desa'         => ['nullable', 'string', 'max:20'],

            'lokasi_lat'        => ['nullable', 'numeric'],
            'lokasi_lng'        => ['nullable', 'numeric'],

            'jenis_usaha'       => ['nullable', 'string', 'max:60'],
            'keterangan_usaha'  => ['nullable', 'string'],

            'jenis_produk'      => ['required', 'in:TABUNGAN,DEPOSITO,KREDIT,ASET'],
            'status'            => ['required', 'in:FOLLOW UP,REJECTED,CLOSING'],
            'catatan'           => ['nullable', 'string'],

            'cabang_id'         => ['required', 'integer', 'exists:cabangs,id'],

            'photos'            => ['array'],
            'photos.*'          => ['image', 'max:5120'],
        ];
    }

    protected function messages(): array
    {
        return [
            'nik.regex'   => 'NIK hanya boleh angka.',
            'no_hp.regex' => 'No HP hanya boleh angka.',
            'no_hp.required' => 'No HP wajib diisi.',
        ];
    }

    public function updatedNoHp($value): void
    {
        $this->no_hp = preg_replace('/[^0-9]/', '', (string)$value);
    }

    public function updatedNik($value): void
    {
        $this->nik = preg_replace('/[^0-9]/', '', (string)$value);
    }

    public function closeDuplicateHpModal(): void
    {
        $this->showDuplicateHpModal = false;
        $this->duplicateHp = null;
    }

    protected function normalizeDigits(?string $value): string
    {
        return preg_replace('/[^0-9]/', '', (string)$value);
    }

    protected function isDuplicatePhone(string $phone): bool
    {
        if ($phone === '') {
            return false;
        }

        $items = Prospect::query()
            ->when($this->id, fn($q) => $q->where('id', '!=', $this->id))
            ->whereNotNull('no_hp')
            ->get(['id', 'no_hp']);

        foreach ($items as $item) {
            $dbPhone = $this->normalizeDigits($item->no_hp);
            if ($dbPhone !== '' && $dbPhone === $phone) {
                return true;
            }
        }

        return false;
    }

    public function save()
    {
        $this->no_hp = $this->normalizeDigits($this->no_hp);
        $this->nik   = $this->normalizeDigits($this->nik);
        $this->kode_provinsi = '33';

        $this->validate();

        if ($this->isDuplicatePhone($this->no_hp)) {
            $this->duplicateHp = $this->no_hp;
            $this->showDuplicateHpModal = true;
            return;
        }

        $u = auth()->user();

        if ($this->id) {
            $p = Prospect::findOrFail($this->id);
        } else {
            $p = new Prospect();
            $p->input_by = $u->id;
            $p->status = 'FOLLOW UP';
        }

        $p->tanggal_prospek   = $this->tanggal_prospek;
        $p->nama              = $this->nama;
        $p->nik               = $this->nik ?: null;
        $p->no_hp             = $this->no_hp;

        $p->alamat            = $this->alamat;
        $p->kab_kota          = $this->kab_kota ?: null;
        $p->kecamatan         = $this->kecamatan ?: null;
        $p->desa              = $this->desa ?: null;

        $p->kode_provinsi     = '33';
        $p->kode_kab_kota     = $this->kode_kab_kota ?: null;
        $p->kode_kecamatan    = $this->kode_kecamatan ?: null;
        $p->kode_desa         = $this->kode_desa ?: null;

        $p->lokasi_lat        = $this->lokasi_lat;
        $p->lokasi_lng        = $this->lokasi_lng;

        $p->jenis_usaha       = $this->jenis_usaha;
        $p->keterangan_usaha  = $this->keterangan_usaha;

        $p->jenis_produk      = $this->jenis_produk;
        $p->status            = $this->status ?: 'FOLLOW UP';
        $p->catatan           = $this->catatan;

        $p->cabang_id         = $this->cabang_id;
        $p->referral_user_id  = $u->name;

        $p->save();

        if (!empty($this->photos)) {
            foreach ($this->photos as $photo) {
                if (!$photo) {
                    continue;
                }

                $path = $photo->storePublicly("prospect-documents/{$p->id}", 'public');

                ProspectDocument::create([
                    'prospect_id' => $p->id,
                    'file_path'   => $path,
                    'file_type'   => 'IMAGE',
                    'uploaded_by' => $u->id,
                ]);
            }

            $this->photos = [];
        }

        session()->flash('ok', 'Prospek berhasil disimpan.');
        return redirect()->route('prospects.index');
    }

    public function removeTempPhoto(int $idx): void
    {
        if (isset($this->photos[$idx])) {
            unset($this->photos[$idx]);
            $this->photos = array_values($this->photos);
        }
    }

    public function deleteDoc(int $docId): void
    {
        $doc = ProspectDocument::findOrFail($docId);

        $u = auth()->user();
        if (!$u) {
            abort(403);
        }

        if ($doc->file_path && Storage::disk('public')->exists($doc->file_path)) {
            Storage::disk('public')->delete($doc->file_path);
        }

        $doc->delete();

        session()->flash('ok', 'Foto berhasil dihapus.');
    }

    #[\Livewire\Attributes\On('setLatLngProspek')]
    public function setLatLngProspek($lat, $lng): void
    {
        $this->lokasi_lat = (string)$lat;
        $this->lokasi_lng = (string)$lng;
    }

    #[\Livewire\Attributes\On('setAlamatProspek')]
    public function setAlamatProspek($alamat): void
    {
        $this->alamat = (string)$alamat;
    }

    public function render()
    {
        $docs = $this->id
            ? ProspectDocument::query()
                ->where('prospect_id', $this->id)
                ->latest('id')
                ->get()
            : collect();

        return view('livewire.prospects.form', [
            'docs' => $docs,
        ])->layout('layouts.bootstrap');
    }
}
