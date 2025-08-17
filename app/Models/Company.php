<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
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

}
