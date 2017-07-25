<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Property;
use App\Models\PropertyTypes;
use App\Models\Message;
use App\Models\PropertyFeatures;
use App\Models\MapMovement;
use App\Models\Search;
use App\Models\PropertyServices;
use App\Models\PropertyProximities;
use App\Models\PropertyView;
use App\Models\BookmarkedLink;
use App, Auth, Mail, Cache, View, Config, Session;
use App\Models\CurrencyRates;
use App\Helpers\PropertySearchHelper;
use App\Models\PropertyStatistics;
use Carbon\Carbon;
use App\Helpers\CommonHelper;
use App\Models\PropertyImages;
use Response;
use App\Models\Language;
use App\Models\Zones;
use App\Models\Features;
use App\Models\Proximities;
use App\Models\View as PrpView;
use App\Models\Country;

class DefaultController extends Controller
{
    /**
     * Shows Map Properties page.
     *
     * @return view
     */
    public function getIndex(Request $request)
    {
        $data = [];
        $this->setLangSession($request);
        
        if (isset($request->property)) {
            if (empty($property = Property::find(base64_decode($request->property))) ) {
                return abort(404);
            } else {
                Session::put('last_property_viewed',$property);
                Session::forget('filtersDataSet');
            }
        }
        
        if (empty($request->all()) && Session::has('filtersDataSet')) {
            $cacheKey = 'map_property_'. md5(json_encode(Session::get('filtersDataSet')));
        } else {
            $cacheKey = 'map_property_'. md5(json_encode($request->all()));
        }

        if (Cache::has($cacheKey)) {
            $data = Cache::get($cacheKey);
        } else {
            
            if (isset($request->property)) {
                
                $data['propertyId'] = $request->property;
                
                $request->geo_lat = $property->geo_lat;
                $request->geo_lng = $property->geo_lng;
                $request->zoom = 15;
            }
            
            if ($request->geo_lat && $request->geo_lng) {
                $data['geo_lat'] = $request->geo_lat;
                $data['geo_lng'] = $request->geo_lng;
            }
            
            $data['address'] = isset($request->address) ? $request->address : null;
            $data['filtersData'] = PropertySearchHelper::getFilterData($request);

            $location = $this->getLocationData($request);
            $data = array_merge($data, $location);

            Cache::put($cacheKey, $data, 60*24);

            if (!empty($request->all()) && empty($request->property)) {
                \Session::put('filtersDataSet', $request->all());
            }
            
        }
        
        if (isset(Session::get('last_property_viewed')['id'])) {
            $data['property'] = Property::find(Session::get('last_property_viewed')['id']); //last viewed property
        } else {
            $defaultProperty = Property::where('main_image_url', '!=', '')->first();
            $data['defaultImage'] = ($defaultProperty) ? $defaultProperty->main_image_url : ''; //defaultImage();
        }

        $data['search_type_map'] = 'active';
        $data['view_type'] = 'Map';
        $data['disablefooter'] = true;
        $data['with_search_bar'] = true;
        $data['share_link'] = URL(SITE_LANG);

        return view('front.index',$data)->with('dontShowObjectCount', 'true');
    }

