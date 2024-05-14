<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloqueoUsuario extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'blocked_at',
        'blocked_until',
        'block_duration',
        'status',
        'unblocked_at',
        'reason',
        'temp_blocks',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
