<?php

namespace App\Modules\Brands\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use App\Modules\Departments\Domain\Models\Department;

abstract class BrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    protected function relationRules(): array
    {
        return [
            'solutions' => ['required', 'array', 'min:1'],
            'solutions.*.solution_id' => ['required', 'integer', 'distinct', 'exists:solutions,id'],
            'solutions.*.departments' => ['required', 'array', 'min:1'],
            'solutions.*.departments.*' => ['required', 'integer', 'distinct', 'exists:departments,id'],
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

                $solutionId  = $solution['solution_id'] ?? null;
                $departments = $solution['departments'] ?? [];

                if (! $solutionId || ! is_array($departments) || $departments === []) {
                    continue;
                }

                // جهّز الـ IDs المرسلة (بعد تنظيفها وتوحيدها)
                $providedIds = collect($departments)
                    ->map(static fn ($id) => (int) $id)
                    ->filter()
                    ->unique()
                    ->values();

                if ($providedIds->isEmpty()) {
                    continue;
                }

                // هات IDs الأقسام اللي فعلاً تابعة للحل ده
                $validIds = Department::query()
                    ->where('solution_id', (int) $solutionId)
                    ->whereIn('id', $providedIds)
                    ->pluck('id')
                    ->map(fn ($id) => (int) $id);

                // طلع المخالِف: اللي اتبعت ومش راجع من الاستعلام (مش تابع للحل)
                $invalidIds = $providedIds->diff($validIds)->values();

                if ($invalidIds->isNotEmpty()) {
                    // تقدر تخصص الرسالة وتعرض IDs المخالفة
                    $validator->errors()->add(
                        "solutions.$index.departments",
                        trans('validation.custom.solutions.*.departments.belongs_to_solution', [
                            'invalid' => $invalidIds->implode(', ')
                        ])
                    );
                }
            }
        });
    }

}
