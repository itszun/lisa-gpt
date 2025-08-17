<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyProperty extends Model
{
    protected $fillable = ['company_id', 'key', 'value'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
