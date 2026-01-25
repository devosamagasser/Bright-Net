<?php

namespace App\Modules\SolutionsCatalog\Domain\Models;

use App\Models\Supplier;
use App\Models\SupplierSolution;
use App\Modules\Brands\Domain\Models\Brand;
use App\Modules\Departments\Domain\Models\Department;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Solution extends Model
{
    /** @use HasFactory<\Database\Factories\SolutionFactory> */
    use HasFactory;
    use Translatable;

    /**
     * Attributes handled through the translation table.
     *
     * @var array<int, string>
     */
    public $translatedAttributes = [
        'name',
    ];

    /**
     * Always eager load translations for convenience.
     *
     * @var array<int, string>
     */
    protected $with = [
        'translations',
    ];

    /**
     * A solution groups many departments.
     */
    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }

    /**
     * A solution can be offered by many brands.
     */
    public function brands(): BelongsToMany
    {
        return $this->belongsToMany(Brand::class, 'solution_brands')
            ->withTimestamps();
    }

    /**
     * Direct access to the supplier_solution pivot records.
     */
    public function supplierSolutions(): HasMany
    {
        return $this->hasMany(SupplierSolution::class);
    }

    /**
     * Suppliers linked to this solution.
     */
    public function suppliers(): BelongsToMany
    {
        return $this->belongsToMany(Supplier::class, 'supplier_solutions')
            ->withTimestamps();
    }
}
