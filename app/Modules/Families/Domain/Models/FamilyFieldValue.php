<?php

namespace App\Modules\Families\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Modules\DataSheets\Domain\Models\DataField;

class FamilyFieldValue extends Model
{
    /**
     * @var string
     */
    protected $table = 'family_field_values';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'family_id',
        'data_field_id',
        'value',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'family_id' => 'integer',
        'data_field_id' => 'integer',
        'value' => 'json',
    ];

    /**
     * @var array<int, string>
     */
    protected $with = [
        'field',
    ];

    public function family(): BelongsTo
    {
        return $this->belongsTo(Family::class);
    }

    public function field(): BelongsTo
    {
        return $this->belongsTo(DataField::class, 'data_field_id');
    }
}
