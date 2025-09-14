<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

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

    public function user()
    {
        return $this->hasOne(User::class, 'talent_id', 'id');
    }

    public function scopeTalent($query)
    {
        if(is_null(auth()->user()->talent_id)){
            return $query;
        } else {
            return $query->where('id', auth()->user()->talent_id);
        }
    }

    public function toFodder() {
        $item = $this;
        $item->skills = implode(", ", $item->skills);
        $item->educations = implode(", ", $item->educations);
        $item->chat_user_id = $item->user->chat_user_id;
        $item->source = config('app.key');
        return Arr::dot($item->toArray());
    }

    public static function createAllUser() {
        $talents = Talent::whereDoesntHave('user')->get();
        $count = count($talents);
        $talents->map(function($item) {
            $user = User::create([
                'name' => $item->name,
                'email' => Str::camel($item->name)."@mail.com",
                'talent_id' => $item->id,
                'password' => Hash::make(1)
            ]);
            $user->assignRole('talent');
            return $item;
        });
        print("$count total user-talent created ");
    }



    public static function feedAll()
    {
        static::query()
            ->with('candidates', fn($q) => $q->select('id', 'job_opening_id', 'talent_id'))
            ->chunk(10, function($records) {
            $records = $records->map(function($item) {
                return $item->toFodder();
            })->map(function($item) {
                if(isset($item['candidates']) && count($item['candidates']) < 1) {
                    $item['candidates'] = "";
                }
                return $item;
            });
            Http::withHeaders([
                'Content-Type' => "application/json",
                'Accept' => "application/json",
            ])->post(config('chatbot.host')."/api/feeder/talents", [
                'data' => $records
            ]);
        });
    }
}
