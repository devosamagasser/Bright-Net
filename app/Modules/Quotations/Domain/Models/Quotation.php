<?php

namespace App\Modules\Quotations\Domain\Models;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Modules\Companies\Domain\Models\Company;
use App\Modules\Products\Domain\ValueObjects\PriceCurrency;
use App\Modules\Quotations\Domain\ValueObjects\QuotationStatus;

class Quotation extends Model
{
    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'status',
        'reference',
        'title',
        'supplier_id',
        'company_id',
        'valid_until',
        'notes',
        'subtotal',
        'discount_total',
        'total',
        'currency',
        'meta',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'status' => QuotationStatus::class,
        'supplier_id' => 'integer',
        'company_id' => 'integer',
        'valid_until' => 'date',
        'subtotal' => 'float',
        'discount_total' => 'float',
        'total' => 'float',
        'currency' => PriceCurrency::class,
        'meta' => 'array',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(QuotationProduct::class)
            ->orderBy('position')
            ->orderBy('id');
    }

    public function accessories(): HasMany
    {
        return $this->hasMany(QuotationProductAccessory::class)
            ->orderBy('position')
            ->orderBy('id');
    }

    public function isDraft(): bool
    {
        return $this->status === QuotationStatus::DRAFT;
    }
}
