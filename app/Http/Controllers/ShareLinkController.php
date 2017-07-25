<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShareLink;
use Auth;

class ShareLinkController extends Controller
{
  public function create(Request $request)
  {
      if (Auth::check())
          $newData['user_id'] =  Auth::id();
      
      if ($request->has('geo_lat'))
          $newData['geo_lat'] = $request->geo_lat;
      
      if ($request->has('geo_lng'))
          $newData['geo_lng'] = $request->geo_lng;
    
      if ($request->has('zoom'))
          $newData['zoom'] = $request->zoom;
    
      $newData['search_filter_json'] = $this->getFilterJson($request);
      $newData['view_type'] = $request->view_type;
      $newLink = ShareLink::where($newData)->first();
    
      if (!$newLink) {
          $shortLinkName = $this->generateShortLink();
          $newLink = ShareLink::create($newData);
          $newLink->short_link_name = $shortLinkName;
          $newLink->save();
      }
    
      return $newLink->short_link_name;
  }
  
  public function openShortlink(Request $request, $shortlink)
  {
      $shareLink = ShareLink::where('short_link_name',$shortlink)->first();
      
      if ($shareLink) {
          $lang = \App::getLocale();
          $filters = $this->getFiltersArray($shareLink); 
          $filters = http_build_query($filters, '&');
          $urlQuery = ['Map' => '?', 'List' => '/buy/property?', 'Gallery' => '/gallery?'];
        
          if ($urlQuery[$shareLink->view_type]) {
            $redirectUrl = $lang . $urlQuery[$shareLink->view_type] . $filters;
            return Redirect($redirectUrl);
          }
      }
    
      return view('errors.404');
  }
  
  private function getFilterJson($request)
  {
      $filtersRequest = $request->except(['geo_lat', 'geo_lng', 'zoom']);
      return json_encode($filtersRequest);
  }
  
  private function getFiltersArray($shareLink)
  {
      $filters = [];
      
      if ($shareLink->search_filter_json) {
          
          $filters = (array) json_decode($shareLink->search_filter_json);
          
          foreach (['geo_lat', 'geo_lng', 'zoom'] as $field) {
              if ($shareLink->$field) {
                  $filters[$field] = $shareLink->$field;
              }
          }
      }
      
      return $filters;
  }
  
  private function generateShortLink()
  {
      do {
          $shortLinkName = str_random(6);
          $checkExistingLink = ShareLink::where('short_link_name', $shortLinkName)->first();
      } while (!empty($checkExistingLink));
    
      return $shortLinkName;
  }
  
}
