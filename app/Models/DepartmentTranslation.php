<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DepartmentTranslation extends Model
{
    /**
     * Attributes allowed for mass assignment.
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
     * Parent department.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
