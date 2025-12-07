<?php

namespace App\Modules\Quotations\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Quotations\Domain\ValueObjects\QuotationActivityType;

class QuotationActivityLog extends Model
{
    protected $fillable = [
        'loggable_type',
        'loggable_id',
        'activity_type',
        'old_object',
        'new_object',
    ];

    protected $casts = [
        'activity_type' => QuotationActivityType::class,
        'old_object' => 'array',
        'new_object' => 'array',
    ];

    public function loggable()
    {
        return $this->morphTo();
    }

}
