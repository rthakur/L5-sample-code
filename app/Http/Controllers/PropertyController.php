<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Features;
use App\Models\Property;
use App\Models\PropertyFeatures;
use App\Models\BookmarkedProperty;
use App\Models\PropertyImages;
use App\Models\PropertyProximities;
use App\Models\Proximities;
use App\Models\Services;
use App\Models\PropertyStatistics;
use App\Helpers\PropertySearchHelper;
use App\Models\Language;
use App\Models\City;
use App\Models\View as PropView;
use App\Models\PropertyView;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Estateagency;
use App\Models\PropertyTexts;
use App\Models\SyncronizePlanRequest;
use App\Models\PropertyTypes;
use App\User;
use App\Helpers\CommonHelper;
use App\Helpers\PropertyEditHelper;
use Auth, Carbon\Carbon, Session, Redirect, App, Cache;

const DETAIL_PAGE_TYPE = 1;
const MAP_PAGE_TYPE = 2;

class PropertyController extends Controller
{
    public function getIndex(Request $request, $buy, $country = null)
    {
        if (!empty($request->country)) {
            Session::put('filtersDataSet', array_add(Session::get('filtersDataSet'), 'country', $request->country));
        }
        
        if (empty($request->country) && empty($request->city) && !empty($request->all())) {
            Session::put('filtersDataSet', $request->except('page'));
        }
        
        if (!empty($request->city)) {
            Session::put('filtersDataSet', array_add(Session::get('filtersDataSet'), 'city', $request->city));
        }
        
        if (empty($request->except('page')) && Session::has('filtersDataSet')) {
            $cacheKey = 'list_property_'. md5(json_encode(Session::get('filtersDataSet')));
        } else {
            $cacheKey = 'list_property_'. md5(json_encode($request->all()));
        }
        
        if ($request->ajax()) {
            
            if ($request->site_lang) {
                App::setLocale($request->site_lang);
            }
        
            if (Cache::has($cacheKey)) {
                $data = Cache::get($cacheKey);
            } else {
                
                $properties = Property::mainImagePropertiesFilter();
                $properties = $properties->selectRaw(
                        'property.*,property_types_name.name as type_name,
                        estate_agencies.public_name as agency_name'
                    )
                    ->leftJoin(
                        'property_types as property_types_name',
                        'property.property_type_id','=','property_types_name.id'
                    )
                    ->leftJoin('estate_agencies', 'property.agency_id','=','estate_agencies.id');

                if ($request->geo_lat && $request->geo_lng) {
                    $data['geo_lat'] = $request->geo_lat;
                    $data['geo_lng'] = $request->geo_lng;
                    $data['zoom'] = isset($request->zoom) ? $request->zoom : 3;
                }

                if (CommonHelper::checkFiltersExist($request)) {
                    $properties = PropertySearchHelper::applySearchFilters($request, $properties);
                }

                foreach(['country', 'city', 'area'] as $field) {
                    
                    if (!empty($request->$field)) {
                        $properties = $properties->where('property.'. $field .'_id',$request->$field);
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
                            
                            $properties = $properties->whereRaw("( 
                                6764 
                                * acos( 
                                    cos( radians(".$request->geo_lat.") ) 
                                    * cos( radians( property.geo_lat ) ) 
                                    * cos( 
                                        radians( property.geo_lng ) 
                                        - radians(".$request->geo_lng.") 
                                    ) 
                                    + sin( radians(".$request->geo_lat.") ) 
                                    * sin( radians( property.geo_lat ) ) 
                                ) ) < ". $distance);
                            
                            $propertiesCount = $properties->get()->count();

                            if ($propertiesCount) {
                                break;
                            }
                        }
                    }
                
                }


                if (isset($request->sorting) && !empty($request->sorting)) {

                    $arr = [
                        1 => ['property.eur_price', 'asc'],
                        2 => ['property.eur_price', 'desc'],
                        3 => ['property.created_at', 'desc']
                    ];

                    if (isset($arr[$request->sorting][0], $arr[$request->sorting][1])) {
                        $properties = $properties->orderBy($arr[$request->sorting][0], $arr[$request->sorting][1]);
                    }

                    $data['sorting'] = $request->sorting;
                }

                $data['properties'] = $properties->paginate(10);

                Cache::put($cacheKey, $data, 60*24);
                
            }
        
            $data['filterString'] = PropertySearchHelper::getSelectedFilterString($request);
        
            return view('front.property.list', array_merge($data, ['count' => $data['properties']->total()]));
            
        } else {
            
            $data = [];
            
            $data['search_type_property'] = 'active';
            $data['address'] = isset($request->address) ? $request->address : null;
            $data['filterString'] = PropertySearchHelper::getSelectedFilterString($request);
            $data['with_search_bar'] = true;
            $data['view_type'] = 'List';
            $data['share_link'] = URL(SITE_LANG .'/buy/property');
            $data['filtersData'] = PropertySearchHelper::getFilterData($request);
            
            return view('front.property.index', $data);
        }

    }
    
    public function autocompleteupdatecitycountry(Request $request)
    {
        if(!empty($request->address))
            \Session::put('address',$request->address);
        if(!empty($request->country))
            $country = Country::where('name_en',$request->country)->first();
        if(isset($country))
            $data['country'] = $country->id;
        if(!empty($request->city))
            $city = City::where('name_en',$request->city)->first();
        if(isset($city))
            $data['city'] = $city->id;
        
        return $data;
        
    }

    public function clearSessionStoredFilter()
    {
        if (Session::has('filtersDataSet')) {
            Session::forget('filtersDataSet');
        }
        
        if (Session::has('address')) {
            Session::forget('address');
        }
    }
    
    public function adminIndex(Request $request)
    {
        $data['countries'] = Country::select('id','name_en')->orderBy('name_en')->get();
        $agencies = Estateagency::select('id','public_name');
        $agent = User::where('role_id', 3);
        $properties = new Property;

        if (isset($request->country) && !empty($request->country)) {
            $properties = $properties->where('country_id', $request->country);
            $agencies = $agencies->where('country_id', $request->country);
            $agent = $agent
                ->join('estate_agencies', 'estate_agencies.id', 'users.agency_id')
                ->where('estate_agencies.country_id', $request->country);
            $data['activecountryId'] = $request->country;
        }

        if (isset($request->agency) && !empty($request->agency)) {
            $properties = $properties->where('agency_id', $request->agency);
            $agent = $agent->where('agency_id', $request->agency);
            $data['activeagencyId'] = $request->agency;
        }

        if (isset($request->agent) && !empty($request->agent)) {
            $properties = $properties->where('agent_id',$request->agent);
            $data['activeagentId'] = $request->agent;
        }

        if ($request->sort) {

            if (!$request->sort_type || $request->sort_type == 'title') {
                if (App::getLocale() == 'en') {
                    $properties = $properties->orderBy('subject_en', $request->sort);
                } else {
                    $properties = $properties
                        ->orderBy('subject_'.App::getLocale(), $request->sort)
                        ->orderBy('subject_en', $request->sort);
                }
            } else {
                $properties = $properties->orderBy($request->sort_type, $request->sort);
            }

            $data['sort_type'] = $request->sort_type ? $request->sort_type : 'title';
            $data['sort'] = $request->sort == 'asc' ? 'desc' : 'asc';
        }

        $data['agencies'] = $agencies->orderBy('public_name')->get();
        $data['agents'] = $agent->orderBy('name')->get();
        $data['properties'] = $properties->paginate(10);

        return view('admin.property.index', $data);
    }

    public function filterProperty(Request $request)
    {
        $agent = User::where('role_id', 3);

        if (isset($request->country) && !empty($request->country) && empty($request->agency)) {
            $data['agencies'] = Estateagency::
                select('id','public_name')
                ->where('country_id', $request->country)
                ->orderBy('public_name')
                ->get();

            $agent = $agent
                ->join('estate_agencies', 'estate_agencies.id', 'users.agency_id')
                ->where('estate_agencies.country_id', $request->country);
        }

        if (isset($request->agency) && !empty($request->agency)) {
            $agent = $agent->where('agency_id',$request->agency);
        }

        $data['agents'] = $agent->orderBy('name')->get();
        return $data;
    }
    
    public function getEditProperty(Request $request, $propertyId, $page = null)
    {
        $property = Property::find($propertyId);

        if (!$property || !Auth::user()->checkPropertyAccess($propertyId)) {
            return back()->with('notification', trans('common.AllowPropertyEditMsg'));
        }

        $data['property'] = $property;
        $data['features'] = Features::all();
        $data['proximities'] = Proximities::all();
        $data['views'] = PropView::all();
        $data['allCurrency'] = Currency::get_exchangeable();
        $data['page'] = $page ?: 'basic_info';

        if (Auth::user()->role_id == '1') {
            return view('admin.property.edit', $data);
        }

        if (Auth::user()->role_id == '3' && $property->agent_checked == '0') {
            $property->agent_checked = 1;
            $property->save();
        }
        
        if (!\View::exists('common.property.'. $data['page'])) {
            return redirect(SITE_LANG.'/404');
        }
        
        return view('common.property.edit', $data);
        
        // return view('profile.agency.property.edit', $data);
    }
    
    public function updatePropertyInfo(Request $request)
    {
        if (method_exists(PropertyEditHelper::class, 'update'. studly_case(base64_decode($request->edit)))) 
            return PropertyEditHelper::{'update'. studly_case(base64_decode($request->edit))}($request);
    
        return back();
    }

    public function getDetail($buy, $property, $type, $country, $area, $city, $agencyName, $id, $subject = null)
    {
        $cacheKey = 'property_view_'.$id;
        $property = Property::find($id);

        Session::put('viewedPropertyId', $id);

        if (!$property) {
            return view('errors.404');
        }

        if (Cache::has($cacheKey)) {
            $data = Cache::get($cacheKey);
        } else {
            $property = Property::select('property.*',
                'property_types_name.name as type_name',
                'estate_agencies.public_name as agency_name',
                'property.city_id as city_id',
                'property.state_id as state_id',
                'property.country_id as country_id'
                )
                ->join('property_types as property_types_name', 'property.property_type_id','=','property_types_name.id')
                ->join('estate_agencies', 'property.agency_id','=','estate_agencies.id')
                ->whereRaw('property.id = '. $id .' and (property.preview_mode = 1 or property.preview_mode = 2)')
                ->first();

            if (empty($property)) {
                return view('errors.404')->with('message',trans('common.PropertyNotFound'));
            }

            $services = Services::
                join('property_services','property_services.service_id', '=', 'services.id')
                ->where('property_services.property_id',$id)
                ->get();

            $features = Features::
                select('features.*')
                ->join('property_features','property_features.feature_id', '=', 'features.id')
                ->where('property_features.property_id',$id)
                ->where('deleted_at', null)->get();

            $proximities = Proximities::
                select('proximities.*','property_proximity.distance as distance')
                ->join('property_proximity','property_proximity.proximity_id', '=', 'proximities.id')
                ->where('property_proximity.property_id',$id)
                ->where('deleted_at', null)->get();

            $data['property'] = $property;
            $data['services'] = $services;
            $data['features'] = $features;
            $data['proximities'] = $proximities;
            $data['with_search_bar'] = true;

            if (isset($property->prop_city) && isset($property->prop_city->weather)) {
                $data['weathers'] = $property->prop_city->weather()->groupBy('month')->get();
            } else {
                $data['weathers'] = null;
            }

            Cache::put($cacheKey, $data, 60*24);
        }
        
        CommonHelper::storePropertyStatistics($data['property'], DETAIL_PAGE_TYPE);
        return view('front.property.detail', $data);
    }
    
    public function getSubmit($id = null)
    {
        $user = Auth::user();
        
        if (!$user->checkAllowToAddProperty())
        {
            if($user->role_id == '4')
             return redirect(SITE_LANG.'/account/agency/subscription')
                ->with('notification', trans('common.AddPropertyMsg'));
            else
              return redirect(SITE_LANG.'/account/agent/myproperties');
        }
        
        $data['features'] = Features::all();
        $data['proximities'] = Proximities::all();
        $data['views'] = PropView::all();
        $data['allCurrency'] = Currency::get_exchangeable();
        $data['page'] = 'basic_info';
        
        //Edit property for superadmin
        if (Auth::user()->role_id == '1') 
            return view('admin.property.edit', $data);
        
        if (!\View::exists('common.property.'. $data['page'])) 
            return redirect(SITE_LANG.'/404');
        
        return view('common.property.edit', $data);
    }

    public function saveFeatures(Request $request)
    {
        if (empty($request->feature) && !is_array($request->feature)) {
            return back()->with('tab', 'features');
        }

        PropertyFeatures::where('property_id', $request->id)
            ->whereNotIn('feature_id', $request->feature)
            ->delete();

        foreach ($request->feature as $featureId => $feature) {
            PropertyFeatures::firstOrCreate([
                'property_id' => $request->id,
                'feature_id' => $featureId
            ]);
        }

        return back()->with([
            'tab' => 'features',
            'success' => trans('common.SuccessfullyUpdated')
        ]);
    }

    public function saveProximities(Request $request)
    {
        if (empty($request->proximity) && !is_array($request->proximity)) {
            return back()->with('tab', 'proximity');
        }

        PropertyProximities::where('property_id', $request->id)
            ->whereNotIn('proximity_id', $request->proximity)
            ->delete();

        foreach ($request->proximity as $proximityId => $proximity) {
            $propertyProximities = PropertyProximities::firstOrCreate([
                'property_id' => $request->id,
                'proximity_id' => $proximityId
            ]);
        }

        return back()->with([
            'tab' => 'proximity',
            'success' => trans('common.SuccessfullyUpdated')
        ]);
    }

    public function saveViews(Request $request)
    {
        if (empty($request->view) && !is_array($request->view)) {
            return back()->with('tab', 'views');
        }

        PropertyView::where('property_id', $request->id)
            ->whereNotIn('view_id', $request->view)
            ->delete();

        foreach ($request->view as $viewId => $view) {
            PropertyView::firstOrCreate([
                'property_id' => $request->id,
                'view_id' => $viewId
            ]);
        }

        return back()->with([
            'tab' => 'views',
            'success' => trans('common.SuccessfullyUpdated')
        ]);
    }

    public function deleteProperty($id)
    {
        $property = Property::find($id);

        if (!$property) {
            return redirect(SITE_LANG .'/403');
        }
        
        if (Auth::user()->allowToDeleteProperty($property)) {
            $property->last_activity_user = Auth::id();
            $property->save();
            
            $property->delete();

            $propertyRelatedModels = [
                'PropertyFeatures','PropertyImages','PropertyProximities','PropertyStatistics',
                'PropertyTexts','PropertyView','BookmarkedProperty','Search','ShareLink'
            ];

            foreach ($propertyRelatedModels as $key => $propertyRelatedModel) {
                app('App\Models\\' . $propertyRelatedModel)->where('property_id',$id)->delete();
            }
        }

        return back();
    }

    public function addBookmark(Request $request)
    {
        $bookmarked = BookmarkedProperty::where([
            'user_id' => Auth::id(),
            'property_id' => $request->propertyId
        ])->first();

        if ($bookmarked) {
            $bookmarked->delete();
        } else {
            $bookmarked = new BookmarkedProperty;
            $bookmarked->user_id = Auth::id();
            $bookmarked->property_id = $request->propertyId;
            $bookmarked->save();
        }
    }

    public function viewProperty($id)
    {
        $property = Property::find($id);

        if ($property) {
            return redirect($property->detailPageURL());
        }

        return redirect('/');
    }
    
    public function updateBasicInfo(Request $request)
    {
        $validate = validator($request->all(), [
            'type' => 'required',
            'title' => 'required|max:255',
            'price' => 'required|numeric',
            'description' => 'required',
            'address' => 'required',
            'latitude' => 'numeric',
            'longitude' => 'numeric',
            'zip' => 'min:4|max:10|regex:/(?i)^[a-z0-9][a-z0-9\- ]{0,10}[a-z0-9]$/',
            // 'zip' => 'min:4|max:10|regex:/^[ A-Z0-9]+$/',
            'currency' => 'required|exists:currencies,id',
            'build_year' => 'size:4',
            'country' => 'required|exists:countries,id',
            'rooms' => 'required|numeric',
            'sold_price_currency' => 'exists:currencies,id'
        ]);
        
        if($request->monthly_fee)
        {
            $validate = validator($request->all(), [
                'monthly_fee_currency' => 'required|exists:currencies,id',
            ]);
        }
        
        if ($validate->fails()) {
            return back()->withErrors($validate)->withInput();
        }
        $property = Property::create();
        
        Cache::flush();
        
        if (Cache::has('property_view_'.$request->property_id))
            Cache::forget('property_view_'.$request->property_id);
        
        $user = Auth::user();
        $language = $request->lang;
        $property->property_type_id = $request->type;

        $propertyTexts = PropertyTexts::firstOrCreate(['property_id' => $property->id]);
        $propertyTexts->{'subject_' . $language} = $request->title;
        $propertyTexts->{'description_' . $language} = $request->description;
        $propertyTexts->save();

        $property->price_currency_id = $request->currency;
        $property->price = $request->price;

        $property->rooms = $request->rooms;

        if ($request->country) {
            $property->country_id = $request->country;
        }

        if ($property->zip_code != $request->zip) {
            $property->zip_code = $request->zip;
        }

        if ($property->street_address != $request->street_address) {
            $property->street_address = $request->street_address;
        }

        if ($request->monthly_fee) {
            $property->monthly_fee = $request->monthly_fee;
        }

        if ($request->monthly_fee_currency) {
            $property->monthly_fee_currency_id = ($request->monthly_fee) ? $request->monthly_fee_currency : null;;
        }

        $cityId = City::findCityByName($request->city, $request->country);
        $property->city_id = $cityId ?: null;
        $property->city = $request->city;

        if ($request->latitude) {
            $property->geo_lat = $request->latitude;
        }

        if ($request->longitude) {
            $property->geo_lng = $request->longitude;
        }

        $property->allow_user_rating = ($request->allow_user_rating) ? 1 : '';

        if ($request->mark_as_sold) {
            $property->mark_as_sold = 1;
            $property->sold_price_currency_id = $request->sold_price_currency;
            $property->sold_price = $request->sold_price;
        } else {
            $property->mark_as_sold = 0;
            $property->sold_price ='';
        }

        if ($request->total_living_area) {
            $property->total_living_area = $request->total_living_area;
            $property->total_living_area_type = $request->total_living_area_type;
        }

        if ($request->total_garden_area) {
            $property->total_garden_area = $request->total_garden_area;
            $property->total_garden_area_type = $request->total_garden_area_type;
        }

        $property->preview_mode = $request->preview_mode;
        $property->build_year = isset($request->build_year) ? $request->build_year : NULL;
        $property->save();

        if ($request->property_id) {
            return back();
        } else {

            if ($user->role_id == '4') //For agency user
                $property->agency_id = $user->agency_id;

            if ($user->role_id == '3') {//For agent users
                $property->agent_id = $user->id;
                $property->agency_id = $user->agency ? $user->agency->id : null;
            }

            $property->save();
            return redirect(SITE_LANG . "/property/" . $property->id . "/edit");
        }

    }

    public function savePropertyImages(Request $request)
    {
        $validate = validator($request->all(), [
            'property_id' => 'required|exists:property,id',
            'images.*' => 'required|file|image'
        ]);

        if ($validate->fails()) {
            return response(['errors' => $validate->getMessageBag()->all()], 403);
        }

        $property = Property::find($request->property_id);

        if ($property) {

            foreach ($request->file('images') as $image) {
                $agency = $property->agency()->first();

                if ($agency) {
                    $s3_path = str_replace('\'', '-', str_replace(' ', '-', strtolower($agency->public_name))) . '/';
                } else {
                    $s3_path = 'property/';
                }

                $s3_path .= $property->id . '/';
                $s3_path .= md5(time() . microtime()) . '.' . $image->getClientOriginalName();


                $propertyImage = new PropertyImages;
                $propertyImage->property_id = $request->property_id;

                $s3Url = $this->uploadFileToS3($image, $s3_path);
                $propertyImage->s3_url = $s3Url;

                if (empty($property->main_image_url)) {
                    $property->main_image_url = $s3Url;
                    $property->save();
                    $propertyImage->main_image = 1;
                }

                $propertyImage->s3_path = str_replace('//cdn.miparo.com/', '', $s3Url);
                $propertyImage->save();
            }
        }

        $data['property'] = Property::find($request->property_id);
        return view('common.property.edit.images', $data);
    }

    public function deletePropertyImage(Request $request)
    {
        $validate = validator($request->all(), [
            'image_id' => 'required|exists:property_images,id',
            'property_id' => 'required|exists:property,id'
        ]);

        if ($validate->fails()) {
            return response(['errors' => $validate->getMessageBag()->all()], 403);
        }

        $image = PropertyImages::find($request->image_id);
        $checkMainImage = $image->main_image;
        $this->s3Delete($image->s3_url);
        $image->delete();

        $property = Property::find($request->property_id);

        if ($checkMainImage == 1) {
            $imageS3Url = null;
            $nextImage = PropertyImages::where('id', $request->image_id)->first();

            if (!empty($nextImage)) {
                $nextImage->main_image = 1;
                $imageS3Url = $nextImage->s3_url;
                $nextImage->save();
            }

            
            $property->main_image_url = $imageS3Url;
            $property->save();
        }

        $data['property'] = $property;
        return view('common.property.images', $data);
    }

    public function setMainPropertyImage(Request $request)
    {
        $validate = validator($request->all(), [
            'image_id' => 'required|exists:property_images,id',
            'property_id' => 'required|exists:property,id'
        ]);

        if ($validate->fails()) {
            return response(['errors' => $validate->getMessageBag()->all()], 403);
        }

        propertyImages::where('property_id', $request->property_id)
            ->where('main_image', '1')
            ->update(['main_image' => '0']);

        $image = propertyImages::where('property_id', $request->property_id)
            ->find($request->image_id);

        $image->main_image = 1;
        $image->save();

        $this->setPropertyMainImageUrl($image->s3_url, $request->property_id);

        $data['property'] = Property::find($request->property_id);
        return view('common.property.images', $data);
    }

    public function getFeaturesJson($type = null)
    {
      $features = Features::select('name','search_key')->get();
      if ($type == 'json') return $features;

      echo '<pre>';
      print_r(array_pluck($features,'name','search_key'));
    }

    public function getProximitiesJson($type = null)
    {
      $proximities = Proximities::select('name','search_key')->get();

      if ($type == 'json') return $proximities;
      echo '<pre>';
      print_r(array_pluck($proximities,'name','search_key'));
    }

    public function saveViewLog(Request $request)
    {
        $lang = str_replace('/','',$request->lang);
        App::setLocale($lang);

        if ($request->currency) {
            Session::put('currency', $request->currency);
        }

        $property = Property::find( $request->property_id );

        if ($property) {
            $propertyJson = CommonHelper::storePropertyStatistics($property, MAP_PAGE_TYPE);
            $propertyHtml = view('common.property.selected',['property' => $property]);
            return ['html' => $this->_minify_html($propertyHtml), 'json' => $propertyJson];
        }
    }

    protected function setPropertyMainImageUrl($url, $propertyId)
    {
        $property = Property::find($propertyId);
        $property->main_image_url = $url;
        $property->save();
    }

    private function _minify_html($html)
    {
        $search = array(
        '/\>[^\S ]+/s',  // strip whitespaces after tags, except space
        '/[^\S ]+\</s',  // strip whitespaces before tags, except space
        '/(\s)+/s'       // shorten multiple whitespace sequences
        );

        $replace = array('>', '<', '\\1');
        $html = preg_replace($search, $replace, $html);
        return str_replace('> <', '><',$html);
    }

    public function getAllPropertyImages($propertyId)
    {
        $property = Property::find($propertyId);
        $data['with_search_bar'] = true;
        $data['property'] = $property;

        if ($property) {
            return view('front.property.all_images', $data);
        }

        return redirect(SITE_LANG);
    }

}
