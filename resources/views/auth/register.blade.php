@extends('layouts.front')
@section('title', trans('common.CreateanAccount') )
@section('auth_pages_class', 'auth-pages')
@section('content')
  <!-- Page Content -->
    <div id="page-content loginpage-content" class="register-page">
        <div class="container logincont">
            <!-- <header><h1 class="page-heading">{{ trans('common.CreateanAccount') }}</h1></header> -->
            <div class="login-Sec registr-sec">
            <div class="row">
                @if(Session::has('message'))
                <div class="alert-danger custom-alert text-center col-sm-6  @if(Session::has('message')) col-sm-offset-3 @endif">
                    {!! Session::get('message') !!}</div>
  <br>
                    <br>
                    @endif
              <div class="sign-inner visible-xs">
                 <div class="signText">
                <h1>{{trans('common.SignUp')}}</h1>
              </div>
              </div>
                <div class="col-sm-6">
                  <div class="regFormBg">
                    <div class="row">
                      <div class="col-sm-12">
                        <a href="{{SITE_LANG}}/auth/facebook" class="btn btn-default btn-block fb_btn"><i class="fa fa-facebook"></i>
                          {{ trans('common.Facebook') }}</a>
                      </div>
                      <div class="col-sm-12">
                        <a href="{{SITE_LANG}}/auth/google" class="btn btn-default btn-block gl_btn"><i class="fa fa-google-plus"></i>
                          {{ trans('common.Google') }}</a>
                      </div>
                    </div>
                    <div class="or-divider"><span>{{ trans('common.Or') }}</span></div>

                    <form role="form" id="form-create-account" method="post" action="/register">
                      {{ csrf_field() }}
                        <div class="form-container register-form">
                            <div class="input-group first-child">
                                <input type="text" class="form-control" placeholder="{{ trans('common.FullName') }}" name="name" value="{{old('name')}}" id="form-create-account-full-name" required>
                                @if ($errors->has('name'))
                                <span class="help-block">
                                  <strong>{{ $errors->first('name') }}</strong>
                                </span>
                                @endif
                                <span class="input-group-addon"><i class="glyph-icon flaticon-social"></i></span>
                            </div><!-- /.form-group -->
                            <div class="input-group">
                                <input type="email" class="form-control" placeholder="{{ trans('common.Email') }}" name="email" value="{{old('email')}}" id="form-create-account-email" required>
                                @if ($errors->has('email'))
                                <span class="help-block">
                                  <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                                <span class="input-group-addon"><i class="glyph-icon flaticon-envelope"></i></span>
                            </div><!-- /.form-group -->
                            <div class="input-group">
                                <input type="password" class="form-control" placeholder="{{ trans('common.Password') }}" name="password" id="form-create-account-password" required>
                                @if ($errors->has('password'))
                                <span class="help-block">
                                  <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                                <span class="input-group-addon"><i class="glyph-icon flaticon-lock"></i></span>
                            </div><!-- /.form-group -->
                            <div class="input-group">
                                <input type="password" class="form-control" placeholder="{{ trans('common.ConfirmPassword') }}" name="password_confirmation" id="form-create-account-confirm-password" required>
                                <span class="input-group-addon"><i class="glyph-icon flaticon-lock"></i></span>
                                @if ($errors->has('password_confirmation'))
                                <span class="help-block">
                                  <strong>{{ $errors->first('password_confirmation') }}</strong>
                                </span>
                                @endif
                            </div><!-- /.form-group -->
                        </div>
                        <div class="form-group">
                          <div class="checkbox newsleter-check" style="display:inline-block">
                              <input type="checkbox" id="account-type-newsletter" name="account-newsletter" @if(old('account-newsletter')) checked @endif>
                                <label for="account-type-newsletter">
                                  {{ trans('common.ReceiveNewsletter') }}
                                </label>
                          </div>
                          <div class="form-group">
                          <div class="checkbox " style="display:inline-block">
                          <input type="checkbox" id="" name=""  checked>
                            <label class="terms-check">
                              <figure class="note">{{ trans('common.AgreeCreateanAccount') }}
                                  <a href="{{SITE_LANG}}/terms-conditions">
                                      {{ trans('common.TermsAndUse') }}
                                  </a>
                                  {{ trans('common.And') }}
                                  <a href="{{SITE_LANG}}/privacy-policy">
                                      {{ trans('common.PrivacyPolicy') }}
                                  </a>
                              </figure>
                            </label>
                          </div>
                          </div>
                          <br>
                          <div class="recaptcha text-justify">
                            {!! Recaptcha::render() !!}
                            @if ($errors->has('g-recaptcha-response'))
                            <span class="help-block">
                              <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                            </span>
                            @endif
                          </div>

                        </div><!-- /.form-group -->
                        <br><br>
                        <div class="form-group text-center">
                          <button type="submit" class="btn btn-default btn-lg form-submit" id="account-submit">{{ trans('common.CreateanAccount') }}</button>
                        </div><!-- /.form-group -->
                    </form>
                    <hr class="clearfix">
                    <div class="form-bottom row">
                        <div class="col-xs-6">
                            <span class="login-account">{{ trans('common.AlreadyHaveAnAccount') }}</span>
                        </div>
                        <div class="col-xs-6 text-right">
                            <a href="{{SITE_LANG}}/login" class="btn btn-primary signbtn">{{ trans('common.Login') }}</a>
                        </div>
                    </div>
                  </div>
                  </div>
                <div class="col-sm-5">
                  <h3 class="register-agency-txt">{{ trans('common.OrRegisterAgencyMsg') }}</h3>
                  <div class="text-center pointing-arrow"><img src="/assets/img/pointing-arrow.png"></div>
                  <a href="{{SITE_LANG}}/register/agency" class="btn btn-default btn-lg register-agency-button btn-block">{{ trans('common.RegisterNewRealestateAgency') }}</a>
                </div>

            </div><!-- /.row -->
          </div>
        </div><!-- /.container -->
    </div>
@endsection
