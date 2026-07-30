<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'website', 'career_page', 'emails', 'phones', 'address', 'social_links',
    ];

    protected $casts = [
        'emails'        => 'array',
        'phones'        => 'array',
        'address'       => 'array',
        'social_links'  => 'array',
    ];

    public function getRouteKeyName(): string{
        return 'slug';
    }

    public function industries()
    {
        return $this->belongsToMany(Industry::class) 
                    ->withTimestamps();
    }

    public function countries()
    {
        return $this->belongsToMany(Country::class)
                    ->withTimestamps();
    }
}
