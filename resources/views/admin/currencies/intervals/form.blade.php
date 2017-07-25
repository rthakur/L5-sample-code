@extends('layouts.admin')
@section('content')

@section('extra-style')
<link rel="stylesheet" href="/assets/css/jquery.tagit.css" type="text/css">
<link rel="stylesheet" href="/assets/css/tagit.ui-zendesk.css" type="text/css">
@endsection

<div class="row">
  <div class="col-lg-12 manage-user-heading">
      <h1>@if(isset($selectedCurrency)) {{trans('common.Edit') .' '. ($type == 1 ? trans('common.Price') : trans('common.MonthlyFee')) .' '. trans('common.Intervals')}} - ({{$selectedCurrency->currency}}) @else {{ trans('common.Add') .' '. ($type == 1 ? trans('common.Price') : trans('common.MonthlyFee')) .' '. trans('common.Intervals')}} @endif</h1>
  </div>
  <form action="/admin/intervals/save-intervals" method="POST">
    {{csrf_field()}}
    <div class="col-lg-12">
        <div class="form-group @if(isset($selectedCurrency)) hidden @endif">
            <label>{{ trans('common.Currency')}}</label>
            <select class="form-control" name="currency">
                <option value="">{{ count($allCurrencies) ? ( trans('common.Select') .' '.trans('common.Currency') ) : trans('common.NoCurrencyToSelect')}}</option>
                @foreach($allCurrencies as $currency)
                    <option value="{{$currency->id}}" @if(isset($selectedCurrency) && $selectedCurrency->id == $currency->id) selected="selected" @endif>{{$currency->currency}}</option>
                @endforeach
            </select>
            @if($errors->has('currency'))
            <p class="help-block">{{$errors->first('currency')}}</p>
            @endif
        </div>
    <!-- /.form-group -->

        <input type="hidden" name="type" value="{{$type}}">

      <div class="form-group">
        <label>{{trans('common.Intervals')}} :</label>
        <input type="text" id="intervals" name="intervals" class="form-control" value="{{ old('intervals', isset($intervalObj) ? $intervalObj->intervals : '') }}">
        @if($errors->has('intervals'))
          <p class="help-block">{{$errors->first('intervals')}}</p>
        @endif
      </div><!-- /.form-group -->


      <button class="btn btn-default"  style="submit">{{ isset($selectedCurrency) ? trans('common.Update') : trans('common.Create') }}</button>
    </div>
  </form>
</div>
@endsection

@section('extra_scripts')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="/assets/js/tag-it.js"></script>
<script>
    $(document).ready(function() {
        $("#intervals").tagit();
    });
</script>
@endSection
