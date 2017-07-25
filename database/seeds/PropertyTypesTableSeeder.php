<?php

use Illuminate\Database\Seeder;
use App\Models\PropertyTypes;

class PropertyTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('set foreign_key_checks = 0;');
        PropertyTypes::truncate();

        $property_types = [
            ['name' => 'Apartment', 'search_key' => 'apartment'],
            ['name' => 'Family House / Town House', 'search_key' => 'family_town_house'],
            ['name' => 'House', 'search_key' => 'house'],
            ['name' => 'Cottage', 'search_key' => 'cottage'],
            ['name' => 'Commercial', 'search_key' => 'commercial'],
            ['name' => 'Land', 'search_key' => 'land'],
            ['name' => 'Farm / Ranches', 'search_key' => 'farm_ranches'],
            ['name' => 'Parking', 'search_key' => 'parking'],
            ['name' => 'Castle', 'search_key' => 'castle'],
            ['name' => 'Island', 'search_key' => 'island'],
            ['name' => 'Timeshare', 'search_key' => 'timeshare']
        ];

        foreach ($property_types as $property_type) {
            $pt = new PropertyTypes();
            $pt->name = $property_type['name'];
            $pt->search_key = $property_type['search_key'];
            $pt->save();
        }


    }
}
