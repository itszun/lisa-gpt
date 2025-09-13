<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

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

    public function scopeCompany($query)
    {
        if(is_null(auth()->user()->company_id)){
            return $query;
        } else {
            return $query->where('company_id', auth()->user()->company_id);
        }
    }

    public static function feedAll()
    {
        static::with('company')->chunk(10, function($records) {
            $records = $records->map(function($item) {
                return Arr::dot($item->toArray());
            });
            Http::withHeaders([
                'Content-Type' => "application/json",
                'Accept' => "application/json",
            ])->post("http://localhost:5000/api/feeder/job_openings", [
                'data' => $records
            ]);
        });
    }
}
