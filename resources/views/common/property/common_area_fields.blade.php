<div class="row">
    <div class="col-md-6 col-sm-6">
        <div class="form-group">
            <label for="submit-property-type">{{ trans('common.PropertyType') }}<sup><span class="asterix">*</span></sup></label>
            <select name="type" id="submit-property-type" class="form-control">
              <option value="">{{ trans('common.SelectType') }}</option>
                @foreach(App\Models\PropertyTypes::all() as $type)
                  <option value="{{$type->id}}" @if(old('type') == $type->id) selected @elseif(isset($property) && $property->property_type_id == $type->id) selected @endif>{{trans('common.'. $type->name)}}</option>
                @endforeach
            </select>
            @if($errors->has('type'))
              <p class="help-block">{{$errors->first('type')}}</p>
            @endif
        </div><!-- /.form-group -->
    </div><!-- /.col-md-6 -->
</div>
<div class="row">
  @php
    $area_view_title = trans('viewproperty.TotalLivingArea');
    $area_field_name = 'total_living_area';
    $area_field_database_value = isset($property) ? $property->total_living_area : '';
    $area_type_field_database_value = isset($property) ? $property->total_living_area_type : '';
  @endphp
  @include('common.property.common_area_fields.common_areas')
  
  @php
    $area_view_title = trans('viewproperty.TotalGardenArea');
    $area_field_name = 'total_garden_area';
    $area_field_database_value = isset($property) ? $property->total_garden_area : '';
    $area_type_field_database_value = isset($property) ? $property->total_garden_area_type : '';
  @endphp
  @include('common.property.common_area_fields.common_areas')
</div><!-- /.row -->

<div class="row">
  @if(isset($property))
    @php $propertyFeatures = $property->getFeatureValues(); @endphp
  @endif
    <div class="col-md-6 col-sm-6">
        <div class="form-group">
            <label for="submit-bedrooms">{{ trans('viewproperty.Rooms') }}</label>
            <select id="submit-bedrooms" name="rooms" class="form-control">
                @for($i = 0; $i <= 50; $i++)
                    <option value="{{$i}}" {{isset($property) && $property->rooms == $i ? 'selected' : (old('rooms') == $i ? 'selected' : '')}}>{{$i}}</option>
                @endfor
            </select>
            @if ($errors->has('rooms'))<span class="help-block"><strong>{{ $errors->first('rooms') }}</strong></span> @endif
        
            <!-- <input type="text" class="form-control" id="submit-bedrooms" name="rooms" value="" pattern="\d*" required> -->
            @if($errors->has('rooms'))
              <p class="help-block">{{$errors->first('rooms')}}</p>
            @endif
        </div><!-- /.form-group -->
    </div><!-- /.col-md-6 -->
    <div class="col-md-6 col-sm-6">
        <div class="form-group">
            <label for="build-year">{{ trans('common.BuildYear') }}</label>
            <div class="input-group">
                <input type="text" class="form-control" id="build-year" name="build_year" value="{{ old('build_year',(isset($property) && $property->build_year) ? $property->build_year : '')}}" pattern="[0-9]{4}" placeholder="YYYY">
                @if($errors->has('build_year'))
                  <p class="help-block">{{$errors->first('build_year')}}</p>
                @endif
            </div>
        </div><!-- /.form-group -->
    </div><!-- /.col-md-6 -->
</div><!-- /.row -->
<div class="row">
  <div class="col-md-6 col-sm-6">
      <div class="form-group">
          <label for="submit-bedrooms">{{ trans('viewproperty.MonthlyFee') }}</label>
          <input type="text" class="form-control" id="submit-bedrooms" name="monthly_fee" value="{{ old('monthly_fee',(isset($property) && $property->monthly_fee) ? $property->monthly_fee : '')}}" pattern="\d*">
      </div><!-- /.form-group -->
  </div><!-- /.col-md-6 -->
  <div class="col-md-6 col-sm-6">
      <div class="form-group">
          <label for="submit-bedrooms">{{ trans('viewproperty.MonthlyFeeCurrency') }}</label>
          <select name="monthly_fee_currency">
              <option @if(!isset($property) || !isset($property->monthly_fee_currency_id)) selected @endif value="">{{ trans('common.SelectCurrency') }}</option>
            @foreach($allCurrency as $currency)
              <option value="{{$currency->id}}" @if(isset($property) && $property->monthly_fee_currency_id == $currency->id) selected @endif>{{$currency->currency}}</option>
            @endforeach
          </select>
          @if($errors->has('monthly_fee_currency'))
            <p class="help-block">{{$errors->first('monthly_fee_currency')}}</p>
          @endif
      </div><!-- /.form-group -->
  </div><!-- /.col-md-6 -->
</div><!-- /.row -->