<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlatformGroup extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'platform_id', 'name', 'slug', 'group_type', 'url',
        'short_desc', 'description', 'access_type', 'is_bangladesh_focused', 'logo', 'cover_image',
        'is_active', 'sort_order', 'last_verified_at', 'created_by', 'updated_by', 'deleted_by',
    ];

    protected function casts(): array
    {
        return [
            'is_bangladesh_focused' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'last_verified_at' => 'datetime',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function platform()
    {
        return $this->belongsTo(Platform::class);
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