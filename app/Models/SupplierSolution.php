<?php

namespace App\Models;

use App\Modules\SolutionsCatalog\Domain\Models\Solution;
use App\Modules\Brands\Domain\Models\Brand;
use App\Modules\Departments\Domain\Models\Department;
use App\Models\SupplierSolutionDepartment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupplierSolution extends Model
{
    /**
     * Mass assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'supplier_id',
        'solution_id',
    ];

    /**
     * Attribute casting.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'supplier_id' => 'integer',
        'solution_id' => 'integer',
    ];

    /**
     * Parent supplier.
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

}
