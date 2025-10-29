<?php

namespace App\Modules\Families\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FamilyTranslation extends Model
{
    /**
     * @var string
     */
    protected $table = 'family_translations';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'description',
        'locale',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'description' => 'string',
        'locale' => 'string',
    ];

    public function family(): BelongsTo
    {
        return $this->belongsTo(Family::class);
    }
}
