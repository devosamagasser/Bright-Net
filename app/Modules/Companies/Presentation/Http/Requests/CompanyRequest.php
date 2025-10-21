<?php

namespace App\Modules\Companies\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class CompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
}
