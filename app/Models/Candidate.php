<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

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

    public function toFodder()
    {
        $item = $this;
        $item->chat_user_id = $item->talent->user->chat_user_id;
        $item->document = view('fodder.candidate', ['candidate' => $item])->render();
        $item = $item->toArray();
        unset($item['talent']);
        unset($item['job_opening']);
        return Arr::dot($item);
    }

    public function feed()
    {
        $fodder = $this->toFodder();
        Http::withHeaders([
            'Content-Type' => "application/json",
            'Accept' => "application/json",
        ])->post(config('chatbot.host')."/api/feeder/candidates", [
            'data' => [$fodder]
        ]);
        $this->fresh()->touch("last_feed_at");
    }


    public static function feedAll()
    {
        static::query()
        ->with('talent', function($q) {
            $q->select('id', 'name','position');
        })
        ->with('jobOpening', function($q) {
            $q->select('id', 'title');
        })->chunk(10, function($records) {
            $records = $records->map(function($item) {
                return $item->toFodder();
            });
            Http::withHeaders([
                'Content-Type' => "application/json",
                'Accept' => "application/json",
            ])->post(config('chatbot.host')."/api/feeder/candidates", [
                'data' => $records
            ]);
        });
    }
}
