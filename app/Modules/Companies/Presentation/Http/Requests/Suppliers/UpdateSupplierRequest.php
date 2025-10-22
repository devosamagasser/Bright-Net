<?php

namespace App\Modules\Companies\Presentation\Http\Requests\Suppliers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
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
            'solutions.*.brands' => ['required', 'array', 'min:1'],
            'solutions.*.brands.*.brand_id' => ['required', 'integer', Rule::exists('brands', 'id')],
            'solutions.*.brands.*.departments' => ['nullable', 'array'],
            'solutions.*.brands.*.departments.*' => ['integer', Rule::exists('departments', 'id')],
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

                $this->validateSolutionBrands($validator, $index, (int) $solutionId, $solution['brands'] ?? []);
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
                'brands' => $this->normalizeBrands($solution['brands'] ?? []),
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

    /**
     * @param  array<int, mixed>  $brands
     * @return array<int, array<string, mixed>>
     */
    private function normalizeBrands(array $brands): array
    {
        $normalized = [];

        foreach ($brands as $brand) {
            if (! is_array($brand) || ! isset($brand['brand_id'])) {
                continue;
            }

            $brandId = (int) $brand['brand_id'];

            if ($brandId <= 0) {
                continue;
            }

            $normalized[$brandId] = [
                'brand_id' => $brandId,
                'departments' => $this->uniqueIds($brand['departments'] ?? []),
            ];
        }

        return array_values($normalized);
    }

    private function validateSolutionBrands(Validator $validator, int $solutionIndex, int $solutionId, mixed $brands): void
    {
        if (! is_array($brands) || $brands === []) {
            return;
        }

        foreach ($brands as $brandIndex => $brand) {
            if (! is_array($brand) || ! isset($brand['brand_id'])) {
                continue;
            }

            $brandId = (int) $brand['brand_id'];

            if ($brandId <= 0) {
                continue;
            }

            if (! $this->brandBelongsToSolution($solutionId, $brandId)) {
                $validator->errors()->add(
                    "solutions.$solutionIndex.brands.$brandIndex.brand_id",
                    'The selected brand is not linked to the specified solution.'
                );

                continue;
            }

            $this->validateBrandDepartments(
                $validator,
                $solutionIndex,
                $brandIndex,
                $solutionId,
                $brandId,
                $brand['departments'] ?? []
            );
        }
    }

    private function brandBelongsToSolution(int $solutionId, int $brandId): bool
    {
        return DB::table('solution_brands')
            ->where('solution_id', $solutionId)
            ->where('brand_id', $brandId)
            ->exists();
    }

    private function validateBrandDepartments(
        Validator $validator,
        int $solutionIndex,
        int $brandIndex,
        int $solutionId,
        int $brandId,
        mixed $departments
    ): void {
        if (! is_array($departments) || $departments === []) {
            return;
        }

        $departmentIds = $this->uniqueIds($departments);

        $count = DB::table('brand_departments as bd')
            ->join('departments as d', 'd.id', '=', 'bd.department_id')
            ->where('bd.brand_id', $brandId)
            ->where('d.solution_id', $solutionId)
            ->whereIn('bd.department_id', $departmentIds)
            ->distinct()
            ->count('bd.department_id');

        if ($count !== count($departmentIds)) {
            $validator->errors()->add(
                "solutions.$solutionIndex.brands.$brandIndex.departments",
                'One or more selected departments are not linked to the specified brand within this solution.'
            );
        }
    }
}
