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

    public function jobOpenings()
    {
        // A Talent belongs to many JobOpenings through the 'candidates' pivot table.
        return $this->belongsToMany(JobOpening::class, 'candidates', 'talent_id', 'job_opening_id');
    }

    public function scopeTalent($query)
    {
        if (is_null(auth()->user()->talent_id)) {
            return $query;
        } else {
            return $query->where('id', auth()->user()->talent_id);
        }
    }

    public function toFodder()
    {
        $item = $this;
        $item->skills = implode(", ", $item->skills);
        $item->educations = implode(", ", $item->educations);
        $item->chat_user_id = $item->user->chat_user_id;
        $item->candidate_ids = implode(", ", $item->candidates->reduce(fn($acc, $i) => array_merge($acc, [$i->id]), []));
        $item->source = config('app.key');
        $item = $item->toArray();
        unset($item['candidates']);

        return Arr::dot($item);
    }

    public static function createAllUser()
    {
        $talents = Talent::whereDoesntHave('user')->get();
        $count = count($talents);
        dump("COUNT $count");
        $talents->each(function (Talent $item) {
            dump("create user for each ".$item->name);
            $item->createUser();
            dump("Done Create".$item->name);

        });
        dump("$count total user-talent created ");
    }

    public function createUser()
    {
        $item = &$this;
        $user = User::updateOrCreate([
            'email' => Str::camel($item->name) . "@mail.com",
        ], [
            'name' => $item->name,
            'talent_id' => $item->id,
            'password' => Hash::make(1)
        ]);
        $user->assignRole('talent');
        return $item;
    }


    public static function feedAll()
    {
        static::query()
            ->whereHas('user')
            ->with(['user:id,talent_id,name,email,chat_user_id', 'candidates:id,job_opening_id,talent_id'])
            ->chunk(10, function ($records) {
                $records = $records->map(function (Talent $item) {
                    return $item->toFodder();
                });
                Http::withHeaders([
                    'Content-Type' => "application/json",
                    'Accept' => "application/json",
                ])->post(config('chatbot.host') . "/api/feeder/talents", [
                    'data' => $records
                ]);
            });
    }
}
