<?php

use Illuminate\Database\Seeder;
use App\Models\Services;

class ServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('set foreign_key_checks = 0;');
        Services::truncate();

        $services = [

            ['name' => 'Internet', 'search_key' => 'internet'],
            ['name' => 'Air-conditioning', 'search_key' => 'airconditioning'],
            ['name' => 'Bay-window', 'search_key' => 'baywindow'],
            ['name' => 'Outdoor lighting', 'search_key' => 'outdoorlightning'],
            ['name' => 'Alarm', 'search_key' => 'alarm'],
            ['name' => 'Electric gate', 'search_key' => 'electricgate'],
            ['name' => 'Jacuzzi', 'search_key' => 'jscuzzi'],
            ['name' => 'Videophone', 'search_key' => 'videophone'],
            ['name' => 'Window shade', 'search_key' => 'windowshade'],
            ['name' => 'Barbecue', 'search_key' => 'barbecue'],
            ['name' => 'Spa', 'search_key' => 'spa'],
            ['name' => 'Home automation', 'search_key' => 'homeautomation'],
            ['name' => 'Caretaker house', 'search_key' => 'caretakerhouse'],
            ['name' => 'Sauna', 'search_key' => 'sauna'],
            ['name' => 'Double pane windows', 'search_key' => 'doublepanewindows'],
            ['name' => 'Electric window shade', 'search_key' => 'eletricwindowshade'],
            ['name' => 'Electric shutter', 'search_key' => 'electricshutters'],
            ['name' => 'Sprinkler system', 'search_key' => 'sprinklersystem'],
            ['name' => 'Fireplace', 'search_key' => 'fireplace'],
            ['name' => 'Swimming pool', 'search_key' => 'swimmingpool'],
            ['name' => 'Lift', 'search_key' => 'lift'],
            ['name' => 'Intercom', 'search_key' => 'intercom'],
            ['name' => 'Water softener', 'search_key' => 'watersoftener'],
            ['name' => 'Security door', 'search_key' => 'securitydoor'],
            ['name' => 'Caretaker', 'search_key' => 'caretaker'],
            ['name' => 'Crawlspace', 'search_key' => 'crawlspace']
        ];

        foreach ($services as $s) {
            $service = new Services();
            $service->name = $s['name'];
            $service->search_key = $s['search_key'];
            $service->save();
        }

    }
}
