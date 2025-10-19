<?php

namespace App\Modules\SolutionsCatalog\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SolutionTranslation extends Model
{
    /**
     * Attributes that may be mass assigned.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'locale',
    ];

    /**
     * Attribute casting.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'name' => 'string',
        'locale' => 'string',
    ];

    /**
     * A translation belongs to its parent solution.
     */
    public function solution(): BelongsTo
    {
        return $this->belongsTo(Solution::class);
    }
}
