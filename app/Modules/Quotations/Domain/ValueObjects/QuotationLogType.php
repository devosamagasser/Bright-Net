<?php

namespace App\Modules\Quotations\Domain\ValueObjects;

enum QuotationLogType: string
{
    case CREATE = 'create';
    case EDIT = 'edit';
    case DELETE = 'delete';
}
