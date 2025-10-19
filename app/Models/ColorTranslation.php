<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ColorTranslation extends Model
{
    /**
     * Mass assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'locale',
    ];

    /**
     * Attribute casting.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'name' => 'string',
        'locale' => 'string',
    ];

    /**
     * Parent color record.
     */
    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }
}
