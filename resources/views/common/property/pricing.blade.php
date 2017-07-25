<div class="addInner-Sec">
    <div class="addheading-sec">
        <h2><span><i class="glyph-icon flaticon-tag"></i></span>{{trans('common.Pricing&Measurements')}}</h2>
        <br>
        <small style="float:left;"><sup><span class="asterix">*</span></sup><span style="color:##2D2E2F;">{{trans('common.RequiredMsg')}}</span></small>
    </div>

    @include('common.property.header')
</div>

<div class="addProSection">
    <div class="formData">
        <div  class="form-row">
            <div class="row">
                <div class="col-sm-5 pricing-col">
                    <div class="form-group">
                        <label for="exampleInputName1">{{trans('common.Currency')}}<sup><span class="asterix">*</span></sup> :</label>
                        <div class="selectOption pricing-sec">
                            <select name="currency">
                                @foreach(App\Models\Currency::get_exchangeable() as $currency)
                                    <option value="{{$currency->id}}" {{$currency->id == $property->price_currency_id ? 'selected' : ''}}>{{$currency->currency}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('currency'))<span class="help-block"><strong>{{ $errors->first('currency') }}</strong></span> @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div  class="form-row">
            <div class="row">
                <div class="col-sm-5 pricing-col">
                    <div class="form-group">
                        <label>{{trans('common.Price')}}<sup><span class="asterix">*</span></sup> :</label>
                        <input type="text" name="price" value="{{$property->price}}" class="form-control custom_bg lineField">
                        @if ($errors->has('price'))<span class="help-block"><strong>{{ $errors->first('price') }}</strong></span> @endif
                    </div>
                </div>
            </div>
        </div>
        <div  class="form-row">
            <div class="row">
                <div class="col-sm-5 pricing-col">
                    <div class="form-group">
                        <label for="exampleInputName1">{{trans('viewproperty.MonthlyFeeCurrency')}} :</label>
                        <div class="selectOption pricing-sec">
                            <select name="monthly_fee_currency">
                                <option @if(!isset($property) || !isset($property->monthly_fee_currency_id)) selected @endif value="">{{ trans('common.SelectCurrency') }}</option>
                                @foreach(App\Models\Currency::get_exchangeable() as $currency)
                                    <option value="{{$currency->id}}" @if(isset($property) && $property->monthly_fee_currency_id == $currency->id) selected @endif>{{$currency->currency}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('monthly_fee_currency'))<span class="help-block"><strong>{{ $errors->first('monthly_fee_currency') }}</strong></span> @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div  class="form-row">
            <div class="row">
                <div class="col-sm-5 pricing-col">
                    <div class="form-group">
                        <label>{{trans('common.MonthlyFee')}} :</label>
                        <input type="text" name="monthly_fee" value="{{($property->monthly_fee > 0) ? $property->monthly_fee : ''}}" class="form-control custom_bg lineField">

                        @if ($errors->has('monthly_fee'))
                            <span class="help-block"><strong>{{ $errors->first('monthly_fee') }}</strong></span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="addProSection">
    <div class="titleMain">
        <h2><span><i class="glyph-icon flaticon-chart"></i></span>{{trans('common.Size')}}</h2>
    </div>
    <div class="formData">
        <div  class="form-row">
            <div class="row">
                <div class="col-sm-5 pricing-col">
                    <div class="form-group">
                        <label for="exampleInputName1">{{trans('common.Rooms')}} :</label>
                        <div class="selectOption pricing-sec">
                            <select name="rooms">
                                @for($i = 0; $i <= 50; $i++)
                                    <option value="{{$i}}" {{$property->rooms == $i ? 'selected' : ''}}>{{$i}}</option>
                                @endfor
                            </select>
                            @if ($errors->has('rooms'))<span class="help-block"><strong>{{ $errors->first('rooms') }}</strong></span> @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div  class="form-row">
            <div class="row">
                <div class="col-sm-5 pricing-col">
                    <label>{{trans('common.TotalLivingArea')}} :</label>
                    <div class="form-inline">
                        <div class="form-group rightOptFld">
                            <div class="input-group">
                                <input type="text" name="total_living_area" value="{{$property->total_living_area}}" class="form-control custom_bg lineField">
                                <div class="input-group-addon m2">
                                    @php
                                      $area_type = $property->total_living_area_type;
                                      $field_name = 'total_living_area_type';
                                    @endphp

                                    @include('common.property.area_type_toggle')
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($errors->has('total_living_area'))<span class="help-block"><strong>{{ $errors->first('total_living_area') }}</strong></span> @endif
                </div>
            </div>
        </div>
        <div  class="form-row">
            <div class="row">
                <div class="col-sm-5 pricing-col">
                    <label>{{trans('common.TotalGardenArea')}} :</label>
                    <div class="form-inline">
                        <div class="form-group rightOptFld">
                            <div class="input-group">
                                <input type="text" name="total_garden_area" value="{{$property->total_garden_area}}" class="form-control custom_bg lineField">
                                <div class="input-group-addon m2">
                                    @php
                                      $area_type = $property->total_garden_area_type;
                                      $field_name = 'total_garden_area_type';
                                    @endphp

                                    @include('common.property.area_type_toggle')
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($errors->has('total_garden_area'))<span class="help-block"><strong>{{ $errors->first('total_garden_area') }}</strong></span> @endif
                </div>
            </div>
        </div>
    </div>
</div>
<div class="addProSection">
    <div class="titleMain">
        <h2><span><i class="glyph-icon flaticon-calendar"></i></span>{{trans('common.PropertyDetails')}}</h2>
    </div>
    <div class="formData">
        <div  class="form-row">
            <div class="row">
                <div class="col-sm-5 pricing-col">
                    <div class="form-group">
                        <label for="exampleInputName1">{{trans('BuildYear')}} :</label>
                        <div class="selectOption pricing-sec">
                            <select name="build_year">
                                <option value="">{{trans('common.SelectBuildYear')}}</option>

                                @for($i = 1950; $i <= date('Y'); $i++)
                                    <option value="{{$i}}" {{$property->build_year == $i ? 'selected' : ''}}>{{$i}}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="btmCenterBtn"><button class="blueGradientBtn">{{ (empty($property) || (isset($property) && (!$property->preview_mode) || ($property->preview_mode && empty($property->getEditCompleted('features'))))) ? trans('common.Save&Continue') : trans('common.Save') }}</button></div>
