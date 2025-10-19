<?php

namespace App\Modules\SolutionsCatalog\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use App\Modules\SolutionsCatalog\Domain\Models\Solution;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Modules\Shared\Support\Traits\HandlesTranslations;
use App\Modules\SolutionsCatalog\Domain\Repositories\SolutionRepositoryInterface;

class EloquentSolutionRepository implements SolutionRepositoryInterface
{
    use HandlesTranslations;
    /**
     * @inheritDoc
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->query()
            ->with(['departments.subcategories'])
            ->paginate($perPage);
    }

    /**
     * @inheritDoc
     */
    public function find(int $id): ?Solution
    {
        return $this->query()
            ->with(['departments.subcategories'])
            ->find($id);
    }

    /**
     * @inheritDoc
     */
    public function create(array $attributes, array $translations): Solution
    {
        $solution = new Solution();
        $this->fillSolution($solution, $attributes, $translations);

        return $solution;
    }

    /**
     * @inheritDoc
     */
    public function update(Solution $solution, array $attributes, array $translations): Solution
    {
        $this->fillSolution($solution, $attributes, $translations);

        return $solution;
    }

    /**
     * @inheritDoc
     */
    public function delete(Solution $solution): void
    {
        $solution->delete();
    }

    /**
     * Base query builder.
     */
    protected function query(): Builder
    {
        return Solution::query();
    }

    /**
     * Fill the solution with shared logic for create/update.
     *
     * @param  array<string, mixed>  $attributes
     * @param  array<string, string> $translations
     */
    protected function fillSolution(Solution $solution, array $attributes, array $translations): void
    {
        $solution->fill($attributes);
        $this->fillTranslations($solution, $translations);
        $solution->save();
    }

    /**
     * Determine a default name from the provided translations.
     *
     * @param  array<string, string> $translations
     */
    // protected function resolveDefaultName(array $translations): string
    // {
    //     if (isset($translations[app()->getLocale()])) {
    //         return $translations[app()->getLocale()];
    //     }

    //     if (isset($translations[config('app.fallback_locale')])) {
    //         return $translations[config('app.fallback_locale')];
    //     }

    //     return (string) reset($translations);
    // }
}
