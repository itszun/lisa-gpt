<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Talent extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'position',
        'birthdate',
        'summary',
        'skills',
        'educations',
        'status',
    ];

    protected $casts = [
        'skills' => 'json',
        'educations' => 'json',
        'status' => 'integer',
    ];

    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }

    public function companies()
    {
        return $this->hasManyThrough(
            Company::class,
            Candidate::class,
            'talent_id',      // FK di candidates → talents.id
            'id',             // PK di companies
            'id',             // PK di talents
            'job_opening_id'  // FK di candidates → job_openings.id
        )
        ->join('job_openings', 'candidates.job_opening_id', '=', 'job_openings.id')
        ->select('companies.*');
    }

    public function scopeTalent($query)
    {
        if(is_null(auth()->user()->talent_id)){
            return $query;
        } else {
            return $query->where('id', auth()->user()->talent_id);
        }
    }
}
