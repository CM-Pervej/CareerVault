<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'icon', 'color', 'base_url', 'job_url', 'job_type'];

    public function getRouteKeyName(): string{
        return 'slug';
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class)
            ->withPivot('url')
            ->withTimestamps();
    }
}
