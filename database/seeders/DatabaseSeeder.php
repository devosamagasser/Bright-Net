<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
            SolutionSeeder::class,
            BrandSeeder::class,
            // CompanySeeder::class,
            // SupplierSeeder::class,
        ]);

        User::create([
            'name' => 'Owner',
            'email' => 'owner@owner.com',
            'password' => Hash::make('password'),
        ]);
    }
}
