<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Seed the regions reference table.
     */
    public function run(): void
    {
        $regions = [
            'Europe, Middle East & Africa',
            'North America',
            'Asia Pacific',
        ];

        foreach ($regions as $name) {
            Region::query()->firstOrCreate(['name' => $name]);
        }
    }
}

