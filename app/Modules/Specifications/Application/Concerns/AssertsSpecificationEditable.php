<?php

namespace App\Modules\Specifications\Application\Concerns;

use Illuminate\Validation\ValidationException;
use App\Modules\Specifications\Domain\Models\Specification;
use App\Modules\Quotations\Domain\ValueObjects\QuotationStatus;

trait AssertsSpecificationEditable
{
    /**
     * Assert that the specification is editable by the given company.
     *
     * @throws ValidationException
     */
    protected function assertEditable(Specification $specification, int $companyId): void
    {
        if ((int) $specification->company_id !== $companyId || $specification->status !== QuotationStatus::DRAFT) {
            throw ValidationException::withMessages([
                'specification' => trans('apiMessages.forbidden'),
            ]);
        }
    }
}


