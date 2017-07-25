<?php

use Illuminate\Database\Seeder;
Use App\Models\Proximities;

class ProximitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('set foreign_key_checks = 0;');
        Proximities::truncate();

        $proximities = [
            ['name' => 'Airport', 'search_key' => 'airport'],
            ['name' => 'Beach', 'search_key' => 'beach'],
            ['name' => 'Golf', 'search_key' => 'golf'],
            ['name' => 'Public Swimming Pool', 'search_key' => 'public_swimming_pool'],
            ['name' => 'Public Transportation', 'search_key' => 'public_transportation'],
            ['name' => 'Park', 'search_key' => 'park'],
            ['name' => 'Sea', 'search_key' => 'sea'],
            ['name' => 'School', 'search_key' => 'school'],
            ['name' => 'Shopping', 'search_key' => 'shopping'],
            ['name' => 'Tennis', 'search_key' => 'tennis'],
            ['name' => 'Laundry', 'search_key' => 'laundry'],
            ['name' => 'Spa Hammam', 'search_key' => 'spa_hammam'],
            ['name' => 'Cinema', 'search_key' => 'cinema'],
            ['name' => 'Supermarket', 'search_key' => 'supermarket'],
            ['name' => 'Town center', 'search_key' => 'town_centre']
        ];

        foreach ($proximities as $p) {
            $proximity = new Proximities();
            $proximity->name = $p['name'];
            $proximity->search_key = $p['search_key'];
            $proximity->save();
        }

    }
}
