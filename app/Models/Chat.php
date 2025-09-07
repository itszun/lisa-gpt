<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = [
        'session_id',
        'parent_id_session',
        'user_id',
        'title',
        'context',
        'message',
        'response',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
