<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RegionSeeder::class,
            ColorSeeder::class,
            TagSeeder::class,
            SolutionSeeder::class,
            BrandSeeder::class,
            CompanySeeder::class,
            SupplierSeeder::class,
            AccessControlSeeder::class,
        ]);
    }
}
