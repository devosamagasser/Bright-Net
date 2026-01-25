<?php

namespace App\Modules\PriceRules\Domain\Models;

use App\Models\Supplier;
use App\Modules\PriceRules\Domain\ValueObjects\PriceCurrency;
use Illuminate\Database\Eloquent\Model;

class CurrencyTransformFactor extends Model
{
    protected $fillable = [
        'supplier_id',
        'from',
        'to',
        'factor'
    ];

    protected $casts = [
        'from' => PriceCurrency::class,
        'to' => PriceCurrency::class,
        'factor' => 'decimal:8',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function isValid(): bool
    {
        return $this->factor > 0 && $this->from !== $this->to;
    }

    public function convert(float $amount): float
    {
        if (!$this->isValid()) {
            return $amount;
        }

        return $amount * (float) $this->factor;
    }
}
