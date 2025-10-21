<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Companies\Domain\Models\Company;
use App\Modules\Companies\Domain\ValueObjects\CompanyType;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = [
            [
                'name' => 'Tech Distribution Co.',
                'type' => CompanyType::SUPPLIER ,
            ],
            [
                'name' => 'BritNet Contractors',
                'type' => CompanyType::CONTRACTOR,
            ],
            [
                'name' => 'Insight Consultants',
                'type' => CompanyType::CONSULTANT,
            ],
        ];

        foreach ($companies as $companyData) {
            Company::query()->firstOrCreate(
                ['name' => $companyData['name']],
                $companyData
            );
        }
    }
}
