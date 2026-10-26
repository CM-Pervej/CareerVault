<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Platform extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable=[
        'name', 'slug', 'official_name', 'base_url', 'job_url',
        'short_desc', 'description', 'job_type', 'business_model', 'account_required', 'is_active',
        'color', 'icon', 'logo', 'cover_image', 'sort_order',
        'is_bangladesh_focused', 'founded_month', 'founded_year', 'founded_by', 'last_verified_at',
        'created_by', 'updated_by', 'deleted_by',
    ];

    protected function casts(): array
    {
        return [
            'account_required'=>'boolean',
            'is_active'=>'boolean',
            'is_bangladesh_focused'=>'boolean',
            'founded_month'=>'integer',
            'founded_year'=>'integer',
            'sort_order'=>'integer',
            'last_verified_at'=>'datetime',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_platform')
            ->withPivot([
                'profile_url',
                'is_verified',
                'last_verified_at',
            ])
            ->withTimestamps();
    }

    public function platformPages()
    {
        return $this->hasMany(PlatformPage::class)
            ->orderBy('sort_order')
            ->orderBy('name');
    }

    public function platformGroups()
    {
        return $this->hasMany(PlatformGroup::class)
            ->orderBy('sort_order')
            ->orderBy('name');
    }

    public function connectedPlatforms(): BelongsToMany
    {
        return $this->belongsToMany(
            Platform::class,
            'platform_platforms',
            'platform_id',
            'connected_platform_id'
        )
            ->withPivot('id', 'account_url', 'deleted_at', 'deleted_by_platform_id')
            ->withTimestamps()
            ->wherePivotNull('deleted_at');
    }

    public function connectedFromPlatforms(): BelongsToMany
    {
        return $this->belongsToMany(
            Platform::class,
            'platform_platforms',
            'connected_platform_id',
            'platform_id'
        )
            ->withPivot('id', 'account_url', 'deleted_at', 'deleted_by_platform_id')
            ->withTimestamps()
            ->wherePivotNull('deleted_at');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}