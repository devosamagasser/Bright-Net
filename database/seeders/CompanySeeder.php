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
                'description' => 'A leading tech distributor.',
                'contact_email' => 'info@techdistribution.com',
                'contact_phone' => '123-456-7890',
                'website' => 'https://www.techdistribution.com',
                'type' => CompanyType::SUPPLIER,
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
