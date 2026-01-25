<?php

namespace App\Modules\Specifications\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Modules\Companies\Domain\Models\Company;
use App\Modules\Quotations\Domain\ValueObjects\QuotationStatus;
use App\Modules\SpecificationLogs\Domain\Models\SpecificationActivityLog;

class Specification extends Model
{
    protected $table = 'specifications';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'status',
        'reference',
        'title',
        'company_id',
        'general_notes',
        'show_quantity',
        'show_approval',
        'show_reference',
        'log_status',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'status' => QuotationStatus::class,
        'company_id' => 'integer',
        'general_notes' => 'array',
        'show_quantity' => 'boolean',
        'show_approval' => 'boolean',
        'show_reference' => 'boolean',
        'log_status' => 'integer',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SpecificationItem::class)
            ->orderBy('position')
            ->orderBy('id');
    }

    public function accessories(): HasMany
    {
        return $this->hasMany(SpecificationItemAccessory::class)
            ->orderBy('position')
            ->orderBy('id');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(SpecificationActivityLog::class);
    }

    public function isDraft(): bool
    {
        return $this->status === QuotationStatus::DRAFT;
    }
}


