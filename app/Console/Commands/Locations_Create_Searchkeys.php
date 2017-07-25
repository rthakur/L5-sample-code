<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Helpers\LocationsSKHelper;

class Locations_Create_Searchkeys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'locations:searchkeys';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command has been made for generating search_key(s) for locations objects where those are missing.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $arr = ['countries', 'states', 'cities'];

        foreach ($arr as $what) {

            if ($what == 'countries') {
                $entities = Country::where('search_key', null)->get();
            } elseif ($what == 'states') {
                $entities = State::where('search_key', null)->get();
            } elseif ($what == 'cities') {
                $entities = City::where('search_key', null)->get();
            } else {
                continue;
            }

            if (count($entities)) {
                foreach ($entities as $entity) {
                    $entity = LocationsSKHelper::generate($entity, $what);
                    $entity->save();
                }
            }


        }

    }
}
