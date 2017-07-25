<?php

use Illuminate\Database\Seeder;

class IntervalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $currencies = App\Models\Currency::all();
      $demoInterval = App\Models\PriceInterval::find(1)->intervals;
      foreach($currencies as $currency) 
      {
        $inter = App\Models\PriceInterval::find($currency->id);
        if(empty($inter))
          App\Models\PriceInterval::create(['currency_id' => $currency->id, 'intervals' => $demoInterval]);
      }
      
      echo 'Done!' . PHP_EOL;
    }
}
