@extends('layouts.front')
@section('title',$agency->public_name)
@section('content')
    <!-- Page Content -->
    <div id="page-content">

        <div class="container">
            <div class="row">
                <!-- Agent Detail -->
                <div class="col-md-9 col-sm-9">
                    <section id="agent-detail">
                        <header><h1>{{$agency->public_name}}</h1></header>
                        <section id="agent-info">
                            <div class="row">
                                <div class="col-md-3 col-sm-3">
                                    <img class="agency_logo" src="{{$agency->logo}}">
                                </div><!-- /.col-md-3 -->
                                <div class="col-md-5 col-sm-5">
                                    <h3>{{ trans('common.ContactInfo') }}</h3>
                                    <address>
                                        @if(isset($agency->geo_lat) && isset($agency->geo_lng)) <a href="#" class="show-on-map"><i class="glyph-icon flaticon-signs-2"></i><figure>Map</figure></a> @endif
                                        <strong>{{$agency->public_name}}</strong><br>
                                        {!! $agency->address_line_1?: '&nbsp' !!}<br>
                                        {!! $agency->address_line_2?: '&nbsp' !!}
                                    </address>
                                    <dl>
                                      <dt>{{ trans('common.Email') }} : &nbsp</dt>
                                      <dd><a href="mailto:{{$agency->info_email}}">{{$agency->info_email}}</a></dd>
                                      <dt>{{ trans('common.Phone') }} : &nbsp</dt>
                                      <dd>{!! $agency->formatPhone()?: trans('common.NA') !!}</dd>
                                      <dt>{{ trans('common.Mobile') }} : &nbsp</dt>
                                      <dd>{!! $agency->mobile?: trans('common.NA') !!}</dd>
                                    </dl>
                                </div><!-- /.col-md-5 -->
                                <div class="col-md-4 col-sm-4">
                                        <h3>{{ trans('common.ShortlyAboutUs') }}</h3>
                                        <p>{!! $agency->description?: trans('common.NA') !!}</p>
                                @if(($agencyUser->twitter) || ($agencyUser->facebook) || ($agencyUser->skype) || ($agencyUser->pinterest))
                                    <div id="social">
                                        <h3>{{ trans('common.SocialProfiles') }}</h3>
                                        <div class="agent-social">
                                            @if($agencyUser->twitter)
                                                <a href="{{$agencyUser->twitter}}" target="_blank" class="fa fa-twitter btn btn-grey-dark"></a>
                                            @endif
                                            @if($agencyUser->facebook)
                                                <a href="{{$agencyUser->facebook}}"target="_blank"class="fa fa-facebook btn btn-grey-dark"></a>
                                            @endif
                                            @if($agencyUser->pinterest)
                                                <a href="{{$agencyUser->pinterest}}" target="_blank" class="fa fa-pinterest-p btn btn-grey-dark"></a>
                                            @endif
                                            @if($agencyUser->skype)
                                                <a href="{{$agencyUser->skype}}"target="_blank"class="fa fa-skype btn btn-grey-dark"></a>
                                            @endif
                                            <!-- <a href="#" class="fa fa-linkedin btn btn-grey-dark"></a> -->
                                        </div>
                                    </div><!-- /.block -->
                                @endif
                                </div><!-- /.col-md-4 -->
                            </div><!-- /.row -->
                            <div class="row">

                            </div><!-- /.row -->
                        </section><!-- /#agent-info -->
                        @if($agentsproperties->count())
                        <hr class="thick">
                        <section id="agent-properties">
                            <header><h3>{{ trans('common.OurProperties') }} {{$agentsproperties->total()}} </h3></header>
                              <div class="row">
                                @foreach($agentsproperties as $getProperty)

                                <div class="col-md-4 property-list-cols">
                                <div class="thumbnail property-list-wrapper  property-Caption">
                                  <a href="{{$getProperty->detailPageURL()}}" class="property-list-thumb-img">
                                      <img alt="" src="{{ $getProperty->getMainImageUrl() }}">
                                  </a>
                                  <div class="caption">
                                      <hr>
                                      <div class="row">
                                          <div class="col-xs-6 total-views">
                                              <figure>{{$getProperty->langAddress()}}</figure>
                                          </div>
                                          @php
                                            $originalPrice = $getProperty->originalPrice();
                                            $exchangePrice = $getProperty->exchangePrice();
                                            $showPrice = ($originalPrice != $exchangePrice) ? $exchangePrice : $originalPrice;
                                          @endphp
                                          <div class="col-xs-6">
                                              <span class="property-price pull-right">{{$showPrice}}</span>
                                          </div>
                                      </div><!--row-->
                                    </div><!--caption-->
                                  </div><!--list-wrapper-->
                                </div>
                                @endforeach
                              </div><!-- /.row-->
                          @if(count($agentsproperties)) {{$agentsproperties->links()}} @endif
                        </section><!-- /#agent-properties -->
                        @endif
                        @if($agents->count())
                        <hr class="thick">
                            <section id="agents-listing">
                                <header><h3>{{ trans('common.OurAgents') }}</h3></header>
                                <div class="row">
                                  @include('front.agent.list')
                                </div>
                            </section><!-- /#agents-listing -->
                        @endif
                        <hr class="thick">
                        <div class="row agency-contact-form">
                            @include('front.agency.send_message')
                        </div><!-- /.row -->
                    </section><!-- /#agent-detail -->
                </div><!-- /.col-md-9 -->
                <!-- end Agent Detail -->

                <!-- sidebar -->
                @include('includes.sidebar.right_sidebar')
                <!-- end Sidebar -->
            </div><!-- /.row -->
        </div><!-- /.container -->
    </div>

@endsection
