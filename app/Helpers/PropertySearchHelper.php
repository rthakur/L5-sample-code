<?php

namespace App\Helpers;

use Session, Cache, App; 
use App\Models\PropertyFeatures;
use App\Models\PropertyProximities;
use App\Models\PropertyView;
use App\Models\View as PrpView;
use App\Models\Property;
use App\Models\SearchStatistics;
use App\Models\PropertyTypes;
use App\Models\Proximities;
use App\Models\Features;
use App\Models\Country;
use App\Models\City;
use App\Models\Currency;
use App\Models\Language;
use App\Models\CurrencyRates;

class PropertySearchHelper
{
    public static function applySearchFilters($request, $properties)
    {
      $properties = self::typeFilter($request->type, $properties);//Filter search property type
      $properties = self::priceFilter($request, $properties);//Filter search property price
      $properties = self::sizeFilter($request, $properties);//Filter search property size
      $properties = self::viewsFilter($request->views, $properties);//Filter search views
      $properties = self::proximitiesFilter($request->proximities, $properties);//Filter search proximities
      $properties = self::featuresFilter($request->features, $properties);//Filter search features
      return $properties;
    }
    
    public static function typeFilter($type, $properties)
    {
        if (!empty($type) && is_array($type) && count($type) ) {
            $properties = $properties->join('property_types', function($join) use($type){
                $join->on('property.property_type_id', '=', 'property_types.id')->whereIn('property_types.search_key', $type);
            });
        }
        return $properties;
    }
  
    public static function priceFilter($request, $properties)
    {
        if (!empty($request->min_price) || !empty($request->max_price)) {
            $currencyId = Session::has('currency') ? Session::get('currency') : ($request->site_curr ?: 10);
            $exchangeRate = self::getCurrencyRates($currencyId);
            
            if (!$exchangeRate) $exchangeRate = self::getCurrencyRates();

            $havingQuery = 'property.eur_price ';
            
            if (!empty($request->min_price) && empty($request->max_price) )
                $havingQuery .= '>= '. ($request->min_price / $exchangeRate->exchange_rate);
            else if(empty($request->min_price) && !empty($request->max_price))
                $havingQuery .= '<= '. ($request->max_price / $exchangeRate->exchange_rate);
            else
                $havingQuery .= 'BETWEEN '. ($request->min_price / $exchangeRate->exchange_rate) .' AND '. ($request->max_price / $exchangeRate->exchange_rate);

            $properties = $properties->where('status', 'sale')->whereRaw($havingQuery);
        }

        if (!empty($request->monthly_min_price) || !empty($request->monthly_max_price)) {
            $currency = $request->site_curr ?: 'USD';
            $exchangeRate = CurrencyRates::select('exchange_rate')->where('base_currency_id', 'EUR')->where('convert_currency_id', $currency)->first();
            $havingQuery = ($currency != 'EUR' && $exchangeRate) ?  '(property.eur_monthly_fee / '. $exchangeRate->exchange_rate .') ' : 'eur_monthly_fee ';

            if (!empty($request->monthly_min_price) && empty($request->monthly_max_price) )
                $havingQuery .= '>= '. $request->monthly_min_price;
            else if(empty($request->monthly_min_price) && !empty($request->monthly_max_price))
                $havingQuery .= '<= '. $request->monthly_max_price;
            else
                $havingQuery .= 'BETWEEN '. $request->monthly_min_price .' AND '. $request->monthly_max_price;
          
            $properties = $properties->where('status', 'sale')->whereRaw($havingQuery);
        }
      
      /*
      if (!empty($prices['sqm_min_price']) || !empty($prices['sqm_max_price'])) {
      
        $string .= '<p>Price / Sqm : '.($prices['sqm_min_price'] ? 'Min $ '.$prices['sqm_min_price'].' ' : ''). ($prices['sqm_max_price'] ? 'Max $ '.$prices['sqm_max_price'] : '').'</p>';  
        $propertiesJoin = self::getPriceSqmJoin();
        
        $maxPrice = !empty($prices['sqm_max_price']) ? $prices['sqm_max_price'] : $propertiesJoin->max('price_sqm');
        $minPrice = !empty($prices['sqm_min_price']) ? $prices['sqm_min_price'] : $propertiesJoin->min('price_sqm');
        
        $propertyIds = $propertiesJoin->filter(function ($value, $key) use($maxPrice, $minPrice) {
                                            return $value->price_sqm >= $minPrice && $value->price_sqm <= $maxPrice ;
                                        });
        
        $properties = $properties->whereIn('property.id', $propertyIds);
      }
      */
    
        return $properties;
    }
  
