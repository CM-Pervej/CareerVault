<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Override;

class PlatformPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'platform_id', 'name', 'slug', 'url', 'description', 'activity_level', 'is_active'
    ];

    #[Override]
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }
}
