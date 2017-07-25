@extends('layouts.front')
@section('title','Update Agency')
@section('content')
    <!-- Page Content -->
    <div id="page-content">
        <div class="container">
            <header><h1>Update Agency Profile</h1></header>
            <div class="row">
                <div class="col-md-8 col-sm-12 col-md-offset-2">
                    <form role="form" action="/register/agency" id="form-create-agency" method="post">
                      {{ csrf_field() }}
                        <section>
                            <div class="form-group">
                                <label for="form-create-agency-title">Agency Title:</label>
                                <input type="text" value="{{old('agency_title')}}"class="form-control" name="agency_title" id="form-create-agency-title" required>
                                @if ($errors->has('agency_title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('agency_title') }}</strong>
                                    </span>
                                @endif
                            </div><!-- /.form-group -->
                            <div class="form-group">
                                <label for="form-create-agency-description">Description</label>
                                <textarea class="form-control" name="agency_description" id="form-create-agency-description" rows="4" required>{{old('agency_description')}}</textarea>
                                @if ($errors->has('agency_description'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('agency_description') }}</strong>
                                    </span>
                                @endif
                            </div><!-- /.form-group -->
                        </section>
                        <h3>Contact Information</h3>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <section id="address">
                                    <div class="form-group">
                                        <label for="form-create-agency-address-1">Address Line 1:</label>
                                        <input type="text" class="form-control" name="address_1" value="{{old('address_1')}}" id="form-create-agency-address-1" required>
                                        @if ($errors->has('address_1'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('address_1') }}</strong>
                                            </span>
                                        @endif
                                    </div><!-- /.form-group -->
                                    <div class="form-group">
                                        <label for="form-create-agency-address-2">Address Line 2:</label>
                                        <input type="text" class="form-control" name="address_2" value="{{old('address_2')}}" id="form-create-agency-address-2" required>
                                        @if ($errors->has('address_2'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('address_2') }}</strong>
                                            </span>
                                        @endif
                                    </div><!-- /.form-group -->
                                    <div class="row">
                                        <div class="col-md-8 col-sm-8">
                                            <div class="form-group">
                                                <label for="form-create-agency-city">City:</label>
                                                <input type="text" class="form-control" name="city" value="{{old('city')}}" id="form-create-agency-city" required>
                                                @if ($errors->has('city'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('city') }}</strong>
                                                    </span>
                                                @endif
                                            </div><!-- /.form-group -->
                                        </div><!-- /.col-md-8 -->
                                        <div class="col-md-4 col-sm-4">
                                            <div class="form-group">
                                                <label for="form-create-agency-zip">ZIP:</label>
                                                <input type="text" class="form-control" value="{{old('zip')}}" name="zip" id="form-create-agency-zip" required>
                                                @if ($errors->has('zip'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('zip') }}</strong>
                                                    </span>
                                                @endif
                                            </div><!-- /.form-group -->
                                        </div><!-- /.col-md-4 -->
                                    </div><!-- /.row -->
                                </section><!-- /#address -->
                            </div><!-- /.col-md-6 -->
                            <div class="col-md-6 col-sm-6">
                                <section id="contacts">
                                    <div class="form-group">
                                        <label for="form-create-agency-email">Email:</label>
                                        <input type="email" class="form-control" value="{{old('email')}}" name="email" id="form-create-agency-email" required>
                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div><!-- /.form-group -->
                                    <div class="form-group">
                                        <label for="form-create-agency-phone">Phone:</label>
                                        <input type="tel" class="form-control" value="{{old('phone')}}" name="phone" id="form-create-agency-phone">
                                        @if ($errors->has('phone'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('phone') }}</strong>
                                            </span>
                                        @endif
                                    </div><!-- /.form-group -->
                                    <div class="form-group">
                                        <label for="form-create-agency-website">Website:</label>
                                        <input type="text" class="form-control" value="{{old('website')}}" name="website" id="form-create-agency-website">
                                        @if ($errors->has('website'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('website') }}</strong>
                                            </span>
                                        @endif
                                    </div><!-- /.form-group -->
                                </section><!-- /#address -->
                            </div><!-- /.col-md-6 -->
                        </div><!-- /.row -->
                        <section id="submit">
                            <div class="form-group center">
                                <button type="submit" class="btn btn-default large" id="account-submit">Update Agency</button>
                            </div><!-- /.form-group -->
                        </section>
                    </form>
                    <hr>
                    <section class="center">
                        <figure class="note">By clicking the “Create Agency” button you agree with our <a href="{{SITE_LANG}}/terms-conditions">Terms and conditions</a></figure>
                    </section>
                </div><!-- /.col-md-8 -->
            </div><!-- /.row -->
        </div><!-- /.container -->
    </div>
@endsection