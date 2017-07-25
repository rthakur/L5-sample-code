<?php

namespace App\Helpers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Models\PropertyTexts;
use App\Models\Property;
use App\Models\PropertyFeatures;
use App\Models\PropertyView;
use App\Models\PropertyProximities;
use App\Models\PropertyImages;
use App\Models\PropertyTypes;
use App\Models\City;

use Auth, Carbon\Carbon, Session, Redirect, App, Cache;


class PropertyEditHelper extends Controller
{
    
    public static function updateBasicInfo(Request $request)
    {
        $user = Auth::user();
        $validateVars = $request->all();
        $propertyId = !empty($request->property_id) ? base64_decode($request->property_id) : '';
        $validateVars['property_id'] = $propertyId;
        
        $validate = validator($validateVars, [
            'property_id' => 'exists:property,id',
            'type' => 'required|exists:property_types,search_key',
            'title' => 'required|max:255',
            'lang' => 'required|exists:languages,country_code,has_lang,1',
        ]);
        
        
        if ($validate->fails()) {
            return back()->withErrors($validate)->withInput();
        }
        
        $property = Property::firstOrCreate(['id' => $propertyId]);
        
        if (empty($propertyId)) {
            
            $property->preview_mode = 0;
            
            if ($user->role_id == '4') //For agency user
                $property->agency_id = $user->agency_id;

            if ($user->role_id == '3') {//For agent users
                $property->agent_id = $user->id;
                $property->agency_id = $user->agency ? $user->agency->id : null;
            }
        }
        
        if ( $property->type != ($propertyType = PropertyTypes::where('search_key', $request->type)->first()) ) {
            $property->property_type_id = $propertyType->id;
        }
        $propertyTexts = PropertyTexts::firstOrCreate(['property_id' => $property->id]);
        $propertyTexts->{'subject_' . $request->lang} = $request->title;
        $propertyTexts->save();
        
        if ($request->mark_as_sold) {
            $property->mark_as_sold = 1;
            $property->sold_price_currency_id = $request->sold_price_currency;
            $property->sold_price = $request->sold_price;
        } else {
            $property->mark_as_sold = 0;
            $property->sold_price ='';
        }
        
        $property->save();
        
        $nextPage = ($property->preview_mode && $property->getEditCompleted('description'))?  'basic_info' : 'description';
        
        return redirect(SITE_LANG .'/property/'. $property->id .'/edit/'.$nextPage);
    }
    
    public static function updateDescription(Request $request)
    {
        $validateVars = $request->all();
        $propertyId = base64_decode($request->property_id);
        $validateVars['property_id'] = $propertyId;
        
        $validate = validator($validateVars, [
            'property_id' => 'required|exists:property,id',
            'description' => 'required',
            'lang' => 'required|exists:languages,country_code,has_lang,1',
        ]);
        
        if ($validate->fails()) {
            return back()->withErrors($validate)->withInput();
        }
        
        $propertyTexts = PropertyTexts::firstOrCreate(['property_id' => $propertyId]);
        $propertyTexts->{'description_' . $request->lang} = $request->description;
        $propertyTexts->save();
        
        $property = Property::find($propertyId);
        
        $nextPage = ($property->preview_mode && $property->getEditCompleted('location'))?  'description' : 'location';

        return redirect(SITE_LANG .'/property/'. $propertyId .'/edit/'. $nextPage);
    }
    
