<?php

namespace App\Modules\Quotations\Application\UseCases;

use Illuminate\Validation\ValidationException;
use App\Modules\Quotations\Application\DTOs\QuotationAccessoryUpdateInput;
use App\Modules\Quotations\Domain\Models\{
    Quotation,
    QuotationProductAccessory,
};
use App\Modules\Quotations\Domain\Repositories\QuotationRepositoryInterface;
use App\Modules\Quotations\Domain\ValueObjects\QuotationStatus;
use App\Modules\QuotationLogs\Domain\Services\ActivityService;
use App\Modules\QuotationLogs\Domain\ValueObjects\QuotationActivityType;

class UpdateQuotationAccessoryUseCase
{
    public function __construct(
        private readonly QuotationRepositoryInterface $quotations,
        private readonly ActivityService $activityService,
    ) {
    }

    public function handle(QuotationProductAccessory $accessory, QuotationAccessoryUpdateInput $input, int $supplierId): Quotation
    {
        $quotation = $accessory->quotation;

        $this->assertEditable($quotation, $supplierId);

        $this->quotations->updateAccessory($accessory, $input->attributes());
            $this->activityService->log(
            model: $accessory,
            activityType: QuotationActivityType::UPDATE_ACCESSORY,
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
