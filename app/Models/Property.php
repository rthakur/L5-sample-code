<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App, Config;
use App\Models\CurrencyRates;
use App\Models\Features;
use App\Models\BookmarkedProperty;
use App\Models\PropertyFeatures;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\CommonHelper;
use Auth, Cache;

class Property extends Model
{
    use SoftDeletes;

    public static $DISABLED_OBSERVER = false;
    protected $table = 'property';
    protected $guarded = array();
    protected $dates = ['deleted_at'];
    protected $previewModes;

    public static function getPreviewModes()
    {
        return [trans('viewproperty.Published') => '1', trans('viewproperty.Preview') => '2', trans('viewproperty.Archived') => '3']; // Property preview Modes        
    }

    public function agent()
    {
        return $this->belongsTo('App\User');
    }

    public function agency()
    {
        return $this->belongsTo('App\Models\Estateagency');
    }

    public function type()
    {
        return $this->hasOne('App\Models\PropertyTypes', 'id', 'property_type_id');
    }

    public function texts()
    {
        if (isset($this->property_id))
            return $this->hasOne('App\Models\PropertyTexts', 'property_id', 'property_id');

        return $this->hasOne('App\Models\PropertyTexts', 'property_id', 'id');
    }


    public function country()
    {
        return $this->hasOne('App\Models\Country', 'id', 'country_id');
    }

    public function state()
    {
        return $this->hasOne('App\Models\State', 'id', 'state_id');
    }

    public function prop_city()
    {
        return $this->hasOne('App\Models\City', 'id', 'city_id');
    }

    public function area()
    {
        return $this->hasOne('App\Models\Area', 'id', 'area_id');
    }

    public function price_currency()
    {
        return $this->hasOne('App\Models\Currency', 'id', 'price_currency_id');
    }

    public function property_tax_currency()
    {
        return $this->hasOne('App\Models\Currency', 'id', 'property_tax_currency_id');
    }

    public function personal_property_tax_currency()
    {
        return $this->hasOne('App\Models\Currency', 'id', 'personal_property_tax_currency_id');
    }

    public function monthly_fee_currency()
    {
        return $this->hasOne('App\Models\Currency', 'id', 'monthly_fee_currency_id');
    }

    public function sold_price_currency()
    {
        return $this->hasOne('App\Models\Currency', 'id', 'sold_price_currency_id');
    }

    public function propertyImages()
    {
        return $this->hasMany('App\Models\PropertyImages', 'property_id', 'id')->select('id', 's3_url')->whereRaw('main_image != 1  and s3_url != ""');
    }

    public function propertyImagesWithMainImage()
    {
        return $this->hasMany('App\Models\PropertyImages', 'property_id', 'id')->select('id', 's3_url', 'main_image')->whereRaw('s3_url != ""');
    }

    public function propertyAllImages()
    {
        return $this->hasMany('App\Models\PropertyImages', 'property_id', 'id')->whereRaw('s3_url != ""');
    }

    public function propertyPreviewImages()
    {
        return $this->hasOne('App\Models\PropertyImages', 'property_id', 'id')->whereRaw('main_image != 1 and s3_path != ""');
    }

    public static function defaultImage()
    {
        $property = self::where('main_image_url', '!=', '')->inRandomOrder()->first();
        return ($property) ? $property->main_image_url : '';
    }

    public function features()
    {
        return $this->hasMany('App\Models\PropertyFeatures', 'property_id', 'id')
            ->join('features', 'property_features.feature_id', '=', 'features.id');
    }

    public function services()
    {
        return $this->hasMany('App\Models\PropertyServices', 'property_id', 'id')
            ->join('services', 'property_services.service_id', '=', 'services.id');
    }

    public function views()
    {
        return $this->hasMany('App\Models\PropertyView', 'property_id', 'id')
            ->join('views', 'property_views.view_id', '=', 'views.id');
    }

    public function proximities()
    {
        return $this->hasMany('App\Models\PropertyProximities', 'property_id', 'id')
            ->join('proximities', 'property_proximity.proximity_id', '=', 'proximities.id');
    }

