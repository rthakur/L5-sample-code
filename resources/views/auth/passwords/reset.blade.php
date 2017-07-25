@extends('layouts.front')
@section('title', trans('common.ResetPassword'))
@section('auth_pages_class', 'auth-pages')
@section('content')
    <!-- Page Content -->
    <div id="page-content">
        <div class="container">
            <div class="signn_page">
                <header><h1>{{ trans('common.ResetPassword') }}</h1></header>
                <form role="form" method="post" action="{{ url('/password/reset') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="form-container">
                        <div class="input-group first-child">
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
                    <div class="form-group clearfix resetPassword">
                        <button type="submit" class="btn pull-right btn-default btn-lg">{{ trans('common.ResetPassword') }}</button>
                    </div><!-- /.form-group -->
                </form>
            </div>
        </div><!-- /.container -->
    </div>
@endsection
