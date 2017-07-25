@extends('layouts.admin')
@section('content')
<div class="row">
  <div class="col-lg-12 manage manage-user-heading">
      <h1>{{isset($zone)? trans('common.Edit') : trans('common.Add')}} {{ trans('common.NewZoneZoomLevel')}} : <span id="map_zoom_level">2</span>)</h1>
        @include('includes.notification')
      @if(!isset($zone))
      {{trans('common.SelectZoomLevel')}}
      <select name="zoom" id="select_zoom">
        @for($i=2; $i <= 8 ; $i++)
          <option @if($i == $selectedZoomLevel) selected @endif value="{{URL('/')}}/{{SITE_LANG}}/admin/zones/create?zoom={{$i}}"> {{$i }} </option>
        @endfor
      </select>
      @endif

      <div id="load-map"></div>
      <form action="/admin/zones" method="POST">
          {{csrf_field()}}
          <input type="hidden" name="zone_id" value="{{isset($zone)? $zone->id : ''}}">
          <input type="hidden" name="ne_lat" value="{{isset($zone) ? $zone->ne_lat : old('ne_lat')}}">
          <input type="hidden" name="ne_lng" value="{{isset($zone) ? $zone->ne_lng : old('ne_lng')}}">
          <input type="hidden" name="sw_lat" value="{{isset($zone) ? $zone->sw_lat : old('sw_lat')}}">
          <input type="hidden" name="sw_lng" value="{{isset($zone) ? $zone->sw_lng : old('sw_lng')}}">
          <input type="hidden" name="zoomlevel" value="2" class="form-control">
          <br>
          <button class="btn btn-default"  style="submit"> {{isset($zone)? trans('common.Update') : trans('common.Create')}}</button>
          </div>
      </form>
  </div>
</div>
@endsection

@section('extra_scripts')
<script>
  var rectangle;
  var map;
  var infoWindow;
  initMap();
  function initMap() {
    map = new google.maps.Map(document.getElementById('load-map'), {
      center: {lat: 44.5452, lng: -78.5389},
      zoom: {{$selectedZoomLevel}},
      scrollwheel:false
    });


    google.maps.event.addListener(map, 'idle', function()
    {
        zoom = map.getZoom();
        if(zoom >= 3){
          var ne = map.getBounds().getNorthEast();
          var sw = map.getBounds().getSouthWest();
          editRectangle.setMap(null);

          number = 2.5;
          ne_lat = ne.lat() + ((sw.lat() - ne.lat()) / number);
          sw_lat = sw.lat() + ((ne.lat() - sw.lat()) / number);
          ne_lng = ne.lng() + ((sw.lng() - ne.lng()) / number);
          sw_lng = sw.lng() + ((ne.lng() - sw.lng()) / number);

          var editableBounds =
          {
            north: ne_lat,
            south: sw_lat,
            east: ne_lng,
            west: sw_lng
          };

          editRectangle = new google.maps.Rectangle({
            bounds: editableBounds,
            editable: true,
            draggable: true,
            strokeColor: '#FF0000',
          });
          editRectangle.setMap(map);
          editRectangle.addListener('bounds_changed', showNewRect);
        }
    });



    var editableBounds = {
      north: {{ isset($zone)? $zone->ne_lat : $getBounds['ne_lat']}},
      south: {{ isset($zone)? $zone->sw_lat : $getBounds['sw_lat']}},
      east: {{ isset($zone)? $zone->ne_lng : $getBounds['ne_lng']}},
      west: {{ isset($zone)? $zone->sw_lng : $getBounds['sw_lng']}}
    };

    // Define the rectangle and set its editable property to true.
    editRectangle = new google.maps.Rectangle({
      bounds: editableBounds,
      editable: true,
      draggable: true,
      strokeColor: '#FF0000',
    });

    editRectangle.setMap(map);
    editRectangle.addListener('bounds_changed', showNewRect);
    $('#map_zoom_level').text(map.getZoom());
    $('input[name=zoomlevel]').val(map.getZoom());

    google.maps.event.addListener(map, 'zoom_changed', function()
    {
      $('#map_zoom_level').text(map.getZoom());
      $('input[name=zoomlevel]').val(map.getZoom());
    });

    @foreach($zones as $zone)
      var bounds = {
        north: {{$zone->ne_lat}},
        south: {{$zone->sw_lat}},
        east: {{$zone->ne_lng}},
        west: {{$zone->sw_lng}}
      };
      rectangle = new google.maps.Rectangle({
        bounds: bounds,
        editable: false,
        draggable: false,
      });
      rectangle.setMap(map);
    @endforeach
  }

  /** @this {google.maps.Rectangle} */
  function showNewRect(event) {
    $('#map_zoom_level').text(map.getZoom());
    $('input[name=zoomlevel]').val(map.getZoom());
    var ne = editRectangle.getBounds().getNorthEast();
    var sw = editRectangle.getBounds().getSouthWest();
    $('input[name=ne_lat]').val(ne.lat());
    $('input[name=ne_lng]').val(ne.lng());
    $('input[name=sw_lat]').val(sw.lat());
    $('input[name=sw_lng]').val(sw.lng());
  }

  $(function()
  {
    // bind change event to select
    $('#select_zoom').on('change', function () {
        var url = $(this).val();
        if (url) {
            window.location = url;
        }
        return false;
    });
  });
</script>
@endsection
