<?php

namespace App\Modules\Products\Domain\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Modules\Families\Domain\Models\Family;
use App\Modules\DataSheets\Domain\Models\DataTemplate;
use App\Modules\Products\Domain\Models\ProductFieldValue;
use App\Modules\Products\Domain\Models\ProductPrice;
use App\Modules\Products\Domain\Models\ProductAccessory;
use App\Modules\Taxonomy\Domain\Models\Color;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia, Translatable;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'family_id',
        'data_template_id',
        'code',
        'stock',
        'disclaimer',
        'color',
        'style',
        'manufacturer',
        'application',
        'origin',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'family_id' => 'integer',
        'data_template_id' => 'integer',
        'stock' => 'integer',
    ];

    /**
     * @var array<int, string>
     */
    protected $with = [
        'translations',
    ];

    /**
     * @var array<int, string>
     */
    protected $translatedAttributes = [
        'name',
        'description',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('gallery');
        $this->addMediaCollection('documents');
        $this->addMediaCollection('dimensions');
    }

    public function family(): BelongsTo
    {
        return $this->belongsTo(Family::class);
    }

    public function dataTemplate(): BelongsTo
    {
        return $this->belongsTo(DataTemplate::class);
    }

    public function fieldValues(): HasMany
    {
        return $this->hasMany(ProductFieldValue::class);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(ProductPrice::class);
    }

    public function accessories(): HasMany
    {
        return $this->hasMany(ProductAccessory::class);
    }

}
