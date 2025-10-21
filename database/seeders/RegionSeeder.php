<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Geography\Domain\Models\Region;

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

