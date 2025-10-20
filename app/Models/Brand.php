<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Modules\Departments\Domain\Models\Department;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\SolutionsCatalog\Domain\Models\Solution;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Brand extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\BrandFactory> */
    use HasFactory, InteractsWithMedia;

    /**
     * The attributes that can be mass assigned.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'region_id',
    ];

    /**
     * Attribute casting.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'region_id' => 'integer',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')->singleFile();
    }

    /**
     * Brand -> Region relationship.
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    /**
     * Departments linked to the brand.
     */
    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'brand_departments')
            ->withTimestamps();
    }

    /**
     * Solutions supported by the brand.
     */
    public function solutions(): BelongsToMany
    {
        return $this->belongsToMany(Solution::class, 'solution_brands')
            ->withTimestamps();
    }

    /**
     * Pivot records linking suppliers and brands through solutions.
     */
    public function supplierSolutionBrands(): HasMany
    {
        return $this->hasMany(SupplierSolutionBrand::class);
    }

    /**
     * Supplier-solution combinations that include this brand.
     */
    public function supplierSolutions(): BelongsToMany
    {
        return $this->belongsToMany(
            SupplierSolution::class,
            'supplier_solution_brands',
            'brand_id',
            'supplier_solution_id'
        )->withTimestamps();
    }
}
