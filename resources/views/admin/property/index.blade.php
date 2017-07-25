@extends('layouts.admin')
@section('content')
<div class="row">
  <div class="col-lg-12 manage-user-heading">
      <h1>{{ trans('common.Manage').' '. trans('common.Properties') }}
        <!-- <a href="/admin/agency/create" class="btn btn-default pull-right"><i class="fa fa-plus"></i><span class="text"> Add New Agency</span></a> -->
      </h1>
      <div class="property-list-count">
          <label>{{trans('common.TotalProperties')}} </label> <span> {{$properties->total()}}</span>
      </div>
        <div class="row" style="margin-bottom: 15px;">

            <form mathod="get" id="filter-property">
                <div class="col-sm-4 col-md-3 property-list-filter">
                    <select name="country" class="select-filter select2 form-control" id="select-country">
                        <option value="">- - - {{trans('common.Country')}} - - -</option>
                        @foreach($countries as $country)
                        <option value="{{$country->id}}"@if(isset($activecountryId) && $activecountryId == $country->id) selected @endif>{{$country->name_en}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-4 col-md-3 property-list-filter" id="agency-filter">
                    <select name="agency" class="select-filter select2 form-control" id="select-agency">
                        <option value="">- - - {{trans('common.Agency')}} - - -</option>
                        @foreach($agencies as $agency)
                        <option value="{{$agency->id}}" @if(isset($activeagencyId) && $activeagencyId == $agency->id) selected @endif>{{$agency->public_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-4 col-md-3 property-list-filter" id="agent-filter">
                    <select name="agent" id="select-agent" class="select2 form-control">
                        <option value="">- - - {{trans('common.Agent')}} - - -</option>
                        @foreach($agents as $agent)
                        <option value="{{$agent->id}}" @if(isset($activeagentId) && $activeagentId == $agent->id) selected @endif>{{$agent->name}}</option>
                        @endforeach

                    </select>
                </div>
                <div class="col-sm-12 col-md-3">
                  <div class="properties-btn-sec">
                    <button class="btn btn-sm btn-primary"> {{trans('common.Go')}}</button>
                  </div>
                </div>
        </form>
     </div>
      @include('admin.property.list')

  </div>
</div>
@endsection
@section('extra_scripts')
<script>
$('body').on('change','.select-filter', function(){
    if($(this).attr('id') =='select-country')
        $('#select-agency').val('');

    $.ajax({
        url : '/admin/filter-property',
        data : $('#filter-property').serialize(),
        success : function(data){
            if(data.agencies)
            $('#select-agency').html(Agencylist(data.agencies));
            $('#select-agent').html(Agentlist(data.agents));
        }
    });
});

$('#filter-property').submit(function(){
    if(!$('#select-country').val())
        $('#select-country').attr('disabled','disabled');

    if(!$('#select-agency').val())
        $('#select-agency').attr('disabled','disabled');

    if(!$('#select-agent').val())
        $('#select-agent').attr('disabled','disabled');
});

function Agencylist(agencies)
{
    agencyhtml='<option value="">- - Agency - -</option>';
        $.each(agencies, function( key, agency){
            agencyhtml+='<option value="'+agency.id+'">'+agency.public_name+'</option>';
        });
    return agencyhtml;
}
function Agentlist(agents)
{
    agenthtml='<option value="">- - Agent - -</option>';
        $.each(agents, function( key, agent){
            agenthtml+='<option value="'+agent.id+'">'+agent.name+'</option>';
        });
    return agenthtml;
}
</script>
@endsection
