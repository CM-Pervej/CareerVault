<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'legal_name',
        'tagline',
        'short_desc',
        'description',
        'logo',
        'cover_image',
        'website',
        'career_page',
        'emails',
        'phones',
        'founded_by',
        'founded_month',
        'founded_year',
        'employee_count',
        'employee_count_updated_at',
        'company_size_id',
        'company_type_id',
        'ownership_type_id',
        'primary_industry_id',
        'parent_company_id',
        'status',
        'operating_status',
        'is_verified',
        'is_featured',
        'last_verified_at',
        'notes',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected function casts(): array
    {
        return [
            'emails' => 'array',
            'phones' => 'array',
            'founded_month' => 'integer',
            'founded_year' => 'integer',
            'employee_count' => 'integer',
            'employee_count_updated_at' => 'datetime',
            'is_verified' => 'boolean',
            'is_featured' => 'boolean',
            'last_verified_at' => 'datetime',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function companySize(): BelongsTo
    {
        return $this->belongsTo(CompanySize::class);
    }

    public function companyType(): BelongsTo
    {
        return $this->belongsTo(CompanyType::class);
    }

    public function ownershipType(): BelongsTo
    {
        return $this->belongsTo(OwnershipType::class);
    }

    public function primaryIndustry(): BelongsTo
    {
        return $this->belongsTo(Industry::class, 'primary_industry_id');
    }

    public function parentCompany(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_company_id');
    }

    public function subsidiaries(): HasMany
    {
        return $this->hasMany(self::class, 'parent_company_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deleter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /*
     * Keep these pivot relationships if the pivot tables and their
     * foreign-key names match your existing schema.
     *
     * Adjust the table names if your migrations use different names.
     */
    public function businessModels(): BelongsToMany
    {
        return $this->belongsToMany(
            BusinessModel::class,
            'company_business_model'
        )->withTimestamps();
    }

    public function industries(): BelongsToMany
    {
        return $this->belongsToMany(
            Industry::class,
            'company_industry'
        )->withTimestamps();
    }

    public function technologies(): BelongsToMany
    {
        return $this->belongsToMany(
            Technology::class,
            'company_technology'
        )->withTimestamps();
    }

    public function countries()
    {
        return $this->belongsToMany(
            Country::class,
            'company_country'
        )->withTimestamps();
    }

    public function states()
    {
        return $this->belongsToMany(
            State::class,
            'company_state'
        )->withTimestamps();
    }

    public function companyPlaces()
    {
        return $this->hasMany(CompanyCity::class);
    }

    public function platforms(): BelongsToMany
    {
        return $this->belongsToMany(Platform::class, 'company_platform')
            ->withPivot([
                'profile_url',
                'is_verified',
                'last_verified_at',
            ])
            ->withTimestamps();
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }
}