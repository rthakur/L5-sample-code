<?php

use Illuminate\Database\Seeder;
use App\Models\MonthlyFeeInterval;

class MonthlyFeeIntervalTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        MonthlyFeeInterval::truncate();
        
        $priceIntervals = [
            ['currency_id' => '3', 'intervals' => '100,150,200,250,300,400,500,700,900,1200,1500,2000,4000,5000,10000'],
            ['currency_id' => '4', 'intervals' => '100,250,500,1000,1500,2000,2500,3000,4000,5000,7000,9000,10000'],
            ['currency_id' => '5', 'intervals' => '5000,5500,6000,6500,7000,7500,8000,8500,9000,9500,10000,15000,20000'],
        ];
        
        MonthlyFeeInterval::insert($priceIntervals);
    }
}
