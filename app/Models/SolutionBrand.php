<?php

namespace App\Models;

use App\Modules\SolutionsCatalog\Domain\Models\Solution;
use App\Modules\Brands\Domain\Models\Brand;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SolutionBrand extends Model
{
    /**
     * Mass assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'solution_id',
        'brand_id',
    ];

    /**
     * Attribute casting.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'solution_id' => 'integer',
        'brand_id' => 'integer',
    ];

    /**
     * Related solution.
     */
    public function solution(): BelongsTo
    {
        return $this->belongsTo(Solution::class);
    }

    /**
     * Related brand.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
}
