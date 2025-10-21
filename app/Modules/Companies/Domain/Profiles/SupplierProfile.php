<?php

namespace App\Modules\Companies\Domain\Profiles;

use App\Models\Supplier;
use App\Modules\Companies\Domain\Models\Company;
use App\Modules\Companies\Domain\ValueObjects\CompanyType;

class SupplierProfile implements CompanyProfileInterface
{
    public function type(): CompanyType
    {
        return CompanyType::SUPPLIER;
    }

    public function relations(): array
    {
        return ['supplier'];
    }

    public function create(Company $company, array $payload): void
    {
        $company->supplier()->updateOrCreate([], $this->filterPayload($payload));
    }

    public function update(Company $company, array $payload): void
    {
        /** @var Supplier|null $supplier */
        $supplier = $company->supplier;

        if ($supplier === null) {
            $company->supplier()->create($this->filterPayload($payload));

            return;
        }

        $supplier->fill($this->filterPayload($payload));
        $supplier->save();
    }

    public function serialize(Company $company): array
    {
        /** @var Supplier|null $supplier */
        $supplier = $company->relationLoaded('supplier')
            ? $company->supplier
            : $company->supplier()->first();

        return [
            'contact_email' => $supplier?->contact_email,
            'contact_phone' => $supplier?->contact_phone,
            'website' => $supplier?->website,
        ];
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    private function filterPayload(array $payload): array
    {
        return [
            'contact_email' => $payload['contact_email'] ?? null,
            'contact_phone' => $payload['contact_phone'] ?? null,
            'website' => $payload['website'] ?? null,
        ];
    }
}
