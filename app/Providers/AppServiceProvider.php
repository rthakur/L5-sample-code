<?php

namespace App\Providers;

use App\User;
use App\Models\CurrencyRates;
use App\Models\Estateagency;
use App\Models\Property;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Observers\UsersObserver;
use App\Observers\CurrencyRatesObserver;
use App\Observers\EstateagencyObserver;
use App\Models\PropertyTexts;
use App\Observers\PropertyObserver;
use App\Observers\PropertyTextsObserver;
use Illuminate\Support\ServiceProvider;
use App\Observers\CountriesObserver;
use App\Observers\StatesObserver;
use App\Observers\CitiesObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if(env('APP_ENV') === 'production')
         \URL::forceSchema('https');
        
        Estateagency::observe(EstateagencyObserver::class);
        PropertyTexts::observe(PropertyTextsObserver::class);
        Property::observe(PropertyObserver::class);
        CurrencyRates::observe(CurrencyRatesObserver::class);
        Country::observe(CountriesObserver::class);
        State::observe(StatesObserver::class);
        City::observe(CitiesObserver::class);
        User::observe(UsersObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
