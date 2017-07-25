<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Request;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {   
        $locale = \Request::segment(1);
        $rules = [
            'middleware' => 'web',
            'namespace' => $this->namespace,
            'prefix' => $locale,
        ];
        
        $ignorePrefixPath = ['auth/google/callback', 'auth/facebook/callback'];
        
        if ($locale ==  'cdn')
         $rules['middleware'] = 'cdn';
        
        //ignore localization
        if ($locale == 'api' || $locale == 'cdn' || $locale == 'json' || !Request::isMethod('get') || Request::ajax() || in_array(Request::path(), $ignorePrefixPath)) 
          unset($rules['prefix']);
        else
          $this->app->setLocale($locale);
           
        Route::group($rules, function ($router) {
            require base_path('routes/web.php');
        });
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::group([
            'middleware' => 'api',
            'namespace' => $this->namespace,
            'prefix' => 'api',
        ], function ($router) {
            require base_path('routes/api.php');
        });
    }
}
