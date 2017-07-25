@extends('layouts.admin')
@section('content')
<div class="row">
  <div class="col-lg-12 manage manage-user-heading">
      <h1>{{ trans('common.Manage').' '. trans('common.Zones') }}

      </h1>
      <div class="row">
        <div class="col-sm-12">
<a href="{{SITE_LANG}}/admin/zones/create" class="btn btn-default pull-right manage-user"><i class="fa fa-plus"></i><span class="text"> {{ trans('common.AddNewZone') }}</span></a>
</div>
</div>
      @include('admin.zones.list')
  </div>
</div>
@endsection
