<?php

namespace App\Modules\DataSheets\Presentation\Http\Controllers;

use Illuminate\Http\Response;
use App\Modules\DataSheets\Application\DTOs\DataTemplateInput;
use App\Modules\Shared\Support\Helper\ApiResponse;
use App\Modules\DataSheets\Application\UseCases\{
    CreateDataTemplateUseCase,
    DeleteDataTemplateUseCase,
    ShowDataTemplateUseCase,
    UpdateDataTemplateUseCase,
    ListSubcategoryDataTemplatesUseCase
};
use App\Modules\DataSheets\Presentation\Resources\DataTemplateResource;
use App\Modules\DataSheets\Presentation\Http\Requests\{
    StoreDataTemplateRequest,
    UpdateDataTemplateRequest,
};

use App\Modules\DataSheets\Domain\ValueObjects\DataTemplateType;

class DataTemplateController
{
    public function __construct(
        private readonly ShowDataTemplateUseCase $showDataTemplate,
        private readonly CreateDataTemplateUseCase $createDataTemplate,
        private readonly UpdateDataTemplateUseCase $updateDataTemplate,
        private readonly DeleteDataTemplateUseCase $deleteDataTemplate,
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

    public function store(StoreDataTemplateRequest $request)
    {
        $input = DataTemplateInput::fromArray($request->validated());
        $template = $this->createDataTemplate->handle($input, DataTemplateType::from($request->type));

        return ApiResponse::success(
            DataTemplateResource::make($template),
            __('apiMessages.created'),
            Response::HTTP_CREATED,
        );
    }

    public function show(int $dataTemplate)
    {
        $template = $this->showDataTemplate->handle($dataTemplate);

        return ApiResponse::success(DataTemplateResource::make($template));
    }

    public function update(UpdateDataTemplateRequest $request, int $dataTemplate)
    {
        $input = DataTemplateInput::fromArray($request->validated());
        $template = $this->updateDataTemplate->handle($dataTemplate, $input, DataTemplateType::from($request->type));

        return ApiResponse::success(
            DataTemplateResource::make($template),
            __('apiMessages.updated'),
        );
    }

    public function destroy(int $dataTemplate)
    {
        $this->deleteDataTemplate->handle($dataTemplate);

        return ApiResponse::deleted();
    }
}
