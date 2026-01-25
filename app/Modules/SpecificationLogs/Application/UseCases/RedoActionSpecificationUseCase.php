<?php

namespace App\Modules\SpecificationLogs\Application\UseCases;

use Illuminate\Validation\ValidationException;
use App\Modules\Specifications\Domain\Models\Specification;
use App\Modules\SpecificationLogs\Domain\Services\RedoService;
use App\Modules\Specifications\Domain\Repositories\SpecificationRepositoryInterface;
use App\Modules\SpecificationLogs\Domain\Models\SpecificationActivityLog;

class RedoActionSpecificationUseCase
{
    public function __construct(
        private readonly RedoService $redoService,
        private readonly SpecificationRepositoryInterface $specifications,
    ) {
    }

    public function handle(int $companyId, Specification $specification): Specification
    {
        if ($specification->company_id !== $companyId) {
            throw new ValidationException(null, response()->json([
                'errors' => ['company' => [trans('apiMessages.forbidden')]],
            ], 422));
        }

        $currentOperation = $specification->log_status;
        if (! $currentOperation) {
            throw new ValidationException(null, response()->json([
                'errors' => ['specification' => [trans('apiMessages.no_redo_available')]],
            ], 422));
        }

        $logs = $specification->logs()
            ->with('loggable')
            ->where('id', '>=', $currentOperation)
            ->limit(2)
            ->get();

        /** @var SpecificationActivityLog|null $current */
        $current = $logs->first();
        /** @var SpecificationActivityLog|null $last */
        $last = $logs->get(1);

        if (! $current) {
            throw new ValidationException(null, response()->json([
                'errors' => ['specification' => [trans('apiMessages.no_redo_available')]],
            ], 422));
        }

        $this->redoService->make($current, $last, $specification);

        return $this->specifications->loadRelations($specification);
    }
}


