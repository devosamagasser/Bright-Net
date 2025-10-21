<?php

namespace App\Modules\Companies\Application\DTOs;

use Illuminate\Support\Arr;
use Illuminate\Http\UploadedFile;
use App\Modules\Companies\Domain\ValueObjects\CompanyType;

class CompanyInput
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    private function __construct(
        public readonly array $attributes,
        public readonly ?UploadedFile $logo,
    ) {
    }

    /**
     * Build the DTO from validated request data.
     *
     * @param  array<string, mixed>  $payload
     */
    public static function fromArray(array $payload): self
    {
        $logo = Arr::pull($payload, 'logo');

        if (array_key_exists('type', $payload)) {
            $type = $payload['type'];
            $payload['type'] = $type instanceof CompanyType
                ? $type->value
                : CompanyType::from((string) $type)->value;
        }

        return new self(
            attributes: $payload,
            logo: $logo instanceof UploadedFile ? $logo : null,
        );
    }
}
