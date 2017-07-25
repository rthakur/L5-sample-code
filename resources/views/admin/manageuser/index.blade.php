@extends('layouts.admin')
@section('content')
<div class="row">
  <div class="col-lg-12 manage manage-user-heading">
      <h1>{{ trans('common.Manage').' '. trans('common.Users') }}</h1>
        <div class="row">
          <form action="{{SITE_LANG}}/admin/manageuser">
            <div class="col-md-6">
              <input type="text" name="s" value="{{Input::get('s')}}" placeholder="{{trans('common.NameOrEmail')}}">
            </div>
            <div class="col-md-2 search-col-btn"><input type="submit" class="btn btn-default" value="{{trans('common.Search')}}"></div>
          </form>
          <a href="{{SITE_LANG}}/admin/manageuser/create" class="btn btn-default btn-sm pull-right manage-user"><i class="fa fa-plus" aria-hidden="true"></i> {{trans('common.AddNewUser')}}</a>
        </div>
      @include('admin.manageuser.list')
  </div>
</div>
@endsection
