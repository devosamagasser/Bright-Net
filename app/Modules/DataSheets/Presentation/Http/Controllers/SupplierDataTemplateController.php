<?php

namespace App\Modules\DataSheets\Presentation\Http\Controllers;

use App\Modules\Shared\Support\Helper\ApiResponse;
use App\Modules\DataSheets\Presentation\Resources\DataTemplateResource;
use App\Modules\DataSheets\Application\UseCases\ShowSubcategoryDataTemplateUseCase;
use App\Modules\DataSheets\Domain\ValueObjects\DataTemplateType;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SupplierDataTemplateController
{
    public function __construct(
        private readonly ShowSubcategoryDataTemplateUseCase $showSubcategoryDataTemplate,
    ) {
    }

    public function show(int $subcategory, string $type)
    {
        $typeEnum = DataTemplateType::tryFrom(strtolower($type));

        if (! $typeEnum) {
            return ApiResponse::validationError([
                'type' => [trans('validation.in', ['attribute' => 'type'])],
            ]);
        }

        try {
            $template = $this->showSubcategoryDataTemplate->handle($subcategory, $typeEnum);
        } catch (ModelNotFoundException $exception) {
            return ApiResponse::notFound();
        }

        return ApiResponse::success(
            DataTemplateResource::make($template)->resolve()
        );
    }
}
