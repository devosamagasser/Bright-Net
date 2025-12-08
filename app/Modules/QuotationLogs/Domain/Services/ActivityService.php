<?php

namespace App\Modules\QuotationLogs\Domain\Services;

use Illuminate\Database\Eloquent\Model;
use App\Modules\QuotationLogs\Domain\Models\QuotationActivityLog;
use App\Modules\QuotationLogs\Domain\ValueObjects\QuotationActivityType;
use App\Modules\Quotations\Domain\Models\QuotationProduct;
use App\Modules\Quotations\Domain\Models\QuotationProductAccessory;

class ActivityService
{
    public function log(
        QuotationProduct|QuotationProductAccessory $model,
        QuotationActivityType $activityType,
        array|string|null $oldObject = null,
        array|string|null $newObject = null,
    ): void {
        QuotationActivityLog::create([
            'loggable_type' => get_class($model),
            'loggable_id'  => $model->id,
            'activity_type' => $activityType,
            'old_object'   => $oldObject,
            'new_object'   => $newObject,
            'quotation_id' => $model->quotation_id,
        ]);
    }
}

