<?php

namespace App\Modules\Products\Domain\Models;

use App\Models\Supplier;
use App\Models\SupplierDepartment;
use App\Models\SupplierSolution;
use App\Modules\Brands\Domain\Models\Brand;
use App\Modules\DataSheets\Domain\Models\DataTemplate;
use App\Modules\Departments\Domain\Models\Department;
use App\Modules\Families\Domain\Models\Family;
use App\Modules\Shared\Support\Traits\ModelHelper;
use App\Modules\SolutionsCatalog\Domain\Models\Solution;
use App\Modules\Subcategories\Domain\Models\Subcategory;
use Astrotomic\Translatable\Translatable;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia, Translatable, Filterable, ModelHelper;

    /**
     * @var array<int, string>
     */
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
        'product_group_id',
        'data_template_id',
        'code',
        'stock',
        'disclaimer',
        'color',
        'style',
        'manufacturer',
        'application',
        'origin',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'family_id' => 'integer',
        'data_template_id' => 'integer',
        'stock' => 'integer',
    ];

    /**
     * @var array<int, string>
     */
    protected $with = [
        'translations',
    ];

    /**
     * @var array<int, string>
     */
    protected $translatedAttributes = [
        'name',
        'description',
    ];


    public function setStockAttribute(?int $value): void
    {
        $this->attributes['stock'] = $value ?? 0;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('gallery');
        $this->addMediaCollection('documents');
        $this->addMediaCollection('dimensions');
        $this->addMediaCollection('quotation_image');
        $this->addMediaCollection('consultant_approvals');
    }

    public function family(): BelongsTo
    {
        return $this->belongsTo(Family::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(ProductGroup::class);
    }

    public function dataTemplate(): BelongsTo
    {
        return $this->belongsTo(DataTemplate::class);
    }

    public function fieldValues(): HasMany
    {
        return $this->hasMany(ProductFieldValue::class);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(ProductPrice::class);
    }

    public function accessories(): HasMany
    {
        return $this->hasMany(ProductAccessory::class);
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

}
