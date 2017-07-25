<?php

use Illuminate\Database\Seeder;
use App\Models\Property;

class FixPropertyPrices extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $props = Property::whereRaw('price_on_request = 1 and price != 0')->get();
        if (count($props)) {
            foreach ($props as $prop) {
                $prop->price_on_request = 0;
                $prop->save();
            }
        }

        echo 'Done!' . PHP_EOL;
    }
}
