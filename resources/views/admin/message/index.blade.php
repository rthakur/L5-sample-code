@extends('layouts.admin')
@section('content')

<div class="row">
  <div class="col-lg-12 manage manage-user-heading">
      <h1>{{ trans('common.Manage').' '. trans('common.Messages') }}</h1>
        @include('admin.message.list')
  </div>
</div>
@endsection
