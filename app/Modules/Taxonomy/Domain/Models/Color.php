<?php

namespace App\Modules\Taxonomy\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Color extends Model
{
    use Translatable;

    /**
     * Fillable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'hex_code',
    ];

    /**
     * Attribute casting.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'hex_code' => 'string',
    ];

    /**
     * Translated attributes.
     *
     * @var array<int, string>
     */
    public $translatedAttributes = [
        'name',
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
     * Color translations.
     */
    public function translations(): HasMany
    {
        return $this->hasMany(ColorTranslation::class);
    }
}