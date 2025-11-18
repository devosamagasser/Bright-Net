<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DataTemplateSeeder extends Seeder
{
    public function run(): void
    {
        /**
         * Map subcategory_id => TemplateClass
         * كل Template Class موجود في:
         * database/seeders/DataTemplates/
         */
        $templates = [
            // -------------------------------
            // Indoor (1 → 14)
            // -------------------------------
            1  => \Database\Seeders\DataTemplates\DownlightTemplate::class,
            2  => \Database\Seeders\DataTemplates\SpotlightTemplate::class,
            3  => \Database\Seeders\DataTemplates\TrackLightTemplate::class,
            4  => \Database\Seeders\DataTemplates\LinearTemplate::class,
            5  => \Database\Seeders\DataTemplates\PendantTemplate::class,
            7  => \Database\Seeders\DataTemplates\HighBayTemplate::class,
            8  => \Database\Seeders\DataTemplates\LowBayTemplate::class,
            9  => \Database\Seeders\DataTemplates\WallWasherTemplate::class,
            10 => \Database\Seeders\DataTemplates\WallGrazerTemplate::class,
            11 => \Database\Seeders\DataTemplates\WallMountedIndoorTemplate::class,
            12 => \Database\Seeders\DataTemplates\LEDFlexStripIndoorTemplate::class,
            13 => \Database\Seeders\DataTemplates\EmergencyExitTemplate::class,

            // -------------------------------
            // Outdoor (15 → 30)
            // -------------------------------
            15 => \Database\Seeders\DataTemplates\FloodlightOutdoorTemplate::class,
            16 => \Database\Seeders\DataTemplates\WallPackOutdoorTemplate::class,
            17 => \Database\Seeders\DataTemplates\BollardOutdoorTemplate::class,
            18 => \Database\Seeders\DataTemplates\InGroundOutdoorTemplate::class,
            19 => \Database\Seeders\DataTemplates\StreetlightOutdoorTemplate::class,
            20 => \Database\Seeders\DataTemplates\PostTopOutdoorTemplate::class,
            21 => \Database\Seeders\DataTemplates\OutdoorDownlightIPTemplate::class,
            22 => \Database\Seeders\DataTemplates\OutdoorSpotlightIPTemplate::class,
            23 => \Database\Seeders\DataTemplates\OutdoorLinearIPTemplate::class,
            24 => \Database\Seeders\DataTemplates\OutdoorPendantIPTemplate::class,
            25 => \Database\Seeders\DataTemplates\OutdoorWallWasherIPTemplate::class,
            26 => \Database\Seeders\DataTemplates\OutdoorWallGrazerIPTemplate::class,
            27 => \Database\Seeders\DataTemplates\OutdoorWallMountedTemplate::class,
            28 => \Database\Seeders\DataTemplates\OutdoorLEDStripIPTemplate::class,
            29 => \Database\Seeders\DataTemplates\OutdoorEmergencyExitTemplate::class,
        ];

        foreach ($templates as $subcategoryId => $class) {
            (new $class)->build($subcategoryId);
        }
    }
}
