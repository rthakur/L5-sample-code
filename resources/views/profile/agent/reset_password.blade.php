@extends('layouts.front')
@section('title',trans('common.ResetPassword'))
@section('content')
    <!-- Page Content -->
    <div id="page-content">
        <div class="container">
            <div class="row">
              <div class="col-sm-6 col-md-offset-3">
                <section id="contact">
                  <form role="form" action="/reset-password" id="form-account-password" method="post">
                    {{csrf_field()}}
                    <input type="hidden" name="id" value="{{$id}}">
                    <h3>{{ trans('common.BasicInfo') }}</h3>
                    <div class="form-group">
                      <label for="form-account-email">{{ trans('common.Name') }}</label>
                        <input type="text" class="form-control" id="form-account-name" name="form_account_name" required value="{{$user->name}}">
                        @if ($errors->has('form_account_name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('form_account_name') }}</strong>
                            </span>
                        @endif
                    </div><!-- /.form-group -->
                    <div class="form-group">
                      <label for="form-account-email">{{ trans('common.Email') }}</label>
                      <input type="text" class="form-control" id="form-account-email" name="email" value="{{$user->email}}" disabled>
                      @if ($errors->has('email'))
                          <span class="help-block">
                              <strong>{{ $errors->first('email') }}</strong>
                          </span>
                      @endif
                    </div><!-- /.form-group -->
                    
                    <div class="form-group">
                      <label for="form-account-password-new">{{ trans('common.NewPassword') }}</label>
                      <input type="password" class="form-control" id="form-account-password-new" name="password" required="">
                      @if($errors->has('password'))
                        <p class="help-block">{{$errors->first('password')}}</p>
                      @endif
                    </div><!-- /.form-group -->
                  
                    <div class="form-group">
                        <label for="form-account-password-confirm-new">{{ trans('common.ConfirmNewPassword') }}</label>
                        <input type="password" class="form-control" id="form-account-password-confirm-new" name="password_confirmation" required="">
                        @if($errors->has('password_confirmation'))
                          <p class="help-block">{{$errors->first('password_confirmation')}}</p>
                        @endif
                    </div><!-- /.form-group -->
                    <div class="form-group clearfix">
                        <button type="submit" class="btn pull-right btn-default" id="account-submit">{{ trans('common.SaveChanges') }}</button>
                    </div><!-- /.form-group -->
                  </form>
                </section>
              </div><!-- /.col-md-9 -->
            </div>
        </div>
    </div>
    
@endsection
