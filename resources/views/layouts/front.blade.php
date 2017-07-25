@php
  $currentUrl = Request::segment(2);
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="MiParo">
    <meta name="token" content="{{csrf_token()}}">
    <title>{{Config::get('app.name')}} @if($currentUrl) | @endif @yield('title')</title>
    <meta name="description" content="@yield('site_description',trans('seolinks.site_description'))" />

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:site" content="{{URL('/')}}/en">
    <meta name="twitter:title" content="@yield('title',trans('seolinks.site_title'))">
    <meta name="twitter:description" content="@yield('site_description',trans('seolinks.site_description'))">
    <meta name="twitter:image" content="@yield('image')">

    <meta name="lang" content="{{SITE_LANG}}">

    <!-- Open Graph data -->
    <meta property="og:title" content="@yield('title',trans('seolinks.site_title'))" />
    <meta property="fb:app_id" content="{{ env('FB_CLIENT_ID')}}" />
    <meta property="og:type" content="article" />
    <meta property="og:url" content="{{url()->current()}}" />
    <meta property="og:image" content="@yield('image',url('assets/img/property-img.jpg'))" />    
    <meta property="og:description" content="@yield('site_description',trans('seolinks.site_description'))" />
    <meta property="og:site_name" content="MiParo" />

    <link href='//fonts.googleapis.com/css?family=Roboto:300,400,700' rel='stylesheet' type='text/css'>
    <link href="/assets/fonts/font-awesome.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="/assets/css/bootstrap-select.min.css" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="/assets/css/jquery.slider.min.css" type="text/css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/assets/css/custom-checkbox-radio.css" type="text/css">
    <link rel="stylesheet" href="/assets/css/owl.carousel.css" type="text/css">
    <link rel="stylesheet" href="/assets/css/style.css" type="text/css">
    <link rel="stylesheet" href="/assets/css/front-style.css" type="text/css">
    <link rel="stylesheet" href="/assets/css/common.css" type="text/css">
    <link rel="stylesheet" href="/assets/css/responsive.css" type="text/css">
    <link rel="stylesheet" href="/assets/css/sweetalert2.min.css" type="text/css">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    @yield('extra-style')
    @if(env('APP_ENV') == 'production')
    <!--pingdom-->
    <script>
    var _prum = [['id', '58ebdd64264ef413d6481b14'],
                 ['mark', 'firstbyte', (new Date()).getTime()]];
    (function() {
        var s = document.getElementsByTagName('script')[0]
          , p = document.createElement('script');
        p.async = 'async';
        p.src = '//rum-static.pingdom.net/prum.min.js';
        s.parentNode.insertBefore(p, s);
    })();
    </script>
    <!--pingdom-->
    @endif
</head>

<body data-height='' data-load-location="1" data-map-clear="0" data-map-zoom="{{isset($zoom) ? $zoom : 3}}" data-map-last-view-zoom="{{isset($zoom) ? $zoom : 3}}"  data-default-zoom="{{isset($zoom) ? $zoom : 3}}" data-map-active-zoom="{{ isset($_GET['zoom']) ? $_GET['zoom'] : '3' }}" data-map-lat="{{isset($geo_lat) ? $geo_lat : 48.87}}" data-map-lng="{{isset($geo_lng) ? $geo_lng : 2.29}}" data-site-lang="{{SITE_LANG}}" data-user-ip="{{isset($_SERVER['REMOTE_ADDR'])? $_SERVER['REMOTE_ADDR'] : ''}}" class="page-homepage navigation-fixed-top map-google @if(Request::segment(2) == '')  has-fullscreen-map @endif @if(!isset($with_search_bar)) without-search-barheader-pages @endif @yield('auth_pages_class')" id="page-top" data-spy="scroll" data-target=".navigation" data-offset="90">

  <div class="main-wrapper">
      <!-- Wrapper -->
      <div class="wrapper container">

        @include('includes.navigation')

        @if((isset($view_type) && $view_type != 'List') || !isset($view_type))
            @include('includes.sidebar.left-sidebar')
        @endif

        @yield('content')

      </div>

      <div class="container margin-left-inherit"></div>
      <div class="container margin-left-inherit"></div>
  </div>
  @if(empty($disablefooter))
      @include('includes.footer')
  @endif
