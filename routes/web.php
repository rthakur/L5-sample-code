<?php
/*
|--------------------------------------------------------------------------
| Api routes group
|--------------------------------------------------------------------------
*/

include('api.php');

Route::get('clear',function(){
  // Artisan::call('db:seed',['--class'=>'FeaturesTableSeeder']);
  Artisan::call('cache:clear');
  Artisan::call('view:clear');
});

Route::get('seed', function(){
    Artisan::call('db:seed',['--class'=>'PackageSeeder']);
    // Artisan::call('db:seed',['--class'=>'IntervalSeeder']);
});

Route::group(['prefix' => 'json'], function () {
  Route::get('states','JsonResponseController@getStates');
  Route::get('cities','JsonResponseController@getCities');
});

Route::get('clearSessionStoredFilter','PropertyController@clearSessionStoredFilter');
Route::get('clearSessionStoredAutocomplete','Front\DefaultController@clearSessionStoredAutocomplete');
Route::get('/autocompleteupdatecitycountry','PropertyController@autocompleteupdatecitycountry');

//Admin routes without auth middleware
Route::group(['prefix' => 'va'], function () {
  Route::get('country','Admin\VAController@country');
  Route::get('country/agency/{countryid}','Admin\VAController@countryAgency');
  Route::get('listAreas/{countryId}','Admin\VAController@listAreas');
  Route::post('listAreas/add','Admin\VAController@listAreasAdd');
  
  Route::get('listState/{countryId}','Admin\VAController@listStates');
  Route::post('listState/add','Admin\VAController@listStateAdd');

  Route::get('realEstates/{countryId}/{stateId}','Admin\VAController@listRealEstates');
  
  Route::get('manage','Admin\VAController@vaManage');
  Route::post('manage/add','Admin\VAController@vaAdd');
  
  Route::get('manage/agency/{vaId}','Admin\VAController@manageAgency');
  Route::post('manage/agencyAdd','Admin\VAController@agencyAdd');
});

Auth::routes();
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

Route::get('/', 'Front\DefaultController@getIndex');

Route::get('/set-cookie', 'Front\DefaultController@setCookie');
Route::get('set_curr/{currency}', 'Front\DefaultController@changeCurrency');
Route::get('share-link', 'ShareLinkController@create');
Route::get('s/{shortlink?}', 'ShareLinkController@openShortlink');

Route::post('stripeevents','StripeWebhookController@handleWebhook');

Route::get('propertydata/features/{type?}', 'PropertyController@getFeaturesJson');
Route::get('propertydata/proximities/{type?}', 'PropertyController@getProximitiesJson');
Route::get('propertydata/services/{type?}', 'PropertyController@getServicesJson');

Route::get('/register/agency', 'Auth\RegisterController@registerAgency');
Route::post('/register/agency', 'Auth\RegisterController@storeAgency');
Route::get('/validateEmail', 'Auth\RegisterController@validateEmail');


Route::get('/campaign/{id}', 'Auth\RegisterController@registerAgencyCampaign');
Route::get('/agency/edit/{token}','Auth\RegisterController@registerAgency');
Route::get('logout', 'Auth\LoginController@logout');
Route::get('social/reset-password/{id}', 'Auth\LoginController@resetPassword');
Route::post('social/reset-password', 'Auth\LoginController@savePassword');

Route::get('reset-password/{userId}', 'ProfileController@resetPassword');
Route::post('reset-password', 'ProfileController@savePassword');

Route::get('gallery','Front\DefaultController@getGallery');
Route::get('team','Front\DefaultController@getTeam');
Route::get('about','Front\DefaultController@getContactAbout');
Route::get('faq','Front\DefaultController@getFAQ');
Route::get('terms-conditions','Front\DefaultController@getTermsconditions');
Route::get('privacy-policy','Front\DefaultController@getPrivacypolicy');
Route::get('cookies','Front\DefaultController@getCookies');
Route::get('400','Front\DefaultController@getError400');
Route::get('403','Front\DefaultController@getError403');
Route::get('404','Front\DefaultController@getError404');
Route::get('500','Front\DefaultController@getError500');
Route::get('/account/profile/unsubscribe/{user_id}','ProfileController@unsubscribe');

