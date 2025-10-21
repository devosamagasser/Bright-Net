<?php

namespace App\Modules\Companies\Application\DTOs;

use Illuminate\Http\UploadedFile;
use App\Modules\Companies\Domain\ValueObjects\CompanyType;

class CompanyInput
{
    /**
     * @param  array<string, mixed>  $attributes
     * @param  array<string, mixed>  $profilePayload
     */
    private function __construct(
        public readonly CompanyType $type,
        public readonly array $attributes,
        public readonly array $profilePayload,
        public readonly ?UploadedFile $logo,
    ) {
    }

    /**
     * Build the DTO for the supplied type.
     *
     * @param  array<string, mixed>  $attributes
     * @param  array<string, mixed>  $profilePayload
     */
    public static function forType(CompanyType $type, array $attributes, array $profilePayload, ?UploadedFile $logo = null): self
    {
        return new self(
            type: $type,
            attributes: $attributes,
            profilePayload: $profilePayload,
            logo: $logo,
        );
    }
}
