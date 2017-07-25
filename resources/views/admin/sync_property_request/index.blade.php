@extends('layouts.admin')
@section('content')
<div class="row">
  <div class="col-lg-12 manage manage-user-heading">

      <h1>{{ trans('common.Manage').' '. trans('common.SyncPropertyRequests') }}</h1>
      @include('admin.sync_property_request.list')

  </div>
</div>
@endsection
