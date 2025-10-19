<?php

namespace App\Modules\Geography\Presentation\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Modules\Shared\Support\Helper\ApiResponse;
use App\Modules\Geography\Application\DTOs\RegionInput;
use App\Modules\Geography\Presentation\Resources\RegionResource;
use App\Modules\Geography\Presentation\Http\Requests\{StoreRegionRequest, UpdateRegionRequest};
use App\Modules\Geography\Application\UseCases\{CreateRegionUseCase, DeleteRegionUseCase, ListRegionsUseCase, ShowRegionUseCase, UpdateRegionUseCase};

class RegionController
{
    public function __construct(
        private readonly ListRegionsUseCase $listRegions,
        private readonly ShowRegionUseCase $showRegion,
        private readonly CreateRegionUseCase $createRegion,
        private readonly UpdateRegionUseCase $updateRegion,
        private readonly DeleteRegionUseCase $deleteRegion,
    ) {
    }

    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 15);
        $perPage = max(1, min(100, $perPage));

        $paginator = $this->listRegions->handle($perPage);

        return ApiResponse::success(RegionResource::collection($paginator)->resource);
    }

    public function store(StoreRegionRequest $request)
    {
        $input = RegionInput::fromArray($request->validated());
        $region = $this->createRegion->handle($input);

        return ApiResponse::success(
            RegionResource::make($region),
            __('apiMessages.created'),
            Response::HTTP_CREATED
        );
    }

    public function show(int $region)
    {
        $dto = $this->showRegion->handle($region);

        return ApiResponse::success(RegionResource::make($dto));
    }

    public function update(UpdateRegionRequest $request, int $region)
    {
        $input = RegionInput::fromArray($request->validated());
        $dto = $this->updateRegion->handle($region, $input);

        return ApiResponse::success(
            RegionResource::make($dto),
            __('apiMessages.updated')
        );
    }

    public function destroy(int $region)
    {
        $this->deleteRegion->handle($region);

        return ApiResponse::deleted();
    }
}
