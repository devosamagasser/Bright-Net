<?php

namespace App\Modules\SpecificationLogs\Domain\Services\UndoRedoActions;

use App\Modules\Specifications\Domain\Models\{
    SpecificationItem,
    SpecificationItemAccessory
};
use App\Modules\SpecificationLogs\Domain\Models\SpecificationActivityLog;

class RevertSpecificationItemUpdateAction
{
    public function execute(SpecificationActivityLog $log): SpecificationItem|SpecificationItemAccessory
    {
        $oldData = $log->old_object;

        $model = $log->loggable;
        $model->update($oldData);

        $log->update([
            'old_object' => $log->new_object,
            'new_object' => $oldData,
        ]);

        return $model;
    }
}


