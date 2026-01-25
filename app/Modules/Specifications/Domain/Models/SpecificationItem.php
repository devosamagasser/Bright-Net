<?php

namespace App\Modules\Specifications\Domain\Models;

use App\Models\Supplier;
use App\Modules\Brands\Domain\Models\Brand;
use App\Modules\Departments\Domain\Models\Department;
use App\Modules\Families\Domain\Models\Family;
use App\Modules\Products\Domain\Models\Product;
use App\Modules\SolutionsCatalog\Domain\Models\Solution;
use App\Modules\Subcategories\Domain\Models\Subcategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SpecificationItem extends Model
{
    use SoftDeletes;

    protected $table = 'specification_products';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'item_ref',
        'position',
        'specification_id',
        'solution_id',
        'supplier_id',
        'brand_id',
        'department_id',
        'subcategory_id',
        'family_id',
        'product_id',
        'product_code',
        'product_name',
        'product_description',
        'product_origin',
        'brand_name',
        'notes',
        'quantity',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'specification_id' => 'integer',
        'solution_id' => 'integer',
        'supplier_id' => 'integer',
        'brand_id' => 'integer',
        'department_id' => 'integer',
        'subcategory_id' => 'integer',
        'family_id' => 'integer',
        'product_id' => 'integer',
        'position' => 'integer',
        'quantity' => 'integer',
    ];

    public function specification(): BelongsTo
    {
        return $this->belongsTo(Specification::class);
    }

    public function solution(): BelongsTo
    {
        return $this->belongsTo(Solution::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function family(): BelongsTo
    {
        return $this->belongsTo(Family::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class)->with('accessories');
    }

    public function accessories(): HasMany
    {
        return $this->hasMany(SpecificationItemAccessory::class, 'spec_product_id');
    }
}


