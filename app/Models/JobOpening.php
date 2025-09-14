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

    public function talents()
    {
        return $this->belongsToMany(Talent::class, 'candidates', 'job_opening_id', 'talent_id');
    }

    public function scopeCompany($query)
    {
        if(is_null(auth()->user()->company_id)){
            return $query;
        } else {
            return $query->where('company_id', auth()->user()->company_id);
        }
    }

    public function toFodder() {
        $item = $this;
        $item->job_opening_id = $item->id;
        $item->candidate_ids = implode(", ",$item->candidates->reduce(fn($acc, $i) => array_merge($acc, [$i->id]), []));
        $item->candidate_talent_ids = implode(",",$item->talents->reduce(fn($acc, $i) => array_merge($acc, [$i->id]), []));
        $item = $item->toArray();
        unset($item['candidates']);
        unset($item['talents']);
        return Arr::dot($item);
    }

    public static function feedAll()
    {
        static::with('company', 'candidates:id,talent_id,status', 'talents:id,name')->chunk(10, function($records) {
            $records = $records->map(function($item) {
                return $item->toFodder();
            });
            Http::withHeaders([
                'Content-Type' => "application/json",
                'Accept' => "application/json",
            ])->post(config('chatbot.host')."/api/feeder/job_openings", [
                'data' => $records
            ]);
        });
    }
}
