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