    /**
     * Returns all/filtered properties data.
     *
     * @return array
     */
    public function getPropertyLocations(Request $request)
    {
        App::setLocale(str_replace('/','', $request->site_lang));
        PropertySearchHelper::saveSearch($request);
        
        if (empty($request->map_view_property_id)) {
            \Session::put('filtersDataSet',$request->all());
        }
        
        if (!empty($request->map_view_property_id)) {
            
            $propertiesData['locations'] = Property::mainImagePropertiesFilter()
                ->selectRaw("
                    property.id,
                    property.geo_lat,
                    property.geo_lng
                ")
                ->where('id', base64_decode($request->map_view_property_id))
                ->get();
            
        } else {
            
            if ($request->zoom >= 8) {
                $propertiesData = $this->selectedPropertiesByLatLng($request);
            } else {
                $propertiesData = $this->zonesProperty($request);
            }
        }
        
        ## counts property objects in $propertiesData
        
        if (isset($propertiesData['propertiesCount'])) {
            $propertiesCount = array_pull($propertiesData, 'propertiesCount');
            $returnData['propertiesCount'] = trans('viewproperty.PropertiesFound') .' : '. $propertiesCount;
        }
        
        $returnData['properties']['locations'] = $propertiesData['locations'];
        $returnData['properties']['type'] = isset($propertiesData['type']) ? $propertiesData['type'] : '';
        $returnData['filterString'] = PropertySearchHelper::getSelectedFilterString($request);

        return response($returnData);
    }

    /**
     * Returns all/filtered properties Gallery view data.
     *
     * @return array
     */
    public function loadGridView(Request $request)
    {
        Session::put('currency', $request->site_curr);
        
        ## Cache key name according to selected filters
        $cacheKey = 'load_grid_view_'.md5(json_encode($request->all()));

        \Session::put('filtersDataSet',$request->all());

        if (Cache::has($cacheKey)) {
            return base64_decode(Cache::get($cacheKey)); // Get cached grid view data
        }

        $lang = $request->session()->get('site_lang')? : $request->site_lang ?: 'en';
        App::setLocale($lang);

        $properties = Property::mainImagePropertiesFilter()->with('texts');
        $properties = $this->applyAllFilters($request, $properties);
        
        $properties = $properties
            ->join('property_images', 'property.id', '=', 'property_images.property_id')
            ->leftJoin('users', 'property.agent_id', '=','users.id')
            ->leftJoin('estate_agencies', 'property.agency_id', '=', 'estate_agencies.id')
            ->where('property_images.s3_url', '!=', '')
            ->where('property.country_id', '!=', '');
        
        $propertyCount = clone $properties;
        $propertyCount = $propertyCount
            ->selectRaw('count(distinct(property_images.property_id)) as count_prop')
            ->first();
            
        $propertyCount = ($propertyCount) ? $propertyCount->count_prop : 0;
        
        $data['properties'] = $properties
            ->selectRaw('
                property.*,
                property_images.s3_url as s3_url,
                property.country_id as country_id,
                property.city_id as city_id,
                property.state_id as state_id,
                users.id as user_id,
                users.name as agent_name,
                users.profile_picture as agent_profile_picture,
                estate_agencies.public_name as agency_name'
            )
            ->simplePaginate(20);
        
        $data['siteCurrency'] = $request->site_curr;
        $data['lang'] = $lang;
        $data['filterString'] = PropertySearchHelper::getSelectedFilterString($request);
        $data['status'] = count($data['properties']) ? 'success' : 'error';
        $data['propertiesCount'] = trans('viewproperty.PropertiesFound') .' : '. $propertyCount;
        
        $html = view('front.gridview', $data);
        
        Cache::put($cacheKey, base64_encode($html), 60*24);
        
        return $html;
    }
    
    private function applyAllFilters(Request $request, $properties)
    {
        if (CommonHelper::checkFiltersExist($request)) {
            $properties = PropertySearchHelper::applySearchFilters($request, $properties);
        }
        
        foreach(['country', 'city', 'area'] as $field) {
            
            if (isset($request->{$field}) && !empty($request->{$field})) {
                $properties = $properties->where('property.'. $field .'_id', $request->{$field});
            }
        }
        
        if ($request->zoom && $request->zoom >= 3) {
            
            if ($request->zoom <= 7) {

                $zoomlevel = $request->zoom;
                $properties = $properties->join('zones', function($join)  use ($zoomlevel){
                    $join->on('property.zoom_level'.$zoomlevel,'=','zones.id')
                        ->where('zones.zoomlevel',$zoomlevel);
                });
            } else {

                foreach (PropertySearchHelper::getZoomLevelDistances($request) as $distance) {

                    $properties = $properties
                        ->selectRaw("
                            ( 6764 * acos( cos( radians(".$request->geo_lat.") )
                            * cos( radians( property.geo_lat ) )
                            * cos( radians( property.geo_lng )
                            - radians(".$request->geo_lng.") )
                            + sin( radians(".$request->geo_lat.") )
                            * sin( radians( property.geo_lat ) ) )
                            ) AS distance")
                        ->havingRaw('distance < '. $distance);
                    
                    $propertiesCount = count($properties->get());
                    
                    if ($propertiesCount) {
                        break;
                    }

                }

            }
        }
        
        return $properties;
    }
    
    public function clearSessionStoredAutocomplete()
    {
        if (Session::has('address')) {
            Session::forget('address');
        }
        Session::forget('filtersDataSet.address');
        Session::forget('filtersDataSet.address');
    }

    /**
     * Shows About page.
     *
     * @return view
     */
    public function getAbout()
    {

        return view('front.default.about-us');
    }

    /**
     * Shows Terms & Conditions page.
     *
     * @return view
     */
    public function getTermsconditions()
    {
        $view = 'front.default.'.App::getLocale().'.terms-conditions';

        if (!View::exists($view)) {
            $view = 'front.default.en.terms-conditions';
        }

        return view($view);
    }

    /**
     * Shows Privacy policy page.
     *
     * @return view
     */
    public function getPrivacypolicy()
    {
        $view = 'front.default.'.App::getLocale().'.privacy-policy';

        if (!View::exists($view)) {
            $view = 'front.default.en.privacy-policy';
        }

        return view($view);
    }

    /**
     * Shows Cookies policy page.
     *
     * @return view
     */
    public function getCookies()
    {
        $view = 'front.default.'.App::getLocale().'.cookies';

        if (!View::exists($view)) {
            $view = 'front.default.en.cookies';
        }

        return view($view);
    }

    /**
     * Shows 403 Error page.
     *
     * @return view
     */
     
     public function getError400()
     {
         return view('errors.400');
     }
     
    public function getError403()
    {
        return view('errors.403');
    }

    /**
     * Shows 404 Error page.
     *
     * @return view
     */
    public function getError404()
    {
        return view('errors.404');
    }

    /**
     * Shows 500 Error page.
     *
     * @return view
     */
    public function getError500()
    {
        return view('errors.500');
    }

    /**
     * Shows Contact page.
     *
     * @return view
     */
    public function getContact()
    {
        $contacts = [
            ['country' => 'c8f4261f9f46e6465709e17ebea7a92b', 'phone'  => '+46 735 11 04 24', 'email' => 'sweden@miparo.com '], //Sweden
            ['country' => '0309a6c666a7a803fdb9db95de71cf01', 'phone'  =>  '+33 582 88 00 11', 'email' => 'france@miparo.com'], //France
            ['country' => '458e4cbc78201c1aec5fc53a31c59378', 'phone'  => '+33 97 721 87 78', 'email' => 'singapore@miparo.com'], //Singapore
            ['country' => '77dab2f81a6c8c9136efba7ab2c4c0f2', 'phone'  => '+63 975 557 65 13', 'email' => 'philippines@miparo.com'], //philippines
            ['country' => '0c7d5ae44b2a0be9ebd7d6b9f7d60f20', 'phone'  => '+40 73 492 51 96', 'email' => 'romania@miparo.com'],//Romania
            ['country' => '5d839147c83e283c1d1bb705dc50586f', 'phone'  => '+23 4806 299 24 00', 'email' => 'nigeria@miparo.com'],//nigeria
            ['country' => 'f78a77f631d275aac6a914a17fe1b885', 'phone'  => '+88 0191 278 47 68', 'email' => 'bangladesh@miparo.com'],//bangladesh
            ['country' => 'e95294b730f61c8175550ec244bfcb50', 'phone'  => '+58 412 883 24 92', 'email' => 'venezuela@miparo.com'],//venezuela

        ];

        return view('front.contact.contact', ['contacts' => $contacts]);
    }

    /**
     * Shows Contact Miparo page.
     *
     * @return view
     */
    public function getContactMiparo()
    {
        return view('front.contact.contact-miparo');
    }

    /**
     * Shows Investment page.
     *
     * @return view
     */
    public function getContactInvestment()
    {
        return view('front.contact.investment');
    }

    /**
     * Shows About page.
     *
     * @return view
     */
    public function getTeam()
    {
        $data['salesManagerTeam'] =
        [
            ['name' => 'Dilruba Akhter (BANGLADESH)', 'email' => 'dilruba.akhter@miparo.com'],
            ['name' => 'Jovele Pusta (PHILIPPINES)', 'email' => 'jovelle.pusta@miparo.com'],
            ['name' => 'Gheorghe Madalina Paula (ROMANIA)', 'email' => 'madalina.gheorghe@miparo.com'],
            ['name' => 'Yeniree Valentina Bocaney Mendoza (VENEZUELA)', 'email' => 'yeniree.bocaney@miparo.com'],
            ['name' => 'Sargin Ogheneruona Paul (NIGERIA)', 'email' => 'ruona.sargin@miparo.com']
        ];

        $data['devManagerTeam'] =
        [
            ['name' => 'Andrey Khitsenko', 'email' => 'andrey.khitsenko@miparo.com'],
            ['name' => 'Ravi Kumar', 'email' => 'ravi.kumar@miparo.com'],
            ['name' => 'Harwinder', 'email' => 'harwinder@miparo.com']
        ];
        return view('front.contact.about', $data);
    }
    
    public function getContactAbout()
    {
        return view('front.default.about-us');
    }

    /**
     * Shows FAQ page.
     *
     * @return view
     */
    public function getFAQ()
    {
        $view = 'front.default.'.App::getLocale().'.faq';

        if (!View::exists($view)) {
            $view = 'front.default.en.faq';
        }

        return view($view);
    }

    /**
     * Shows Gallery page.
     *
     * @return view
     */
    public function getGallery(Request $request)
    {
        $this->setLangSession($request);
        
        $filters = PropertySearchHelper::getFilterData($request);
        
        if (Session::has('filtersDataSet') && empty(array_filter($filters))) {
            $request = (object) Session::get('filtersDataSet');
        } else {
            Session::put('filtersDataSet', $request->all());
        }
        
        if (!empty($request->geo_lat) && !empty($request->geo_lng)) {
            $data['geo_lat'] = $request->geo_lat;
            $data['geo_lng'] = $request->geo_lng;
        }

        ## if geo_lat not empty then bydefault sets zoom = 6 otherwise null
        $data['zoom'] = !empty($request->zoom) ? $request->zoom : (!empty($request->geo_lat) ? 3 : null);
        $data['disablefooter'] = true;
        $data['filtersData'] = PropertySearchHelper::getFilterData($request);
        $data['address'] = isset($request->address) ? $request->address : null;

        $data['share_link'] = URL(SITE_LANG .'/gallery');
        $data['with_search_bar'] = true;
        $data['view_type'] = 'Gallery';
        $data['search_type_gallery'] = 'active';

        return view('front.gallery',$data);
    }

    /**
     * Sends Contact message to miparo admin.
     *
     * @return response
     */
    public function postSendmail(Request $request)
    {
        $validate = validator($request->all(),[
            'user_name' => 'required',
            'user_email' => 'required|email',
            'user_message' => 'required',
            'g-recaptcha-response' => 'required|recaptcha',
        ]);

        if ($validate->fails())
            return back()->withErrors($validate)->withInput();

        $message = new Message;

        if (Auth::check()) {
            $message->entry_by = Auth::id();
            $message->user_id = Auth::id();
        }

        $message->sender_name = $request->user_name;
        $message->sender_email = $request->user_email;
        $message->to_email = 'info@miparo.com';
        $message->message_text = $request->user_message;
        $message->type = 'contact_us';
        $message->save();

        $request->session()->flash('notification_alert', [
            'type' => 'success', 'message' => trans('common.ThankYouContactMessage')
        ]);

        return back();

        //disabled email notification
        $emailStr = trans('emails.contact_email');
        $emailStr = str_replace('<user_name>', $request->user_name, $emailStr);
        $emailStr = str_replace('<user_email>', $request->user_email, $emailStr);
        $emailStr = str_replace('<user_message>', $request->user_message, $emailStr);
        $emailStr = nl2br($emailStr);

        $subject = trans('emails.contact_email_subject');
        $subject = str_replace('<user_name>', $request->user_name, $subject);

        Mail::send('emails.contact_email',['emailContent' => $emailStr], function($message) use($subject){
            $message->to(\Config::get('sitesettings.contact_email'))->subject($subject);
        });

    }

    /**
     * Saves user's map position in database on zoom/latitude/longitude change in zoom.
     *
     * @return null
     */
    public function saveMapPosition(Request $request)
    {
        if (!isset($request->zoom) || !isset($request->geo_lat) || !isset($request->geo_lng)) {
            return response()->header(trans('common.InsufficientData'), 403);
        }

        $mapMovement = new MapMovement;
        $mapMovement->user_id = Auth::id();
        $mapMovement->zoom = $request->zoom;
        $mapMovement->geo_lat = $request->geo_lat;
        $mapMovement->geo_lng = $request->geo_lng;
        $mapMovement->save();
    }

    /**
     * Changes user's current currency.
     *
     * @return response
     */
    public function changeCurrency($currency)
    {
        Session::put('currency', $currency);
        return back();
    }

    /**
     * Enables cookies for a user.
     *
     * @return null
     */
    public function setCookie()
    {
        setCookie('enableCookies', 1, time() + (10 * 365 * 24 * 60 * 60));
    }

    /**
     * Return properties according to zones.
     *
     * @return array
     */
    public function zonesProperty($request)
    {
        $zoomlevel = $request->zoom < 3 ? 3 : $request->zoom;

        $property = Property::mainImagePropertiesFilter()
            ->join('zones', function($join)  use ($zoomlevel) {
                $join->on('property.zoom_level'.$zoomlevel,'=','zones.id')
                    ->where('zones.zoomlevel',$zoomlevel);
            });
        
        if ($request->geo_lat && $request->geo_lng) {
            $data['geo_lat'] = $request->geo_lat;
            $data['geo_lng'] = $request->geo_lng;
        }

        $property = PropertySearchHelper::applySearchFilters($request, $property);
        
        foreach(['country', 'city', 'area'] as $field) {
            if (!empty($request->$field)) {
                $property = $property->where('property.'. $field .'_id',$request->$field);
            }
        }

        $data['type'] = 'country_locations';
        $data['propertiesCount'] = $property->count();
        $data['locations'] = $property
            ->selectRaw('
                (case WHEN(count(property.id) = 1) THEN property.geo_lat ELSE zones.geo_lat END) as geo_lat,
                (case WHEN(count(property.id) = 1) THEN property.geo_lng ELSE zones.geo_lng END) as geo_lng,
                count(property.id) as property_count,
                property.id as get_property_id, zones.zoomlevel
            ')
            ->groupBy('property.zoom_level'. $zoomlevel)
            ->get();

        return $data;
    }

    /**
     * Return nearby properties by zoom level distance.
     *
     * @return array
     */
    public function selectedPropertiesByLatLng($request)
    {
        $nearestCheck = false;
        $geoLat = $request->lat;
        $geoLng = $request->lng;

        foreach (PropertySearchHelper::getZoomLevelDistances($request) as $distance) {

            $locations = $this->getLatLngProperties($distance, $geoLat, $geoLng, $request);

            if (count($locations['locations'])) {
                return $locations;
            }
        }

    }

    /**
     * Return nearby properties according to latitude & longitude.
     *
     * @return array
     */
    private function getLatLngProperties($distance, $geoLat, $geoLng, $request)
    {
        $property = Property::mainImagePropertiesFilter()
            ->selectRaw("
                property.id,
                property.geo_lat,
                property.geo_lng,
                ( 6764 * acos( 
                    cos( radians(".$geoLat.") ) 
                    * cos( radians( geo_lat ) ) 
                    * cos( radians( geo_lng ) - radians(".$geoLng.") ) 
                    + sin( radians(".$geoLat.") ) 
                    * sin( radians( geo_lat ) ) 
                ) ) AS distance
            ");
        
        if ($request->geo_lat && $request->geo_lng) {
            $data['geo_lat'] = $request->geo_lat;
            $data['geo_lng'] = $request->geo_lng;
        }
        
        $property = PropertySearchHelper::applySearchFilters($request, $property);
        
        if (isset($request->country) && !empty($request->country)) {
            $property = $property->where('property.country_id',$request->country);
        }
        
        if (isset($request->city) && !empty($request->city)) {
            $property = $property->where('property.city_id',$request->city);
        }
        
        if (isset($request->area) && !empty($request->area)) {
            $property = $property->where('property.area_id',$request->area);
        }
        
        $property = $property->havingRaw('distance < '. $distance);

        $data['propertiesCount'] = $property->get()->count();
        $data['locations'] = $property
            ->groupBy('distance')
            ->limit(500)
            ->get();
            
        return $data;
    }

    /**
     * Return distance according to zoom.
     *
     * @return array
     */
    private function getCountDistance($request)
    {
        if ($request->zoom == '5')
            $distances =  '20000';
        elseif ($request->zoom == '6')
            $distances =  '10000';
        elseif ($request->zoom == '7')
            $distances =  '8000';
        elseif ($request->zoom == '8')
            $distances =  '5000';
        elseif ($request->zoom == '9')
            $distances =  '4000';
        elseif ($request->zoom == '10')
            $distances =  '3000';
        else
            $distances =  '2000';

        return $distances;
    }

    /**
     * Set user's current lang to session.
     *
     * @return null
     */
    private function setLangSession($request)
    {
        $urlLang = $request->segment(1);
        $appLanguages = Language::getAllLanguages()->toArray();
        $appLanguages = array_flip($appLanguages);

        if (isset($appLanguages[strtolower($urlLang)]))
            $request->session()->put('site_lang',$urlLang);
    }

    /**
     * Calculates latitude, longitude, zoom according to given parameters.
     *
     * @return array
     */
    public function getLocationData(Request $request)
    {
        if (!empty($request->geo_lat) && !empty($request->geo_lng)) {
            $data['geo_lat'] = $request->geo_lat;
            $data['geo_lng'] = $request->geo_lng;
        } elseif (Auth::check() && $lastMovement = Auth::user()->getLastMapMovement()) {
            $lastMovement = $lastMovement;
            $data['geo_lat'] = $lastMovement->geo_lat;
            $data['geo_lng'] = $lastMovement->geo_lng;
            $data['zoom'] = $lastMovement->zoom;
        } else {
            $ipInfo = CommonHelper::getIpInfo();
            $data['geo_lat'] = isset($ipInfo) && !empty($ipInfo->latitude) ? $ipInfo->latitude : 48.87;
            $data['geo_lng'] = isset($ipInfo) && !empty($ipInfo->longitude) ? $ipInfo->longitude : 2.29;
        }
        
        if (!empty($request->country)) {
            $country = Country::where('id', $request->country)->first();
            $data['geo_lat'] = $country->geo_lat;
            $data['geo_lng'] = $country->geo_lng;
            $data['zoom'] = 4;
        }

        $data['geo_lat'] = !empty($data['geo_lat']) ? $data['geo_lat'] : 48.87;
        $data['geo_lng'] = !empty($data['geo_lng']) ? $data['geo_lng'] : 2.29;
        $data['zoom'] = !empty($data['zoom']) ? $data['zoom'] : (isset($request->zoom) ? $request->zoom : 3);

        return $data;
    }

}
