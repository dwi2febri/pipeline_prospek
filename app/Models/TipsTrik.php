<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipsTrik extends Model
{
    protected $table = 'tips_trik';

    protected $fillable = [
        'judul',
        'slug',
        'deskripsi',
        'gambar',
        'link_url',
        'kategori',
        'urutan',
        'aktif',
    ];
}
