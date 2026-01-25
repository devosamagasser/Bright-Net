<?php

namespace App\Modules\PriceRules\Domain\Models;

use App\Modules\Products\Domain\Models\ProductPrice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductPriceFactor extends Model
{
    protected $fillable = [
        'price_id',
        'factor_id',
    ];

    public function price(): BelongsTo
    {
        return $this->belongsTo(ProductPrice::class, 'price_id');
    }

    public function factor(): BelongsTo
    {
        return $this->belongsTo(PriceFactor::class, 'factor_id');
    }
}
