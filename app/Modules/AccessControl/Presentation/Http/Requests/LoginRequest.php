<?php

namespace App\Modules\AccessControl\Presentation\Http\Requests;

use App\Modules\AccessControl\Application\DTOs\LoginData;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'device_name' => ['sometimes', 'string', 'max:255'],
        ];
    }

    public function toLoginData(string $defaultDeviceName): LoginData
    {
        $validated = $this->validated();

        return new LoginData(
            $validated['email'],
            $validated['password'],
            $validated['device_name'] ?? $defaultDeviceName,
        );
    }
}
