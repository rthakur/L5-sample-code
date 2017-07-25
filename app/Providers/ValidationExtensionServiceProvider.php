<?php 

namespace App\Providers;

use App\Services\ValidatorExtended;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class ValidationExtensionServiceProvider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::resolver( function( $translator, $data, $rules, $messages = [], $customAttributes = [] ) {
            return new ValidatorExtended( $translator, $data, $rules, $messages, $customAttributes );
        } );
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

}