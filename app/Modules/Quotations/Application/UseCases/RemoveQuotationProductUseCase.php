<?php

namespace App\Modules\Quotations\Application\UseCases;

use Illuminate\Validation\ValidationException;
use App\Modules\Quotations\Domain\Models\{
    Quotation,
    QuotationProduct,
};
use App\Modules\Quotations\Domain\Repositories\QuotationRepositoryInterface;
use App\Modules\Quotations\Domain\ValueObjects\QuotationStatus;
use App\Modules\QuotationLogs\Domain\Services\ActivityService;
use App\Modules\QuotationLogs\Domain\ValueObjects\QuotationActivityType;
use Illuminate\Support\Facades\DB;
class RemoveQuotationProductUseCase
{
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
                activityType: QuotationActivityType::DELETE_PRODUCT,
            );

            return $this->quotations->refreshTotals($quotation);
        });
    }

    private function assertEditable(Quotation $quotation, int $supplierId): void
    {
        if ((int) $quotation->supplier_id !== $supplierId || $quotation->status !== QuotationStatus::DRAFT) {
            throw ValidationException::withMessages([
                'quotation' => trans('apiMessages.forbidden'),
            ]);
        }
    }
}
