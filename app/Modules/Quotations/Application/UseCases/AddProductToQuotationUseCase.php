<?php

namespace App\Modules\Quotations\Application\UseCases;

use App\Modules\Products\Domain\Services\ProductAccessoryService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use App\Modules\Quotations\Application\Concerns\AssertsQuotationEditable;
use App\Modules\Quotations\Domain\Models\Quotation;
use App\Modules\Quotations\Application\DTOs\QuotationProductInput;
use App\Modules\Products\Domain\Repositories\ProductRepositoryInterface;
use App\Modules\Quotations\Domain\Repositories\QuotationRepositoryInterface;
use App\Modules\QuotationLogs\Domain\Services\ActivityService;
use App\Modules\QuotationLogs\Domain\ValueObjects\QuotationActivityType;

class AddProductToQuotationUseCase
{
    use AssertsQuotationEditable;

    public function __construct(
        private readonly QuotationRepositoryInterface $quotations,
        private readonly ProductRepositoryInterface $products,
        private readonly ActivityService $activityService,
        private readonly ProductAccessoryService $accessoryService,
    ) {
    }

    public function handle(Model $user, QuotationProductInput $input): Quotation
    {
        $quotation = $this->quotations->getOrCreateDraft($user);

        $this->assertEditable($quotation, $user->company->supplier->id);

        $product = $this->products->find($input->productId, relations:[
            'family',
            'brand',
            'prices',
            'translations',
            'accessories.accessory.prices',
            'accessories.accessory.family',
            'accessories.accessory.brand',
            'accessories.accessory.supplier',
            'accessories.accessory.translations',
        ]);

        if ($product->supplier_id === null || (int) $product->supplier_id !== $user->company->supplier->id) {
            throw ValidationException::withMessages([
                'product_id' => trans('apiMessages.forbidden'),
            ]);
        }

        $accessories = $this->accessoryService->buildQutOrSpecAccessoriesPayload($product, $input->accessories());

        $quotationProduct = $this->quotations->addProduct(
            $quotation,
            $product,
            $input->attributes(),
            $accessories
        );

        $this->activityService->log(
            model: $quotationProduct,
            activityType: QuotationActivityType::CREATE,
        );

        return $this->quotations->refreshTotals($quotation);
    }
}
