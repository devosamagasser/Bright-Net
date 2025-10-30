<?php

namespace App\Modules\Products\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Modules\DataSheets\Domain\Models\DataField;

class ProductFieldValue extends Model
{
    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'data_field_id',
        'value',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'product_id' => 'integer',
        'data_field_id' => 'integer',
        'value' => 'json',
    ];

    /**
     * @var array<int, string>
     */
    protected $with = [
        'field',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function field(): BelongsTo
    {
        return $this->belongsTo(DataField::class, 'data_field_id');
    }
}
