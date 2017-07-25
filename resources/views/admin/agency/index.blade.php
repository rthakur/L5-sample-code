@extends('layouts.admin')
@section('content')
<div class="row">
  <div class="col-lg-12 manage manage-user-heading">
      <h1>{{ trans('common.Manage').' '. trans('common.Agencies') }}</h1>
      <div class="row">
          <form action="{{SITE_LANG}}/admin/agency">
            <div class="col-md-6">
              <input type="text" name="s" value="{{Input::get('s')}}" placeholder="Public Name or email">
            </div>
            <div class="col-md-2 search-col-btn"><input type="submit" class="btn btn-default" value="Search"></div>
          </form>
          <a href="{{SITE_LANG}}/admin/agency/create" class="btn btn-default pull-right manage-user"><i class="fa fa-plus"></i><span class="text"> {{ trans('common.AddNewAgency') }}</span></a>
      </div>
      @include('admin.agency.list')
  </div>
</div>
@endsection
