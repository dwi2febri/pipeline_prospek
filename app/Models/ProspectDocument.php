<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProspectDocument extends Model
{
    protected $table = 'prospect_documents';

    protected $fillable = [
        'prospect_id',
        'file_path',
        'file_type',
        'uploaded_by',
    ];

    // ✅ supaya $doc->url ikut muncul saat toArray()/JSON
    protected $appends = ['url'];

    public function prospect()
    {
        return $this->belongsTo(Prospect::class, 'prospect_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getUrlAttribute()
    {
        return $this->file_path ? asset('storage/' . ltrim($this->file_path, '/')) : null;
    }
}
