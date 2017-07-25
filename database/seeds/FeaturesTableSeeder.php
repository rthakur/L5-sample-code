<?php

use Illuminate\Database\Seeder;
use App\Models\Features;

class FeaturesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('set foreign_key_checks = 0;');
        Features::truncate();

        $features = [
            ['name' => "Air Conditioning", 'search_key' => 'air_conditioning'],
            ['name' => 'Attic', 'search_key' => 'attic'],
            ['name' => 'Balcony', 'search_key' => 'balcony'],
            ['name' => 'Car port', 'search_key' => 'car_port'],
            ['name' => 'Cellar', 'search_key' => 'cellar'],
            ['name' => 'Energy efficient', 'search_key' => 'energy_efficient'],
            ['name' => 'Fireplace', 'search_key' => 'fireplace'],
            ['name' => 'Garage', 'search_key' => 'garage'],
            ['name' => 'Garden', 'search_key' => 'garden'],
            ['name' => 'Gated community', 'search_key' => 'gated_community'],
            ['name' => 'Jacuzzi', 'search_key' => 'jacuzzi'],
            ['name' => 'Laundry', 'search_key' => 'laundry'],
            ['name' => 'Lift', 'search_key' => 'lift'],
            ['name' => 'Master Bedroom', 'search_key' => 'master_bedroom'],
            ['name' => 'Modern Exterior', 'search_key' => 'modern_exterior'],
            ['name' => 'Modern Interior', 'search_key' => 'modern_interior'],
            ['name' => 'Open Floor Plan', 'search_key' => 'open_floor_plan'],
            ['name' => 'Patio', 'search_key' => 'patio'],
            ['name' => 'Sauna', 'search_key' => 'sauna'],
            ['name' => 'Seashore', 'search_key' => 'seashore'],
            ['name' => 'Smart home', 'search_key' => 'smart_home'],
            ['name' => 'Swimming pool', 'search_key' => 'swimming_pool'],
            ['name' => 'Veranda', 'search_key' => 'veranda'],
            ['name' => 'Walk In Closet', 'search_key' => 'walk_in_closet'],
            ['name' => 'Ski in/Ski out', 'search_key' => 'ski_in_ski_out'],
        ];

        foreach ($features as $f) {
            $feature = new Features();

            $feature->name = $f['name'];
            $feature->search_key = $f['search_key'];
            $feature->save();
        }


    }
}
