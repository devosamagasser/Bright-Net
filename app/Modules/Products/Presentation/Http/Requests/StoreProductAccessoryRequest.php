<?php

namespace App\Modules\Products\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Modules\Products\Domain\ValueObjects\AccessoryType;

class StoreProductAccessoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'accessory_id' => ['required', 'integer', 'exists:products,id'],
            'accessory_type' => ['required', Rule::in(AccessoryType::values())],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ];
    }

    /**
     * @return array{accessory_id:int, accessory_type:string, quantity?:int|null}
     */
    public function payload(): array
    {
        return $this->validated();
    }
}
