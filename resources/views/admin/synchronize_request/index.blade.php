@extends('layouts.admin')
@section('content')
<div class="row">
  <div class="col-lg-12 manage manage-user-heading">

      <h1>{{ trans('common.Manage').' '. trans('common.SyncRequests') }}</h1>
      @include('admin.synchronize_request.list')

  </div>
</div>
@endsection

@section('extra_scripts')

@if($status = Session::get('status'))
<script>
    $(document).ready(function(){
        swal('Successful!!', "{{$status}}", 'success');
    });
</script>
@endif

@endsection
