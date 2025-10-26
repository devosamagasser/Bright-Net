<?php

namespace App\Modules\DataSheets\Presentation\Http\Controllers;

use Illuminate\Http\{Request, Response};
use App\Modules\Shared\Support\Helper\ApiResponse;
use App\Modules\DataSheets\Application\DTOs\DataTemplateInput;
use App\Modules\DataSheets\Application\UseCases\{
    CreateDataTemplateUseCase,
    DeleteDataTemplateUseCase,
    ListDataTemplatesUseCase,
    ShowDataTemplateUseCase,
    UpdateDataTemplateUseCase,
};
use App\Modules\DataSheets\Presentation\Resources\DataTemplateResource;
use App\Modules\DataSheets\Presentation\Http\Requests\{
    StoreDataTemplateRequest,
    UpdateDataTemplateRequest,
};

class DataTemplateController
{
    public function __construct(
        private readonly ListDataTemplatesUseCase $listDataTemplates,
        private readonly ShowDataTemplateUseCase $showDataTemplate,
        private readonly CreateDataTemplateUseCase $createDataTemplate,
        private readonly UpdateDataTemplateUseCase $updateDataTemplate,
        private readonly DeleteDataTemplateUseCase $deleteDataTemplate,
    ) {
    }

    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 15);
        $perPage = max(1, min(100, $perPage));

        $paginator = $this->listDataTemplates->handle($perPage);

        $collection = DataTemplateResource::collection($paginator);

        return ApiResponse::success(
            $collection->response()->getData(true),
        );
    }

    public function store(StoreDataTemplateRequest $request)
    {
        $input = DataTemplateInput::fromArray($request->validated());
        $template = $this->createDataTemplate->handle($input);

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
        $template = $this->updateDataTemplate->handle($dataTemplate, $input);

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
