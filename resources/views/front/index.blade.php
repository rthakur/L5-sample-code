@extends('layouts.front')
@section('content')
<!-- @php 
if (Auth::check()) {
    $propertyHistory = App\Helpers\CommonHelper::propertyHistory();
}
@endphp -->
<div class="map-main-container">
    @include('front.left_container')
    <div id="map"></div>
</div>
<!-- @if(Auth::check() && count($propertyHistory))
<div class="home-history-btn">
    <button data-text="{{ trans('common.History') }}" data-name="history" class=" history-btn show-right-container"><i class="fa fa-history" aria-hidden="true"></i> {{ trans('common.History') }}</button></div>
@endif -->
<div class="home-bottom-section">
    @if(!isset($_COOKIE['enableCookies']))
        <div id="cookie-accept-terms" class="container">
          <span>{{ trans('common.CookieAcceptTermsBanner') }} <a href="{{SITE_LANG}}/cookies">{{ trans('common.LearnMore') }}</a></span>
          <button type="button" class="btn btn-default pull-right" id="accept-cookie-btn">{{ trans('common.GotIt') }}!</button>
        </div>
    @endif
</div>

@endsection

@section('extra_scripts')

<script>
    map = null;
    _siteLang = "{{SITE_LANG}}";
    _latitude = {{ $geo_lat }};
    _longitude = {{ $geo_lng }};
    _zoom = {{ $zoom }};

    var saveMapPostionCheck = {{Auth::check() ? "true" : "false"}};

</script>

<script type="text/javascript" src="/assets/js/front.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.5.12/clipboard.min.js"></script>
@endsection
