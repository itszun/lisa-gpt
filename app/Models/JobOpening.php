<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobOpening extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'title',
        'body',
        'due_date',
        'status',
    ];

    protected $casts = [
        'status' => 'integer',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }
}
