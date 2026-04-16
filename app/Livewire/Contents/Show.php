<?php

namespace App\Livewire\Contents;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Show extends Component
{
    public string $jenis = 'produk';
    public string $slug = '';
    public string $backUrl = '/';

    public ?object $item = null;
    public array $relatedItems = [];

    public function mount(string $jenis, string $slug): void
    {
        if (!in_array($jenis, ['produk', 'tips'], true)) {
            abort(404);
        }

        $this->jenis = $jenis;
        $this->slug = $slug;

        $role = strtoupper(trim((string) (Auth::user()->role ?? '')));

        if ($role === 'ADMIN') {
            $this->backUrl = route('contents.index');
        } else {
            $currentUrl = url()->current();
            $referer = request()->headers->get('referer');
            $fallbackUrl = route('prospects.index');

            $isRefererContentDetail = false;

            if (!empty($referer)) {
                $parsedRefererPath = parse_url($referer, PHP_URL_PATH) ?: '';
                $isRefererContentDetail = preg_match('#^/contents/(produk|tips)/[^/]+$#', $parsedRefererPath) === 1;
            }

            if (
                !empty($referer) &&
                $referer !== $currentUrl &&
                !$isRefererContentDetail &&
                !str_contains($referer, '/login')
            ) {
                session(['contents_back_url' => $referer]);
                $this->backUrl = $referer;
            } else {
                $this->backUrl = session('contents_back_url', $fallbackUrl);
            }
        }

        $table = $jenis === 'produk' ? 'katalog_produk' : 'tips_trik';

        $row = DB::table($table)
            ->where('slug', $slug)
            ->where('aktif', 1)
            ->first();

        if (!$row) {
            abort(404);
        }

        $row->gambar_url = !empty($row->gambar) ? Storage::url($row->gambar) : null;
        $this->item = $row;

        $this->relatedItems = DB::table($table)
            ->where('aktif', 1)
            ->where('id', '!=', $row->id)
            ->orderBy('urutan')
            ->orderByDesc('id')
            ->limit(6)
            ->get()
            ->map(function ($x) use ($jenis) {
                $x->gambar_url = !empty($x->gambar) ? Storage::url($x->gambar) : null;
                $x->detail_url = route('contents.show', [
                    'jenis' => $jenis,
                    'slug'  => $x->slug,
                ]);
                return (array) $x;
            })
            ->toArray();
    }

    public function render()
    {
        return view('livewire.contents.show')->layout('layouts.bootstrap');
    }
}
