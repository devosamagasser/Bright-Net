<?php

namespace App\Modules\Quotations\Domain\Services;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Quotations\Domain\Models\QuotationProduct;
use App\Modules\Quotations\Domain\Models\QuotationActivityLog;
use App\Modules\Quotations\Domain\Models\QuotationProductAccessory;

class ActivityService
{
    public function record(QuotationProduct|QuotationProductAccessory $model, string $activityType, string $oldValue = null, string $newValue = null): void
    {
        QuotationActivityLog::create([
            'loggable_type' => get_class($model),
            'loggable_id'=> $model->id,
            'activity_type'=> $activityType,
            'old_value'=> $oldValue,
            'new_value'=> $newValue,
        ]);
    }
}
