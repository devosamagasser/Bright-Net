<?php

namespace App\Modules\DataSheets\Domain\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Modules\DataSheets\Domain\ValueObjects\DataFieldType;

class DataField extends Model
{
    use Translatable;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'data_template_id',
        'type',
        'is_required',
        'options',
        'is_filterable',
        'position',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'data_template_id' => 'integer',
        'is_required' => 'boolean',
        'is_filterable' => 'boolean',
        'options' => 'array',
        'position' => 'integer',
        'type' => DataFieldType::class,
    ];

    /**
     * @var array<int, string>
     */
    public $translatedAttributes = [
        'label',
        'placeholder',
    ];

    /**
     * @var array<int, string>
     */
    protected $with = [
        'translations',
    ];

    /**
     * Template the field belongs to.
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(DataTemplate::class, 'data_template_id');
    }

    public static function booted(): void
    {
        static::saving(function (DataField $dataField) {
            $dataField->name = Str::slug($dataField->type->value . '-' . $dataField->position);
        });
    }
}
