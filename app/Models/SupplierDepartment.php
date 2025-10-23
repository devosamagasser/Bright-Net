<?php

namespace App\Models;

use App\Modules\Departments\Domain\Models\Department;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierDepartment extends Model
{
    /**
     * Mass assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'supplier_brand_id',
        'department_id',
    ];

    /**
     * Attribute casting.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'supplier_brand_id' => 'integer',
        'department_id' => 'integer',
    ];

    /**
     * Parent supplier-brand association.
     */
    public function supplierBrand(): BelongsTo
    {
        return $this->belongsTo(SupplierBrand::class);
    }

    /**
     * Underlying department entity.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
