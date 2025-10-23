<?php

namespace App\Modules\Authentication\Domain\Types;

use InvalidArgumentException;
use App\Modules\Authentication\Domain\ValueObjects\UserType;
use App\Modules\Authentication\Domain\Types\UserTypeInterface;

class UserTypeFactory
{
    /**
     * @param  iterable<int, UserTypeInterface>  $profiles
     */
    public function __construct(
        private readonly iterable $profiles,
    ) {
    }

    public function make(UserType $type)
    {
        foreach ($this->profiles as $profile) {
            if ($profile->type() === $type) {
                return $profile;
            }
        }

        throw new InvalidArgumentException("No company profile registered for type {$type->value}.");
    }
}
