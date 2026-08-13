<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Override;

class City extends Model
{
    use HasFactory;

    protected $fillable = ['country_id','name', 'slug'];

    #[Override]
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class);
    }
}