Route::group(['prefix' => 'cdn', 'middleware' => 'with.headers'], function () {
  Route::get('property-locations','Front\DefaultController@getPropertyLocations');
  Route::get('loadgrideview', 'Front\DefaultController@loadGridView');
  Route::get('save-log', 'PropertyController@saveViewLog');
});

Route::group(['middleware' => 'auth'], function (){
  Route::get('save-map-postions', 'Front\DefaultController@saveMapPosition');
});

/*
|--------------------------------------------------------------------------
| Social Auth Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'auth'], function () {
  Route::get('/google', 'Auth\SocialController@redirectToGoogle');
  Route::get('/google/callback', 'Auth\SocialController@googleCallback');
  Route::get('/facebook', 'Auth\SocialController@redirectToFacebook');
  Route::get('/facebook/callback', 'Auth\SocialController@facebookCallback');
  Route::get('accessaccount/{id}','Admin\DefaultController@accessAccount')->middleware('auth');
});

Route::group([ 'prefix' => 'payment', 'middleware' => 'auth'], function() {
  Route::get('/update/{package_id?}', 'PaymentController@getUpdate');
  Route::post('/checkout', 'PaymentController@checkout');
  Route::get('/', 'PaymentController@getIndex');
  Route::post('api_checkout', 'PaymentController@apiCheckout');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin_account_check']], function () {
  Route::get('/','Admin\DefaultController@index');
  
  Route::group(['prefix' => 'agency'], function() {
    Route::get('email-content', 'Admin\AgencyController@getEmailContent');
    Route::get('check-email-send', 'Admin\AgencyController@getCheckEmailSend');
    Route::get('email-send', 'Admin\AgencyController@getEmailSend');
  });
  
  Route::resource('agency','Admin\AgencyController');
  Route::resource('manageuser','Admin\ManageUserController'); 
  Route::get('property','PropertyController@adminIndex');
  Route::get('filter-property','PropertyController@filterProperty');
  Route::resource('message','Admin\ManageMessageController');
  Route::get('contact/message','Admin\ManageMessageController@getContactMessage');
  Route::resource('zones','Admin\ZonesController');
  Route::get('campaign/code','Admin\CampaignController@generateRandomCampaignCode');
  Route::get('campaign/checkCode','Admin\CampaignController@checkCode');
  Route::resource('campaign','Admin\CampaignController');
  
  Route::group(['prefix' => 'currencies'], function(){
      Route::get('/','Admin\DefaultController@currencies');
      Route::post('/change-order', 'Admin\DefaultController@currenciesChangeOrder');
  });
  
  Route::group(['prefix' => 'intervals'], function(){
      Route::get('add/{type}','Admin\DefaultController@addCurrencyIntervals');
      Route::get('/','Admin\DefaultController@getCurrency');
      Route::get('edit/{currency_id}/{type}','Admin\DefaultController@editCurrencyIntervals');
      Route::post('save-intervals/','Admin\DefaultController@saveCurrencyIntervals');
  });
  
  Route::group(['prefix' => 'sync-request'], function(){
      Route::get('requests', 'Admin\SynchronizeController@index');
      Route::get('{user_id}/{action}', 'Admin\SynchronizeController@requestResponse');
  });
  
  Route::get('sync-property-request/requests', 'SynchronizeController@index');
  
});


Route::group(['prefix' => 'account', 'middleware' => 'auth'], function () {
    Route::get('profile/delete-pic/{action}','ProfileController@removeProfilePic');
    Route::resource('profile','ProfileController');
    Route::get('bookmarked', 'ProfileController@getBookmarked');
    Route::get('property-stats', 'ProfileController@getPropertyStatData');
    Route::get('bills', 'ProfileController@getBills');
    Route::get('invoice/{invoice}', 'ProfileController@downloadInvoice');
    Route::get('message/delete/{action}', 'ProfileController@deleteMessage');
    Route::post('save-profile','ProfileController@postAgentProfile');
    
    Route::group(['prefix' => 'agent'], function () {
      Route::get('myproperties/{new?}', 'ProfileController@getMyProperties');
      Route::get('mymessages', 'ProfileController@getMyMessages');
      Route::get('property-details/{propertyId}', 'ProfileController@getPropertyDetails');
      Route::get('{propertyId}/{status}/confirm', 'ProfileController@confirmPropertyStatus');
    });
    
    Route::group(['prefix' => 'agency'], function () {
      Route::get('details', 'ProfileController@getAgencyDetails');
      Route::get('properties', 'ProfileController@getProperties');
      Route::get('property-details/{propertyId}', 'ProfileController@getPropertyDetails');
      Route::get('agents', 'ProfileController@getAgents');
      Route::get('agent/new', 'ProfileController@getNewAgent');
      Route::get('agent/random-password', 'ProfileController@getRandomAgentPassword');
      Route::get('agent/{agentId?}', 'ProfileController@getEditAgent');
      Route::post('agent/{agentId?}', 'ProfileController@postSaveAgent');
      Route::get('subscription', 'ProfileController@getSubscriptionPlan');
      Route::get('apicredential', 'ProfileController@getApiCredential');
      Route::get('synchronization', 'ProfileController@getSyncStatus');
      Route::get('resetapicredential/{agency_id}', 'ProfileController@resetApiCredential');
      Route::get('statistics', 'ProfileController@getStatistics');
      Route::get('stats', 'ProfileController@getStatisticsData');
      Route::get('messages', 'ProfileController@getAgencyMessages');
    });
});

/*
|--------------------------------------------------------------------------
| PropertyController Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => '{buy}/property/{country?}/'], function () {
  Route::get('/','PropertyController@getIndex');
  Route::post('/','PropertyController@getIndex');
});

Route::group(['prefix' => 'property'], function () {
  Route::get('add-bookmark','PropertyController@addBookmark');
  Route::get('view/{id}','PropertyController@viewProperty');
  Route::get('{propertyId}/all-images', 'PropertyController@getAllPropertyImages');

  Route::group(['middleware' => 'auth'], function () {
    Route::get('{propertyId}/edit/{action?}', 'PropertyController@getEditProperty');
    
    Route::group(['prefix' => 'plans'], function () {
        Route::get('/', 'PlansController@index');
        Route::get('api', 'PlansController@getApiPlan');
        Route::get('api/order', 'PlansController@getOrderApi');
        Route::get('syncronize/confirm', 'PlansController@postSyncronizePlan');
        Route::get('syncronize/{syncstatus?}', 'PlansController@getSyncronizePlan');
        Route::get('change/{action}', 'PlansController@index');
    });
    
    Route::get('submit','PropertyController@getSubmit');
    Route::post('updateBasicInfo','PropertyController@updateBasicInfo');
    Route::post('update_property', 'PropertyController@updatePropertyInfo');
    Route::get('delete/{propertyId}','PropertyController@deleteProperty');
    Route::post('save-images/{propertyId}','PropertyController@savePropertyImages');
    Route::get('delete-image','PropertyController@deletePropertyImage');
    Route::get('set-main-image','PropertyController@setMainPropertyImage');
    Route::post('save-features','PropertyController@saveFeatures');
    Route::post('save-proximities','PropertyController@saveProximities');
    Route::post('save-views','PropertyController@saveViews');
  });
  
});

Route::group(['prefix' => 'agent'], function () {
  //Route::get('/','Front\AgentController@getIndex');
  Route::post('sendmessage','Front\AgentController@postSendmessage');
});

#Agency Routes
Route::group(['prefix' => 'agency'], function () {
// Route::get('/','Front\AgencyController@getIndex');
  Route::post('create','Front\AgencyController@postCreateAgency');
  Route::post('save-logo','Front\AgencyController@postAgencyLogo');
  Route::get('/delete-logo/{action}','Front\AgencyController@removeLogo');  
  Route::post('sendmessage','Front\AgencyController@postSendmessage');
});  
# End Agency Routes

Route::get('sync-now', 'SynchronizeController@sync');

#contact Routes
Route::group(['prefix' => 'contact'], function () {
  Route::get('/','Front\DefaultController@getContact');
  Route::post('sendmail','Front\DefaultController@postSendmail');
  Route::get('miparo','Front\DefaultController@getContactMiparo');
  Route::get('investment','Front\DefaultController@getContactInvestment');
});
#Contact Agency Routes


#Property detail
Route::get('{buy}/{property}/{type}/{country}/{area}/{city}/{agencyName}/{propertyId}/{subject?}','PropertyController@getDetail');

Route::get('{country}/{state}/{realestateagency}/{agencyId}/{agencyName}','Front\AgencyController@getDetail');
Route::get('{realestateagent}/{agentId}/{agencyName}/{agentName}','Front\AgentController@getDetail');