    public function api_format()
    {
        $property_texts = $this->texts()->first();
        $property = [
            'external_id' => $this->external_id,
            'property_type' => ($this->property_type_id && $this->type()->first()) ? $this->type()->first()->search_key : 'house',
            'property_url' => $this->property_url,
            'geo_lat' => $this->geo_lat,
            'geo_lng' => $this->geo_lng,
            'agent_email' => $this->agent()->first()->email,
            'country' => ($this->country_id != null && $this->country()->first()) ? $this->country()->first()->name_en : null,
            'state' => ($this->state_id != null && $this->state()->first()) ? $this->state()->first()->name_en : null,
            'city' => ($this->city_id != null && $this->prop_city()->first()) ? $this->prop_city()->first()->name_en : $this->city,
            'street_address' => $this->street_address,
            'rooms' => $this->rooms,
            'texts' => [
                'subject_en' => $property_texts->subject_en,
                'subject_fr' => $property_texts->subject_fr,
                'subject_de' => $property_texts->subject_de,
                'subject_sv' => $property_texts->subject_sv,
                'subject_ar' => $property_texts->subject_ar,
                'subject_no' => $property_texts->subject_no,
                'subject_es' => $property_texts->subject_es,
                'subject_ma' => $property_texts->subject_ma,
                'subject_da' => $property_texts->subject_da,
                'subject_fi' => $property_texts->subject_fi,
                'subject_hi' => $property_texts->subject_hi,
                'subject_pt' => $property_texts->subject_pt,
                'subject_ru' => $property_texts->subject_ru,
                'subject_ja' => $property_texts->subject_ja,
                'subject_uk' => $property_texts->subject_uk,
                'subject_it' => $property_texts->subject_it,
                'description_en' => $property_texts->description_en,
                'description_fr' => $property_texts->description_fr,
                'description_de' => $property_texts->description_de,
                'description_sv' => $property_texts->description_sv,
                'description_ar' => $property_texts->description_ar,
                'description_no' => $property_texts->description_no,
                'description_es' => $property_texts->description_es,
                'description_ma' => $property_texts->description_ma,
                'description_da' => $property_texts->description_da,
                'description_fi' => $property_texts->description_fi,
                'description_hi' => $property_texts->description_hi,
                'description_pt' => $property_texts->description_pt,
                'description_ru' => $property_texts->description_ru,
                'description_ja' => $property_texts->description_ja,
                'description_uk' => $property_texts->description_uk,
                'description_it' => $property_texts->description_it
            ],
            'price_on_request' => $this->price_on_request,
            'sell_price_original' => $this->price,
            'sell_price_original_currency' => ($this->price_currency()->first()) ? $this->price_currency()->first()->currency : Currency::get_eur()->currency,
            'monthly_fee' => $this->monthly_fee,
            'monthly_fee_currency' => ($this->monthly_fee_currency()->first()) ? $this->monthly_fee_currency()->first()->currency : Currency::get_eur()->currency,
            'property_tax' => $this->property_tax,
            'property_tax_currency' => ($this->property_tax_currency()->first()) ? $this->property_tax_currency()->first()->currency : Currency::get_eur()->currency,
            'personal_property_tax' => $this->personal_property_tax,
            'personal_property_tax_currency' => ($this->personal_property_tax_currency()->first()) ? $this->personal_property_tax_currency()->first()->currency : Currency::get_eur()->currency,
            'build_year' => $this->build_year,
            'total_living_area' => $this->total_living_area,
            'total_living_area_type' => $this->total_living_area_type,
            'total_garden_area' => $this->total_garden_area,
            'total_garden_area_type' => $this->total_garden_area_type
        ];

        $property['property_images'] = $this->api_pictures();
        $property['features'] = $this->api_features();
        $property['views'] = $this->api_views();
        $property['proximities'] = $this->api_proximities();

        return $property;

    }

    public function api_pictures()
    {
        $pi = $this->hasMany('App\Models\PropertyImages', 'property_id', 'id')->get();
        $data = array();
        foreach ($pi as $i) {
            $data[] = [
                'url' => $i->image_url,
                'is_main' => $i->main_image
            ];
        }
        return $data;
    }

    public function api_features()
    {
        $pf = $this->features()->get();
        $data = array();
        foreach ($pf as $f) {
            $data[$f->feature()->first()->search_key] = true;
        }
        return $data;
    }

    public function api_services()
    {
        $ps = $this->services()->get();
        $data = array();
        foreach ($ps as $s) {
            $data[$s->service()->first()->search_key] = true;
        }
        return $data;
    }

    public function api_proximities()
    {
        $pp = $this->proximities()->get();
        $data = array();
        foreach ($pp as $p) {
            $data[$p->proximity()->first()->search_key] = true;
        }
        return $data;
    }

    public function api_views()
    {
        $pv = $this->views()->get();
        $data = array();
        foreach ($pv as $p) {
            $data[$p->view()->first()->search_key] = true;
        }
        return $data;
    }

