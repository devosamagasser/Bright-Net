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

class UndoService
{
    public function make(SpecificationActivityLog $lastLog, Specification $specification): SpecificationItem|SpecificationItemAccessory|null
    {
        $executer = match ($lastLog->activity_type) {
            'create' => app()->make(RemoveSpecificationItemAction::class),
            'update' => app()->make(RevertSpecificationItemUpdateAction::class),
            'delete' => app()->make(ReviveSpecificationItemAction::class),
            default => null,
        };

        $item = $executer?->execute($lastLog);

        if ($item === null) {
            return null;
        }

        $this->updateSpecificationStatusLog($specification, $lastLog);

        return $item;
    }

    public function updateSpecificationStatusLog(Specification $specification, SpecificationActivityLog $lastLog): void
    {
        $specification->update([
            'log_status' => $lastLog->id,
        ]);
    }
}


