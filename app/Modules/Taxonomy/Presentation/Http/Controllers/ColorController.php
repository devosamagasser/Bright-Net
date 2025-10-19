<?php

namespace App\Modules\Taxonomy\Presentation\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Modules\Shared\Support\Helper\ApiResponse;
use App\Modules\Taxonomy\Application\DTOs\ColorInput;
use App\Modules\Taxonomy\Presentation\Resources\ColorResource;
use App\Modules\Taxonomy\Presentation\Http\Requests\{StoreColorRequest, UpdateColorRequest};
use App\Modules\Taxonomy\Application\UseCases\{CreateColorUseCase, DeleteColorUseCase, ListColorsUseCase, ShowColorUseCase, UpdateColorUseCase};

class ColorController
{
    public function __construct(
        private readonly ListColorsUseCase $listColors,
        private readonly ShowColorUseCase $showColor,
        private readonly CreateColorUseCase $createColor,
        private readonly UpdateColorUseCase $updateColor,
        private readonly DeleteColorUseCase $deleteColor,
    ) {
    }

    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 15);
        $perPage = max(1, min(100, $perPage));

        $paginator = $this->listColors->handle($perPage);

        return ApiResponse::success(ColorResource::collection($paginator)->resource);
    }

    public function store(StoreColorRequest $request)
    {
        $input = ColorInput::fromArray($request->validated());
        $color = $this->createColor->handle($input);

        return ApiResponse::success(
            ColorResource::make($color),
            __('apiMessages.created'),
            Response::HTTP_CREATED
        );
    }

    public function show(int $color)
    {
        $dto = $this->showColor->handle($color);

        return ApiResponse::success(ColorResource::make($dto));
    }

    public function update(UpdateColorRequest $request, int $color)
    {
        $input = ColorInput::fromArray($request->validated());
        $dto = $this->updateColor->handle($color, $input);

        return ApiResponse::success(
            ColorResource::make($dto),
            __('apiMessages.updated')
        );
    }

    public function destroy(int $color)
    {
        $this->deleteColor->handle($color);

        return ApiResponse::deleted();
    }
}
