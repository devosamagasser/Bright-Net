<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subcategory extends Model
{
    use HasFactory, Translatable;

    /**
     * Fillable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'department_id',
    ];

    /**
     * Attribute casting.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'department_id' => 'integer',
    ];

    /**
     * Translatable attributes.
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
     * Parent department.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
