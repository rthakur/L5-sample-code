<div class="addInner-Sec">
    <div class="addheading-sec">
        <h2><span><i class="glyph-icon flaticon-map-pin-signs"></i></span>{{trans('common.PropertyLocation')}}</h2>
        <br>
        <small style="float:left;"><sup><span class="asterix">*</span></sup><span style="color:##2D2E2F;">{{trans('common.RequiredMsg')}}</span></small>
        
    </div>

    @include('common.property.header')
</div>

<div class="addProSection">
    <div class="formData">
        <div class="form-group form_inner1">
            <h4>{{str_replace(':attribute',trans('common.QuestionMark'),trans('common.WhereIsPropertyLocated'))}}</h4>
            <div class="row">
                <div class="col-sm-12">
                    <input id="address-autocomplete" class="form-control custom_bg lineField" type="text" value="{{old('address')}}" placeholder="{{trans('common.EnterCityPlaceState') }}">
                </div>
            </div>

            <input type="hidden" id="address-map-country" name="country">
            <label for="submit-map"> {{ trans('common.DragTheMarker') }}</label>

            <div id="submit-map" class="mapSection"></div>

            <input type="hidden" id="latitude" name="latitude" value="{{old('latitude', isset($property) ? $property->geo_lat : '') }}" readonly>
            <input type="hidden" id="longitude" name="longitude" value="{{old('longitude', isset($property) ? $property->geo_lng : '') }}" readonly>
        </div>
    </div>
</div>
<div class="addProSection">
    <div class="formData">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group form_inner1">
                    <label for="exampleInputName1">{{trans('common.Address')}} :</label>
                    <input id="entereddAdress" class="form-control custom_bg lineField" value="{{$property->langAddress() != '' ? $property->langAddress() : '' }}" disabled>
                </div>
            </div>
            <div class="col-sm-3 location-col">
                <div class="form-group form_inner1">
                    <label for="exampleInputName1">{{trans('common.Streetaddress')}} :</label>
                    <input id="street_address" name="street_address" value="{{$property->street_address}}" class="form-control custom_bg lineField" placeholder="">
                </div>
            </div>
            <div class="col-sm-3 location-col">
                <div class="form-group form_inner1">
                    <label for="exampleInputName1">{{trans('common.ZipCode')}} :</label>
                    <input id="form-create-agency-zip" name="zip_code" value="{{$property->zip_code}}" class="form-control custom_bg lineField" placeholder="">
                </div>
            </div>
            <div class="col-sm-3 location-col">
                <div class="form-group form_inner1">
                    <label for="exampleInputName1">{{trans('common.City')}} :</label>
                    <input id="city" name="city" value="{{$property->getCity()}}" class="form-control custom_bg lineField" placeholder="">
                </div>
            </div>
            <div class="col-sm-3 location-col">
                <div class="form-group form_inner1">
                    <label for="exampleInputName1">{{trans('common.Country')}}<sup><span class="asterix">*</span></sup> :</label>
                    <div class="selectOption">
                        <select id="country_id" name="country">
                            <option value="" @if(!isset($property) && !( old('country'))) selected @endif>{{ trans('common.SelectYourCountry')}}</option>
                            @foreach(App\Models\Country::orderBy('name_en')->get() as $country)
                                <option value="{{$country->id}}" data-translated-name="{{trans('countries.'. $country->search_key)}}" data-name="{{$country->name_en}}"{{($country->id == $property->country_id)? 'selected' : ((old('country') && old('country') == $country->id) ? 'selected' : '') }}>{{ trans('countries.'. $country->search_key) }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('country'))<span class="help-block"><strong>{{ $errors->first('country') }}</strong></span> @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="btmCenterBtn"><button class="blueGradientBtn">{{ (empty($property) || (isset($property) && (!$property->preview_mode) || ($property->preview_mode && empty($property->getEditCompleted('pricing'))))) ? trans('common.Save&Continue') : trans('common.Save') }}</button></div>

@section('inner_extra_scripts')
<script>
var _latitude = {{isset($property->geo_lat)? $property->geo_lat : '48.87'}};
var _longitude = {{isset($property->geo_lng)? $property->geo_lng : '2.29'}};
var _zoom = 6;

google.maps.event.addDomListener(window, 'load', initSubmitMap(_latitude, _longitude, _zoom));
</script>
@endsection
