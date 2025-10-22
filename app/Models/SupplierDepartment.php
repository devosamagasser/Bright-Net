<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Departments\Domain\Models\Department;
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

}