@if(isset($view_type))
  <input type="hidden" name="view_type" value="{{$view_type}}">
@endif

<script type="text/javascript" src="/assets/js/jquery-2.1.0.min.js"></script>
<script>
geoLocationErrorMessage = "{{ trans('common.GeoLocationError')}}";
deleteConfirmMessage = "{{ trans('common.DeleteConfirm')}}"; ///Are you sure?
deleteConfirmPermission = "{{ trans('common.DeleteConfirmPermission')}}";//You won't be able to revert this!
deleteCancelMessage = "{{ trans('common.DeletionCancelled')}}";
SendEmailConfirmPermission = "{{ trans('common.SendEmailConfirmPermission')}}";//You want to send email
ConfirmationSubscriptionMsg = "{{ trans('common.ConfirmationSubscriptionMsg')}}";//You have already an email to this agnecy
confirmButton = "{{ trans('common.confirmButton')}}";//Yes
Cancel = "{{ trans('common.Cancel')}}";
questionMark = "{{trans('common.QuestionMark')}}";
AlreadySent = "{{ trans('common.AlreadySent')}}";//"You have already sent an email to this agency"
Cancelled = "{{trans('common.Cancelled')}}";
Success = "{{trans('common.Success')}}";
MailSent = "{{trans('common.MailSent')}}";
Success = "{{trans('common.Success')}}";
ChangeLogo = "{{trans('common.ChangeLogo')}}";
deleteConfirmText = "{{trans('common.DeleteThis')}}";
AddLogo = "{{trans('common.AddLogo')}}";
SendaMessage = "{{trans('common.SendaMessage')}}";
</script>

<script type="text/javascript" src="//maps.google.com/maps/api/js?key={{env('GOOGLE_JAVASCRIPT_API_KEY','AIzaSyDu9PqQ6FnIZP-ckRlQOv6BlyUWLELOnBk')}}&libraries=places&language={{App::getLocale()}}"></script>
<script type="text/javascript" src="/assets/js/jquery.form.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="/assets/js/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="/assets/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/assets/js/smoothscroll.js"></script>
<script type="text/javascript" src="/assets/js/markerwithlabel_packed.js"></script>
<script type="text/javascript" src="/assets/js/infobox.js"></script>
<script type="text/javascript" src="/assets/js/owl.carousel.min.js"></script>
<script type="text/javascript" src="/assets/js/bootstrap-select.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript" src="/assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="/assets/js/jquery.placeholder.js"></script>
<!-- <script type="text/javascript" src="/assets/js/icheck.min.js"></script> -->
<script type="text/javascript" src="/assets/js/sweetalert2.min.js"></script>
<script type="text/javascript" src="/assets/js/jquery.vanillabox-0.1.5.min.js"></script>
<!--
<script type="text/javascript" src="/assets/js/retina-1.1.0.min.js"></script>

-->
<script type="text/javascript" src="/assets/js/jshashtable-2.1_src.js"></script>
<script type="text/javascript" src="/assets/js/jquery.numberformatter-1.2.3.js"></script>
<script type="text/javascript" src="/assets/js/tmpl.js"></script>
<script type="text/javascript" src="/assets/js/jquery.dependClass-0.1.js"></script>
<script type="text/javascript" src="/assets/js/draggable-0.1.js"></script>
<!--<script type="text/javascript" src="/assets/js/jquery.slider.js"></script>-->
<script type="text/javascript" src="/assets/js/markerclusterer_packed.js"></script>
<script type="text/javascript" src="/assets/js/bootstrap-tabcollapse.js"></script>

