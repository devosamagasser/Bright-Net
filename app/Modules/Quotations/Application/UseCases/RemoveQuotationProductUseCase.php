<?php

namespace App\Modules\Quotations\Application\UseCases;

use Illuminate\Support\Facades\DB;
use App\Modules\Quotations\Application\Concerns\AssertsQuotationEditable;
use App\Modules\Quotations\Domain\Models\{
    Quotation,
    QuotationProduct,
};
use App\Modules\Quotations\Domain\Repositories\QuotationRepositoryInterface;
use App\Modules\QuotationLogs\Domain\Services\ActivityService;
use App\Modules\QuotationLogs\Domain\ValueObjects\QuotationActivityType;

class RemoveQuotationProductUseCase
{
    use AssertsQuotationEditable;

    public function __construct(
        private readonly QuotationRepositoryInterface $quotations,
        private readonly ActivityService $activityService,
    ) {
    }

    public function handle(QuotationProduct $item, int $supplierId): Quotation
    {
        return DB::transaction(function () use ($item, $supplierId) {
            $quotation = $item->quotation;

            $this->assertEditable($quotation, $supplierId);

            $this->quotations->deleteProduct($item);

            $this->activityService->log(
                model: $item,
                activityType: QuotationActivityType::DELETE,
            );

            return $this->quotations->refreshTotals($quotation);
        });
    }
}
