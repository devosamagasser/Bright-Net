<?php

namespace Tests\Unit\Modules\Companies\Domain\Profiles;

use App\Modules\Companies\Domain\Models\Company;
use App\Modules\Companies\Domain\Profiles\SupplierProfile;
use App\Modules\Companies\Domain\ValueObjects\CompanyType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SupplierProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_syncs_supplier_relations_with_batched_queries(): void
    {
        Carbon::setTestNow(Carbon::parse('2024-01-01 00:00:00'));

        $company = Company::query()->create([
            'name' => 'Supplier Co',
            'description' => null,
            'type' => CompanyType::SUPPLIER,
        ]);

        $catalog = $this->seedCatalog();

        $profile = new SupplierProfile();

        $payload = [
            'contact_email' => 'supplier@example.com',
            'solutions' => [
                [
                    'solution_id' => $catalog['solutions']['alpha'],
                    'brands' => [
                        [
                            'brand_id' => $catalog['brands']['atlas'],
                            'departments' => [
                                $catalog['departments']['alpha_one'],
                                $catalog['departments']['alpha_two'],
                            ],
                        ],
                        [
                            'brand_id' => $catalog['brands']['beacon'],
                            'departments' => [
                                $catalog['departments']['alpha_three'],
                            ],
                        ],
                    ],
                ],
                [
                    'solution_id' => $catalog['solutions']['beta'],
                    'brands' => [
                        [
                            'brand_id' => $catalog['brands']['cobalt'],
                            'departments' => [
                                $catalog['departments']['beta_one'],
                                $catalog['departments']['beta_two'],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $queryCount = $this->countQueries(static function () use ($profile, $company, $payload): void {
            $profile->create($company, $payload);
        });

        $this->assertLessThanOrEqual(18, $queryCount);

        $this->assertDatabaseCount('supplier_solutions', 2);
        $this->assertDatabaseCount('supplier_brands', 3);
        $this->assertDatabaseCount('supplier_departments', 5);

        $alphaSupplierSolutionId = DB::table('supplier_solutions')
            ->where('solution_id', $catalog['solutions']['alpha'])
            ->value('id');

        $this->assertNotNull($alphaSupplierSolutionId);

        $atlasSupplierBrandId = DB::table('supplier_brands')
            ->where('supplier_solution_id', $alphaSupplierSolutionId)
            ->where('brand_id', $catalog['brands']['atlas'])
            ->value('id');

        $this->assertNotNull($atlasSupplierBrandId);

        $this->assertDatabaseHas('supplier_departments', [
            'supplier_brand_id' => $atlasSupplierBrandId,
            'department_id' => $catalog['departments']['alpha_two'],
        ]);

        Carbon::setTestNow();
    }

    public function test_update_syncs_supplier_relations_and_removes_unused_entries(): void
    {
        Carbon::setTestNow(Carbon::parse('2024-01-01 00:00:00'));

        $company = Company::query()->create([
            'name' => 'Supplier Co',
            'description' => null,
            'type' => CompanyType::SUPPLIER,
        ]);

        $catalog = $this->seedCatalog();

        $profile = new SupplierProfile();

        $initialPayload = [
            'contact_email' => 'supplier@example.com',
            'solutions' => [
                [
                    'solution_id' => $catalog['solutions']['alpha'],
                    'brands' => [
                        [
                            'brand_id' => $catalog['brands']['atlas'],
                            'departments' => [
                                $catalog['departments']['alpha_one'],
                                $catalog['departments']['alpha_two'],
                            ],
                        ],
                        [
                            'brand_id' => $catalog['brands']['beacon'],
                            'departments' => [
                                $catalog['departments']['alpha_three'],
                            ],
                        ],
                    ],
                ],
                [
                    'solution_id' => $catalog['solutions']['beta'],
                    'brands' => [
                        [
                            'brand_id' => $catalog['brands']['cobalt'],
                            'departments' => [
                                $catalog['departments']['beta_one'],
                                $catalog['departments']['beta_two'],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $profile->create($company, $initialPayload);

        $updatePayload = [
            'contact_email' => 'supplier@example.com',
            'solutions' => [
                [
                    'solution_id' => $catalog['solutions']['alpha'],
                    'brands' => [
                        [
                            'brand_id' => $catalog['brands']['atlas'],
                            'departments' => [
                                $catalog['departments']['alpha_two'],
                                $catalog['departments']['alpha_three'],
                            ],
                        ],
                    ],
                ],
                [
                    'solution_id' => $catalog['solutions']['gamma'],
                    'brands' => [
                        [
                            'brand_id' => $catalog['brands']['denim'],
                            'departments' => [
                                $catalog['departments']['gamma_one'],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $queryCount = $this->countQueries(static function () use ($profile, $company, $updatePayload): void {
            $profile->update($company, $updatePayload);
        });

        $this->assertLessThanOrEqual(20, $queryCount);

        $this->assertDatabaseCount('supplier_solutions', 2);
        $this->assertDatabaseHas('supplier_solutions', [
            'solution_id' => $catalog['solutions']['alpha'],
        ]);
        $this->assertDatabaseHas('supplier_solutions', [
            'solution_id' => $catalog['solutions']['gamma'],
        ]);
        $this->assertDatabaseMissing('supplier_solutions', [
            'solution_id' => $catalog['solutions']['beta'],
        ]);

        $this->assertDatabaseCount('supplier_brands', 2);
        $this->assertDatabaseMissing('supplier_brands', [
            'brand_id' => $catalog['brands']['beacon'],
        ]);
        $this->assertDatabaseMissing('supplier_brands', [
            'brand_id' => $catalog['brands']['cobalt'],
        ]);

        $atlasSupplierBrandId = DB::table('supplier_brands')
            ->where('brand_id', $catalog['brands']['atlas'])
            ->value('id');

        $this->assertNotNull($atlasSupplierBrandId);

        $this->assertDatabaseCount('supplier_departments', 3);
        $this->assertDatabaseHas('supplier_departments', [
            'supplier_brand_id' => $atlasSupplierBrandId,
            'department_id' => $catalog['departments']['alpha_three'],
        ]);
        $this->assertDatabaseMissing('supplier_departments', [
            'supplier_brand_id' => $atlasSupplierBrandId,
            'department_id' => $catalog['departments']['alpha_one'],
        ]);

        Carbon::setTestNow();
    }

    /**
     * @return array<string, array<string, int>>
     */
    private function seedCatalog(): array
    {
        $now = Carbon::now();

        $regionId = DB::table('regions')->insertGetId([
            'name' => 'Region',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $solutionAlpha = DB::table('solutions')->insertGetId([
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $solutionBeta = DB::table('solutions')->insertGetId([
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $solutionGamma = DB::table('solutions')->insertGetId([
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $brandAtlas = DB::table('brands')->insertGetId([
            'name' => 'Atlas',
            'region_id' => $regionId,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $brandBeacon = DB::table('brands')->insertGetId([
            'name' => 'Beacon',
            'region_id' => $regionId,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $brandCobalt = DB::table('brands')->insertGetId([
            'name' => 'Cobalt',
            'region_id' => $regionId,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $brandDenim = DB::table('brands')->insertGetId([
            'name' => 'Denim',
            'region_id' => $regionId,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $departmentAlphaOne = DB::table('departments')->insertGetId([
            'solution_id' => $solutionAlpha,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $departmentAlphaTwo = DB::table('departments')->insertGetId([
            'solution_id' => $solutionAlpha,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $departmentAlphaThree = DB::table('departments')->insertGetId([
            'solution_id' => $solutionAlpha,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $departmentBetaOne = DB::table('departments')->insertGetId([
            'solution_id' => $solutionBeta,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $departmentBetaTwo = DB::table('departments')->insertGetId([
            'solution_id' => $solutionBeta,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $departmentGammaOne = DB::table('departments')->insertGetId([
            'solution_id' => $solutionGamma,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return [
            'solutions' => [
                'alpha' => $solutionAlpha,
                'beta' => $solutionBeta,
                'gamma' => $solutionGamma,
            ],
            'brands' => [
                'atlas' => $brandAtlas,
                'beacon' => $brandBeacon,
                'cobalt' => $brandCobalt,
                'denim' => $brandDenim,
            ],
            'departments' => [
                'alpha_one' => $departmentAlphaOne,
                'alpha_two' => $departmentAlphaTwo,
                'alpha_three' => $departmentAlphaThree,
                'beta_one' => $departmentBetaOne,
                'beta_two' => $departmentBetaTwo,
                'gamma_one' => $departmentGammaOne,
            ],
        ];
    }

    /**
     * @param  callable(): void  $callback
     */
    private function countQueries(callable $callback): int
    {
        $connection = DB::connection();
        $connection->flushQueryLog();
        $connection->enableQueryLog();

        $callback();

        $queries = $connection->getQueryLog();

        $connection->flushQueryLog();
        $connection->disableQueryLog();

        return count($queries);
    }
}
