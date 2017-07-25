@extends('layouts.front')
@section('title', trans('common.SendPasswordResetLink'))
@section('auth_pages_class', 'auth-pages')
@section('content')
    <!-- Page Content -->
    <div id="page-content">
        <div class="container">
                <div class="signn_page resetLink">
                    <form role="form" method="post" action="{{ url('/password/email') }}">
                        {{ csrf_field() }}
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                        <div class="form-container">
                            <div class="input-group first-child">
                              <input type="email" value="{{old('email')}}" placeholder="{{ trans('common.Email') }}" class="form-control" name="email" id="form-create-account-email" required>
                              @if ($errors->has('email'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('email') }}</strong>
                                  </span>
                              @endif
                              <span class="input-group-addon"><i class="glyph-icon flaticon-envelope"></i></span>
                            </div>
                        </div>
                        <input type="hidden" name="lang" value="{{SITE_LANG}}" />
                        <div class="form-group clearfix">
                            <button type="submit" id="SendPasswordResetLink" data-sending-text = "{{trans('common.SendingRequest')}}" class="btn pull-right btn-default btn-lg">{{ trans('common.SendPasswordResetLink') }}</button>
                        </div><!-- /.form-group -->
                    </form>
            </div>
        </div><!-- /.container -->
    </div>
@endsection
