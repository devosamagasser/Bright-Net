<?php

namespace App\Modules\DataSheets\Presentation\Http\Controllers;

use Illuminate\Http\Response;
use App\Modules\Shared\Support\Helper\ApiResponse;
use App\Modules\DataSheets\Application\DTOs\DataTemplateInput;
use App\Modules\DataSheets\Application\UseCases\CreateDataTemplateUseCase;
use App\Modules\DataSheets\Presentation\Resources\DataTemplateResource;
use App\Modules\DataSheets\Presentation\Http\Requests\StoreDataTemplateRequest;

class DataTemplateController
{
    public function __construct(
        private readonly CreateDataTemplateUseCase $createDataTemplate,
    ) {
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
}
