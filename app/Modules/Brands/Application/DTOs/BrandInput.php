<?php

namespace App\Modules\Brands\Application\DTOs;

use Illuminate\Support\Arr;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class BrandInput
{
    /**
     * @param  array<string, mixed>  $attributes
     * @param  array<int, array{solution_id:int, departments:array<int, int>}>  $solutions
     */
    private function __construct(
        public readonly array $attributes,
        public readonly array $solutions,
        public readonly ?UploadedFile $logo = null,
    ) {
    }

    /**
     * Build the DTO from validated data.
     *
     * @param  array<string, mixed>  $payload
     */
    public static function fromArray(array $payload): self
    {
        $solutions = Arr::pull($payload, 'solutions', []);
        $logo = Arr::pull($payload, 'logo', null);

        if (array_key_exists('region_id', $payload)) {
            $payload['region_id'] = (int) $payload['region_id'];
        }

        $normalizedSolutions = Collection::make($solutions)
            ->map(static function (array $solution): array {
                $solutionId = (int) ($solution['solution_id'] ?? 0);
                $departments = Collection::make($solution['departments'] ?? [])
                    ->map(static fn ($departmentId) => (int) $departmentId)
                    ->unique()
                    ->values()
                    ->all();

                return [
                    'solution_id' => $solutionId,
                    'departments' => $departments,
                ];
            })
            ->values()
            ->all();

        return new self(
            attributes: $payload,
            solutions: $normalizedSolutions,
            logo: $logo,
        );
    }

    /**
     * Resolve the solution identifiers present in the payload.
     *
     * @return array<int, int>
     */
    public function solutionIds(): array
    {
        return Collection::make($this->solutions)
            ->pluck('solution_id')
            ->map(static fn ($id) => (int) $id)
            ->values()
            ->all();
    }

    /**
     * Resolve the unique department identifiers attached to the brand.
     *
     * @return array<int, int>
     */
    public function departmentIds(): array
    {
        return Collection::make($this->solutions)
            ->flatMap(static fn (array $solution) => $solution['departments'])
            ->map(static fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();
    }
}
