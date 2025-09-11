<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'integer',
    ];

    public function jobOpenings()
    {
        return $this->hasMany(JobOpening::class);
    }

    public function properties()
    {
        return $this->hasMany(CompanyProperty::class);
    }

    public function candidates()
    {
        return $this->hasManyThrough(
            Candidate::class,
            JobOpening::class,
            'company_id',
            'job_opening_id',
            'id',
            'id' 
        );
    }

    public function scopeCompany($query)
    {
        if(is_null(auth()->user()->company_id)){
            return $query;
        } else {
            return $query->where('id', auth()->user()->company_id);
        }
    }

}
