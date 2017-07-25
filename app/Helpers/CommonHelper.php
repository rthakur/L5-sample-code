<?php

namespace App\Helpers;
use App\Http\Controllers\Controller;
use App\Models\PropertyStatistics;
use App\Models\Language;
use App\Models\Property;
use App\Models\Country;
use App\Models\Currency;
use Auth, Carbon\Carbon;
use Session, Response, Config, App;
use Exception, Cache;

define('WITHOUT_ZIP_CODE', false);

class CommonHelper extends Controller
{
  
  public static function storePropertyStatistics($property, $type)
  {
      $selectedProperty = [
          'id' => $property->id, 
          'price' => $property->originalPrice(),
          'subject' => $property->texts->langSubject(), 
          'address' => $property->langAddress(), 
          'main_image'=> $property->main_image_url,
          'preview_image' => ($property->propertyPreviewImages)? $property->propertyPreviewImages->s3_url : ''
      ];
    
      Session::put('last_property_viewed',$selectedProperty);
      
      $ipInfo = self::getIpInfo();
      if(empty($ipInfo)) return 'fail';
    
      $propertyId = $property->id;
      $now = Carbon::now();
      $startOfDay = $now->copy()->startOfDay();
    
      $checkPreviousStats = PropertyStatistics::where([
                                                'property_id' => $propertyId,
                                                'ip' => $ipInfo->ip,
                                                'type' => $type
                                              ])
                                              ->whereBetween('created_at', [ $startOfDay, $now ]);
            
      $userId = Auth::check() ? Auth::id() : NULL;
      $lastPropertyId = PropertyStatistics::where('user_id', $userId);
      $lastPropertyId = $lastPropertyId->orderBy('id', 'desc')->pluck('property_id')->first();
      
      if($lastPropertyId == $propertyId) return $selectedProperty;
      
      if (!empty($checkPreviousStats))
          $checkPreviousStats->where('user_id', $userId)->delete();
        
      $propertyStatistics = new PropertyStatistics;
      $propertyStatistics->property_id = $propertyId;
      $propertyStatistics->user_id = Auth::check() ? Auth::id() : null;
      $propertyStatistics->ip = $ipInfo->ip;
      $propertyStatistics->geo_city = $ipInfo->city;
      $propertyStatistics->geo_country = $ipInfo->country_name;
      $propertyStatistics->geo_region = $ipInfo->region_name;
      $propertyStatistics->type = $type;
      $propertyStatistics->save();
      
      return $selectedProperty;
  }
  
  public static function getRecentProperties($limit)
  {
      $ipInfo = self::getIpInfo();
      $selectedProperty = new PropertyStatistics;
      
      if(!empty($ipInfo))
      {    
          $selectedProperty = $selectedProperty->where([
                                                'ip' => $ipInfo->ip,
                                                'type' => 1
                                              ]);            
      }
                                    
      if(Auth::check())
          $selectedProperty = $selectedProperty->where('user_id',Auth::id());
      if(\Request::segment(9))
          $selectedProperty = $selectedProperty->where('property_id','!=',\Request::segment(9));
          
      return $selectedProperty->join('property', 'property.id', 'property_statistics.property_id')
      ->whereRaw('property.main_image_url IS NOT NULL')
          ->where([
              ['property.main_image_url', '<>', ''],
              ['property.geo_lat', '<>', '0'],
              ['property.geo_lng', '<>', '0'],
              ['property.preview_mode', '1'],
              ['property.price', '<>', '0'],
              ['property.agency_id', '<>', NULL]
          ])->orderBy('property_statistics.id','desc')->groupBy('property_statistics.property_id')
            ->pluck('property_statistics.property_id')->take($limit)->toArray();
  }
  
  public static function getIpInfo($ip = null)
  {
      if (is_null($ip))
          $ip = $_SERVER['REMOTE_ADDR'];
      
      try {
          return json_decode(self::file_get_contents_curl("http://freegeoip.net/json/" . $ip));
      } catch(Exception $e) {
          \Log::error($e);  
      }
    
      return false;
  }
  