    public static function updateLocation(Request $request)
    {
        $validateVars = $request->all();
        $propertyId = base64_decode($request->property_id);
        $validateVars['property_id'] = $propertyId;
        
        $validate = validator($validateVars, [
            'property_id' => 'required|exists:property,id',
            'latitude' => 'numeric',
            'longitude' => 'numeric',
            'zip_code' => 'min:4|max:10|regex:/^[ A-Z0-9]+$/',
            'country' => 'required|exists:countries,id',
        ]);
        
        if ($validate->fails()) {
            return back()->withErrors($validate)->withInput();
        }
        
        $property = Property::find($propertyId);
        
        if ($request->country) {
            $property->country_id = $request->country;
        }
        
        if ($property->zip_code != $request->zip_code) {
            $property->zip_code = $request->zip_code;
        }

        if ($property->street_address != $request->street_address) {
            $property->street_address = $request->street_address;
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
        
        $property->save();
        
        $nextPage = ($property->preview_mode && $property->getEditCompleted('pricing'))?  'location' : 'pricing';
        
        return redirect(SITE_LANG .'/property/'. $propertyId .'/edit/'. $nextPage);
    }

    public static function updatePricing(Request $request)
    {
        $validateVars = $request->all();
        $propertyId = base64_decode($request->property_id);
        $validateVars['property_id'] = $propertyId;
        
        $rules = [
            'property_id' => 'required|exists:property,id',
            'total_living_area' => 'numeric',
            'total_garden_area' => 'numeric',
            'currency' => 'required|exists:currencies,id',
            'price' => 'required|numeric',
            'rooms' => 'numeric',
            'monthly_fee' => 'numeric',
            'monthly_fee_currency' => 'required_with:monthly_fee|exists:currencies,id'
        ];

        $validate = validator($validateVars, $rules);
        
        if ($validate->fails()) {
            return back()->withErrors($validate)->withInput();
        }
        
        $property = Property::find($propertyId);
        
        $property->price_currency_id = $request->currency;
        $property->price = $request->price;
        
        $property->monthly_fee_currency_id = ($request->monthly_fee) ? $request->monthly_fee_currency : null;
        $property->monthly_fee = $request->monthly_fee;
        
        $areaFields = [
            'rooms' => 'rooms', 'total_living_area_type' => 'total_living_area',
            'total_garden_area_type' => 'total_garden_area', 'build_year' => 'build_year'
        ];
        
        foreach ($areaFields as $key => $field) {
            $property->$field = $request->$field;
            $property->$key = $request->$key;
        }
        
        $property->save();
        
        $nextPage = ($property->preview_mode && $property->getEditCompleted('features')) ?  'pricing' : 'features';
        
        return redirect(SITE_LANG .'/property/'. $propertyId .'/edit/'. $nextPage);
    }
    
    public static function updateFeatures(Request $request)
    {
        $validateVars = $request->all();
        $propertyId = base64_decode($request->property_id);
        $validateVars['property_id'] = $propertyId;
        
        $validate = validator($validateVars, [
            'property_id' => 'required|exists:property,id',
            'features' => 'required|array',
            'features.*' => 'exists:features,id',
        ]);
        
        if ($validate->fails()) {
            return back()->withErrors($validate)->withInput();
        }

        PropertyFeatures::where('property_id', $propertyId)
            ->whereNotIn('feature_id', $request->features)
            ->delete();

        foreach ($request->features as $featureId => $feature) {
            PropertyFeatures::firstOrCreate([
                'property_id' => $propertyId,
                'feature_id' => $feature
            ]);
        }

        $property = Property::find($propertyId);
        $nextPage = ($property->preview_mode && $property->getEditCompleted('views'))?  'features' : 'views';
        
        return redirect(SITE_LANG .'/property/'. $propertyId .'/edit/'. $nextPage);
    }
    
    public static function updateViews(Request $request)
    {
        $validateVars = $request->all();
        $propertyId = base64_decode($request->property_id);
        $validateVars['property_id'] = $propertyId;
        
        $validate = validator($validateVars, [
            'property_id' => 'required|exists:property,id',
            'views' => 'required|array',
            'views.*' => 'exists:views,id',
        ]);
        
        if ($validate->fails()) {
            return back()->withErrors($validate)->withInput();
        }

        PropertyView::where('property_id', $propertyId)
            ->whereNotIn('view_id', $request->views)
            ->delete();

        foreach ($request->views as $view) {
            PropertyView::firstOrCreate([
                'property_id' => $propertyId,
                'view_id' => $view
            ]);
        }
        $property = Property::find($propertyId);
        $nextPage = ($property->preview_mode && $property->getEditCompleted('proximity'))?  'views' : 'proximity';
        
        return redirect(SITE_LANG .'/property/'. $propertyId .'/edit/'. $nextPage);
    }
    
    public static function updateProximity(Request $request)
    {
        $validateVars = $request->all();
        
        $propertyId = base64_decode($request->property_id);
        $validateVars['property_id'] = $propertyId;
        
        $validate = validator($validateVars, [
            'property_id' => 'required|exists:property,id',
            'proximities' => 'required|array',
            'proximities.*' => 'exists:proximities,id',
        ]);
        
        if ($validate->fails()) {
            return back()->withErrors($validate)->withInput();
        }

        PropertyProximities::where('property_id', $propertyId)
            ->whereNotIn('proximity_id', $request->proximities)
            ->delete();

        foreach ($request->proximities as $proximity) {
            PropertyProximities::firstOrCreate([
                'property_id' => $propertyId,
                'proximity_id' => $proximity
            ]);
        }
        $property = Property::find($propertyId);
        $nextPage = ($property->preview_mode && $property->getEditCompleted('gallery'))?  'proximity' : 'gallery';
        
        return redirect(SITE_LANG .'/property/'. $propertyId .'/edit/'. $nextPage);
    }
    
    public static function updateGallery(Request $request)
    {
        $validateVars = $request->all();
        $propertyId = base64_decode($request->property_id);
        $validateVars['property_id'] = $propertyId;
        
        $validate = validator($validateVars, [
            'property_id' => 'required|exists:property,id',
            'images.*' => 'required|file|image'
        ]);
        
        if ($validate->fails()) {
            return response(['errors' => $validate->getMessageBag()->all()], 403);
        }
        
        $property = Property::find($propertyId);
        
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
            $propertyImage->property_id = $propertyId;

            $ctrl = new Controller;
            $s3Url = $ctrl->uploadFileToS3($image, $s3_path);
            $propertyImage->s3_url = $s3Url;

            if (empty($property->main_image_url)) {
                $property->main_image_url = $s3Url;
                $property->save();
                $propertyImage->main_image = 1;
            }

            $propertyImage->s3_path = str_replace('//cdn.miparo.com/', '', $s3Url);
            $propertyImage->save();
        }
        
        $data['property'] = Property::find($propertyId);
        return view('common.property.images', $data);
    }
    
    public static function updateChooseAgent(Request $request)
    {
        $validateVars = $request->all();
        
        $propertyId = base64_decode($request->property_id);
        $validateVars['property_id'] = $propertyId;
        
        $validate = validator($validateVars, [
            'property_id' => 'required|exists:property,id',
            'agent' => 'exists:users,id',
            'preview_mode' => ['required', Rule::in([1, 2, 3])]
        ]);
        
        if ($validate->fails()) {
            return back()->withErrors($validate)->withInput();
        }
        
        $property = Property::find($propertyId);

        $property->agent_id = ($request->agent) ? $request->agent : null;
        $property->preview_mode = $request->preview_mode;
        $property->save();
        
        return redirect(SITE_LANG .'/property/'. $propertyId .'/edit/choose_agent');
    }
    
}