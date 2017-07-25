<?php

use Illuminate\Database\Seeder;
use App\Models\View;

class ViewsTableSeeder extends Seeder
{
    public function run()
    {
        DB::statement('set foreign_key_checks = 0;');
        View::truncate();

        $views = [
            ['name' => 'City view', 'search_key' => 'city_view'],
            ['name' => 'Garden view', 'search_key' => 'garden_view'],
            ['name' => 'Sea view', 'search_key' => 'sea_view'],
            ['name' => 'Courtyard view', 'search_key' => 'courtyard_view'],
        ];

        foreach ($views as $view) {
            $newView = new View;
            $newView->name = $view['name'];
            $newView->search_key = $view['search_key'];
            $newView->save();
        }
    }
}