    public function getFeatureValues()
    {
        $features = Features::select('id', 'search_key')->get();
        $getfeatures = array_pluck($features, 'search_key', 'id');
        $features = [];

        foreach ($this->features as $feature)
            if (isset($getfeatures[$feature->feature_id]))
                $features[$getfeatures[$feature->feature_id]] = $feature->number;

        return $features;
    }

    public static function latestProperties($limit)
    {
        if (Cache::has('latestProperties' . $limit))
            return Cache::get('latestProperties' . $limit);

        $latestProperties = self::where('preview_mode', '1')->where('id', '!=', \Request::segment(9))->where([
            ['main_image_url', '<>', 'null'],
            ['main_image_url', '<>', ''],
            ['price', '<>', 'null'],
            ['price', '<>', '0']
        ])->orderBy('id', 'desc')->take($limit)->with('texts')->get();

        Cache::put('latestProperties' . $limit, $latestProperties, 60 * 6);

        return $latestProperties;
    }

    public static function recentViewProperties($limit)
    {
        $ids = array_reverse(CommonHelper::getRecentProperties($limit));

        if (is_array($ids) && !empty($ids))
            return self::whereIn('id', $ids)->with('texts')->orderBy('id')->get();
        elseif(empty($ids))
            return self::mainImagePropertiesFilter()->with('texts')->orderBy('id','desc')->take(2)->get();

        return [];
    }

    public static function featuredProperties($limit)
    {
        $featuredProperties = self::mainImagePropertiesFilter();
            // ->join('property_features','property_features.property_id','property.id')
            // ->selectRaw('property.*, COUNT(property_features.property_id) as count');
        
        if (\Session::has('viewedPropertyId')) {
            $featuredProperties->where('property.id', '!=', \Session::get('viewedPropertyId') );
        }
        
        
        $featuredProperties = $featuredProperties->inRandomOrder()
            ->take($limit)
            ->with('texts')
            ->get();  
        
        // $featuredProperties = $featuredProperties->orderBy('count','desc')
        //          ->groupBy('property_features.property_id')
        return $featuredProperties;
    }

    public function createdDate()
    {
        $date = strtotime($this->created_at);
        return $date ? date('d.m.Y', $date) : '';
    }

    public function currencySign()
    {
        $currency = Currency::find($this->price_currency_id);
        return !empty($currency) ? $currency->symbol : '$';
    }
    public function soldCurrencySign()
    {
        $currency = Currency::find($this->sold_price_currency_id);
        return !empty($currency) ? $currency->symbol : '$';
    }

    public function priceCurrency()
    {
        return $this->hasOne('App\Models\Currency', 'id', 'price_currency_id');
    }

    public function originalPrice()
    {
        $priceCurrency = !$this->priceCurrency ? $this->price_currency : $this->priceCurrency->currency;
        return number_format($this->price) . ' ' . $priceCurrency;
    }

    public function exchangeCurreny($returnObj = false)
    {
        return App\Helpers\CommonHelper::getAppCurrency(false, $returnObj);
    }

    public function exchangePrice()
    {
        $langCurrency = $this->exchangeCurreny(true);

        if ($this->price_currency_id == $langCurrency->id)
            return number_format($this->price) . ' ' . $this->priceCurrency->currency;

        if (Cache::has('getCurrencyRates_' . $langCurrency->id)) {
            $getCurrencyRates = Cache::get('getCurrencyRates_' . $langCurrency->id);
        } else {
            $getCurrencyRates = CurrencyRates::getCurrencyRates($langCurrency->id);
            Cache::put('getCurrencyRates_' . $langCurrency->id, $getCurrencyRates, 60 * 24);
        }

        if (isset($getCurrencyRates[$this->price_currency_id]))
            return number_format($this->price * $getCurrencyRates[$this->price_currency_id]) . ' ' . $langCurrency->currency;

        return number_format($this->price) . ' ' . $langCurrency->currency;
    }


    public function langAddress()
    {
        return CommonHelper::propertyLangAddress($this);
    }

    public function detailPageURL()
    {
        return CommonHelper::propertyDetailPageURL($this);
    }

    public function PropertyMapURL()
    {
        $lang = App::getLocale();
        
        if ($this->geo_lat && $this->geo_lat) {
            return '/'. $lang . '?property='. base64_encode($this->id);
        }

        return '/'. $lang;

    }

    public function totalViews()
    {
        return App\Models\PropertyStatistics::where('property_id', $this->id)->count();
    }

    public function getProximityField($proximityId, $field)
    {
        $proximity = $this->proximities->where('proximity_id', $proximityId)->first();
        return isset($proximity) ? $proximity->$field : null;
    }

