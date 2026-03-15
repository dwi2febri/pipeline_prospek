<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProspectNotification extends Model
{
    protected $fillable = [
        'user_id',
        'prospect_id',
        'title',
        'message',
        'status',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function prospect()
    {
        return $this->belongsTo(Prospect::class, 'prospect_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
