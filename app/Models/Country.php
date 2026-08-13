<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'iso_code',
        'phone_code',
        'currency',
        'currency_code',
        'capital',
        'region',
        'flag',
    ];

    public function companies()
    {
        return $this->belongsToMany(Company::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
