<?php

namespace Database\Seeders;

use App\Models\Tags;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Seed generic taxonomy tags.
     */
    public function run(): void
    {
        $tags = [
            'IoT',
            'Cloud-Managed',
            'High Availability',
            'AI-Powered',
        ];

        foreach ($tags as $name) {
            Tags::query()->firstOrCreate(['name' => $name]);
        }
    }
}

