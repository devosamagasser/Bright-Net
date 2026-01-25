<?php

namespace App\Modules\SpecificationLogs\Application\UseCases;

use Illuminate\Validation\ValidationException;
use App\Modules\Specifications\Domain\Models\Specification;
use App\Modules\SpecificationLogs\Domain\Services\UndoService;
use App\Modules\Specifications\Domain\Repositories\SpecificationRepositoryInterface;

class UndoActionSpecificationUseCase
{
    public function __construct(
        private readonly UndoService $undoService,
        private readonly SpecificationRepositoryInterface $specifications,
    ) {
    }

    public function handle(int $companyId, Specification $specification): Specification
    {
        if ($specification->company_id !== $companyId) {
            throw ValidationException::withMessages([
                'company' => trans('apiMessages.forbidden'),
            ]);
        }

        $currentOperation = $specification->log_status;

        $lastLog = $specification->logs()
            ->when($currentOperation, fn ($query) =>
                $query->where('id', '<', $currentOperation)
            )
            ->latest()
            ->first();

        if (! $lastLog) {
            throw ValidationException::withMessages([
                'specification' => trans('apiMessages.no_undo_available'),
            ]);
        }

        $this->undoService->make($lastLog, $specification);

        return $this->specifications->loadRelations($specification);
    }
}


