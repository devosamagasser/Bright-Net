<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Brands\Domain\Models\Brand;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierSolutionBrand extends Model
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
     * Related supplier-solution pivot.
     */
    public function supplierSolution(): BelongsTo
    {
        return $this->belongsTo(SupplierSolution::class);
    }

    /**
     * Related brand.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
}
