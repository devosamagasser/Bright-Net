<?php

namespace App\Modules\DataSheets\Presentation\Http\Controllers;

use App\Modules\DataSheets\Domain\ValueObjects\DataTemplateType;

class ProductDataTemplateController extends AbstractDataTemplateController
{
    protected function type(): ?DataTemplateType
    {
        return DataTemplateType::PRODUCT;
    }
}
