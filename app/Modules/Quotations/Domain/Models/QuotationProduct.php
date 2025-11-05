<?php

namespace App\Modules\Quotations\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Modules\Brands\Domain\Models\Brand;
use App\Modules\Departments\Domain\Models\Department;
use App\Modules\Products\Domain\Models\Product;
use App\Modules\Products\Domain\ValueObjects\{DeliveryTimeUnit, PriceCurrency};
use App\Modules\SolutionsCatalog\Domain\Models\Solution;
use App\Modules\Subcategories\Domain\Models\Subcategory;
use App\Modules\Families\Domain\Models\Family;
use App\Models\Supplier;

class QuotationProduct extends Model
{
    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'item_ref',
        'position',
        'quotation_id',
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
        'product_snapshot',
        'roots_snapshot',
        'price_snapshot',
        'notes',
        'delivery_time_unit',
        'delivery_time_value',
        'vat_included',
        'quantity',
        'list_price',
        'price',
        'discount',
        'total',
        'currency',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'quotation_id' => 'integer',
        'solution_id' => 'integer',
        'supplier_id' => 'integer',
        'brand_id' => 'integer',
        'department_id' => 'integer',
        'subcategory_id' => 'integer',
        'family_id' => 'integer',
        'product_id' => 'integer',
        'position' => 'integer',
        'quantity' => 'integer',
        'list_price' => 'float',
        'price' => 'float',
        'discount' => 'float',
        'total' => 'float',
        'vat_included' => 'boolean',
        'product_snapshot' => 'array',
        'roots_snapshot' => 'array',
        'price_snapshot' => 'array',
        'delivery_time_unit' => DeliveryTimeUnit::class,
        'currency' => PriceCurrency::class,
    ];

    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class);
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
        return $this->belongsTo(Product::class);
    }

    public function accessories(): HasMany
    {
        return $this->hasMany(QuotationProductAccessory::class);
    }
}
