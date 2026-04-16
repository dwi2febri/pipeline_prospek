<?php

namespace App\Livewire\Contents;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class Manager extends Component
{
    use WithFileUploads;

    public string $tab = 'produk';
    public ?int $editingId = null;

    public string $judul = '';
    public string $slug = '';
    public string $deskripsi = '';
    public string $badge = '';
    public string $kategori = '';
    public int $urutan = 1;
    public int $aktif = 1;

    public $gambar;
    public ?string $existingGambar = null;

    protected $listeners = [
        'deleteConfirmed' => 'delete',
        'forceCloseContentModal' => 'forceCloseContentModal',
    ];

    public function mount(): void
    {
        $role = strtoupper(trim((string) (auth()->user()->role ?? '')));
        if ($role !== 'ADMIN') {
            abort(403);
        }
    }

    protected function rules(): array
    {
        if ($this->tab === 'produk') {
            return [
                'judul'     => ['required', 'string', 'max:255'],
                'slug'      => ['nullable', 'string', 'max:255'],
                'deskripsi' => ['nullable', 'string'],
                'badge'     => ['nullable', 'string', 'max:255'],
                'urutan'    => ['required', 'integer', 'min:1'],
                'aktif'     => ['required', 'in:0,1'],
                'gambar'    => ['nullable', 'image', 'max:5120'],
            ];
        }

        return [
            'judul'     => ['required', 'string', 'max:255'],
            'slug'      => ['nullable', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'kategori'  => ['nullable', 'string', 'max:255'],
            'urutan'    => ['required', 'integer', 'min:1'],
            'aktif'     => ['required', 'in:0,1'],
            'gambar'    => ['nullable', 'image', 'max:5120'],
        ];
    }

    protected function messages(): array
    {
        return [
            'judul.required'  => 'Judul wajib diisi.',
            'urutan.required' => 'Urutan wajib diisi.',
            'gambar.image'    => 'File harus berupa gambar.',
            'gambar.max'      => 'Ukuran gambar maksimal 5MB.',
        ];
    }

    public function updatedJudul($value): void
    {
        if (!$this->editingId) {
            $this->slug = Str::slug((string) $value);
        }
    }

    public function switchTab(string $tab): void
    {
        if (!in_array($tab, ['produk', 'tips'], true)) {
            return;
        }

        $this->tab = $tab;
        $this->resetForm();
        $this->resetValidation();
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->resetValidation();
        $this->dispatch('open-content-modal');
    }

    public function closeModal(): void
    {
        $this->dispatch('close-content-modal');
    }

    public function forceCloseContentModal(): void
    {
        $this->resetForm();
        $this->resetValidation();
    }

    public function resetForm(): void
    {
        $this->editingId = null;
        $this->judul = '';
        $this->slug = '';
        $this->deskripsi = '';
        $this->badge = '';
        $this->kategori = '';
        $this->urutan = 1;
        $this->aktif = 1;
        $this->gambar = null;
        $this->existingGambar = null;
    }

    protected function getTableName(): string
    {
        return $this->tab === 'produk' ? 'katalog_produk' : 'tips_trik';
    }

    protected function uniqueSlug(string $table, string $slug, ?int $ignoreId = null): string
    {
        $slug = trim($slug) !== '' ? Str::slug($slug) : 'item-' . now()->timestamp;
        $base = $slug;
        $i = 1;

        while (true) {
            $q = DB::table($table)->where('slug', $slug);

            if ($ignoreId) {
                $q->where('id', '!=', $ignoreId);
            }

            if (!$q->exists()) {
                return $slug;
            }

            $slug = $base . '-' . $i;
            $i++;
        }
    }

    protected function uploadImageIfAny(?string $oldPath = null): ?string
    {
        if (!$this->gambar) {
            return $oldPath;
        }

        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }

        return $this->gambar->store(
            $this->tab === 'produk' ? 'konten/produk' : 'konten/tips',
            'public'
        );
    }

