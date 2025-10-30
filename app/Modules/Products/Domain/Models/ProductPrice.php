<?php

namespace App\Modules\Products\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Modules\Products\Domain\ValueObjects\PriceCurrency;
use App\Modules\Products\Domain\ValueObjects\DeliveryTimeUnit;

class ProductPrice extends Model
{
    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'price',
        'from',
        'to',
        'currency',
        'delivery_time_unit',
        'delivery_time_value',
        'vat_status',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'product_id' => 'integer',
        'price' => 'float',
        'from' => 'integer',
        'to' => 'integer',
        'currency' => PriceCurrency::class,
        'delivery_time_unit' => DeliveryTimeUnit::class,
        'vat_status' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