    public static function sizeFilter($request, $properties)
    {
        if (!empty($request->rooms_min) || !empty($request->rooms_max)) {
            
            $minRooms = $request->rooms_min;
            $maxRooms = $request->rooms_max;
                                             
            if (!empty($minRooms) && empty($maxRooms))
                $properties = $properties->where('property.rooms', '>=', $minRooms);
            elseif (empty($minRooms) && !empty($maxRooms))
                $properties = $properties->where('property.rooms', '<=', $maxRooms);
            else
                $properties = $properties->whereBetween('property.rooms', [ $minRooms, $maxRooms ]);
        }
        
        if (!empty($request->living_min) || !empty($request->living_max))
            $properties = self::getSizeBetweenPropertyIds($properties, $request->living_min, $request->living_max, 'total_living_area');
     
        if (!empty($request->gardens_min_sqm) || !empty($request->gardens_max_sqm))
            $properties = self::getSizeBetweenPropertyIds($properties, $request->gardens_min_sqm, $request->gardens_max_sqm, 'total_garden_area');

        return $properties;
    }
    
    public static function viewsFilter($views, $properties)
    {
        if (!empty($views)) {
            $viewIds = PropertySearchHelper::getIdsBySearchKey(new PrpView, $views);
            $countViewIds = count($viewIds);
            $viewIds = (count($viewIds) == '1')?  $viewIds[0] : implode(',', $viewIds->toArray());
            $properties = $properties->join(
                                        \DB::raw("
                                            (SELECT property_id 
                                            FROM property_views 
                                            WHERE view_id IN (". $viewIds .") 
                                            GROUP BY property_id 
                                            HAVING COUNT(property_id) >= ". $countViewIds ."
                                            ) property_views
                                        "),
                                        'property.id', '=', 'property_views.property_id');
        }
     
        return $properties;
    }
  
    public static function proximitiesFilter($proximities, $properties)
    {
        if (!empty($proximities)) {
            $proximitiesIds = PropertySearchHelper::getIdsBySearchKey(new Proximities, $proximities);
            $countProximitiesIds = count($proximitiesIds);
            $proximitiesIds = (count($proximitiesIds) == '1') ?  $proximitiesIds[0] : implode(',', $proximitiesIds->toArray());
            $properties = $properties->join(
                                        \DB::raw("
                                            (SELECT property_id 
                                            FROM property_proximity 
                                            WHERE proximity_id IN (". $proximitiesIds .") 
                                            GROUP BY property_id 
                                            HAVING COUNT(property_id) >= ". $countProximitiesIds ."
                                            ) property_proximity
                                        "),
                                        'property.id', '=', 'property_proximity.property_id');
        }
        
        return $properties;
    }
  
    public static function featuresFilter($features, $properties)
    {
        if (!empty($features)) {
            $featureIds =  PropertySearchHelper::getIdsBySearchKey(new Features, $features);
            $countFeatureIds = count($featureIds);
            $featureIds = (count($featureIds) == '1')?  $featureIds[0] : implode(',', $featureIds->toArray());

            $properties = $properties->join(
                                        \DB::raw("
                                            (SELECT property_id 
                                            FROM property_features 
                                            WHERE feature_id IN (". $featureIds .") 
                                            GROUP BY property_id 
                                            HAVING COUNT(property_id) >= ". $countFeatureIds ."
                                            ) property_feature
                                        "),
                                        'property.id', '=', 'property_feature.property_id');
        }
     
        return $properties;
    }
  
  public static function getMinMaxRooms()
  {
      if (Cache::has('propertyFeaturesMinMax'))
          $propertyFeaturesMinMax = Cache::get('propertyFeaturesMinMax');
      else {
          $propertyFeaturesMinMax = Property::selectRaw('MIN(rooms) as min, MAX(rooms) as max')->first();
          Cache::put('propertyFeaturesMinMax', $propertyFeaturesMinMax, 24*60);
      }
    
      $min = $propertyFeaturesMinMax->min ?: 1;
      $max = $propertyFeaturesMinMax->max ?: 1;
      
      $rooms = [];
      
      for ($i = 1; $i <= $max; $i++) {
          array_push($rooms, $i);
      }
      
      return $rooms;
  }
  
  public static function saveSearch($request)
  {
      $filtersData = self::getFilterData($request);
      if (empty(array_filter($filtersData)))
        return;
    
      $srchStats = new SearchStatistics;
      $filterStr = ['type', 'price', 'size', 'views', 'proximities', 'features'];
      
      foreach ($filterStr as $str)
        $srchStats->{'property_'. $str} = !empty($filtersData[$str]) ? json_encode($filtersData[$str]) : null;
    
      $srchStats->save();
  }
  
  public static function getMinPriceSqm()
  {
      if (Cache::has('minPriceSqm')) return Cache::get('minPriceSqm');
      $properties = self::getPriceSqmJoin();
      $min = $properties->min('price_sqm');
      $max = $properties->max('price_sqm');
      $calcDiff = self::calculatePriceDiff($min, $max);
      Cache::put('minPriceSqm', $calcDiff, 5);
      return $calcDiff;
  }
  
  public static function getPriceSqmJoin()
  {
      $properties = Property::mainImagePropertiesFilter();
      return $properties->selectRaw('property.id, CEIL(property.price / property.total_living_area) as price_sqm')->get();
  }
  
  public static function getGardenSqmJoin()
  {
      if(Cache::has('GardenSqmJoin')) return Cache::get('GardenSqmJoin');
      $properties = Property::mainImagePropertiesFilter();
      $properties = $properties->join('property_features', function($join){
                                    $join->on('property.id', '=', 'property_features.property_id');
                                })->join('features', function($join){
                                    $join->on('property_features.feature_id', '=', 'features.id')->where('search_key', 'garden');
                                })->get();
                                
      Cache::put('GardenSqmJoin', $properties, 24*60);
      return $properties;
  }
  
  public static function getMinMaxGardenSqm()
  {
      $totalGardenSqm = self::getSizeMinMax('total_garden_area');
      return self::calculateGardenAreaIntervals($totalGardenSqm->min, $totalGardenSqm->max);
    //   return self::calculatePriceDiff($totalGardenSqm->min, $totalGardenSqm->max);
  }
  
  public static function salePriceMinMaxValues()
  {
      $currency = CommonHelper::getAppCurrency(false, true);
      
      if (empty($currency))
          return [];
          
      $currency = $currency->id;
        
      if (Cache::has('propertiesSalePriceIntervals_'. $currency)) {
          return Cache::get('propertiesSalePriceIntervals_'. $currency);
      }
      
      $priceInterval = Currency::select('intervals')
          ->where('currency_id', $currency)
          ->join('price_intervals', 'currencies.id', '=', 'price_intervals.currency_id')
          ->first();
          
      $intervals = isset($priceInterval) ? explode(',', $priceInterval->intervals) : [];
      Cache::put('propertiesSalePriceIntervals_'. $currency, $intervals, 24*60);
      
      return $intervals;
  }
  
  public static function monthlyFeeMinMaxValues()
  {
      $currency = CommonHelper::getAppCurrency(false, true);
      if (!isset($currency) || !isset($currency->currency))
        return [];
    
      $currency = $currency->currency;
      
      if (Cache::has('propertiesMonthlyPriceIntervals_'. $currency))
          return Cache::get('propertiesMonthlyPriceIntervals_'. $currency);
          
      $priceInterval = Currency::select('intervals')->where('currency', $currency)->join('monthly_fee_intervals', 'currencies.id', '=', 'monthly_fee_intervals.currency_id')->first();
      $intervals = isset($priceInterval) ? explode(',', $priceInterval->intervals) : [];
      Cache::put('propertiesMonthlyPriceIntervals_'. $currency, $intervals, 24*60);
      return $intervals;
  }
  
  public static function calculatePriceDiff($min, $max)
  {
      $priceDiff = $max-$min;
      $priceRange = [];
      
      if (empty($priceDiff) && $min > 0) array_push($priceRange, $min);
      if (empty($priceDiff)) return $priceRange;
      
      for ($i=0; $i != 16; $i++) {
          if(empty($priceDiff/15*$i + $min)) continue;
          array_push($priceRange, ceil($priceDiff/15*$i + $min));
      }

      return $priceRange;
  }
  
  public static function calculateLivingAreaIntervals($min, $max)
  {
      $areaRange  = [];
      $areaDiff = $max - $min;
      
      if($areaDiff > 5){
          
          $intervalStart = 5;
          
          array_push($areaRange, $intervalStart );
          
          for ($i=0; $intervalStart < $max; $i++) {
              if($intervalStart == 5 )      $diff = 5 ;
              if($intervalStart == 160 )    $diff = 10 ;
              if($intervalStart == 180 )    $diff = 20 ;
              if($intervalStart == 200 )    $diff = 50 ;
              if($intervalStart == 300 )    $diff = 100 ;
              if($intervalStart == 500 )    $diff = 250 ;
              if($intervalStart == 1000 )    $diff = 500 ;
              if($intervalStart == 3000 )    $diff = 1000 ;
              if($intervalStart == 5000 )    $diff = 2500 ;
              if($intervalStart == 10000 )    $diff = 5000 ;
              if($intervalStart == 20000 )    $diff = 10000 ;
              if($intervalStart == 30000 )    $diff = 20000 ;
              if($intervalStart == 50000 )    $diff = 50000 ;
              $intervalStart = $intervalStart + $diff;
              
              if($intervalStart < $max)
                array_push($areaRange, $intervalStart );
              else
                $intervalStart = $max;
          }
          
          array_push($areaRange, round($intervalStart/10)*10 );
      }
      
      return $areaRange;  
  }
  
  public static function calculateGardenAreaIntervals($min, $max)
  {
      $areaRange  = [];
      $areaDiff = $max - $min;
      
      if($areaDiff > 100){
          
          $intervalStart = 100;
          
          array_push($areaRange, $intervalStart );
          
          for ($i=0; $intervalStart < $max; $i++) {
              if($intervalStart == 100 )      $diff = 200 ;
              if($intervalStart == 500 )    $diff = 250 ;
              if($intervalStart == 1000 )    $diff = 500 ;
              if($intervalStart == 3000 )    $diff = 1000 ;
              if($intervalStart == 5000 )    $diff = 2500 ;
              if($intervalStart == 10000 )    $diff = 5000 ;
              if($intervalStart == 20000 )    $diff = 10000 ;
              if($intervalStart == 30000 )    $diff = 20000 ;
              if($intervalStart == 50000 )    $diff = 50000 ;
              if($intervalStart == 100000 )    $diff = 100000 ;
              $intervalStart = $intervalStart + $diff;
              
              if($intervalStart < $max)
                array_push($areaRange, $intervalStart );
              else
                $intervalStart = $max;
          }
          
          array_push($areaRange, round($intervalStart/10)*10 );
      }
      
      return $areaRange;  
  }
  
  public static function getFilterName($modelClass, $filterSearchKey)
  {
      $filterObj = $modelClass::where('search_key', $filterSearchKey)->first();
      return $filterObj ? $filterObj->name : '';
  }
  
  public static function getFeaturesJoin($minMaxColumn, $searchKeys)
  {
      $propertyFeaturesMinMax = new PropertyFeatures;
      
      if ($minMaxColumn)
          $propertyFeaturesMinMax = $propertyFeaturesMinMax->selectRaw('MIN('. $minMaxColumn .') as min, MAX('. $minMaxColumn .') as max');
          
    return $propertyFeaturesMinMax->join('features', function($join) use($searchKeys){
                                                $join->on('property_features.feature_id', '=', 'features.id')
                                                    ->whereIn('search_key', $searchKeys);
                                            });
  }
  
  public static function getMinMaxLivingSqm()
  {
      $totalLivingSqm = self::getSizeMinMax('total_living_area');
      return self::calculateLivingAreaIntervals($totalLivingSqm->min, $totalLivingSqm->max);
    //   return self::calculatePriceDiff($totalLivingSqm->min, $totalLivingSqm->max);
  }

  public static function getSizeMinMax($column)
  {
      return Property::selectRaw('MIN(CASE 
                                        WHEN '. $column .'_type  = "sq.ft."
                                        THEN ('. $column .' * 10.7639)
                                        ELSE '. $column
                                        .' END) AS min,
                                MAX(CASE 
                                        WHEN '. $column .'_type  = "sq.ft."
                                        THEN ('. $column .' * 10.7639)
                                        ELSE '. $column
                                        .' END) AS max
                    ')->where($column, '!=', '0')->first();
  }
  
  public static function getSizeBetweenPropertyIds($properties, $min, $max, $column)
  {
      if (!empty($min) && empty($max))
        $str =  ">= ". $min;
      elseif (empty($min) && !empty($max))
        $str =  "<= ". $max;
      else
        $str =  "BETWEEN ". $min ." AND ". $max;
        
      return $properties->whereRaw('
                            ( ('. $column .'_type = "sq.ft." 
                                and ('. $column .' * 10.7639) '. $str .')
                                OR ('. $column .'_type = "sq.m." 
                                and '. $column .' '. $str 
                            .') )'
                        );
  }
  
  public static function getFilterData($request)
  {
      $requestAll = (get_class($request) == 'stdClass') ? json_decode(json_encode($request), true): $request->all();
      
      $price = array_where($requestAll, function ($value, $key) {
          return strpos($key, 'price') > -1;
      });
    
      $size = array_where($requestAll, function ($value, $key) {
          return strpos($key, 'rooms') > -1 || strpos($key, 'living') > -1 || strpos($key, 'gardens') > -1;
      });

      $data['type'] = isset($request->type) ? $request->type : [];
      $data['price'] = $price;
      $data['size'] = $size;
      $data['views'] = isset($request->views) ? $request->views : [];
      $data['proximities'] = isset($request->proximities) ? $request->proximities : [];
      $data['features'] = isset($request->features) ? $request->features : [];
      return $data;
  }
  
  public static function getIdsBySearchKey($model, $searchKeys)
  {
      return $model->whereIn('search_key', $searchKeys)->pluck('id');
  }
  
  public static function TypeFilterHtml($filtersData = null)
  {
      if (Cache::has('typeFilterHtml_'. App::getLocale()))
          $data = Cache::get('typeFilterHtml_'. App::getLocale());
      else {
          $types = PropertyTypes::getGroupedTypes();
          $allTypes = PropertyTypes::getAllTypes();
          $data['types'] = $types;
          $data['allTypes'] = $allTypes;
          Cache::put('typeFilterHtml_'. App::getLocale(), $data, 60 * 24 *30);
      }      
    
      $data['typeData'] = isset($filtersData['type']) ? $filtersData['type'] : [];
      return view('front.modals.search.type', $data);
  }
  
  public static function PriceFilterHtml($filtersData = null)
  {
      $currency = CommonHelper::getAppCurrency(false, true);
      if (!isset($currency) || !isset($currency->currency))
          return;
      
      $currency = $currency->currency;
      $data = [];
      
      if (Cache::has('priceFilterHtml_'. App::getLocale() .'_'.$currency))
          $data = Cache::get('priceFilterHtml_'. App::getLocale() .'_'.$currency);
      else {
          $data['salePriceMinMaxValues'] = self::salePriceMinMaxValues();
          $data['monthlyFeeMinMaxValues'] = self::monthlyFeeMinMaxValues();
    //   $data['getMinPriceSqm'] = self::getMinPriceSqm();
          Cache::put('priceFilterHtml_'. App::getLocale() .'_'.$currency,$data, 60 * 24 *30);
      }
      
      $data['priceData']['min_price'] = isset($filtersData['min_price']) ? $filtersData['min_price'] : [];
      $data['priceData']['max_price'] = isset($filtersData['max_price']) ? $filtersData['max_price'] : [];
      $data['priceData']['monthly_min_price'] = isset($filtersData['monthly_min_price']) ? $filtersData['monthly_min_price'] : [];
      $data['priceData']['monthly_max_price'] = isset($filtersData['monthly_max_price']) ? $filtersData['monthly_max_price'] : [];
      $data['priceData']['sqm_min_price'] = isset($filtersData['sqm_min_price']) ? $filtersData['sqm_min_price'] : [];
      $data['priceData']['sqm_max_price'] = isset($filtersData['sqm_max_price']) ? $filtersData['sqm_max_price'] : [];
      
      return view('front.modals.search.price',$data);
  }
  
  public static function SizeFilterHtml($filtersData = null)
  {
      if (Cache::has('sizeFilterHtml_'. App::getLocale()))
          $data = Cache::get('sizeFilterHtml_'. App::getLocale());
      else {
          $data['getMinMaxRooms'] = self::getMinMaxRooms();
          $data['livingSqm'] = self::getMinMaxLivingSqm();
          $data['getMinMaxGardenSqm'] = self::getMinMaxGardenSqm();
          Cache::put('sizeFilterHtml_'. App::getLocale(),$data, 60 * 24 * 30);
      }
      $data['sizeData']['rooms_min'] = isset($filtersData['rooms_min']) ? $filtersData['rooms_min'] : '';
      $data['sizeData']['rooms_max'] = isset($filtersData['rooms_max']) ? $filtersData['rooms_max'] : '';
      $data['sizeData']['living_min'] = isset($filtersData['living_min']) ? $filtersData['living_min'] : [];
      $data['sizeData']['living_max'] = isset($filtersData['living_max']) ? $filtersData['living_max'] : [];
      $data['sizeData']['gardens_min_sqm'] = isset($filtersData['gardens_min_sqm']) ? $filtersData['gardens_min_sqm'] : [];
      $data['sizeData']['gardens_max_sqm'] = isset($filtersData['gardens_max_sqm']) ? $filtersData['gardens_max_sqm'] : [];

      return view('front.modals.search.size',$data);
  }
  
  public static function ViewsFilterHtml($filtersData = null)
  {
      if (Cache::has('viewsFilterHtml_'. App::getLocale()))
          $data = Cache::get('viewsFilterHtml_'. App::getLocale());
      else {
          $data['views'] = PrpView::orderBy('name')->get();
          Cache::put('viewsFilterHtml_'. App::getLocale(),$data, 60 * 24 * 30);
      }
    
      $data['viewsData'] = isset($filtersData['views']) ? $filtersData['views'] : [];
      return view('front.modals.search.views',$data);
  }
  
  public static function ProximitiesFilterHtml($filtersData = null)
  {
      if (Cache::has('proximitiesFilterHtml_'. App::getLocale()))
          $data = Cache::get('proximitiesFilterHtml_'. App::getLocale());
      else {
          $data['proximities'] =  Proximities::getAllProximities();
          Cache::put('proximitiesFilterHtml_'. App::getLocale(),$data, 60 * 24 * 30);
      }
    
      $data['proximitiesData'] = isset($filtersData['proximities']) ? $filtersData['proximities'] : [];
      return view('front.modals.search.proximities',$data);
  }
  
  public static function FeaturesFilterHtml($filtersData = null)
  {
      if (Cache::has('featuresFilterHtml_'. App::getLocale()))
          $data = Cache::get('featuresFilterHtml_'. App::getLocale());
      else {
          $data['features'] =  Features::getAllFeatures();
          Cache::put('featuresFilterHtml_'. App::getLocale(),$data, 60 * 24 * 30);
      }
    
      $data['featuresData'] = isset($filtersData['features']) ? $filtersData['features'] : [];
      return view('front.modals.search.features',$data);
  }

  public static function getSelectedFilterString($request)
  {
      $typeFilterString = [];
      $viewFilterString = [];
      $proximityFilterString = [];
      $featureFilterString = [];
      $str='';      
      $siteLang = App::getLocale();
      
      if (!empty($request->country)){
          $countryName = Country::find($request->country);
          $str .= '<p>'.trans('common.Country').' : '.($request->country ? '<span>'. $countryName->{'name_'.$siteLang} .' <sup><a data-type="country" class="close-btn">&times;</a></sup> </span>' : '').'</p>';
      }
      $data['country'] = $str;
      $str = '';
      
      if (!empty($request->city)){
          $cityName = City::find($request->city);
          $str .= '<p>'.trans('common.City').'  : '.($request->city ? '<span>'. $cityName->{'name_'.$siteLang} .' <sup><a data-type="city" class="close-btn">&times;</a></sup> </span>' : '').'</p>';
      }
      $data['city'] = $str;
      $str = '';
      
      if (!empty($request->type)) {
          $types = PropertyTypes::whereIn('search_key', $request->type)->get();
          
          foreach ($types as $propType)
              $typeFilterString[] = '<span>'. trans('common.'. $propType->name) .' <sup><a data-type="type" class="close-btn" data-id="'. $propType->search_key .'">&times;</a></sup></span>';
      }
      
    
      $str = implode(' ', $typeFilterString);
      $data['type'] = ($str != '') ? '<p>'.trans('common.Types').'  : '. $str .'</p>' : '';
      
      if (!empty($request->map_view_property_id)) {
          $property = Property::find(base64_decode($request->map_view_property_id));
          $data['property'] = '<p> '.trans('common.Property').' : <span>'. $property->texts->subject_en .' </span></p>';
      }
      
      $str = '';
      
      if (!empty($request->min_price) || !empty($request->max_price))
          $str .= '<p>'.trans('common.Price').' : '.($request->min_price ? '<span> '.trans('common.Min').' - '. $request->min_price .' <sup><a data-type="min_price" class="close-btn">&times;</a></sup> </span>' : ''). ($request->max_price ? '<span> '.trans('common.Max').' - '.$request->max_price .' <sup><a data-type="max_price" class="close-btn">&times;</a></sup> </span>' : '').'</p>';
      
      if (!empty($request->monthly_min_price) || !empty($request->monthly_max_price))
          $str .= '<p>'.trans('common.MonthlyFee').' : '.($request->monthly_min_price ? '<span> '.trans('common.Min').' - '.$request->monthly_min_price .' <sup><a data-type="monthly_min_price" class="close-btn">&times;</a></sup> </span>' : ''). ($request->monthly_max_price ? '<span> '.trans('common.Max').' - '.$request->monthly_max_price .' <sup><a data-type="monthly_max_price" class="close-btn">&times;</a></sup> </span>' : '').'</p>';
      
      $data['price'] = $str;
      $str = '';
      
      
      if (!empty($request->rooms_min) || !empty($request->rooms_max))
          $str .= '<p>'.trans('common.Rooms').' : '.($request->rooms_min ? '<span> '.trans('common.Min').' - '.$request->rooms_min .' <sup><a data-type="rooms_min" data-id="room-min" class="close-btn">&times;</a></sup> </span>' : ''). ($request->rooms_max ? '<span> '.trans('common.Max').' - '.$request->rooms_max .' <sup><a data-type="rooms_max" data-id="room-max" class="close-btn">&times;</a></sup> </span>' : '').'</p>';
    
      if (!empty($request->living_min) || !empty($request->living_max))
          $str .= '<p>'.trans('common.LivingSqm').' : '.($request->living_min ? '<span> '.trans('common.Min').' - '.$request->living_min .' <sup><a data-type="living_min" data-id="living-min" class="close-btn">&times;</a></sup> </span>' : ''). ($request->living_max ? '<span> '.trans('common.Max').' - '.$request->living_max .' <sup><a data-type="living_max" data-id="living-max" class="close-btn">&times;</a></sup> </span>' : '').'</p>';
          
      if (!empty($request->gardens_min_sqm) || !empty($request->gardens_max_sqm))
          $str .= '<p>'.trans('common.GardenSqm').' : '.($request->gardens_min_sqm ? '<span> '.trans('common.Min').' - '.$request->gardens_min_sqm .' <sup><a data-type="gardens_min_sqm" data="gardens-min-sqm"class="close-btn">&times;</a></sup> </span>' : ''). ($request->gardens_max_sqm ? '<span> '.trans('common.Max').' - '.$request->gardens_max_sqm .' <sup><a data-type="gardens_max_sqm" data-id="gardens-max-sqm" class="close-btn">&times;</a></sup> </span>' : '').'</p>';
      
      $data['size'] = $str;
      
      if (!empty($request->views))
          foreach ($request->views as $viewId)
          {
              $viewText = self::getFilterName(PrpView::class, $viewId);
              $viewText = trans('viewproperty.' . str_replace(' ', '', $viewText));
              $viewText = str_replace(["\'", '\"'], "'", $viewText);
              $viewText = str_replace(["->'", '->"'], "", $viewText);
              $viewFilterString[] = '<span>'. $viewText . ' <sup><a data-type="views" class="close-btn" data-id="'. $viewId .'">&times;</a></sup></span>';
          }
      
      $str = implode(' ', $viewFilterString);
      $data['views'] = ($str != '') ? '<p>'.trans('common.Views').' : '. $str .'</p>' : '';
      
      if (!empty($request->proximities))
          foreach ($request->proximities as $proximityId)
          {
              $proximityText = self::getFilterName(Proximities::class, $proximityId);
              $proximityText = trans('common.'.str_replace(' ','',$proximityText));
              $proximityFilterString[] = '<span>'. $proximityText . ' <sup><a data-type="proximities" class="close-btn" data-id="'. $proximityId .'">&times;</a></sup></span>';
          }

      $str = implode(' ', $proximityFilterString);
      $data['proximities'] = $str != '' ? '<p>'.trans('common.Proximities').' : '. $str .'</p>' : '';
    
      if (!empty($request->features))
          foreach ($request->features as $featureId)
          {
              $featureText = self::getFilterName(Features::class, $featureId);
              $featureText  = trans('viewproperty.'.str_replace(' ','',$featureText));
              $featureText = str_replace(["\'",'\"'], "'",$featureText);
              $featureText = str_replace(["->'",'->"'], "",$featureText);
              $featureFilterString[] = '<span>'. $featureText . ' <sup><a data-type="features" class="close-btn" data-id="'. $featureId .'">&times;</a></sup></span>';
          }
        
      $str = implode(' ', $featureFilterString);
      $data['features'] = $str != '' ? '<p>'.trans('common.Features').' : '. $str .'</p>' : '';
    
    
      return (is_array($data) && count($data)) ? implode('', $data) : '';
  }
  
  public static function getCurrencyRates($currencyId = null)
  {
      $currencyRates = new CurrencyRates;
      
      if ($currencyId)
         $currencyRates = $currencyRates->where('convert_currency_id', $currencyId); // Default 10 for USD
      
      $currencyRates = $currencyRates->select('exchange_rate')
                                     ->where('base_currency_id', 1);

      return $currencyRates->where('currencies.exchangeable', '1')
                           ->join('currencies', 'currencies.id', '=', 'currency_rates.convert_currency_id')
                           ->first();
  }
  
  public static function getZoomLevelDistances($request)
  {
      if ($request->zoom == '8')      
        $distances =  ['500','1000','2000'];  
      elseif ($request->zoom == '9')      
        $distances =  ['400','500','1000','1500'];  
      elseif ($request->zoom == '10')      
        $distances =  ['300','400','500','1000','1500']; 
      elseif ($request->zoom == '11')      
        $distances =  ['200','400','500','1000','1500']; 
      elseif ($request->zoom >= 12)      
        $distances =  ['150','200','300','400','500','1000','1500','2000'];
      else
        $distances =  ['500','1000','1500','2000','3000','4000','5000','6000','10000'];
      
      return $distances;
  }
  
}