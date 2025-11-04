<?php

namespace App\Modules\Products\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Modules\Products\Domain\ValueObjects\AccessoryType;

class ProductAccessory extends Model
{
    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'accessory_id',
        'accessory_type',
        'quantity',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'product_id' => 'integer',
        'accessory_id' => 'integer',
        'accessory_type' => AccessoryType::class,
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function accessory(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'accessory_id');
    }
}
