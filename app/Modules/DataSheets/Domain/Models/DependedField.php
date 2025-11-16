<?php
namespace App\Modules\DataSheets\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DependedField extends Model
{
    protected $fillable = [
        'data_field_id',
        'depends_on_field_id',
        'values',
    ];
    protected $casts = [
        'values' => 'array',
    ];
    public function dataField(): BelongsTo
    {
        return $this->belongsTo(DataField::class, 'data_field_id');
    }

    public function dependsOnField(): BelongsTo
    {
        return $this->belongsTo(DataField::class, 'depends_on_field_id');
    }
}
