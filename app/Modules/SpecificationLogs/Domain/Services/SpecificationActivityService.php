<?php

namespace App\Modules\SpecificationLogs\Domain\Services;

use App\Modules\SpecificationLogs\Domain\Models\SpecificationActivityLog;
use App\Modules\Specifications\Domain\Models\{
    Specification,
    SpecificationItem,
    SpecificationItemAccessory
};

class SpecificationActivityService
{
    public function log(
        SpecificationItem|SpecificationItemAccessory $model,
        string $activityType,
        array|string|null $oldObject = null,
        array|string|null $newObject = null,
    ): void {
        $spec = $model->specification;

        $log = SpecificationActivityLog::create([
            'loggable_type' => get_class($model),
            'loggable_id'  => $model->id,
            'activity_type' => $activityType,
            'old_object'   => $oldObject,
            'new_object'   => $newObject,
            'specification_id' => $spec?->getKey(),
        ]);

        if ($spec !== null) {
            $spec->update(['log_status' => $log->id]);
        }
    }
}


