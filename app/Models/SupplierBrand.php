<?php

namespace App\Models;

use App\Modules\Brands\Domain\Models\Brand;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupplierBrand extends Model
{
    /**
     * Mass assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'supplier_solution_id',
        'brand_id',
    ];

    /**
     * Attribute casting.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'supplier_solution_id' => 'integer',
        'brand_id' => 'integer',
    ];

    /**
     * Owning supplier-solution record.
     */
    public function supplierSolution(): BelongsTo
    {
        return $this->belongsTo(SupplierSolution::class);
    }

    /**
     * Related brand entity.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Departments linked to this supplier-brand association.
     */
    public function departments(): HasMany
    {
        return $this->hasMany(SupplierDepartment::class);
    }
}
