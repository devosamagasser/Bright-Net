<?php

namespace App\Models;

use App\Modules\SolutionsCatalog\Domain\Models\Solution;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use App\Modules\Companies\Domain\Models\Company;
use App\Models\SupplierSolution;
use App\Models\SupplierSolutionBrand;
use App\Models\SupplierSolutionDepartment;

class Supplier extends Model
{
    /**
     * Mass assignable supplier attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'contact_email',
        'contact_phone',
        'website',
    ];

    /**
     * Attribute casting definitions.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'company_id' => 'int',
    ];

    /**
     * Base company record associated with this supplier.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

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

    /**
     * Pivot records linking this supplier to departments via solutions.
     */
    public function supplierSolutionDepartments(): HasManyThrough
    {
        return $this->hasManyThrough(
            SupplierSolutionDepartment::class,
            SupplierSolution::class,
            'supplier_id',
            'supplier_solution_id'
        );
    }
}
