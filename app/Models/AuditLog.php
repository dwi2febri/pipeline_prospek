<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $table = 'audit_logs';

    protected $fillable = [
        'actor_id',
        'actor_name',
        'action',
        'type',
        'auditable_type',
        'auditable_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'meta',
        'model_id',
        'ip',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'actor_id');
    }
}
