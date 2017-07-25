<?php

use Illuminate\Database\Seeder;
use App\Models\PriceInterval;

class PriceIntervalTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        PriceInterval::truncate();
        
        $priceIntervals = [
            ['currency_id' => '10', 'intervals' => '10000,25000,50000,100000,150000,200000,250000,300000,400000,500000,700000,900000,1200000,1500000,2000000,4000000,5000000,10000000'],
            ['currency_id' => '1', 'intervals' => '10000,25000,50000,100000,150000,200000,250000,300000,400000,500000,700000,900000,1200000,1500000,2000000,4000000,5000000,10000000'],
            ['currency_id' => '68', 'intervals' => '50000,100000,250000,500000,1000000,1500000,2000000,2500000,3000000,4000000,5000000,7000000,9000000,12000000,15000000,20000000,50000000,100000000,200000000,500000000'],
        ];
        
        PriceInterval::insert($priceIntervals);
    }
}
