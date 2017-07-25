@extends('layouts.admin')
@section('content')
<div class="row">
  <div class="col-lg-12 manage manage-user-heading interval-section">
      <h1>{{ trans('common.Manage').' '. trans('common.PriceIntervals') }}</h1>
      <div class="col-sm-6 manage manage-user-subheading padding">
          @php
            $intervals = $priceIntervals;
            $type = 1;
          @endphp
          <a href="{{SITE_LANG}}/admin/intervals/add/{{$type}}" class="btn btn-default btn-sm pull-right manage-user "><i class="fa fa-plus" aria-hidden="true"></i> {{ trans('common.AddNewIntervals') }}</a>
          <br>
          <h2>{{trans('common.PriceIntervals') }}</h2>
          @include('admin.currencies.intervals.list')
      </div>
      <div class="col-sm-6 manage manage-user-subheading padding">
          @php
          $intervals = $monthlyFeeIntervals;
          $type = 2;
          @endphp
          <a href="{{SITE_LANG}}/admin/intervals/add/{{$type}}" class="btn btn-default btn-sm pull-right manage-user"><i class="fa fa-plus" aria-hidden="true"></i> {{ trans('common.AddNewIntervals') }}</a>
          <br>
          <h2>{{trans('common.MonthlyFeeIntervals')}}</h2>
          @include('admin.currencies.intervals.list')
      </div>
  </div>
</div>
@endsection
