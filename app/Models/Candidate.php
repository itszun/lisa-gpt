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
        'screening' => 'json',
    ];

    public function talent()
    {
        return $this->belongsTo(Talent::class);
    }

    public function jobOpening()
    {
        return $this->belongsTo(JobOpening::class);
    }

    public function scopeCompany($query)
    {
        if(is_null(auth()->user()->company_id) && is_null(auth()->user()->talent_id)){
            return $query;
        } elseif(is_null(auth()->user()->company_id)) {
            return $query->whereHas('talent', function ($q) {
                $q->where('id', auth()->user()->talent_id);
            });
        } else {
            return $query->whereHas('jobOpening', function ($q) {
                $q->where('company_id', auth()->user()->company_id ? auth()->user()->company_id : null);
            });
        }
    }
}
