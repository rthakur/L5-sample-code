<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Swap\Builder;
use App\Models\Currency;
use App\Models\CurrencyRates;

class Get_Exchange_Rates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exchange_rate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get currency exchange rates';

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
        $swap = (new Builder())
            ->add('google')
            ->build();

        $currencies = Currency::get_exchangeable();

        foreach ($currencies as $base_cur) {
            foreach ($currencies as $convert_cur) {

                $rate = $swap->latest($base_cur->currency . '/' . $convert_cur->currency);
                $currencyRates = CurrencyRates::firstorNew(
                    [
                        'base_currency_id' => $base_cur->id,
                        'convert_currency_id' => $convert_cur->id,
                    ]
                );

                $currencyRates->exchange_rate = $rate->getValue();
                $currencyRates->save();
            }
        }

        echo "Done!" . PHP_EOL;
    }
}
