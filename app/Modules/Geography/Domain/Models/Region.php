<?php

namespace App\Modules\Geography\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Brands\Domain\Models\Brand;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    /**
     * Mass assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Brands operating within this region.
     */
    public function brands(): HasMany
    {
        return $this->hasMany(Brand::class);
    }
}
