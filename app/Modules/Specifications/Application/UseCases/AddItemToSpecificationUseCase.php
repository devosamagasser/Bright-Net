<?php

namespace App\Modules\Specifications\Application\UseCases;

use App\Modules\Products\Domain\Services\ProductAccessoryService;
use App\Modules\Specifications\Application\Concerns\AssertsSpecificationEditable;
use App\Modules\Specifications\Application\DTOs\SpecificationItemInput;
use App\Modules\Specifications\Domain\Models\Specification;
use App\Modules\Specifications\Domain\Repositories\SpecificationRepositoryInterface;
use App\Modules\Products\Domain\Repositories\ProductRepositoryInterface;
use App\Modules\SpecificationLogs\Domain\Services\SpecificationActivityService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AddItemToSpecificationUseCase
{
    use AssertsSpecificationEditable;

    public function __construct(
        private readonly SpecificationRepositoryInterface $specifications,
        private readonly ProductRepositoryInterface $products,
        private readonly SpecificationActivityService $activityService,
        private readonly ProductAccessoryService $accessoryService,
    ) {
    }

    public function handle(Model $user, SpecificationItemInput $input): Specification
    {

        $spec = $this->specifications->getOrCreateDraft($user);

        $this->assertEditable($spec, $user->company->id);

        $product = $this->products->find($input->productId, relations: [
            'family',
            'brand',
            'translations',
        ]);
        $accessories = $this->accessoryService->buildQuotationAccessoriesPayload($product, $input->accessories());

        return DB::transaction(function () use ($spec, $accessories, $user, $product, $input) {
            $item = $this->specifications->addItem(
                $spec,
                $product,
                $input->attributes(),
                $accessories,
            );

            $this->activityService->log(
                model: $item,
                activityType: 'create',
            );

            return $this->specifications->loadRelations($spec);
        });
    }
}