  public static function getAppCurrency($withSymbol = true, $returnCurrencyObj = false)
  {
      $appLang = App::getLocale() ? App::getLocale() : 'en';
      $name = 'language_currency_'. (Session::has('currency') ? Session::get('currency') : $appLang);
      if (Cache::has($name))
          $lang = Cache::get($name);
      else {
          $lang = Currency::find(Session::get('currency'));
          Cache::put($name, $lang, 60*24*30);   
      }
    
      if (empty($lang)) {
          $lang = Language::select('currencies.id', 'currencies.currency', 'currencies.symbol')
            ->where('country_code', $appLang)
            ->join('currencies', 'languages.currency_id', '=', 'currencies.id')
            ->first();
      }
      if(empty($lang))
      {
          $lang = Language::select('currencies.id', 'currencies.currency', 'currencies.symbol')
            ->where('country_code', 'en')
            ->join('currencies', 'languages.currency_id', '=', 'currencies.id')
            ->first();
      }
    
      return $returnCurrencyObj ? $lang : ( ($withSymbol ? $lang->symbol.' ' : ''). $lang->currency );
  }
  
  
  public static function cleanString($string)
  {
      $utf8 = array(
        '/[áàâãªä]/u'   =>   'a',
        '/[ÁÀÂÃÄ]/u'    =>   'A',
        '/[ÍÌÎÏ]/u'     =>   'I',
        '/[íìîï]/u'     =>   'i',
        '/[éèêë]/u'     =>   'e',
        '/[ÉÈÊË]/u'     =>   'E',
        '/[óòôõºö]/u'   =>   'o',
        '/[ÓÒÔÕÖ]/u'    =>   'O',
        '/[úùûü]/u'     =>   'u',
        '/[ÚÙÛÜ]/u'     =>   'U',
        '/ç/'           =>   'c',
        '/Ç/'           =>   'C',
        '/ñ/'           =>   'n',
        '/Ñ/'           =>   'N',
        '/–/'           =>   '-', // UTF-8 hyphen to "normal" hyphen
        '/[’‘‹›‚]/u'    =>   ' ', // Literally a single quote
        '/[“”«»„]/u'    =>   ' ', // Double quote
        '/ /'           =>   ' ', // nonbreaking space (equiv. to 0x160)
      );
    
      $string = preg_replace(array_keys($utf8), array_values($utf8), $string);
      $string = preg_replace('/\s/', ' ', $string);
      $string = str_replace(' - ','-',$string);
      $string = str_replace(["\'",'\"',"->'",'->"'], "",$string);
      return str_replace([' ', '/', "'"], '-', strtolower($string));
  }
  
  
 public static function propertyDetailPageURL($property)
 {
     $lang = App::getLocale();
     $type = (isset($property->type_name) && $property->type_name) ? self::cleanString(trans('common.'. $property->type_name)) : trans('seolinks.unknown');
     $country = ($property->country)? self::cleanString(trans('countries.' . $property->country->search_key)) : trans('common.country');
     $state = ($property->state)? self::cleanString(trans('states.' . $property->state->search_key)) : trans('common.states');
     $city = ($property->prop_city)? self::cleanString(trans('cities.' . $property->prop_city->search_key)) : trans('common.city');
     $agencyName = (isset($property->agency_name) && $property->agency_name) ? self::cleanString($property->agency_name) : trans('seolinks.unknown');
     $propertyId = isset($property->property_id) ? $property->property_id : $property->id;
    
    if (!empty($property->texts)) {
        if (isset($property->texts->{'subject_' . $lang}))
        $propertySubject = $property->texts->{'subject_' . $lang};
        else
        $propertySubject = $property->texts->{'subject_en'};
    } else {
        $propertySubject = '';
    }

    $propertySubject = self::cleanString($propertySubject);
    $propertyURL = '/'. $lang . '/' . trans('seolinks.buy') . '/' . trans('seolinks.property') . '/' . $type;
    $propertyURL .= '/' . $country . '/' . $state . '/' . $city . '/' . $agencyName . '/' . $propertyId . '/' . $propertySubject;
    return $propertyURL;
 }
 
 public static function propertyLangAddress($property)
 {
     $address = '';

     if ($property->street_address)
        $address .= $property->street_address. ', ';
    
    if ($property->city_id)
         $address .= trans('cities.' . $property->prop_city->search_key) . ', ';
    elseif($property->city)
        $address .= $property->city . ', ';
        
    if ($property->state_id)
        $address .= trans('states.' . $property->state->search_key) . ', ';
        
    if ($property->country_id)
        $address .= trans('countries.' . $property->country->search_key) . ' ';
    
    return self::filterString($address);
 }
 
 public static function filterString($string)
 {
     $string = str_replace(["\'", '\"'], "'", $string);
     $string = str_replace(["->'", '->"'], "", $string);
     return $string;
 }
 
 public static function propertyHistory()
 {
     return Property::join('property_statistics', function($join){
                            $join->on('property_statistics.property_id','=','property.id')
                                 ->where('property_statistics.user_id', Auth::id());
                             })->take(10)->orderBy('property_statistics.id','desc')->get();
 }
 
 
 public static function checkFiltersExist($request)
 {
     $price = array_where($request->all(), function ($value, $key) {
         return strpos($key, 'price') > -1;
     });
     
     $size = array_where($request->all(), function ($value, $key) {
         return strpos($key, 'rooms') > -1 || strpos($key, 'living') > -1 || strpos($key, 'gardens') > -1;
     });
     
     return $request->has('type') || count($price) || count($size) || $request->has('size') || $request->has('proximities') || $request->has('features') || $request->has('views');
 }
 
}
