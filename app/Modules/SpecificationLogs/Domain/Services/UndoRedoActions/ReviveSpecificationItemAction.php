<?php

namespace App\Modules\SpecificationLogs\Domain\Services\UndoRedoActions;

use App\Modules\Specifications\Domain\Models\{
    SpecificationItem,
    SpecificationItemAccessory
};
use App\Modules\SpecificationLogs\Domain\Models\SpecificationActivityLog;

class ReviveSpecificationItemAction
{
    public function execute(SpecificationActivityLog $log): SpecificationItem|SpecificationItemAccessory
    {
        $model = $log->loggable;

        if ($model) {
            $model->restore();
        }

        return $model;
    }
}


