<?php

namespace App\Modules\Favourites\Presentation\Http\Requests;

use App\Modules\Favourites\Application\DTOs\CollectionInput;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCollectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
        ];
    }

    public function toCollectionInput(): CollectionInput
    {
        return CollectionInput::fromArray($this->validated());
    }
}

