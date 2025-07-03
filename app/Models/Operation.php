<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    protected $fillable = [
        'accounting_period_id',
        'message',
        'type',
        'model_type',
        'model_id',
        'user_id',
        'is_seen',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
