<?php

namespace App\Models;

use App\Modules\SolutionsCatalog\Domain\Models\Solution;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Department extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\DepartmentFactory> */
    use HasFactory, InteractsWithMedia, Translatable;

    /**
     * Fillable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'solution_id',
    ];

    /**
     * Attribute casting.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'solution_id' => 'integer',
    ];

    /**
     * Translated attributes handled through department_translations table.
     *
     * @var array<int, string>
     */
    public $translatedAttributes = [
        'name',
    ];

    /**
     * Always eager load translations.
     *
     * @var array<int, string>
     */
    protected $with = [
        'translations',
    ];

    /**
     * Department belongs to a solution.
     */
    public function solution(): BelongsTo
    {
        return $this->belongsTo(Solution::class);
    }

    /**
     * Department owns many subcategories.
     */
    public function subcategories(): HasMany
    {
        return $this->hasMany(Subcategory::class);
    }

    /**
     * Brands associated to the department.
     */
    public function brands(): BelongsToMany
    {
        return $this->belongsToMany(Brand::class, 'brand_departments')
            ->withTimestamps();
    }
}
