<?php

namespace App\Console\Commands;

use App\Models\Currency;
use App\Models\CurrencyRates;
use App\Models\Property;
use Illuminate\Console\Command;
use App\Helpers\PropertyPricesHelper;

class Property_prices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'property:prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command been made for fixes of property prices.';

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
        $props = Property::where('price_currency', '<>', 'EUR')->orWhere('monthly_fee_currency', '<>', 'EUR')->get();
        if (count($props)) {
            foreach ($props as $prop) {
                PropertyPricesHelper::reCount($prop);
            }
        }

        echo 'Done!' . PHP_EOL;

    }
}
