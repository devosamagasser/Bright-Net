<?php

namespace App\Modules\Quotations\Domain\ValueObjects;

enum QuotationActivityType: string
{
    case CREATE = 'create';
    case UPDATE = 'update';
    case DELETE = 'delete';
}
