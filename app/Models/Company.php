<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

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

    public static function boot() {
        parent::boot();
        static::saved(function($model) {
            $model->feed();
        });
    }

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
        if (is_null(auth()->user()->company_id)) {
            return $query;
        } else {
            return $query->where('id', auth()->user()->company_id);
        }
    }

    public function feed()
    {
        $item = $this;
        $properties = $item->properties
        ->reduce(fn($acc, $i) => $acc.($i['key'] . ": ". $i['value'])."\n", "");
        $item->description = $item->description."\nDetail: \n".$properties;
        $item = $item->toArray();
        unset($item['properties']);
        $item = Arr::dot($item);

        Http::withHeaders([
            'Content-Type' => "application/json",
            'Accept' => "application/json",
        ])->post(config('chatbot.host') . "/api/feeder/companies", [
            'data' => [$item]
        ]);
    }

    public static function feedAll()
    {
        static::query()
            ->with('properties', function ($q) {
                $q->select('id', 'company_id', 'key', 'value');
            })->chunk(10, function ($records) {
                $records = $records->map(function ($item) {
                    $properties = $item->properties
                    ->reduce(fn($acc, $i) => $acc.($i['key'] . ": ". $i['value'])."\n", "");
                    $item->description = $item->description."\nDetail: \n".$properties;
                    $item = $item->toArray();
                    unset($item['properties']);
                    return Arr::dot($item);
                });
                Http::withHeaders([
                    'Content-Type' => "application/json",
                    'Accept' => "application/json",
                ])->post(config('chatbot.host') . "/api/feeder/companies", [
                    'data' => $records
                ]);
            });
    }


    public function createUser() {
        $item = $this;
        $user = User::updateOrCreate([
            'email' => Str::camel($item->name)."@mail.com",
        ],[
            'name' => $item->name,
            'company_id' => $item->id,
            'password' => Hash::make(1)
        ]);
        $user->assignRole('company');
        return $item;
    }
}
