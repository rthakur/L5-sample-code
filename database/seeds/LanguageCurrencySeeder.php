<?php

use Illuminate\Database\Seeder;
use App\Models\Language;
use App\Models\Currency;

class LanguageCurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $langCurrency = [
            'en' => 'USD', 'fr' => 'EUR', 'ar' => 'AED', 'de' => 'EUR', 'no' => 'NOK', 
            'es' => 'EUR', 'ma' => 'CNY', 'sv' => 'SEK', 'da' => 'DKK', 'fi' => 'EUR', 
            'pt' => 'EUR', 'ru' => 'RUB', 'ja' => 'JPY', 'it' => 'EUR', 'uk' => 'UAH', 
            'hi' => 'INR'
        ];
        
        foreach($langCurrency as $lang => $curr) {
            
            $currency = Currency::where('currency', $curr)->first();
            
            if (!$currency) {
                continue;
            }
            
            $language = Language::where('country_code', $lang)->first();
            
            if ($language) {
                $language->currency_id = $currency->id;
                $language->save();
            }
            
        }
    }
}
