<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Talent extends Model
{
    protected $fillable = [
        'name',
        'position',
        'birthdate',
        'summary',
        'skills',
        'educations',
    ];

    protected $casts = [
        'skills' => 'json',
        'educations' => 'json',
    ];

    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }
}
