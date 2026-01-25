<?php

namespace App\Modules\SpecificationLogs\Domain\Services;

use App\Modules\Specifications\Domain\Models\Specification;
use App\Modules\Specifications\Domain\Models\{
    SpecificationItem,
    SpecificationItemAccessory
};
use App\Modules\SpecificationLogs\Domain\Models\SpecificationActivityLog;
use App\Modules\SpecificationLogs\Domain\Services\UndoRedoActions\{
    RemoveSpecificationItemAction,
    ReviveSpecificationItemAction,
    RevertSpecificationItemUpdateAction
};

class RedoService
{
    public function make(SpecificationActivityLog $currentLog, ?SpecificationActivityLog $lastLog, Specification $specification): SpecificationItem|SpecificationItemAccessory|null
    {
        $executer = match ($currentLog->activity_type) {
            'create' => app()->make(ReviveSpecificationItemAction::class),
            'update' => app()->make(RevertSpecificationItemUpdateAction::class),
            'delete' => app()->make(RemoveSpecificationItemAction::class),
            default => null,
        };

        $item = $executer?->execute($currentLog);
        if ($item === null) {
            return null;
        }

        $this->updateSpecificationStatusLog($specification, $lastLog);

        return $item;
    }

    public function updateSpecificationStatusLog(Specification $specification, ?SpecificationActivityLog $lastLog = null): void
    {
        $specification->update([
            'log_status' => $lastLog?->id ?? 0,
        ]);
    }
}


