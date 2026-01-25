<?php

namespace App\Modules\SpecificationLogs\Presentation\Http\Controllers;

use Illuminate\Validation\ValidationException;
use App\Modules\Shared\Support\Helper\ApiResponse;
use App\Modules\Specifications\Domain\Models\Specification;
use App\Modules\Specifications\Presentation\Resources\SpecificationResource;
use App\Modules\SpecificationLogs\Application\UseCases\{
    RedoActionSpecificationUseCase,
    UndoActionSpecificationUseCase
};

class SpecificationLogsController
{
    public function __construct(
        private readonly UndoActionSpecificationUseCase $undoAction,
        private readonly RedoActionSpecificationUseCase $redoAction,
    ) {
    }

    public function undo(Specification $specification)
    {
        $spec = $this->undoAction->handle(
            $this->companyId(),
            $specification
        );

        return ApiResponse::updated(
            SpecificationResource::make($spec->load('items.accessories'))->resolve()
        );
    }

    public function redo(Specification $specification)
    {
        $spec = $this->redoAction->handle(
            $this->companyId(),
            $specification
        );

        return ApiResponse::updated(
            SpecificationResource::make($spec->load('items.accessories'))->resolve()
        );
    }

    private function companyId(): int
    {
        $user = auth()->user();

        if ($user !== null && method_exists($user, 'company')) {
            $user->loadMissing('company');
        }

        if ($user === null || ! method_exists($user, 'company') || $user->company === null) {
            throw ValidationException::withMessages([
                'company' => trans('apiMessages.forbidden'),
            ]);
        }

        return (int) $user->company->getKey();
    }
}


