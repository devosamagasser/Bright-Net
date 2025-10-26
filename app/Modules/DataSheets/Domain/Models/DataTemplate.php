<?php

namespace App\Modules\DataSheets\Domain\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Modules\Subcategories\Domain\Models\Subcategory;
use App\Modules\DataSheets\Domain\ValueObjects\DataTemplateType;

class DataTemplate extends Model
{
    use Translatable;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'subcategory_id',
        'type',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'subcategory_id' => 'integer',
        'type' => DataTemplateType::class,
    ];

    /**
     * @var array<int, string>
     */
    public $translatedAttributes = [
        'name',
        'description',
    ];

    /**
     * Always eager load translations.
     *
     * @var array<int, string>
     */
    protected $with = [
        'translations',
    ];

    /**
     * Fields that belong to the template.
     */
    public function fields(): HasMany
    {
        return $this->hasMany(DataField::class)->orderBy('position');
    }

    /**
     * Subcategory the template belongs to.
     */
    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class);
    }
}
