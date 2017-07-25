<?php

use Illuminate\Database\Seeder;
use App\Models\Currency;
use App\Models\Country;

class CurrencyTableSeeder extends Seeder
{
    public function run()
    {
        DB::statement('set foreign_key_checks = 0;');
        Currency::truncate();

        $countries = Country::get();

        $exchangeable = ['AED', 'DKK', 'EUR', 'USD', 'INR', 'JPY', 'CNY', 'SEK', 'NOK', 'RUB', 'UAH'];

        foreach ($countries as $country) {
            $result = file_get_contents('https://restcountries.eu/rest/v2/alpha/' . $country->iso);
            $result = json_decode($result);

            foreach ($result->currencies as $c) {
                if ($c->code != null) {
                    $cur = $c;
                    break;
                }
            }

            $currency = Currency::firstorNew([
                'currency' => $cur->code
            ]);

            $currency->name = $cur->name;
            $currency->symbol = $cur->symbol;

            if (in_array($cur->code, $exchangeable)) {
                $currency->exchangeable = 1;
            }
            $currency->save();

            $country->currency_id = $currency->id;
            $country->save();

        }

        echo 'Done!' . PHP_EOL;

    }
}
