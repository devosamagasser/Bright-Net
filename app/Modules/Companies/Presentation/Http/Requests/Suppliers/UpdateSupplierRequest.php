<?php

namespace App\Modules\Companies\Presentation\Http\Requests\Suppliers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use App\Modules\Departments\Domain\Models\Department;
use App\Modules\Companies\Application\DTOs\CompanyInput;
use App\Modules\Companies\Domain\Models\Company;
use App\Modules\Companies\Domain\ValueObjects\CompanyType;

class UpdateSupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'max:5120'],
            'contact_email' => ['required', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'website' => ['nullable', 'url', 'max:255'],
            'solutions' => ['required', 'array', 'min:1'],
            'solutions.*.solution_id' => ['required', 'integer', 'distinct', Rule::exists('solutions', 'id')],
            'solutions.*.departments' => ['nullable', 'array'],
            'solutions.*.departments.*' => ['integer', 'distinct', Rule::exists('departments', 'id')],
            'solutions.*.brands' => ['nullable', 'array'],
            'solutions.*.brands.*' => ['integer', 'distinct', Rule::exists('brands', 'id')],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $solutions = $this->input('solutions', []);

            if (! is_array($solutions)) {
                return;
            }

            foreach ($solutions as $index => $solution) {
                if (! is_array($solution)) {
                    continue;
                }

                $solutionId = $solution['solution_id'] ?? null;

                if ($solutionId === null) {
                    continue;
                }

                $this->validateDepartments($validator, $index, (int) $solutionId, $solution['departments'] ?? []);
                $this->validateBrands($validator, $index, (int) $solutionId, $solution['brands'] ?? []);
            }
        });
    }

    public function toCompanyInput(Company $company): CompanyInput
    {
        if ($company->type !== CompanyType::SUPPLIER) {
            abort(404);
        }

        $validated = $this->validated();

        return CompanyInput::forType(
            CompanyType::SUPPLIER,
            attributes: Arr::only($validated, ['name', 'description']),
            profilePayload: $this->profilePayload($validated),
            logo: $this->file('logo'),
        );
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function profilePayload(array $validated): array
    {
        return Arr::only($validated, ['contact_email', 'contact_phone', 'website']) + [
            'solutions' => $this->normalizeSolutions($validated['solutions'] ?? []),
        ];
    }

    /**
     * @param  array<int, mixed>  $solutions
     * @return array<int, array<string, mixed>>
     */
    private function normalizeSolutions(array $solutions): array
    {
        $normalized = [];

        foreach ($solutions as $solution) {
            if (! is_array($solution) || ! isset($solution['solution_id'])) {
                continue;
            }

            $solutionId = (int) $solution['solution_id'];

            if ($solutionId <= 0) {
                continue;
            }

            $normalized[$solutionId] = [
                'solution_id' => $solutionId,
                'departments' => $this->uniqueIds($solution['departments'] ?? []),
                'brands' => $this->uniqueIds($solution['brands'] ?? []),
            ];
        }

        return array_values($normalized);
    }

    /**
     * @param  array<int, mixed>  $values
     * @return array<int, int>
     */
    private function uniqueIds(array $values): array
    {
        return array_values(array_unique(array_map(static fn ($value) => (int) $value, $values)));
    }

    private function validateDepartments(Validator $validator, int $index, int $solutionId, mixed $departments): void
    {
        if (! is_array($departments) || $departments === []) {
            return;
        }

        $departmentIds = $this->uniqueIds($departments);

        $count = Department::query()
            ->where('solution_id', $solutionId)
            ->whereIn('id', $departmentIds)
            ->distinct()
            ->count('id');

        if ($count !== count($departmentIds)) {
            $validator->errors()->add(
                "solutions.$index.departments",
                'One or more selected departments do not belong to the specified solution.'
            );
        }
    }

    private function validateBrands(Validator $validator, int $index, int $solutionId, mixed $brands): void
    {
        if (! is_array($brands) || $brands === []) {
            return;
        }

        $brandIds = $this->uniqueIds($brands);

        $count = DB::table('solution_brands')
            ->where('solution_id', $solutionId)
            ->whereIn('brand_id', $brandIds)
            ->distinct()
            ->count('brand_id');

        if ($count !== count($brandIds)) {
            $validator->errors()->add(
                "solutions.$index.brands",
                'One or more selected brands are not linked to the specified solution.'
            );
        }
    }
}
