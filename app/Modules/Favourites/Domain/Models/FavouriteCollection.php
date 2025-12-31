<?php

namespace App\Modules\Favourites\Domain\Models;

use App\Modules\Companies\Domain\Models\Company;
use App\Modules\Products\Domain\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FavouriteCollection extends Model
{
    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'company_id',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'company_id' => 'integer',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'favourite_collection_products')
            ->withTimestamps();
    }
}
