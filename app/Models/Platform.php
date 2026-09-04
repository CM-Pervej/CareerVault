<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Platform extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'official_name',
        'base_url',
        'job_url',
        'short_desc',
        'description',
        'job_type',
        'business_model',
        'account_required',
        'is_active',
        'color',
        'icon',
        'logo',
        'cover_image',
        'sort_order',
        'is_bangladesh_focused',
        'founded_month',
        'founded_year',
        'last_verified_at',
    ];

    protected function casts(): array
    {
        return [
            'account_required' => 'boolean',
            'is_active' => 'boolean',
            'is_bangladesh_focused' => 'boolean',
            'founded_month' => 'integer',
            'founded_year' => 'integer',
            'sort_order' => 'integer',
            'last_verified_at' => 'datetime',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class)
            ->withPivot('url')
            ->withTimestamps();
    }

    public function platformPages()
    {
        return $this->hasMany(PlatformPage::class);
    }
}