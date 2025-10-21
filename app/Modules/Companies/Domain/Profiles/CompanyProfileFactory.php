<?php

namespace App\Modules\Companies\Domain\Profiles;

use App\Modules\Companies\Domain\ValueObjects\CompanyType;
use InvalidArgumentException;

class CompanyProfileFactory
{
    /**
     * @param  iterable<int, CompanyProfileInterface>  $profiles
     */
    public function __construct(
        private readonly iterable $profiles,
    ) {
    }

    public function make(CompanyType $type): CompanyProfileInterface
    {
        foreach ($this->profiles as $profile) {
            if ($profile->type() === $type) {
                return $profile;
            }
        }

        throw new InvalidArgumentException("No company profile registered for type {$type->value}.");
    }
}
