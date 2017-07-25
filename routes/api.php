<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


/**
 * Agency api is temporally unavailable
 */
/*Route::group(['prefix' => 'api', 'middleware' => 'auth.master.api'], function () {

    Route::group(['prefix' => 'agency'], function () {

        Route::put('/', 'Api\AgencyController@createUpdate');
        Route::post('/{id}', 'Api\AgencyController@show')->where('id', '[0-9]+');
        Route::delete('/{id}', 'Api\AgencyController@destroy')->where('id', '[0-9]+');

    });

});*/

Route::group(['prefix' => 'api', 'middleware' => 'auth.agency.api'], function () {

    Route::group(['prefix' => 'property'], function () {
        Route::put('/', 'Api\PropertyController@createUpdate');
        Route::post('/', 'Api\PropertyController@show');
        Route::delete('/', 'Api\PropertyController@destroy');

        Route::post('/all', 'Api\PropertyController@all');
        Route::patch('/price', 'Api\PropertyController@price');
        Route::patch('/sold', 'Api\PropertyController@sold');
        Route::patch('/localisation', 'Api\PropertyController@localisation');
        Route::patch('/images', 'Api\PropertyController@images');
        Route::patch('/features', 'Api\PropertyController@features');
        Route::patch('/views', 'Api\PropertyController@views');
        Route::patch('/proximities', 'Api\PropertyController@proximities');
    });

    Route::group(['prefix' => 'error'], function () {
        Route::put('/report', 'Api\ErrorReportingController@report');
    });

    Route::group(['prefix' => 'agent'], function () {
        Route::put('/', 'Api\AgentController@createUpdate');
        Route::post('/', 'Api\AgentController@show');
        Route::delete('/', 'Api\AgentController@destroy');

        Route::post('/all', 'Api\AgentController@all');
    });

});