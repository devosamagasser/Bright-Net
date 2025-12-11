<?php

namespace App\Modules\QuotationLogs\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\QuotationLogs\Domain\ValueObjects\QuotationActivityType;

class QuotationActivityLog extends Model
{
    protected $fillable = [
        'loggable_type',
        'loggable_id',
        'activity_type',
        'old_object',
        'new_object',
        'quotation_id'
    ];

    protected $casts = [
        'activity_type' => QuotationActivityType::class,
        'old_object' => 'array',
        'new_object' => 'array',
    ];

    public function loggable()
    {
        return $this->morphTo()->withTrashed();
    }

}
