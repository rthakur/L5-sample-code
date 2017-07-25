@extends('layouts.admin')
@section('content')
<div class="row">
  <div class="col-lg-12 manage manage-user-heading">
      <h1>{{ trans('common.Manage').' '. trans('common.Campaign') }}
        <a href="{{SITE_LANG}}/admin/campaign/create" class="btn btn-default pull-right"><i class="fa fa-plus"></i><span class="text"> {{ trans('common.AddNewCampaign') }} </span></a>
      </h1>


      @if($campaigns->count())
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>{{ trans('common.Name') }}</th>
              <th>{{trans('common.URL')}}</th>
             <th>{{trans('common.CampaignCode')}}</th>
              <th>{{trans('common.TrialMonths')}}</th>
              <th>{{trans('common.CreatedAt')}}</th>
              <th>{{trans('common.EndDate')}}</th>
              <th width="10%">{{ trans('common.Action') }}</th>
            </tr>
          </thead>
            <tbody class="user">
              @foreach($campaigns as $campaign)
              <tr>
                <td>{{ $campaign->name }}</td>
                <td><a href="{{ URL('/').SITE_LANG.'/campaign/'.$campaign->key }}" target="_blank">{{ URL('/').SITE_LANG.'/campaign/'.$campaign->key }}</a></td>
                <td>{{ $campaign->key }}</td>
                <td>{{ $campaign->trial_months }}</td>
                <td>{{ $campaign->created_at->diffforhumans() }}</td>
                <td>{{ $campaign->end_date }}</td>
                <td>
                  <a href="{{SITE_LANG}}/admin/campaign/{{$campaign->id}}/edit"><i class="fa fa-edit"></i> {{ trans('common.Edit') }}</a>
                  <br>
                  <a class="delete-btn" href="javascript:void(0)" data-item="{{trans('common.campaign')}}"><i class="fa fa-trash-o"></i> {{ trans('common.Delete') }}</a>
                  <form action="/admin/campaign/{{$campaign->id}}" method="post">
                    {{csrf_field()}}
                    {{ method_field('DELETE') }}
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
        </table>
        {{$campaigns->links()}}
      </div>
      @else
      <hr>
      @include('includes.no_result')
      @endif
  </div>
</div>
@endsection
