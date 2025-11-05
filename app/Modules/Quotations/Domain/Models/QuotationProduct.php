<?php

namespace App\Modules\Quotations\Domain\Models;

use App\Modules\Products\Domain\ValueObjects\PriceCurrency;
use Illuminate\Database\Eloquent\Model;

class QuotationProduct extends Model
{
    public $casts = [
        'currency' => PriceCurrency::class,
    ];
}
