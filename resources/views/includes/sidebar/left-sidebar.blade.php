@php

    if (Auth::check()) {
        $propertyHistory = App\Helpers\CommonHelper::propertyHistory();
    }

    $zoom = isset($zoom) ? $zoom : (isset($_GET['zoom']) ? $_GET['zoom'] : '');
    $formId = isset($view_type) ? 'form-'. strtolower($view_type) : 'form-sidebar';

    if(\Session::has('filtersDataSet'))
        $filtersData = \Session::get('filtersDataSet');

    if(\Session::has('address'))
      $address = \Session::get('address');

    $siteCurrency = App\Helpers\CommonHelper::getAppCurrency(false, true);

@endphp

@if(isset($with_search_bar) && isset($view_type))
    @if($view_type == 'List')

        <div class="left-col-filter-list">
    @endif

    <input class="hidden" name="site_lang" value="{{App::getLocale()}}" disabled="">
    <input class="hidden" name="site_curr" value="{{ $siteCurrency['id'] }}" disabled="">

    @if(isset($propertyId))
        <input type="hidden" id="map-property-id" value="{{$propertyId}}">
    @endif

    <div class="left-filter @if($view_type != 'List') fixed-to-left @endif" id="filterLeft">
@endif

@if(isset($with_search_bar) && isset($view_type) && ($view_type == 'List' || $view_type == 'Map' || $view_type == 'Gallery'))
<div class="clear-Main-sec">
    <div class="visible-xs">
      <div class="filterTitle"><a href="javascript:void(0)" class="filterBackArrow"><i class="glyph-icon flaticon-cancel-music"></i></a> <h3>{{trans('common.Filter')}}</h3></div>
    </div>
@endif

<form role="form" id="{{ $formId }}" class="search-form" action="{{SITE_LANG}}/buy/property" class="form-map form-search" method="get">

    <input class="hidden additional" name="country" value="{{isset($filtersData) && isset($filtersData['country']) ? $filtersData['country'] : ''}}">
    <input class="hidden additional" name="city" value="{{isset($filtersData) && isset($filtersData['city']) ? $filtersData['city'] : ''}}">
    <input class="hidden additional" name="area" value="{{isset($filtersData) && isset($filtersData['area']) ? $filtersData['area'] : ''}}">
    <input class="hidden additional" name="geo_lat" value="{{isset($filtersData) && isset($filtersData['geo_lat']) ? $filtersData['geo_lat'] : ''}}">
    <input class="hidden additional" name="geo_lng" value="{{isset($filtersData) && isset($filtersData['geo_lng']) ? $filtersData['geo_lng'] : ''}}">
    <input class="hidden additional" name="zoom" value="{{(isset($zoom) &&!empty($zoom)) ? $zoom : '3'}}">
    <input class="hidden" name="address" value="{{isset($address) ? $address : ''}}">

    @if(isset($with_search_bar) && isset($view_type))
        <div class="left-filter-inner">

            @if($view_type == 'List' || $view_type == 'Gallery')
                <div class="go-to-map">
                    <a id="goToMapView">{{trans('common.GoToMapView')}}</a>
                </div>
            @endif

            @php $data = ['PropertyType', 'Price', 'Size', 'Views', 'Proximities', 'Features']; @endphp

            @foreach($data as $key => $dt)

                <div class="panel">
                    <div class="panel-heading" role="tab" id="heading-{{$key}}">
                        <h4 class="panel-title">
                            <a role="button" data-toggle="collapse" href="#collapse-{{$key}}" aria-expanded="true" aria-controls="collapse-{{$key}}" class="trigger collapsed">
                                <h3 class="side-bar-heading">{{ trans('common.' . $dt) }}
                                    <i class="filter-count-bubble" style="display:none;"></i> <span class="fa fa-angle-up"></span>
                                </h3>
                            </a>
                        </h4>
                    </div>

                    <div id="collapse-{{$key}}" class="panel-collapse collapse " role="tabpanel" aria-labelledby="heading-{{$key}}">
                        <div class="panel-body">
                            {!! App\Helpers\PropertySearchHelper::{($dt == 'PropertyType' ?  'Type' : $dt).'FilterHtml'}($filtersData) !!}
                        </div>
                    </div>
                </div>

            @endforeach
            <div class="hidden" style="padding-top:10px;">
                <div class="show-filters"></div>
            </div>
  </div>


    @endif
</form>


@if(isset($with_search_bar) && isset($view_type))

  <div class="clearFilter hidden">

        <span class="propert-count-sec"><b class="properties-count"></b></span>

    <span class="clearBtn " data-filter-count="{{count(\Session::get('filtersDataSet'))}}">
        <button type="button" title="{{ trans('common.ClearFilters') }}" class="btn filters-clear-btn btn-sm btn-danger clear-all-filters-btn" style="display:none;">
            <i class="fa fa-trash-o"></i>
            {{ trans('common.ClearFilters') }}
        </button>
    </span>
    </div>
</div>
  </div>

@if($view_type == 'List')
    </div>


@endif

@endif
