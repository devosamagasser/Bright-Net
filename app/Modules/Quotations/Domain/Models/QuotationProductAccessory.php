<?php

namespace App\Modules\Quotations\Domain\Models;
use Illuminate\Database\Eloquent\Model;

class QuotationProductAccessory extends Model
{
    public $casts = [
        'currency' => PriceCurrency::class,
        'accessory_type' => AccessoryType::class,
    ];
}
