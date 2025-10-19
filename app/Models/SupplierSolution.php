<?php

namespace App\Models;

use App\Modules\SolutionsCatalog\Domain\Models\Solution;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupplierSolution extends Model
{
    /**
     * Mass assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'supplier_id',
        'solution_id',
    ];

    /**
     * Attribute casting.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'supplier_id' => 'integer',
        'solution_id' => 'integer',
    ];

    /**
     * Parent supplier.
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Related solution.
     */
    public function solution(): BelongsTo
    {
        return $this->belongsTo(Solution::class);
    }

    /**
     * Brands attached to this supplier-solution mapping.
     */
    public function brands(): BelongsToMany
    {
        return $this->belongsToMany(
            Brand::class,
            'supplier_solution_brands',
            'supplier_solution_id',
            'brand_id'
        )->withTimestamps();
    }

    /**
     * Pivot records relating to brands.
     */
    public function supplierSolutionBrands(): HasMany
    {
        return $this->hasMany(SupplierSolutionBrand::class);
    }
}
