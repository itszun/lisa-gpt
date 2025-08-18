<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Candidate extends Model
{
    use HasFactory;

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
