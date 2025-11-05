<?php

namespace App\Modules\Quotations\Domain\Models;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    public $casts = [
        'status' => QuotationStatus::class,
    ];
}
