<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Brands\Domain\Models\Brand;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

}
