<?php

namespace App\Modules\DataSheets\Domain\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasOne};
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
        'name',
        'slug',
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
        'dependency',
    ];

    /**
     * Template the field belongs to.
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(DataTemplate::class, 'data_template_id');
    }

    /**
     * Dependency configuration for the field.
     */
    public function dependency(): HasOne
    {
        return $this->hasOne(DependedField::class, 'data_field_id');
    }

    public static function booted(): void
    {
        static::saving(function (DataField $dataField): void {
            if (empty($dataField->name)) {
                $dataField->name = Str::slug($dataField->type->value . '-' . $dataField->position);
            }
        });
    }

    public function getSlugAttribute(): ?string
    {
        return $this->name;
    }

    public function setSlugAttribute(?string $value): void
    {
        $this->attributes['name'] = $value;
    }
}
