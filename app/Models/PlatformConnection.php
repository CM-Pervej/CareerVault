<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlatformConnection extends Model
{
    use SoftDeletes;

    protected $table = 'platform_platforms';

    protected $fillable = [
        'platform_id',
        'connected_platform_id',
        'account_url',
        'deleted_by_platform_id',
    ];

    public function platform(): BelongsTo
    {
        return $this->belongsTo(Platform::class, 'platform_id');
    }

    public function connectedPlatform(): BelongsTo
    {
        return $this->belongsTo(Platform::class, 'connected_platform_id');
    }
}
