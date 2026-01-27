<?php

namespace App\Modules\DataSheets\Presentation\Http\Controllers;

use App\Modules\Shared\Support\Helper\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Modules\DataSheets\Domain\ValueObjects\DataTemplateType;
use App\Modules\DataSheets\Presentation\Resources\DataTemplateResource;
use App\Modules\DataSheets\Presentation\Resources\DataTemplateResourceV2;
use App\Modules\DataSheets\Application\UseCases\ShowSubcategoryDataTemplateUseCase;

class SupplierDataTemplateController
{
    public function __construct(
        private readonly ShowSubcategoryDataTemplateUseCase $showSubcategoryDataTemplate,
    ) {
    }

//    public function show(int $subcategory, string $type)
//    {
//        $typeEnum = DataTemplateType::tryFrom(strtolower($type));
//
//        if (! $typeEnum) {
//            return ApiResponse::validationError([
//                'type' => [trans('validation.in', ['attribute' => 'type'])],
//            ]);
//        }
//        $template = $this->showSubcategoryDataTemplate->handle($subcategory, $typeEnum);
//
//        return ApiResponse::success(
//            DataTemplateResource::make($template)->resolve()
//        );
//    }

    public function showV2(int $subcategory, string $type)
    {
        $typeEnum = DataTemplateType::tryFrom(strtolower($type));

        if (! $typeEnum) {
            return ApiResponse::validationError([
                'type' => [trans('validation.in', ['attribute' => 'type'])],
            ]);
        }
        $template = $this->showSubcategoryDataTemplate->handle($subcategory, $typeEnum);

        return ApiResponse::success(
            DataTemplateResourceV2::make($template)->resolve()
        );
    }
}
