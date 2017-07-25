@extends('layouts.front')
@section('title', trans('common.RegisterNewAgency'))
@section('content')
    <!-- Page Content -->
    <div id="page-content">
        <div class="container">
            <header><h1>{{ trans('common.Payments') }}</h1></header>
            <div class="row">
                <div class="col-sm-12 col-md-10 col-md-offset-1">
                    @include('includes.notification')
                  <div class="well">
                    <h2>{{ trans('common.SelectedPackage') }}</h2>
                    <div class="row">
                      <div class="col-md-4 col-sm-6 col-md-offset-1">
                        <p><label>{{ trans('common.Name') }} :</label> {{ $package->name }}</p>
                        <p><label>{{ trans('common.PropertiesSubmit') }} :</label> {{ $package->getField('prop_submit_limit') }}</p>
                        <p><label>{{ trans('common.AgentProfiles') }} :</label> {{ $package->getField('agent_profiles') }}</p>
                      </div>
                      <div class="col-md-4 col-sm-6 col-md-offset-3">
                        <p><label>{{ trans('common.AgencyProfiles') }} :</label> {{ $package->getField('agency_profiles') }}</p>
                        <p><label>{{ trans('common.FeaturedProperties') }} :</label> {{ $package->getField('featured_properties') == 1 ? trans('common.Yes') : trans('common.No') }}</p>
                        <p><label>{{ trans('common.GenericStatistics') }} :</label> {{ $package->getField('generic_statistics') == 1 ? trans('common.Yes') : trans('common.No') }}</p>
                      </div>
                      <div class="text-left">
                        <div class="col-md-8 col-sm-8 col-md-offset-1">
                          <h3><label>{{ trans('common.Price') }} :</label> ${{ $package->price }}/ {{ trans('common.MontlyFee') }}</h3>
                          @if(Auth::user()->agencyCountry() && Auth::user()->agencyCountry()->search_key == 'Sweden')
                            <h3><label>{{ trans('invoice.VAT') }} :</label> ${{ ($package->price/100*25) }} <span>({{Auth::user()->vatText()}})</span></h3>
                            <h3><label>{{ Trans('invoice.Totalpricetopay') }} :</label> ${{ $package->price + ($package->price/100*25) }}/ {{ trans('common.MontlyFee') }}</h3>
                          @endif
                        </div>
                      </div>
                      
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="row">
                  <div class="col-sm-10 col-sm-offset-1">
                      @include('payment.form')
                  </div>
              </div>
        </div><!-- /.container -->
    </div>
@endsection

@section('extra_scripts')
  <script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.8.1/jquery.validate.min.js"></script>
  <script type="text/javascript" src="https://js.stripe.com/v1/"></script>
  <script type="text/javascript" src="/assets/js/stripe.js"></script>
  <script type="text/javascript">
  Stripe.setPublishableKey("{{Config::get('services.stripe.key')}}");
  </script>
@endsection
