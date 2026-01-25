<?php

namespace App\Modules\PriceRules\Domain\Models;

use App\Models\CompanyUser;
use App\Models\Supplier;
use App\Modules\PriceRules\Domain\ValueObjects\PriceFactorStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PriceFactor extends Model
{
    protected $fillable = [
        'supplier_id',
        'user_id',
        'factor',
        'status',
        'parent_factor_id',
        'notes',
    ];

    protected $casts = [
        'factor' => 'decimal:8',
        'status' => PriceFactorStatus::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(CompanyUser::class, 'user_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function parentFactor(): BelongsTo
    {
        return $this->belongsTo(PriceFactor::class, 'parent_factor_id');
    }

    public function childFactors(): HasMany
    {
        return $this->hasMany(PriceFactor::class, 'parent_factor_id');
    }

    public function productPriceFactors(): HasMany
    {
        return $this->hasMany(ProductPriceFactor::class, 'factor_id');
    }

    public function isActive(): bool
    {
        return $this->status === PriceFactorStatus::ACTIVE;
    }

    public function isReverted(): bool
    {
        return $this->status === PriceFactorStatus::REVERTED;
    }

    public function revert(): void
    {
        $this->status = PriceFactorStatus::REVERTED;
        $this->save();
    }

    public function activate(): void
    {
        $this->status = PriceFactorStatus::ACTIVE;
        $this->save();
    }
}
