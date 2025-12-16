<?php

namespace App\Modules\QuotationLogs\Domain\ValueObjects;

enum QuotationActivityType: string
{
    case CREATE = 'create';
    case UPDATE = 'update';
    case DELETE = 'delete';
}
