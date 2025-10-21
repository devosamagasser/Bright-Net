<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Departments\Domain\Models\Department;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierSolutionDepartment extends Model
{
    /**
     * Mass assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'supplier_solution_id',
        'department_id',
    ];

    /**
     * Attribute casting.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'supplier_solution_id' => 'integer',
        'department_id' => 'integer',
    ];

    /**
     * Related supplier-solution pivot.
     */
    public function supplierSolution(): BelongsTo
    {
        return $this->belongsTo(SupplierSolution::class);
    }

    /**
     * Related department.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
