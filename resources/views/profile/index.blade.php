@extends('layouts.front')
@section('title', trans('common.Profile'))
@section('content')
    <!-- Page Content -->
    <div id="page-content">
        <div class="container">
            <div class="row">
            <!-- sidebar -->
            @include('profile.sidebar')
                <!-- My Properties -->
                <div class="col-md-9 col-sm-10 profile-inner-sec">
                  @include('includes.notification')
                    <section id="profile">
                        <header>
                            @if(Auth::user()->agency_id)
                                @php $agency = Auth::user()->agency; @endphp
                                <div class="" style="margin-right: ;">
                                    @if($agency->logo)
                                        <img class="img-responsive" style="width:207px;height:184px;" src="{{$agency->logo}}">
                                    @endif
                                    <h2>{{$agency->public_name}}</h2>
                                </div>
                            @endif
                            <h1>{{ trans('common.Profile') }}</h1>
                        </header>
                        <div class="account-profile">
                            <div class="row">
                            @if(Auth::user()->role_id == 3)
                                <div class="col-md-3 col-sm-3">

                                    <img alt="" class="image" src="{{Auth::user()->profile_picture ?: '/assets/img/agent-01.jpg'}}">
                                    <button type="button" class="btn btn-danger  {{Auth::user()->profile_picture ? '' : 'hidden'}}" id="remove-profile-pic-btn" data-noprofile-btn-text = "{{trans('common.BrowseProfilePic')}}" data-item ="{{'common.Image'}}" href="/account/profile/delete-pic/{{base64_encode(Auth::id())}}">×</button>
                                    <form role="form" action="/account/save-profile"  method="post" enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        <input class="upload hidden" id="agency-logo-file" data-text-name ="{{trans('common.ChangeProfilePic')}}" type="file" name="profile_picture" accept="image/gif, image/jpeg, image/png" onchange="readURL(this, '.image')">
                                    </form>
                                    <!-- /.form-group -->
                                    <div class="form-group logo-change-btn">
                                        <div class="fileUpload btn" >
                                            <span id="browse-btn">{{Auth::user()->profile_picture ? trans('common.ChangeProfilePic') : trans('common.BrowseProfilePic') }}</span>
                                        </div>
                                    </div>
                                    <!-- <input type="file" name="profile_picture" accept="image/gif, image/jpeg, image/png"  onchange="readURL(this, '.image');"> -->
                                </div>
                             @endif
                                <div class="col-md-{{Auth::user()->role_id != 3 ? '12' : '9'}} col-sm-{{Auth::user()->role_id != 3 ? '12' : '9'}}">
                                <section id="contact">
                                  <h3>{{ trans('common.Contact') }}</h3>
                                  <form role="form" id="form-account-profile" action="/account/profile" method="post" enctype="multipart/form-data">
					                {{csrf_field()}}
                                      <div class="contact-fields">
                                          <div class="col-md-6 col-sm-6">
                                              <label for="form-account-name">{{ trans('common.YourName') }}:</label>
                                              <div class="form-group">
                                                  <input type="text" class="form-control" id="form-account-name" name="form_account_name" required value="{{Auth::user()->name}}">
                                                  @if ($errors->has('form_account_name'))
                                                      <span class="help-block">
                                                          <strong>{{ $errors->first('form_account_name') }}</strong>
                                                      </span>
                                                  @endif
                                              </div><!-- /.form-group -->
                                              <label for="form-account-email">{{ trans('common.Email') }}:</label>
                                              <div class="form-group">
                                                <input type="text" class="form-control" id="form-account-email" name="email" value="{{Auth::user()->email}}" {{(Auth::user()->role_id == '2') ? 'readonly' : ''}}>
                                                @if ($errors->has('email'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('email') }}</strong>
                                                    </span>
                                                @endif
                                              </div><!-- /.form-group -->
                                          </div>
                                          <div class="col-md-6 col-sm-6">
                                              <label for="form-account-phone">{{ trans('common.Phone') }}:</label>
                                              <div class="form-group">
                                                  <input type="text" class="form-control" id="form-account-phone" name="form_account_phone" value="{{Auth::user()->phone}}">
                                                  @if ($errors->has('form_account_phone'))
                                                      <span class="help-block">
                                                          <strong>{{ $errors->first('form_account_phone') }}</strong>
                                                      </span>
                                                  @endif
                                              </div><!-- /.form-group -->
                                              <label for="form-account-mobile">{{ trans('common.Mobile') }}:</label>
                                              <div class="form-group">
                                                  <input type="text" class="form-control" id="form-account-mobile" name="form_account_mobile" value="{{Auth::user()->mobile}}">
                                                  @if ($errors->has('form_account_mobile'))
                                                      <span class="help-block">
                                                          <strong>{{ $errors->first('form_account_mobile') }}</strong>
                                                      </span>
                                                  @endif
                                              </div><!-- /.form-group -->
                                          </div>
                                          @if(Auth::user()->role_id == 2)
                                            <div class="col-md-12 col-sm-12">
                                              <div class="form-group">
                                                  <div class="checkbox newsleter-check" style="display:inline-block">
                                                      <input type="checkbox" id="account-type-newsletter" name="receiveNewsletter" @if(Auth::user()->receive_newsletter) checked @endif>
                                                        <label for="account-type-newsletter">
                                                          {{ trans('common.ReceiveNewsletter') }}
                                                        </label>
                                                    </div>
                                               </div>
                                            </div>
                                          @endif
                                      </div>
                                      <div class="form-group clearfix">
                                          <button type="submit" class="btn pull-right btn-default" id="account-submit">{{ trans('common.SaveChanges') }}</button>
                                      </div><!-- /.form-group -->
                                  </form><!-- /#form-contact -->
                                </section>

                                    <section id="change-password">
                                        <header><h2>{{ trans('common.ChangeYourPassword') }}</h2></header>
                                        <div class="row">
                                            <form role="form" action="/account/profile/{{Auth::id()}}" id="form-account-password" method="post" >
                                                {{csrf_field()}}
                                                {{ method_field('PUT') }}
                                                <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="form-account-password-current">{{ trans('common.CurrentPassword') }}</label>
                                                        <input type="password" class="form-control" id="form-account-password-current" name="current_password" required="">
                                                        @if($errors->has('current_password'))
                                                        <p class="help-block">{{$errors->first('current_password')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="form-account-password-new">{{ trans('common.NewPassword') }}</label>
                                                        <input type="password" class="form-control" id="form-account-password-new" name="password" required="">
                                                        @if($errors->has('password'))
                                                          <p class="help-block">{{$errors->first('password')}}</p>
                                                        @endif
                                                    </div><!-- /.form-group -->
                                                </div>
                                                    <div class="col-md-12 col-sm-12">
                                                        <div class="form-group">
                                                            <label for="form-account-password-confirm-new">{{ trans('common.ConfirmNewPassword') }}</label>
                                                            <input type="password" class="form-control" id="form-account-password-confirm-new" name="password_confirmation" required="">
                                                            @if($errors->has('password_confirmation'))
                                                            <p class="help-block">{{$errors->first('password_confirmation')}}</p>
                                                            @endif
                                                        </div><!-- /.form-group -->
                                                        <div class="form-group clearfix">
                                                            <button type="submit" class="btn pull-right btn-default" id="form-account-password-submit">{{ trans('common.ChangePassword') }}</button>
                                                        </div><!-- /.form-group -->
                                                    </div>
                                                  </form><!-- /#form-account-password -->
                                            </div>
                                    </section>
                                </div><!-- /.col-md-9 -->
                            </div><!-- /.row -->
                        </div><!-- /.account-profile -->
                    </section><!-- /#profile -->
                </div><!-- /.col-md-9 -->
                <!-- end My Properties -->
            </div><!-- /.row -->
        </div><!-- /.container -->
    </div>
@endsection
