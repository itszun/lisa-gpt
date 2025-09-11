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
        'created_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function casts(): array
    {
        return [
            'session_id' => 'string',
            'parent_id_session' => 'string',
            'user_id' => 'integer',
            'title' => 'string',
            'context' => 'string',
            'message' => 'string',
            'response' => 'json',
            'created_by' => 'integer',
        ];
    }
}
