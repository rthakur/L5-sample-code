@extends('layouts.front')
@section('title', isset($agency) ? trans('common.UpdateAgencyProfile') : trans('common.CreateAgencyProfile'))
@section('auth_pages_class', 'auth-pages')
@section('content')
    <!-- Page Content -->
    <style>body.auth-pages{background-image:none;}</style>
    <div id="page-content">
        <div class="container">
            <header>
                @include('errors.notification')
            </header>

            <div class="agencyProfile-sec">
              @if(!isset($campaignKey))
                  <div class="form-group pull-right campaign-code-form customCode-form">
                      <label for="form-create-agency-title">{{trans('common.CampaignCode')}}:</label>
                      <input type="text" class="form-control" id="campaign-code" placeholder="{{trans('common.EnterCampaignCode')}}..." required>
                      <span class="help-block hidden" id="campaign-code-errors">{{trans('common.EnterCampaignCodeMsg')}}</span>
                      <br>
                      <button class="btn btn-success pull-right" id="submit-campaign-code" style="width:100%"><span class="glyphicon glyphicon-tag"></span> {{trans('common.RevealOffer')}}</button>
                  </div>
              @endif

            <div class="row">
                <div class="col-md-8 col-sm-12 col-md-offset-2">
                    <div class="title-with-message">
                        <span class="registerheading" style="font-size: 28px;font-weight: lighter;margin-bottom: 30px;margin-top: 10px;">
                                {{ isset($agency) ? trans('common.UpdateAgencyProfile') : trans('common.CreateAgencyProfile') }}
                        </span>
                        <br>
                        <small style="float:left;"><sup><span class="asterix">*</span></sup><span style="color:##2D2E2F;">{{trans('common.RequiredMsg')}}</span></small>
                    </div>
                    <br>
                    <form role="form" action="/register/agency" id="form-create-agency" method="post" enctype="multipart/form-data">
                      {{ csrf_field() }}
                      <input type="hidden" name="agency_id" value="{{ isset($agency) ? $agency->id : '' }}">
                        <section>
                            <div class="form-group">
                                <label for="form-create-agency-title">{{trans('common.RealEstateCompanyName')}} <sup><span class="asterix">*</span></sup> :</label>
                                <input type="text" value="{{isset($agency)? $agency->public_name : old('agency_title')}}" class="form-control" name="agency_title" id="form-create-agency-title" required>
                                @if ($errors->has('agency_title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('agency_title') }}</strong>
                                    </span>
                                @endif
                            </div><!-- /.form-group -->
                            <div class="form-group">
                                <label for="form-create-agency-description">{{trans('common.Description')}}</label>
                                <textarea class="form-control" name="agency_description" id="form-create-agency-description" rows="4">{{isset($agency) ? $agency->description : old('agency_description')}}</textarea>
                                @if ($errors->has('agency_description'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('agency_description') }}</strong>
                                    </span>
                                @endif
                            </div><!-- /.form-group -->
                        </section>
                        <div>
                          <a href=""></a>
                            <section id="place-on-map">
                                <header class="section-title">
                                    <h2>{{trans('common.PlaceonMap')}}</h2>
                                    <!--<span class="link-arrow geo-location">Get My Position</span>-->
                                </header>
                                <div class="form-group">
                                    <label for="address-map">{{trans('common.Address')}}</label>
                                    <input type="text" class="form-control" id="address-autocomplete" name="address" value="{{isset($property) ? $property->address : old('address')}}">
                                    @if($errors->has('address'))
                                      <p class="help-block">{{$errors->first('address')}}</p>
                                    @endif
                                    <input type="hidden" id="address-map-country" name="country">
                                    <input type="hidden" id="address-map-state" name="state">
                                </div><!-- /.form-group -->
                                <label for="address-map">{{trans('common.DragMarkerPosition')}}</label>
                                <div id="submit-map"></div>
                            </section><!-- /#place-on-map -->
                        </div><!-- /.col-md-6 -->
                        <h3>{{trans('common.ContactInformation')}}</h3>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <section id="address">
                                    <div class="form-group">
                                        <label for="register-agency-address-1">{{trans('common.Address')}} :</label>
                                        <input type="text" class="form-control autocomplete-copy-address" name="address_1" value="{{isset($agency) ? $agency->address_line_1 : old('address_1')}}" id="register-agency-address-1">
                                        @if ($errors->has('address_1'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('address_1') }}</strong>
                                            </span>
                                        @endif
                                    </div><!-- /.form-group -->
                                    <div class="row">
                                      <div class="col-md-4 col-sm-4">
                                        <div class="form-group">
                                          <label for="form-create-agency-zip">{{trans('common.ZIP')}}:</label>
                                          <input type="text" class="form-control" placeholder="{{ trans('common.EnterZipcode') }}" value="{{isset($agency) ? $agency->zip_code : old('zip')}}" name="zip" id="form-create-agency-zip">
                                          @if ($errors->has('zip'))
                                          <span class="help-block">
                                            <strong>{{ $errors->first('zip') }}</strong>
                                          </span>
                                          @endif
                                        </div><!-- /.form-group -->
                                      </div><!-- /.col-md-4 -->
                                      <div class="col-md-8 col-sm-8">
                                          <div class="form-group">
                                              <label for="form-create-agency-city">{{trans('common.City')}}:</label>
                                              <input type="text" class="form-control" name="city" placeholder="{{ trans('common.EnterCityName') }}" value="{{isset($agency) ? $agency->city : old('city')}}" id="form-create-agency-city">
                                              @if ($errors->has('city'))
                                                  <span class="help-block">
                                                      <strong>{{ $errors->first('city') }}</strong>
                                                  </span>
                                              @endif
                                          </div><!-- /.form-group -->
                                      </div><!-- /.col-md-8 -->
                                    </div><!-- /.row -->
                                    <div class="row">
                                      <div class="col-md-6 col-sm-12">
                                          <div class="form-group">
                                              <label for="form-create-agency-city">{{ trans('common.Country') }} :</label>
                                              <select class="form-control" name="country_id" id="country_id">
                                                <option value="" {{(!isset($agency) && !(old('country_id'))) ?  'selected' : '' }}>{{ trans('common.SelectYourCountry')}}</option>
                                                @foreach(App\Models\Country::orderBy('name_en')->get() as $country)
                                                <option value="{{$country->id}}" data-name="{{$country->iso}}" {{(isset($agency) && $agency->country_id == $country->id)? 'selected' : ((old('country_id') && old('country_id') == $country->id) ? 'selected' : '') }}>{{ trans('countries.'.$country->search_key)}}</option>
                                                @endforeach
                                              </select>
                                          </div><!-- /.form-group -->
                                      </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-md-12 col-sm-12">
                                        <div class="form-group">
                                          <label for="form-create-agency-website">{{ trans('common.Logo') }}:</label>
                                          <div class="chooseOption">
                                          <input type="file" class="chooseField" name="logo" accept="image/gif, image/jpeg, image/png">


                                        </div>
                                        </div>
                                      </div>
                                    </div>
                                </section><!-- /#address -->
                            </div><!-- /.col-md-6 -->
                            <div class="col-md-6 col-sm-6">
                                <section id="contacts">
                                    <div class="form-group">
                                        <label for="form-create-agency-email">{{trans('common.Email')}} <sup><span class="asterix">*</span></sup> :</label>
                                        <input type="email" class="form-control" value="{{isset($agency) ? $agency->info_email : old('info_email')}}" name="info_email" id="form-create-agency-email" required>
                                        <span class="help-block" id = "email_verification"></span>
                                        @if ($errors->has('info_email'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('info_email') }}</strong>
                                            </span>
                                        @endif

                                    </div><!-- /.form-group -->
                                    <div class="form-group">
                                        <label for="form-create-agency-phone">{{trans('common.Phone')}}:</label>
                                        <input type="tel" class="form-control" value="{{isset($agency) ? $agency->phone : old('phone')}}" name="phone" id="form-create-agency-phone">
                                        @if ($errors->has('phone'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('phone') }}</strong>
                                            </span>
                                        @endif
                                    </div><!-- /.form-group -->
                                    <div class="form-group">
                                        <label for="form-create-agency-website">{{trans('common.Website')}}:</label>
                                        <input type="text" class="form-control" value="{{isset($agency) ? $agency->website : old('website')}}" name="website" id="form-create-agency-website">
                                        @if ($errors->has('website'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('website') }}</strong>
                                            </span>
                                        @endif
                                    </div><!-- /.form-group -->

                                </section><!-- /#address -->

                            </div><!-- /.col-md-6 -->
                        </div><!-- /.row -->
                        <br>
                        <section>
                          <div class="row">
                            <div class="col-sm-12">
                              <h2>{{ trans('common.InvoiceInformation') }}</h2>
                              <div class="same-form-container">
                                <p class="copyfromcontact" id="copyfromcontact">{{ trans('common.CopyFromContactInformation') }}</p>
                                <div class="form-group row">
                                    <div class="col-md-6 col-sm-6">
                                      <label for="form-create-agency-title">{{ trans('common.VATNumber') }}:</label>
                                      <input type="text" value="{{old('vat_number',isset($agency) ? $agency->vat_number : '')}}" class="form-control" name="vat_number">
                                      @if ($errors->has('vat_number'))
                                          <span class="help-block">
                                              <strong>{{ $errors->first('vat_number') }}</strong>
                                          </span>
                                      @endif
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                      <label for="form-create-agency-title">{{ trans('common.Streetaddress') }} <sup><span class="asterix">*</span></sup> :</label>
                                      <input type="text" value="{{old('invoice_address', isset($agency) ? $agency->invoice_address : '')}}" class="form-control" name="invoice_address" required>
                                      @if ($errors->has('invoice_address'))
                                          <span class="help-block">
                                              <strong>{{ $errors->first('invoice_address') }}</strong>
                                          </span>
                                      @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-4 col-sm-4">
                                      <label for="form-create-agency-title">{{ trans('common.ContactPerson') }} <sup><span class="asterix">*</span></sup> :</label>
                                      <input type="text" value="{{old('contact_person', isset($agency->contactPerson) ? $agency->contactPerson->first_name : '')}}"class="form-control" name="contact_person" required>
                                      @if ($errors->has('contact_person'))
                                          <span class="help-block">
                                              <strong>{{ $errors->first('contact_person') }}</strong>
                                          </span>
                                      @endif
                                    </div>
                                  <div class="col-md-2 col-sm-4">
                                      <div class="form-group">
                                          <label for="form-create-agency-zip">{{ trans('common.ZIP') }} <sup><span class="asterix">*</span></sup> :</label>
                                          <input type="text" class="form-control" value="{{ old('invoice_zip_code', isset($agency) ? $agency->invoice_zip_code : '') }}" name="invoice_zip_code" required>
                                          @if ($errors->has('invoice_zip_code'))
                                              <span class="help-block">
                                                  <strong>{{ $errors->first('invoice_zip_code') }}</strong>
                                              </span>
                                          @endif
                                      </div><!-- /.form-group -->
                                  </div><!-- /.col-md-4 -->
                                  <div class="col-md-3 col-sm-3">
                                    <div class="form-group">
                                        <label for="form-create-agency-city">{{ trans('common.City') }} <sup><span class="asterix">*</span></sup> :</label>
                                        <input type="text" class="form-control" name="invoice_city" value="{{old('invoice_city', isset($agency) ? $agency->invoice_city : '')}}" required>
                                        @if ($errors->has('invoice_city'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('invoice_city') }}</strong>
                                            </span>
                                        @endif
                                    </div><!-- /.form-group -->
                                  </div><!-- /.col-md-4 -->
                                  <div class="col-md-3 col-sm-3">
                                      <div class="form-group" id="invoice_country_container">
                                          <label for="form-create-agency-city">{{ trans('common.Country') }} :</label>
                                          <select class="form-control" name="invoice_country" id="invoice_country">
                                            <option value="" {{(!isset($agency) && !(old('invoice_country'))) ?  'selected' : '' }}>{{ trans('common.SelectYourCountry')}}</option>
                                            @foreach(App\Models\Country::orderBy('name_en')->get() as $country)
                                            <option value="{{$country->id}}" {{(isset($agency) && $agency->invoice_country == $country->id)? 'selected' : ((old('invoice_country') && old('invoice_country') == $country->id) ? 'selected' : '') }}>{{ trans('countries.'.$country->search_key)}}</option>
                                            @endforeach
                                          </select>
                                      </div><!-- /.form-group -->
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </section>

                        @include('auth.agency.packages')

                    <hr>
                    <section class="center" style="clear:both">
                        <figure class="note">
                             {{ trans('common.AgreeCreateanAgency') }}
                            <a href="{{SITE_LANG}}/terms-conditions">
                                {{ trans('common.TermsAndUse') }}
                            </a>
                            and
                            <a href="{{SITE_LANG}}/privacy-policy">
                                {{ trans('common.PrivacyPolicy') }}
                            </a>
                        </figure>
                        <br>
                        <section style="clear:both">
                            <div class="recaptcha">
                                {!! Recaptcha::render() !!}
                                @if ($errors->has('g-recaptcha-response'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                </span>
                                @endif
                            </div>
                        </section>
                        <section id="submit" style="clear:both">
                            <div class="form-group center">
                                <input type="hidden" name="campaign" value="{{ isset($campaignKey)? $campaignKey : '' }}" />
                                <button type="submit" class="btn btn-default large" id="account-submit">{{ (isset($agency) && $agency) ? trans('common.UpdateAgency') : trans('common.CreateAgency') }}</button>
                            </div><!-- /.form-group -->
                        </section>
                    </section>

                    <input type="hidden" id="latitude" name="latitude" value="">
                    <input type="hidden" id="longitude" name="longitude" value="">

                  </form>
                </div><!-- /.col-md-8 -->
            </div><!-- /.row -->
          </div>
        </div><!-- /.container -->
    </div>
@endsection

@section('extra_scripts')

<script>
  $(document).ready(function(){
      var _latitude = {{old('latitude', (isset($agency->geo_lat) ? $agency->geo_lat : '0')) }};
      var _longitude = {{old('longitude', (isset($agency->geo_lng) ? $agency->geo_lng : '0'))}};
      var _zoom = {{ old('zoom') ?: 'null' }};
      var _sitelang = "{{SITE_LANG}}";
      google.maps.event.addDomListener(window, 'load', initSubmitMap(_latitude,_longitude, _zoom));
      $('#campaign-code').keypress(function(ev){
          if (ev.which == 13) {
              $('#submit-campaign-code').click();
          }
      });

      $('#submit-campaign-code').click(function(){
          campaignCode = $('#campaign-code').val();
          if (campaignCode) {
              window.location.href = _sitelang + '/campaign/' + campaignCode;
          } else {
              $('#campaign-code-errors').removeClass('hidden');
          }
      });

      $('.radio input').click(function () {
          $('input:not(:checked)').parents('.customTable-feature').removeClass("customTable-feature1");
          $('input:checked').parents('.customTable-feature').addClass("customTable-feature1");
      });

      timeout = null;

      $('[name="info_email"]').keyup(function(){

          if (timeout) {
              clearTimeout(timeout);
          }

          timeout = setTimeout(function () {
              if($('[name="info_email"]').val() != '')
              {
              $.ajax({
                  url: '/validateEmail',
                  data: {'info_email' : $('[name="info_email"]').val() },
                  async: false,
                  beforeSend: function() {
                      $('#email_verification').html('');
                  },
                  success: function(response) {
                      $('#email_verification').html('');
                  },
                  error: function(data){
                      var response = data.responseJSON;
                      $('#email_verification').html(response.errors.info_email[0]);
                      // Render the errors with js ...
                  }
              });
              }

          }, 1000);
      });
  });

</script>

<script src="/assets/js/map.common.js" type="text/javascript"></script>
@endsection

@section('script_without_select2')
<script>
// @if(!empty($error) || $errors->has('campaign'))
//         var error = "{{!empty($error) ? $error : $errors->first('campaign')}}";
//         swal("Oops!", error, 'warning').then(
//             function () {
//                 window.location.href = "{{url(SITE_LANG. '/register/agency')}}";
//             },
//             // handling the promise rejection
//             function (dismiss) {
//                 window.location.href = "{{url(SITE_LANG. '/register/agency')}}";
//             }
//         );
// @elseif(!empty($campaignMonths))
//     swal("{{ trans('common.Congratulations') }}" + "!!", "{!! str_replace('{number}', $campaignMonths, trans('common.CampaignSuccessMsg')) !!}", 'success');
// @endif
</script>
@endsection
<script>
    window.intercomSettings = {
        app_id: "sc84f0kb"
    };
</script>
<script>(function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',intercomSettings);}else{var d=document;var i=function(){i.c(arguments)};i.q=[];i.c=function(args){i.q.push(args)};w.Intercom=i;function l(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/sc84f0kb';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);}if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})()</script>
