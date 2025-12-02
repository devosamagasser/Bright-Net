<?php

namespace App\Modules\Brands\Presentation\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Modules\Brands\Application\DTOs\BrandInput;
use App\Modules\Shared\Support\Helper\ApiResponse;
use App\Modules\Brands\Presentation\Resources\BrandResource;
use App\Modules\Brands\Presentation\Http\Requests\{StoreBrandRequest, UpdateBrandRequest};
use App\Modules\Brands\Application\UseCases\{CreateBrandUseCase, DeleteBrandUseCase, ListBrandsUseCase, ShowBrandUseCase, UpdateBrandUseCase};

class BrandController
{
    public function __construct(
        private readonly ListBrandsUseCase $listBrands,
        private readonly ShowBrandUseCase $showBrand,
        private readonly CreateBrandUseCase $createBrand,
        private readonly UpdateBrandUseCase $updateBrand,
        private readonly DeleteBrandUseCase $deleteBrand,
    ) {
    }

    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 15);
        $perPage = max(1, min(100, $perPage));
        $filter = $request->all(['region', 'name', 'solution']);
        $paginator = $this->listBrands->handle($perPage, $filter);

        return ApiResponse::success(
            BrandResource::collection($paginator)->resource
        );
    }

    public function store(StoreBrandRequest $request)
    {
        $input = BrandInput::fromArray($request->validated());
        $brand = $this->createBrand->handle($input);

        return ApiResponse::success(
            BrandResource::make($brand),
            __('apiMessages.created'),
            Response::HTTP_CREATED
        );
    }

    public function show(int $brand)
    {
        $brandData = $this->showBrand->handle($brand);

        return ApiResponse::success(BrandResource::make($brandData));
    }

    public function update(UpdateBrandRequest $request, int $brand)
    {
        $input = BrandInput::fromArray($request->validated());
        $brandData = $this->updateBrand->handle($brand, $input);

        return ApiResponse::success(
            BrandResource::make($brandData),
            __('apiMessages.updated')
        );
    }

    public function updateAvatar(Request $request, int $brand)
    {
        $request->validate([
            'avatar' => ['required', 'image', 'max:2048'], // max 2MB
        ]);
        // return ApiResponse::success(
        //     BrandResource::make(),
        //     __('apiMessages.updated')
        // );
    }

    public function destroy(int $brand)
    {
        $this->deleteBrand->handle($brand);

        return ApiResponse::deleted();
    }
}
