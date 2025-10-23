<?php

namespace App\Modules\AccessControl\Application\DTOs;

/**
 * @phpstan-type AbilityList array<int, string>
 */
final class TokenResult
{
    /**
     * @param  AbilityList  $abilities
     */
    public function __construct(
        public readonly string $plainTextToken,
        public readonly array $abilities,
    ) {
    }
}
