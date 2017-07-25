@extends('layouts.front')
@section('title', trans('common.Login'))
@section('auth_pages_class', 'auth-pages')
@section('content')
<!-- Page Content -->
<div id="page-content loginpage-content">
	<div class="container logincont">
		@if(Session::has('message'))
			<div class="alert-danger custom-alert text-center col-md-4 col-sm-6 col-md-offset-4 col-sm-offset-3">
				{!! Session::get('message') !!}
			</div>
			<br>
			<br>
			<br>
			<br>
		@endif
		<div class="signn_page login-Sec">
			<div class="sign-inner visible-xs">
         <div class="signText">
				<h1>sign in</h1>
			</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="social_sign">
						<a href="{{SITE_LANG}}/auth/facebook" class="btn btn-default btn-block fb_btn">
							<i class="fa fa-facebook"></i>
							{{ trans('common.Facebook') }}
						</a>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-12">
					<div class="social_sign">
						<a href="{{SITE_LANG}}/auth/google" class="btn btn-default btn-block gl_btn">
							<i class="fa fa-google-plus"></i>
							{{ trans('common.Google') }}
						</a>
					</div>
				</div>
			</div>

			<div class="or-divider"><span>or</span></div>

			<div class="row">
				<div class="col-sm-12">
					<form role="form" id="form-create-account" method="post" action="/login">
						{{ csrf_field() }}
						<div class="form-container">

							<div class="input-group first-child ">
								<input type="email" value="{{old('email')}}" placeholder="{{ trans('common.Email') }}" class="form-control" name="email" id="form-create-account-email" required>
								<div class="{{ $errors->has('email') ? 'has-error' : '' }}">
									@if ($errors->has('email')) <span class="help-block"> <strong>{{ $errors->first('email') }}</strong> </span> @endif
								</div>
								<span class="input-group-addon"><i class="glyph-icon flaticon-envelope"></i></span>
							</div>

							<div class="input-group">
								<input type="password" class="form-control" placeholder="{{ trans('common.Password') }}" name="password" id="form-create-account-password" required>
								<div class="{{ $errors->has('password') ? ' has-error' : ''}}">
									@if ($errors->has('password'))<span class="help-block"><strong>{{ $errors->first('password') }}</strong></span> @endif
								</div>
								<span class="input-group-addon"><i class="glyph-icon flaticon-lock"></i></span>
							</div>
						</div><!--form-container-->

						<div class="form-middle-link text-right"><a href="{{SITE_LANG}}/password/reset">{{ str_replace("\'","'",trans('common.Idontremembermypassword')) }}</a></div>

						<div class="form-group clearfix">
							<button type="submit" class="btn btn-default btn-lg form-submit" id="account-submit">{{ trans('common.SigntoMyAccount') }}</button>
						</div>
					</form>

					<hr>

					<div class="form-bottom row">
						<div class="col-xs-6">
							<span class="login-account">{{trans('common.AccountExistMsg')}}</span>
						</div>

						<div class="col-xs-6 text-right">
							<a href="{{SITE_LANG}}/register" class="btn btn-primary signbtn">{{trans('common.Signup')}}</a>
						</div>
					</div>

				</div>
			</div>
			<!-- /.row -->
		</div>
	</div>
	<!-- /.container -->
</div>
@endsection