<script type="text/javascript" src="/assets/js/contact.js"></script>
<script type="text/javascript" src="/assets/js/custom.js"></script>
<script type="text/javascript" src="/assets/js/custom-map.js"></script>
<script type="text/javascript" src="/assets/js/search.js"></script>
<script type="text/javascript" src="/assets/js/common.js"></script>




<!--[if gt IE 8]>
<script type="text/javascript" src="assets/js/ie.js"></script>
<![endif]-->


@yield('extra_scripts')
<script type="text/javascript">
  $('select').not('.not-select2').select2();
</script>

@yield('script_without_select2')

@if(Auth::check() && (Auth::user()->role_id == '3' || Auth::user()->role_id == '4'))
<!--Intercom-->
<script>
var current_user_email = "{{Auth::user()->email}}";
var current_user_name = "{{Auth::user()->name}}";
var current_user_id = "{{Auth::id()}}";

window.intercomSettings = {
    app_id: "sc84f0kb",
    name: current_user_name, // Full name
    email: current_user_email, // Email address
    user_id: current_user_id // current_user_id
 };
(function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',intercomSettings);}else{var d=document;var i=function(){i.c(arguments)};i.q=[];i.c=function(args){i.q.push(args)};w.Intercom=i;function l(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/sc84f0kb';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);}if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})()

</script>
<!--Intercom-->
@endif

<script>

  @if(Session::has('notification_alert'))
  swal("Thank you!", "{{Session::get('notification_alert')['message']}}", "{{Session::get('notification_alert')['type']}}");
  @endif

  $(window).load(function(){
      if($('#map').length == '0')
        map = null;

      initializeOwl(false);

      if(!map){
      //Autocomplete
      var input = /** @type {HTMLInputElement} */( document.getElementsByClassName('map-search-box')[0].getElementsByClassName('address-map-input')[0] );
      var autocomplete = new google.maps.places.Autocomplete(input);

      autocomplete.addListener('place_changed', function() {
          place = autocomplete.getPlace();
          lat = place.geometry.location.lat();
          lng = place.geometry.location.lng();

          $('[name="geo_lat"]').val(place.geometry.location.lat());
          $('[name="geo_lng"]').val(place.geometry.location.lng());
          $('[name="address"]').val($(input).val()).removeAttr('disabled');
          formAction = $('#search-type-map').data('href');
          zoomVal = $('body').attr('data-map-active-zoom');
          $('[name="zoom"]').val(zoomVal > 5 ? zoomVal : 5);

          addressComponents = place.address_components;

          var address = '', city = '', country = '';

          for (var i = 0; i < addressComponents.length; i++) {

              for (var j = 0; j < addressComponents[i].types.length; j++) {

                  if(addressComponents[i].types[j] == "locality") //update city
                      city = addressComponents[i].long_name;

                  if(addressComponents[i].types[j] == "country") //update country
                      country = addressComponents[i].long_name;
              }
          }

          address = place.formatted_address;

          $.ajax({
              url: '/autocompleteupdatecitycountry',
              data: {'country' : country, 'city' : city , 'address' : address},
              async: false,
              success: function(response) {
                  var cityId = '', countryId = '' ;
                  if(response.country)
                     countryId = response.country;
                  if(response.city)
                      cityId = response.city;

                 window.location.href = formAction+'?geo_lat='+lat+'&geo_lng='+lng+'&zoom=6&address='+address+'&country='+countryId+'&city='+cityId;
              },
              error: function(data){
                  console.log('Something went wrong');
                  // Render the errors with js ...
              }
          });
      });
     }

  });

  //Google Analytics
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-88639572-1', 'auto');
  ga('send', 'pageview');
  @if(Auth::user())
  ga('set', 'userId', {{Auth::id()}}); // Set USERID of the logged in user
  @endif
  //Google Analytics ends
</script>

</body>
</html>