    public function getFeatureField($featuresId, $field)
    {
        $feature = $this->features->where('feature_id', $featuresId)->first();
        return isset($feature) ? $feature->$field : null;
    }

    public function getFeatureFieldBySearchKey($searchKey, $field)
    {
        $feature = Features::select('id')->where('search_key', $searchKey)->first();
        $featureId = $feature ? $feature->id : null;
        return $this->getFeatureField($featureId, $field);
    }

    public function stats()
    {
        return $this->hasMany('App\Models\PropertyStatistics', 'property_id', 'id')->where('type', '2');
    }

    public static function mainImagePropertiesFilter()
    {
        return self::whereRaw('property.main_image_url IS NOT NULL')
            ->where([
                ['property.main_image_url', '<>', ''],
                ['property.geo_lat', '<>', '0'],
                ['property.geo_lng', '<>', '0'],
                ['property.preview_mode', '1'],
                ['property.price', '<>', '0'],
                ['property.agency_id', '<>', NULL]
            ]);
    }

    public static function applyMainImageFilters($properties)
    {
        return $properties->whereRaw('property.main_image_url IS NOT NULL')
            ->where([
                ['property.main_image_url', '<>', ''],
                ['property.geo_lat', '<>', '0'],
                ['property.geo_lng', '<>', '0'],
                ['property.preview_mode', '1'],
                ['property.deleted_at', NULL],
                ['property.price', '<>', '0'],
                ['agency_id', '<>', NULL]
            ]);
    }

    public function checkBookmarked()
    {
        return BookmarkedProperty::where('user_id', Auth::id())->where('property_id', $this->id)->first();
    }

    public function langSubject()
    {
        $lang = App::getLocale();
        $col = 'text_subject_' . ($this->{'text_subject_' . $lang} ? $lang : 'en');
        return CommonHelper::filterString($this->$col);
    }
    
    public static function similarProperties($propertyId, $limit)
    {
        $property = self::find($propertyId);

        $geoLat = $property->geo_lat;
        $geoLng = $property->geo_lng;

        if (empty($geoLat) && empty($geoLng)) {
            return [];
        }

        return self::mainImagePropertiesFilter()
            ->selectRaw("
                property.id, country_id, city_id, city, state_id, street_address, 
                agency_id, price, price_currency_id, main_image_url, 
                (
                6764 
                * acos(
                    cos( radians(" . $geoLat . ") )  
                    * cos( radians( geo_lat ) ) 
                    * cos( radians( geo_lng )  - radians(" . $geoLng . ") ) 
                    + sin( radians(" . $geoLat . ") ) 
                    * sin( radians( geo_lat ) ) 
                )
                ) AS distance
            ")
            ->where('property.id', '!=', $propertyId)
            ->inRandomOrder()
            ->take($limit)
            ->get();
    }

    public function getEditCompleted($tab = null)
    {
        $count = 0;
        
        if ($this->property_type_id && $this->texts->checkTextExists('subject_')) {
            if ($tab == 'basic_info') {
                return true;
            }
            
            $count++;
        }
        
        if ($this->texts->checkTextExists('description_')) {
            if ($tab == 'description') {
                return true;
            }
            $count++;
        }
        
        if (!empty($this->country_id)) {
            if ($tab == 'location') {
                return true;
            }
            
            $count++;
        }
        
        if ($this->price) {
            if ($tab == 'pricing') {
                return true;
            }
            $count++;
        }
        
        if ($this->features()->count()) {
            if ($tab == 'features') {
                return true;
            }
            $count++;
        }
        
        if ($this->views()->count()) {
            if ($tab == 'views') {
                return true;
            }
            $count++;
        }
        
        if ($this->proximities()->count()) {
            if ($tab == 'proximity') {
                return true;
            }
            $count++;
        }
        
        if ($this->propertyImages()->count()) {
            if ($tab == 'gallery') {
                return true;
            }
            $count++;
        }
        
        if ($this->preview_mode) {
            if ($tab == 'choose_agent') {
                return true;
            }
            
            $count++;
        }
        
        if (!empty($tab)) {
            return false;
        }
        
        return ceil($count * 11.11);
    }

    public function getCity()
    {
        if ($this->prop_city) {
            return trans('cities.'. $this->prop_city->search_key);
        }
        
        return $this->city;
    }
    
    public function getMainImageUrl()
    {
        return isset($this->main_image_url) ? $this->main_image_url : '/assets/img/noimageavailable.png';
    }
}
