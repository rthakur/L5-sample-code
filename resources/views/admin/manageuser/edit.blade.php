@extends('layouts.admin')
@section('content')
<div class="row">
  <div class="col-lg-12">
      <h1>Edit User
        <!-- <a href="/admin/agency/create" class="btn btn-default pull-right"><i class="fa fa-plus"></i><span class="text"> Add New Agency</span></a> -->
      </h1>
  </div>
  <form action="/admin/manageuser/{{$user->id}}" method="post">
    {{csrf_field()}}
    {{ method_field('PUT') }}
    
    <div class="col-lg-12">
      
      <div class="form-group">
        <label>Name :</label>
        <input type="text" class="form-control" disabled="" value="{{$user->name}}">
      </div><!-- /.form-group -->
      
      <div class="form-group">
        <label>Email :</label>
        <input type="email" class="form-control" disabled="" value="{{$user->email}}">
      </div><!-- /.form-group -->
      
      <div class="form-group">
        <label>{{ trans('common.Role')}}</label>
        <select class="form-control" name="role_id" disabled="">
          <option>{{ trans('common.Role')}}</option>
          @foreach($roles as $role)
            <option @if(isset($user->role_id) && $user->role_id == $role->id) selected @endif value="{{$role->id}}">{{$role->name}}</option>
          @endforeach
          @if($errors->has('role_id'))
            <p class="help-block">{{$errors->first('role_id')}}</p>
          @endif
        </select>
      </div><!-- /.form-group -->
      
      @if($user->role_id == '3')
      <div class="form-group">
        <label>{{ trans('common.Agency')}}</label>
        <select class="form-control" name="agency">
          <option>{{ trans('common.SelectAgency')}}</option>
          @foreach($agencies as $agency)
            <option @if(isset($user->agency) && $user->agency->id == $agency->id) selected @endif value="{{$agency->id}}">{{$agency->public_name}}</option>
          @endforeach
          @if($errors->has('agency'))
            <p class="help-block">{{$errors->first('agency')}}</p>
          @endif
        </select>
      </div><!-- /.form-group -->
      @endif
      
      <button class="btn btn-success">{{ trans('common.Update') }}</button>
      <a href="{{SITE_LANG}}/admin/manageuser" class="btn btn-default">{{ trans('common.Cancel') }}</a>
    </div>
    
  </form>
</div>
@endsection