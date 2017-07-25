<?php

namespace App\Helpers;

use App\Models\PropertyStatistics;
use App\Models\Estateagency;
use App\Models\Property;
use Carbon\Carbon;

class PropertyViewsHelper
{
  public static function getTimeArray($property_id)
  {
      $newArray = $monthArray = $monthData = $monthAllData = $allData = [];
      $startMonthDay = Carbon::today()->subMonth();
      $endMonthDay = Carbon::now();
    
      for ($day = $startMonthDay ; $day->lte($endMonthDay) ; $day->addDay()) {
          $endOfDay = $day->copy()->endOfDay();
          $views = PropertyStatistics::where('property_id', $property_id)->where('type', 2)->whereBetween('created_at', [ $day, $endOfDay ])->count();
          $allViews= PropertyStatistics::where('property_id', $property_id)->where('type', 1)->whereBetween('created_at', [ $day, $endOfDay ])->count();
          $monthArray[] = $day->toFormattedDateString();
          $monthData[] = $views; //only map previews of property
          $monthAllData[] = $allViews; // detailed page views of property
      }
    
      array_push($allData, $monthData, $monthAllData);
      array_push($newArray, $monthArray, $allData);
    
      return $newArray;
  }
  
  public static function getCountriesArray($property_id)
  {
      $uniqueCountries = PropertyStatistics::selectRaw('count(geo_country) countryCount, geo_country')
        ->where('property_id', $property_id)
        ->where('geo_country', '!=', '')
        ->groupBy('geo_country')
        ->pluck('countryCount', 'geo_country')
        ->toArray();
          
      $viewCount = [];
      
      foreach ($uniqueCountries as $country => $count) {
          array_push($viewCount, ['name' => $country, 'y' => $count]);
      }

      return $viewCount;
  }
  
  public static function getAgencyMonthlyStats($agencyId, $month)
  {
      $agency = Estateagency::find($agencyId);
      
      if (isset($agency)) {
          
          $allData = $newArray = [];
          $endMonthDay = Carbon::now()->subDays(($month - 1) * 30);
          $startMonthDay = $endMonthDay->copy()->subDays(30);
      
          $properties = Property::selectRaw('
                DATE_FORMAT(property_statistics.created_at, "%Y%m%d") as day, 
                DATE_FORMAT(property_statistics.created_at, "%d/%m/%Y") as date,
                count(property_statistics.created_at) as views
            ')
            ->where('property.agency_id', $agencyId)
            ->join('property_statistics', 'property_statistics.property_id', 'property.id')
            ->whereBetween('property_statistics.created_at', [$startMonthDay, $endMonthDay]);
      
          $allViewsstats = $properties->where('type', '1')->groupBy('day')->get()->toArray();
          $frontViewStats = $properties->where('type', '2')->groupBy('day')->get()->toArray();
      
          for ($day = $startMonthDay ; $day->lte($endMonthDay) ; $day->addDay()) {
              
              $date = $day->format('Ymd');
              $dateFormatted = $day->format('d/m/Y');
        
              if (!in_array($date, array_pluck($allViewsstats, 'day'))) {
                  $allViewsstats[] = ['day' => $date, 'date' => $dateFormatted, 'views' => 0];
              }
                  
              if (!in_array($date, array_pluck($frontViewStats, 'day'))) {
                  $frontViewStats[] = ['day' => $date, 'date' => $dateFormatted, 'views' => 0];                  
              }
           
              $monthArray[] = $day->toFormattedDateString();
          }
      
          $allViewsstats = self::sortViewsArray($allViewsstats);
          $frontViewStats = self::sortViewsArray($frontViewStats);
      
          array_push($allData, array_pluck($frontViewStats, 'views'), array_pluck($allViewsstats, 'views'));
          array_push($newArray, $monthArray, $allData);
      
          return $newArray;
    }
      
  }
  
  public static function sortViewsArray($array)
  {
      return array_values(array_sort($array, function ($value) {
          return $value['day'];
      }));
  }
  
  public static function getMonthsNames($last_n_month)
  {
      $start = Carbon::now()->subDays(30 * $last_n_month)->startOfMonth();
      $end = Carbon::now()->subDays(30 * ($last_n_month - 1))->startOfMonth();
      return $start->format('F, Y') . ($start == $end ? '' : (' - '. $end->format('F, Y')));
  }
  
}