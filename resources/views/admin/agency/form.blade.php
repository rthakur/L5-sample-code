@extends('layouts.admin')
@section('title', isset($agency) ? $agency->public_name : trans('common.AgencyDetails'))
@section('content')
<div class="row">
  <div class="col-lg-12 ac">
    <div>
    <div class="header-container">
      <h1>{{trans('common.CreateAgency')}}
        <a href="{{SITE_LANG}}/admin/agency" class="btn btn-default pull-right"><i class="fa fa-list"></i> <span class="text"> {{trans('common.ListAgencies')}}</span></a>
      </h1>
      <div class="col-lg-10"> 
      <small style="float:left;"><sup><span class="asterix">*</span></sup><span style="color:##2D2E2F;">{{trans('common.RequiredMsg')}}</span></small>  
      <br>
        <form class="form-horizontal" action='/admin/agency' method="POST" enctype="multipart/form-data">
          {{csrf_field()}}
          <input type="hidden" value="{{(isset($agency)) ? $agency->id : ''}}" name="agency_id">
            <fieldset>

                <div class="control-group">
                  <label class="control-label" for="company_logo">	{{ trans('common.UploadCompanyLogo') }}</label> <span style="color:#A0A0A0">({{ trans('common.MaxFileSize') }})</span>
                  <input type="file" id="company_logo" name="company_logo" placeholder="" @if(!isset($agency))  @endif>
                  @if($errors->has('company_logo'))
                    <p class="help-block">{{$errors->first('company_logo')}}</p>
                  @endif
                </div>
                <br>

                <div class="control-group">
                  <label class="control-label"  for="username">{{trans('common.PublicName')}} <sup class="text-danger">*</sup></label>
                  <div class="controls">
                    <input type="text" id="username" name="public_name" value="{{isset($agency) ? $agency->public_name : old('public_name')}}" placeholder="" class="input-xlarge" >
                    @if($errors->has('public_name'))
                      <p class="help-block">{{$errors->first('public_name')}}</p>
                    @endif
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label"  for="company_name">	{{ trans('common.RealEstateCompanyName') }} <sup class="text-danger">*</sup></label>
                  <div class="controls">
                    <input type="text" id="company_name" name="company_name" value="{{isset($agency) ? $agency->legal_companyname : old('company_name')}}" placeholder="" class="input-xlarge" >
                    @if($errors->has('company_name'))
                      <p class="help-block">{{$errors->first('company_name')}}</p>
                    @endif
                  </div>
                </div>
                <div class="row">
                  <div class="control-group col-md-9 col-sm-12">
                    <label class="control-label"  for="email">	{{ trans('common.Email') }} <sup class="text-danger">*</sup></label>
                    <div class="controls">
                      <input type="email" id="email" name="email" value="{{ isset($agency) ? $agency->info_email : old('email')}}" placeholder="" class="input-xlarge" >
                      @if($errors->has('email'))
                        <p class="help-block">{{$errors->first('email')}}</p>
                      @endif
                    </div>
                  </div>
                  <div class="control-group col-md-3 col-sm-12">
                    <label class="control-label"  for="phone">	{{ trans('common.Phone') }}</label>
                    <div class="controls">
                      <input type="text" id="phone" name="phone" value="{{ isset($agency) ? $agency->phone : old('phone')}}" placeholder="" class="input-xlarge">
                      @if($errors->has('phone'))
                        <p class="help-block">{{$errors->first('phone')}}</p>
                      @endif
                    </div>
                  </div>
                  <div class="control-group col-md-3 col-sm-12">
                    <label class="control-label"  for="mobile">	{{ trans('common.Mobile') }}</label>
                    <div class="controls">
                      <input type="text" id="mobile" name="mobile" value="{{ isset($agency) ? $agency->mobile : old('mobile')}}" placeholder="" class="input-xlarge">
                      @if($errors->has('mobile'))
                        <p class="help-block">{{$errors->first('mobile')}}</p>
                      @endif
                    </div>
                  </div>
                  <div class="control-group col-md-9 col-sm-12">
                    <label class="control-label"  for="mobile">	{{ trans('common.Website') }}</label>
                    <div class="controls">
                      <input type="text" id="website" name="website" value="{{ isset($agency) ? $agency->website : old('website')}}" placeholder="" class="input-xlarge">
                      @if($errors->has('website'))
                        <p class="help-block">{{$errors->first('website')}}</p>
                      @endif
                    </div>
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label"  for="address_line_1">	{{ trans('common.AddressLine1') }}:</label>
                  <div class="controls">
                    <input type="text" id="address_line_1" name="address_line_1" value="{{ isset($agency) ? $agency->address_line_1 : old('address_line_1')}}" placeholder="" class="input-xlarge">
                    @if($errors->has('address_line_1'))
                      <p class="help-block">{{$errors->first('address_line_1')}}</p>
                    @endif
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label"  for="address_line_2">	{{ trans('common.AddressLine2') }}:</label>
                  <div class="controls">
                    <input type="text" id="address_line_2" name="address_line_2" value="{{ isset($agency) ? $agency->address_line_2 : old('address_line_2')}}" placeholder="" class="input-xlarge">
                    @if($errors->has('address_line_2'))
                      <p class="help-block">{{$errors->first('address_line_2')}}</p>
                    @endif
                  </div>
                </div>

                <div class="row">
                  <div class="control-group col-md-2 col-sm-12">
                    <label class="control-label"  for="zip_code">	{{ trans('common.ZipCode') }}:</label>
                    <div class="controls">
                      <input type="text" id="zip_code" name="zip_code" value="{{ isset($agency) ? $agency->zip_code : old('zip_code')}}" placeholder="" class="input-xlarge">
                      @if($errors->has('zip_code'))
                        <p class="help-block">{{$errors->first('zip_code')}}</p>
                      @endif
                    </div>
                  </div>
                  <div class="control-group col-md-5 col-sm-12">
                    <label class="control-label"  for="city">	{{ trans('common.City') }}:</label>
                    <div class="controls">
                      <input type="text" id="city" name="city" value="{{ isset($agency) ? $agency->city : old('city')}}" placeholder="" class="input-xlarge">
                      @if($errors->has('city'))
                        <p class="help-block">{{$errors->first('city')}}</p>
                      @endif
                    </div>
                  </div>
                  <div class="control-group col-md-5 col-sm-12">
                    <label class="control-label"  for="country">	{{ trans('common.Country') }} <sup class="text-danger">*</sup></label>
                    <div class="controls">
                      <select type="text" id="country" name="country" class="input-xlarge" >
                        <option value="">Select Country</option>
                        @foreach($countries as $country)
                          <option value="{{$country->id}}" @if(isset($agency) && $agency->country_id == $country->id) selected @elseif($country->id == old('country')) selected @endif>{{$country->name_en}}</option>
                        @endforeach
                      </select>
                      @if($errors->has('country'))
                        <p class="help-block">{{$errors->first('country')}}</p>
                      @endif
                    </div>
                  </div>
                </div>

                <div class="control-group">
                  <label class="control-label"  for="description">	{{ trans('common.Description') }}:</label>
                  <div class="controls">
                    <textarea type="text" id="description" name="description" placeholder="" class="input-xlarge description">{{ isset($agency) ? $agency->description : old('description')}}</textarea>
                    @if($errors->has('description'))
                      <p class="help-block">{{$errors->first('description')}}</p>
                    @endif
                  </div>
                </div>
                <br>

                <div class="control-group">
                  <div class="controls">
                    <button class="btn btn-success">{{ isset($agency) ? trans('common.Update') : trans('common.Create')}}</button>
                    <a class="btn btn-default" href="{{SITE_LANG}}/admin/agency">{{ trans('common.Cancel') }}</a>
                  </div>
                </div>
            </fieldset>
        </form>
      </div>
    </div>

  </div>

  </div>
</div>
@endsection
