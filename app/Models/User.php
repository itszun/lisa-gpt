<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Filament\Panel;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasRoles, HasPanelShield;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'company_id',
        'talent_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        // Super admin hanya boleh masuk panel 'admin'
        if ($this->hasRole('super_admin') or Str::endsWith($this->email, 'altateknologi.com')) {
            return $panel->getId() === 'admin';
        }

        // Talent user hanya boleh ke talent panel
        if ($this->hasRole('talent')) {
            return $panel->getId() === 'talent';
        }

        // Company user hanya boleh ke company panel
        if ($this->hasRole('company')) {
            return $panel->getId() === 'company';
        }

        return false;
    }

    public function chat()
    {
        return $this->hasMany(Chat::class);
    }

    public function getChatUserIdAttribute()
    {
        return $this->id . "@" . Str::snake($this->name);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function talent()
    {
        return $this->belongsTo(Talent::class);
    }


    public static function feedAll()
    {
        static::query()
            ->with('company', fn($q) => $q->select('id', 'name'))
            ->with('talent', fn($q) => $q->select('id', 'name'))
            ->chunk(10, function ($records) {
                $records = $records->map(function ($item) {
                    return Arr::dot($item->toArray());
                });
                Http::withHeaders([
                    'Content-Type' => "application/json",
                    'Accept' => "application/json",
                ])->post(config('chatbot.host')."/api/feeder/users", [
                    'data' => $records
                ]);
            });
    }
}
