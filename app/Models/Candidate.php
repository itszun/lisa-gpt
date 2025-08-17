<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $fillable = [
        'talent_id',
        'job_opening_id',
        'status',
        'regist_at',
        'interview_schedule',
        'notified_at',
    ];

    protected $casts = [
        'status' => 'integer',
        'regist_at' => 'datetime',
        'interview_schedule' => 'datetime',
        'notified_at' => 'datetime',
    ];

    public function talent()
    {
        return $this->belongsTo(Talent::class);
    }

    public function jobOpening()
    {
        return $this->belongsTo(JobOpening::class);
    }
}
