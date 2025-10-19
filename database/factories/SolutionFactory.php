<?php

namespace Database\Factories;

use App\Modules\SolutionsCatalog\Domain\Models\Solution;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories.Factory<\App\Modules\SolutionsCatalog\Domain\Models\Solution>
 */
class SolutionFactory extends Factory
{
    /**
     * The model the factory corresponds to.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Solution::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
        ];
    }
}
