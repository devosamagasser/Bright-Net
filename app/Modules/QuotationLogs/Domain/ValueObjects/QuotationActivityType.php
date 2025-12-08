<?php

namespace App\Modules\QuotationLogs\Domain\ValueObjects;

enum QuotationActivityType: string
{
    case CREATE_PRODUCT = 'create_product';
    case UPDATE_PRODUCT = 'update_product';
    case DELETE_PRODUCT = 'delete_product';
    case CREATE_ACCESSORY = 'create_accessory';
    case UPDATE_ACCESSORY = 'update_accessory';
    case DELETE_ACCESSORY = 'delete_accessory';
}
