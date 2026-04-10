<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KatalogProduk extends Model
{
    protected $table = 'katalog_produk';

    protected $fillable = [
        'judul',
        'slug',
        'deskripsi',
        'gambar',
        'link_url',
        'badge',
        'urutan',
        'aktif',
    ];
}
