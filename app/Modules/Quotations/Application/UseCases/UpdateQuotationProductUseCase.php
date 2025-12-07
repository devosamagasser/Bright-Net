<?php

namespace App\Modules\Quotations\Application\UseCases;

use Illuminate\Validation\ValidationException;
use App\Modules\Quotations\Application\DTOs\QuotationProductUpdateInput;
use App\Modules\Quotations\Domain\Models\{
    Quotation,
    QuotationProduct,
};
use App\Modules\Quotations\Domain\Services\ActivityService;
use App\Modules\Quotations\Domain\ValueObjects\QuotationActivityType;
use App\Modules\Quotations\Domain\Repositories\QuotationRepositoryInterface;
use App\Modules\Quotations\Domain\ValueObjects\QuotationStatus;

class UpdateQuotationProductUseCase
{
    public function __construct(
        private readonly QuotationRepositoryInterface $quotations,
        private readonly ActivityService $activityService,
    ) {
    }

    public function handle(QuotationProduct $item, QuotationProductUpdateInput $input, int $supplierId): Quotation
    {
        $quotation = $item->quotation;

        $this->assertEditable($quotation, $supplierId);

        $newItem = $this->quotations->updateProduct($item, $input->attributes());
        $this->activityService->log(
            model: $item,
            activityType: QuotationActivityType::UPDATE,
            oldObject: $item->toArray(),
            newObject: $newItem->toArray()
        );

        return $this->quotations->refreshTotals($quotation);
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
