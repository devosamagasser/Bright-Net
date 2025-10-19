<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

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
                'type' => Company::TYPE_SUPPLIER,
            ],
            [
                'name' => 'BritNet Contractors',
                'type' => Company::TYPE_CONTRACTOR,
            ],
            [
                'name' => 'Insight Consultants',
                'type' => Company::TYPE_CONSULTANT,
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
