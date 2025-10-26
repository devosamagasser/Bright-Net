<?php

namespace App\Modules\DataSheets\Presentation\Http\Controllers;

use App\Modules\Shared\Support\Helper\ApiResponse;
use App\Modules\DataSheets\Presentation\Resources\DataTemplateResource;
use App\Modules\DataSheets\Application\UseCases\ListSubcategoryDataTemplatesUseCase;

class SubcategoryDataTemplateController
{
    public function __construct(
        private readonly ListSubcategoryDataTemplatesUseCase $listSubcategoryDataTemplates,
    ) {
    }

    public function index(int $subcategory)
    {
        $templates = $this->listSubcategoryDataTemplates->handle($subcategory);

        return ApiResponse::success(
            DataTemplateResource::collection($templates)->resolve()
        );
    }
}
