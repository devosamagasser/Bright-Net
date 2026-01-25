<?php

namespace App\Modules\SolutionsCatalog\Presentation\Http\Controllers;

use App\Modules\Shared\Support\Helper\ApiResponse;
use App\Modules\SolutionsCatalog\Application\UseCases\ListSolutionBrandsUseCase;
use App\Modules\SolutionsCatalog\Application\UseCases\ShowSolutionBrandUseCase;
use App\Modules\SolutionsCatalog\Presentation\Resources\SolutionBrandResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SolutionBrandController
{
    public function __construct(
        private readonly ListSolutionBrandsUseCase $listSolutionBrands,
        private readonly ShowSolutionBrandUseCase $showSolutionBrand,
    ) {
    }

    public function index(int $solution)
    {
        try {
            $brands = $this->listSolutionBrands->handle($solution);
        } catch (ModelNotFoundException $exception) {
            return ApiResponse::notFound();
        }

        return ApiResponse::success(
            SolutionBrandResource::collection($brands)->resolve()
        );
    }

    public function show(int $solution, int $brand)
    {
        try {
            $brandData = $this->showSolutionBrand->handle($solution, $brand);
        } catch (ModelNotFoundException $exception) {
            return ApiResponse::notFound();
        }

        return ApiResponse::success(
            SolutionBrandResource::make($brandData)->resolve()
        );
    }
}
