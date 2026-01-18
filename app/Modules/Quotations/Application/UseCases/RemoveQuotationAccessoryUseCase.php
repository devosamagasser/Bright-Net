<?php

namespace App\Modules\Quotations\Application\UseCases;

use App\Modules\Quotations\Application\Concerns\AssertsQuotationEditable;
use App\Modules\Quotations\Domain\Models\{
    Quotation,
    QuotationProductAccessory,
};
use App\Modules\Quotations\Domain\Repositories\QuotationRepositoryInterface;
use App\Modules\QuotationLogs\Domain\Services\ActivityService;
use App\Modules\QuotationLogs\Domain\ValueObjects\QuotationActivityType;

class RemoveQuotationAccessoryUseCase
{
    use AssertsQuotationEditable;

    public function __construct(
        private readonly QuotationRepositoryInterface $quotations,
        private readonly ActivityService $activityService,
    ) {
    }

    public function handle(QuotationProductAccessory $accessory, int $supplierId): Quotation
    {
        $quotation = $accessory->quotation;

        $this->assertEditable($quotation, $supplierId);

        $this->quotations->deleteAccessory($accessory);

        $this->activityService->log(
            model: $accessory,
            activityType: QuotationActivityType::DELETE,
        );

        return $this->quotations->refreshTotals($quotation);
    }
}
