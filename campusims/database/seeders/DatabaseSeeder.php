<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CampusSpace;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $spaces = [
            // V Building
            ['building' => 'V Building', 'name' => 'Canteen',          'capacity' => 100],
            ['building' => 'V Building', 'name' => 'Library',          'capacity' => 80],

            // A Building
            ['building' => 'A Building', 'name' => 'Registrar Area',   'capacity' => 40],
            ['building' => 'A Building', 'name' => 'Student Lounge',   'capacity' => 60],

            // L Building
            ['building' => 'L Building', 'name' => 'Kwago',            'capacity' => 50],
            ['building' => 'L Building', 'name' => 'Cisco',            'capacity' => 50],
            ['building' => 'L Building', 'name' => '3rd Floor',        'capacity' => 120],
            ['building' => 'L Building', 'name' => '4th Floor',        'capacity' => 120],
            ['building' => 'L Building', 'name' => '5th Floor',        'capacity' => 120],
            ['building' => 'L Building', 'name' => 'AVT',              'capacity' => 60],

            // F Building
            ['building' => 'F Building', 'name' => '1st Floor',        'capacity' => 100],
            ['building' => 'F Building', 'name' => '2nd Floor',        'capacity' => 100],
            ['building' => 'F Building', 'name' => '3rd Floor',        'capacity' => 100],
            ['building' => 'F Building', 'name' => '4th Floor',        'capacity' => 100],

            // LCR (Large Convention/Recreation area)
            ['building' => 'LCR',        'name' => 'Main Hall',        'capacity' => 200],
            ['building' => 'LCR',        'name' => 'Left Bleachers',   'capacity' => 150],
            ['building' => 'LCR',        'name' => 'Right Bleachers',  'capacity' => 150],
            ['building' => 'LCR',        'name' => 'Back Bleachers',   'capacity' => 100],
        ];

        foreach ($spaces as $space) {
            CampusSpace::firstOrCreate(
                ['building' => $space['building'], 'name' => $space['name']],
                ['capacity' => $space['capacity'], 'current_occupancy' => 0]
            );
        }
    }
}