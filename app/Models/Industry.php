<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Industry extends Model
{
    protected $fillable = ['name', 'slug'];

    public function companies()
    {
        return $this->belongsToMany(Company::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
