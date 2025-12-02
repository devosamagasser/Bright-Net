<?php

namespace App\Modules\Quotations\Domain\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationActivityLog extends Model
{
    protected $fillable = [
        'loggable_type',
        'loggable_id',
        'activity_type',
        'old_value',
        'new_value',
    ];

    protected $casts = [
        'activity_type' => QuotationLogType::class,
    ];

    public function loggable()
    {
        return $this->morphTo();
    }

}
