@extends('layouts.admin')
@section('content')
<div class="row">
  <div class="col-lg-12 manage manage-user-heading">
      <h1>{{ isset($campaign) ? trans('common.UpdateNewCampaign') : trans('common.AddNewCampaign') }}</h1>
  </div>
</div>
<div class="row">
    <form action="/admin/campaign" method="POST" id="campaign-form">
        {{csrf_field()}}
        <input type="hidden" name="id" value="{{ isset($campaign)? $campaign->id : '' }}" />

        <div class="col-sm-6 col-lg-3">
            <div class="form-group">
                <label>{{trans('common.CampaignName')}} :</label>
                <input type="text" name="name" class="form-control" value="{{ isset($campaign) ? $campaign->name :old('name')}}" placeholder="{{ trans('common.EnterName') }}" required >
                @if($errors->has('name'))
                    <p class="help-block">{{$errors->first('name')}}</p>
                @endif
            </div><!-- /.form-group -->
        </div>
        <div class="col-sm-6 col-lg-3">
            <label>{{trans('common.CampaignCode')}} : <button class="btn btn-warning btn-xs random-btn" type="button" title="Generates random code"><i class="fa fa-random"></i> {{trans('common.Generate')}}</button></label>
            <input type="text" name="campaign_code" class="form-control" value="{{old('campaign_code', (isset($campaign) ? $campaign->key : ''))}}" placeholder="{{ trans('common.EnterCampaignCode') }}">
            @if($errors->has('campaign_code'))
                <p class="help-block code_verification">{{$errors->first('campaign_code')}}</p>
            @endif
            <p class="help-block" id = "code_verification"></p>
        </div>
        <div class="col-sm-6 col-lg-2">
            <div class="form-group trial-group">
                <label>{{trans('common.TrialPeriod')}} :</label>
                        <select class="form-control selectHgt" name="trial_months" required >
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" @if(isset($campaign) && $campaign->trial_months == $i) selected @endif>{{ $i . ' Month' . (($i == 1) ? '' : 's') }}</option>
                            @endfor
                            <option value="24" @if(isset($campaign) && $campaign->trial_months == 24) selected @endif >24 Months</option>
                            <option value="36" @if(isset($campaign) && $campaign->trial_months == 36) selected @endif >36 Months</option>

                        </select>
                @if($errors->has('trial_months'))
                    <p class="help-block">{{$errors->first('trial_months')}}</p>
                @endif
            </div><!-- /.form-group -->
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="form-group">
                <label>{{trans('common.CampaignEndsOnDate')}} :</label>
                <input type="text" id="date-picker" class="form-control" placeholder="{{ trans('common.PickEndDate') }}">
                <input type='hidden' id="actual-date" name="end_date"  value="{{ old('end_date', isset($campaign)? $campaign->end_date : '')}}" required>
                <i class="fa fa-calendar custom-calendar-icon cursor-pointer"></i>
                @if($errors->has('end_date'))
                    <p class="help-block">{{$errors->first('end_date')}}</p>
                @endif
            </div><!-- /.form-group -->
        </div>

</div>
<br>
<div class="row">
    <div class="col-sm-3 col-sm-offset-4">
        <button class="btn btn-default btn-lg" style="submit">{{ isset($campaign) ? 'Update' : 'Add' }}</button>
    </div>
</div>
</form>
@endsection

@section('extra_scripts')
    <script>
        $(document).ready(function(){

            $('#date-picker').datepicker({
                altField: "#actual-date",
                altFormat: "yy-mm-dd",
                dateFormat: 'dd/mm/yy',
                minDate: new Date(),
                prevText: '<',
                nextText: '>'
            });

            timeout = null;

            $('[name="campaign_code"]').keyup(function(){
                if ($('[name="campaign_code"]').val().length) {
                    $('[name="campaign_code"]').val($('[name="campaign_code"]').val().toUpperCase())
                }

                if (timeout) {
                    clearTimeout(timeout);
                }

                timeout = setTimeout(function () {

                    $.ajax({
                        url: '/admin/campaign/checkCode',
                        data: {'code' : $('[name="campaign_code"]').val() },
                        async: false,
                        success: function(response) {
                            $('#code_verification').html(response);
                            $('.code_verification').html('');
                        },
                        error: function(data){
                            var response = data.responseJSON;
                            $('#code_verification').html(response.errors.code[0]);
                            $('.code_verification').html('');
                            // Render the errors with js ...
                        }
                    });

                }, 1000);
            });

            $('#date-picker').datepicker("setDate", "{{old('end_date') ? date('d/m/Y', strtotime(old('end_date'))) : (isset($campaign) && !empty($campaign->end_date) ? date('d/m/Y', strtotime($campaign->end_date)) : '+0')}}");

            $('.custom-calendar-icon').click(function(){
                $('#date-picker').datepicker($('#ui-datepicker-div').is(':visible') ? 'hide' : 'show');
            });
        });

        $('.random-btn').click(function(){
            $.ajax({
                url: '/admin/campaign/code',
                success: function(code){
                    $('[name="campaign_code"]').val(code);
                }
            });
        });
    </script>
@endsection
