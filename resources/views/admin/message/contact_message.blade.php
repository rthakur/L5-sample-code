@extends('layouts.admin')
@section('content')

<div class="row">
  <div class="col-lg-12 manage manage-user-heading">
      <h1>{{ trans('common.Contact').' '. trans('common.Messages') }}</h1>
      @include('admin.message.contact_message_list')
  </div>
</div>
@endsection
