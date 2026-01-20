<?php

namespace App\Modules\Products\Domain\Models;

use App\Models\Supplier;
use App\Models\SupplierDepartment;
use App\Models\SupplierSolution;
use App\Modules\Brands\Domain\Models\Brand;
use App\Modules\Departments\Domain\Models\Department;
use App\Modules\Families\Domain\Models\Family;
use App\Modules\SolutionsCatalog\Domain\Models\Solution;
use App\Modules\Subcategories\Domain\Models\Subcategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductGroup extends Model
{
    protected $fillable = [
        'supplier_id',
        'solution_id',
        'supplier_solution_id',
        'brand_id',
        'supplier_brand_id',
        'department_id',
        'supplier_department_id',
        'subcategory_id',
        'family_id',
        'data_template_id'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function solution(): BelongsTo
    {
        return $this->belongsTo(Solution::class);
    }

    public function supplierSolution(): BelongsTo
    {
        return $this->belongsTo(SupplierSolution::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function supplierDepartment(): BelongsTo
    {
        return $this->belongsTo(SupplierDepartment::class);
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function family(): BelongsTo
    {
        return $this->belongsTo(Family::class);
    }
}
