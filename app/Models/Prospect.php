<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prospect extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tanggal_prospek',
        'nama',
        'nik',
        'no_hp',
        'alamat',
        'kab_kota',
        'kecamatan',
        'desa',
        'keterangan_usaha',
        'lokasi_lat',
        'lokasi_lng',
        'jenis_usaha',
        'jenis_produk',
        'status',
        'cabang_id',
        'input_by',
        'referral_user_id',
        'catatan',
    ];

    protected $casts = [
        'tanggal_prospek' => 'date',
        'cabang_id' => 'integer',
        'input_by' => 'integer',
        'referral_user_id' => 'integer',
    ];

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'input_by');
    }

    public function documents()
    {
        return $this->hasMany(ProspectDocument::class, 'prospect_id');
    }
}
