<?php

namespace App\Console\Commands;

use App\Models\Property;
use Doctrine\DBAL\Query\QueryBuilder;
use Illuminate\Console\Command;
use App\Helpers\PropertyLocationsHelper;

class Property_locations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'property:locations {id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command has been made for properties that have lat and lng set but no city_id or state_id';

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
        $prop_id = $this->argument('id');

        if (!$prop_id) {
            $properties = Property::where('geo_lat', '<>', null)
                ->where('geo_lat', '<>', '')
                ->where('geo_lng', '<>', null)
                ->where('geo_lng', '<>', '')
                ->where('location_confirm_attempt', 0)
                ->where(function ($query) {
                    $query->where(function ($query) {
                        $query->where('city_id', null)
                            ->orWhere('city_id', '');
                    })
                        ->orWhere(function ($query) {
                            $query->where('state_id', null)
                                ->orWhere('state_id', '');
                        });
                })
                ->limit(50)
                ->orderBy('id')
                ->get();
        } else {
            $properties = Property::where('id', $prop_id)->get();
        }

      //  echo 'System have to locate ' . count($properties) . 'Properties' . PHP_EOL;
        if (count($properties)) {
            foreach ($properties as $property) {
                $property::$DISABLED_OBSERVER = true;
      //          echo 'Working on prop -> ' . $property->id . PHP_EOL;
                PropertyLocationsHelper::detect($property);
                $property::$DISABLED_OBSERVER = false;
            }
        }

        echo 'Done!' . PHP_EOL;

    }
}
