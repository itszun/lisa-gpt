<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = [
        'session_id',
        'user_id',
        'message',
        'response',
    ];
}
