@extends('layouts.front')
@section('title', trans('common.RegisterNewAgency'))
@section('content')
<!-- Page Content -->
<div id="page-content">
  <div class="container">
    <header><h1>{{ trans('common.PaymentSuccess')  }}</h1></header>
    <div class="row">
      <div class="col-sm-12 col-md-10 col-md-offset-1">
        <h2 class="text-center"> {{ trans('common.PaymentSuccessMessage')  }}</h2>
      </div>
    </div>
  </div>
</div>
@endsection