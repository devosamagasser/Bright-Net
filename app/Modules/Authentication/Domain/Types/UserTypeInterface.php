<?php

namespace App\Modules\Authentication\Domain\Types;

use App\Modules\Companies\Domain\Models\Company;
use Illuminate\Database\Eloquent\Model;

interface UserTypeInterface
{
    public function type();

    /**
     * Return the relationships required to hydrate this profile.
     *
     * @return array<int, string>
     */
    public function relations(): array;

    /**
     * Transform the profile specific data for presentation.
     *
     * @return array<string, mixed>
     */
    public function serialize(Model $company): array;


    public function generateToken($user);

    public function checkCredentials($credentials);
}
