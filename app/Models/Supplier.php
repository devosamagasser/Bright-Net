<?php

namespace App\Models;

use App\Modules\SolutionsCatalog\Domain\Models\Solution;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Supplier extends Model
{
    /**
     * Guard all attributes by default and opt-in later as the schema evolves.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * Pivot records connecting this supplier to solutions.
     */
    public function supplierSolutions(): HasMany
    {
        return $this->hasMany(SupplierSolution::class);
    }

    /**
     * Solutions delivered by this supplier.
     */
    public function solutions(): BelongsToMany
    {
        return $this->belongsToMany(Solution::class, 'supplier_solutions')
            ->withTimestamps();
    }

    /**
     * Pivot records linking this supplier to brands via solutions.
     */
    public function supplierSolutionBrands(): HasManyThrough
    {
        return $this->hasManyThrough(
            SupplierSolutionBrand::class,
            SupplierSolution::class,
            'supplier_id',
            'supplier_solution_id'
        );
    }
}
