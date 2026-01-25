<?php

namespace App\Modules\SpecificationLogs\Domain\Models;

use Illuminate\Database\Eloquent\Model;

class SpecificationActivityLog extends Model
{
    protected $fillable = [
        'loggable_type',
        'loggable_id',
        'activity_type',
        'old_object',
        'new_object',
        'specification_id',
    ];

    protected $casts = [
        'old_object' => 'array',
        'new_object' => 'array',
    ];

    public function loggable()
    {
        return $this->morphTo()->withTrashed();
    }
}