    public function save(): void
    {
        $this->validate();

        $table = $this->getTableName();
        $now = now();

        if ($this->editingId) {
            $row = DB::table($table)->where('id', $this->editingId)->first();

            if (!$row) {
                $this->dispatch('swal', [
                    'icon' => 'error',
                    'title' => 'Gagal',
                    'text' => 'Data tidak ditemukan.',
                ]);
                return;
            }

            $slug = $this->uniqueSlug($table, $this->slug ?: $this->judul, $this->editingId);
            $gambarPath = $this->uploadImageIfAny($row->gambar);

            $payload = [
                'judul'      => $this->judul,
                'slug'       => $slug,
                'deskripsi'  => $this->deskripsi ?: null,
                'gambar'     => $gambarPath,
                'urutan'     => (int) $this->urutan,
                'aktif'      => (int) $this->aktif,
                'updated_at' => $now,
            ];

            if ($this->tab === 'produk') {
                $payload['badge'] = $this->badge ?: null;
                if (DB::getSchemaBuilder()->hasColumn($table, 'link_url')) {
                    $payload['link_url'] = null;
                }
            } else {
                $payload['kategori'] = $this->kategori ?: null;
                if (DB::getSchemaBuilder()->hasColumn($table, 'link_url')) {
                    $payload['link_url'] = null;
                }
            }

            DB::table($table)->where('id', $this->editingId)->update($payload);

            $this->dispatch('close-content-modal');
            $this->resetForm();
            $this->resetValidation();

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Berhasil',
                'text' => 'Konten berhasil diperbarui.',
            ]);
            return;
        }

        $slug = $this->uniqueSlug($table, $this->slug ?: $this->judul);
        $gambarPath = $this->uploadImageIfAny();

        $payload = [
            'judul'      => $this->judul,
            'slug'       => $slug,
            'deskripsi'  => $this->deskripsi ?: null,
            'gambar'     => $gambarPath,
            'urutan'     => (int) $this->urutan,
            'aktif'      => (int) $this->aktif,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        if ($this->tab === 'produk') {
            $payload['badge'] = $this->badge ?: null;
            if (DB::getSchemaBuilder()->hasColumn($table, 'link_url')) {
                $payload['link_url'] = null;
            }
        } else {
            $payload['kategori'] = $this->kategori ?: null;
            if (DB::getSchemaBuilder()->hasColumn($table, 'link_url')) {
                $payload['link_url'] = null;
            }
        }

        DB::table($table)->insert($payload);

        $this->dispatch('close-content-modal');
        $this->resetForm();
        $this->resetValidation();

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Berhasil',
            'text' => 'Konten berhasil ditambahkan.',
        ]);
    }

    public function edit(string $tab, int $id): void
    {
        if (!in_array($tab, ['produk', 'tips'], true)) {
            return;
        }

        $this->tab = $tab;
        $table = $this->getTableName();
        $row = DB::table($table)->where('id', $id)->first();

        if (!$row) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Gagal',
                'text' => 'Data tidak ditemukan.',
            ]);
            return;
        }

        $this->editingId = (int) $row->id;
        $this->judul = (string) ($row->judul ?? '');
        $this->slug = (string) ($row->slug ?? '');
        $this->deskripsi = (string) ($row->deskripsi ?? '');
        $this->badge = (string) ($row->badge ?? '');
        $this->kategori = (string) ($row->kategori ?? '');
        $this->urutan = (int) ($row->urutan ?? 1);
        $this->aktif = (int) ($row->aktif ?? 1);
        $this->gambar = null;
        $this->existingGambar = !empty($row->gambar) ? Storage::url($row->gambar) : null;

        $this->resetValidation();
        $this->dispatch('open-content-modal');
    }

    public function askDelete(string $tab, int $id): void
    {
        $this->dispatch('askDelete', [
            'tab' => $tab,
            'id' => $id,
            'title' => 'Hapus konten ini?',
            'text' => 'Data yang dihapus tidak bisa dikembalikan.',
        ]);
    }

    public function delete($payload): void
    {
        $tab = $payload['tab'] ?? null;
        $id = (int) ($payload['id'] ?? 0);

        if (!in_array($tab, ['produk', 'tips'], true) || $id < 1) {
            return;
        }

        $table = $tab === 'produk' ? 'katalog_produk' : 'tips_trik';
        $row = DB::table($table)->where('id', $id)->first();

        if (!$row) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Gagal',
                'text' => 'Data tidak ditemukan.',
            ]);
            return;
        }

        if (!empty($row->gambar) && Storage::disk('public')->exists($row->gambar)) {
            Storage::disk('public')->delete($row->gambar);
        }

        DB::table($table)->where('id', $id)->delete();

        if ($this->editingId === $id) {
            $this->resetForm();
            $this->dispatch('close-content-modal');
        }

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Berhasil',
            'text' => 'Data berhasil dihapus.',
        ]);
    }

    public function render()
    {
        $produk = DB::table('katalog_produk')
            ->orderBy('urutan')
            ->orderByDesc('id')
            ->get()
            ->map(function ($row) {
                $row->gambar_url = !empty($row->gambar) ? Storage::url($row->gambar) : null;
                $row->detail_url = route('contents.show', ['jenis' => 'produk', 'slug' => $row->slug]);
                return $row;
            });

        $tips = DB::table('tips_trik')
            ->orderBy('urutan')
            ->orderByDesc('id')
            ->get()
            ->map(function ($row) {
                $row->gambar_url = !empty($row->gambar) ? Storage::url($row->gambar) : null;
                $row->detail_url = route('contents.show', ['jenis' => 'tips', 'slug' => $row->slug]);
                return $row;
            });

        return view('livewire.contents.manager', [
            'produkItems' => $produk,
            'tipsItems'   => $tips,
        ])->layout('layouts.bootstrap');
    }
}
