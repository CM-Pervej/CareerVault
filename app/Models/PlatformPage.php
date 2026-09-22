<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlatformPage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'platform_id',
        'name',
        'slug',
        'page_type',
        'url',
        'short_desc',
        'description',
        'is_active',
        'sort_order',
        'last_verified_at',
        'deleted_by'
    ];

    protected function casts(): array
    {
        return [
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

    public function deletedBy()
    {
        return $this->belongsTo(User::class,'deleted_by');
    }
}