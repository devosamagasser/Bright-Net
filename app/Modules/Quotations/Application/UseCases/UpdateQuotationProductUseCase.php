<?php

namespace App\Modules\Quotations\Application\UseCases;

use App\Modules\Quotations\Application\Concerns\AssertsQuotationEditable;
use App\Modules\Quotations\Application\DTOs\QuotationProductUpdateInput;
use App\Modules\Quotations\Domain\Models\{
    Quotation,
    QuotationProduct,
};
use App\Modules\QuotationLogs\Domain\Services\ActivityService;
use App\Modules\QuotationLogs\Domain\ValueObjects\QuotationActivityType;
use App\Modules\Quotations\Domain\Repositories\QuotationRepositoryInterface;

class UpdateQuotationProductUseCase
{
    use AssertsQuotationEditable;

    public function __construct(
        private readonly QuotationRepositoryInterface $quotations,
        private readonly ActivityService $activityService,
    ) {
    }

    public function handle(QuotationProduct $item, QuotationProductUpdateInput $input, int $supplierId): Quotation
    {
        $quotation = $item->quotation;

        $this->assertEditable($quotation, $supplierId);

        $oldData = $item->toArray();

        $newItem = $this->quotations->updateProduct(
            $item,
            $input->attributes()
        );

        $this->activityService->log(
            model: $item,
            activityType: QuotationActivityType::UPDATE,
            oldObject: $oldData,
            newObject: $newItem->toArray()
        );

        return $this->quotations->refreshTotals($quotation);
    }
}
