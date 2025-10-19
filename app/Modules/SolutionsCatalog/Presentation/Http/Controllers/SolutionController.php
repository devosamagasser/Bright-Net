<?php

namespace App\Modules\SolutionsCatalog\Presentation\Http\Controllers;

use Illuminate\Http\{Request, Response};
use App\Modules\Shared\Support\Helper\ApiResponse;
use App\Modules\SolutionsCatalog\Application\DTOs\SolutionInput;
use App\Modules\SolutionsCatalog\Presentation\Resources\SolutionResource;
use App\Modules\SolutionsCatalog\Presentation\Http\Requests\{StoreSolutionRequest, UpdateSolutionRequest};
use App\Modules\SolutionsCatalog\Application\UseCases\{CreateSolutionUseCase, DeleteSolutionUseCase, ListSolutionsUseCase, ShowSolutionUseCase, UpdateSolutionUseCase};

class SolutionController
{
    public function __construct(
        private readonly ListSolutionsUseCase $listSolutions,
        private readonly ShowSolutionUseCase $showSolution,
        private readonly CreateSolutionUseCase $createSolution,
        private readonly UpdateSolutionUseCase $updateSolution,
        private readonly DeleteSolutionUseCase $deleteSolution,
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 15);
        $perPage = max(1, min(100, $perPage));

        $paginator = $this->listSolutions->handle($perPage);

        return ApiResponse::success(SolutionResource::collection($paginator)->resource);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSolutionRequest $request)
    {
        $input = SolutionInput::fromArray($request->validated());
        $solution = $this->createSolution->handle($input);

        return Apiresponse::success(
        SolutionResource::make( $solution),
        __('apiMessages.created'),
        Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $solution)
    {
        $dto = $this->showSolution->handle($solution);

        return ApiResponse::success(SolutionResource::make($dto));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSolutionRequest $request, int $solution)
    {
        $input = SolutionInput::fromArray($request->validated());
        $dto = $this->updateSolution->handle($solution, $input);

        return ApiResponse::success(
            SolutionResource::make($dto),
            __('apiMessages.updated'),
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $solution)
    {
        $this->deleteSolution->handle($solution);

        return ApiResponse::deleted();
    }
}

