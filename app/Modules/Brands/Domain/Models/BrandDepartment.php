<?php

namespace App\Modules\Brands\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Modules\Departments\Domain\Models\Department;

class BrandDepartment extends Model
{
    /**
     * Mass assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'brand_id',
        'department_id',
    ];

    /**
     * Attribute casting.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'brand_id' => 'integer',
        'department_id' => 'integer',
    ];

    /**
     * The brand side of the pivot.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * The department side of the pivot.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
