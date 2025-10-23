<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Modules\Companies\Domain\Models\Company;
use App\Models\SupplierSolution;

class Supplier extends Model
{
    /**
     * Mass assignable supplier attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'contact_email',
        'contact_phone',
        'website',
    ];

    /**
     * Attribute casting definitions.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'company_id' => 'int',
    ];

    /**
     * Base company record associated with this supplier.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Solutions that this supplier is associated with.
     */
    public function solutions(): HasMany
    {
        return $this->hasMany(SupplierSolution::class);
    }
}
