@extends('layouts.admin')
@section('content')
<div class="row">
  <div class="col-lg-12">
      <h1>Add New User</h1>
  </div>
  <form action="/admin/manageuser" method="POST">
    {{csrf_field()}}
    <div class="col-lg-12">
      <div class="form-group">
        <label>Name :</label>
        <input type="text" name="name" class="form-control" required >
        @if($errors->has('name'))
          <p class="help-block">{{$errors->first('name')}}</p>
        @endif
      </div><!-- /.form-group -->
      
      <div class="form-group">
        <label>Email :</label>
        <input type="email" name="email" class="form-control"  autocomplete="off"  required>
        @if($errors->has('email'))
          <p class="help-block">{{$errors->first('email')}}</p>
        @endif
      </div><!-- /.form-group -->
      
      <div class="form-group">
        <label>{{ trans('common.Role')}}</label>
        <select class="form-control" name="role_id" required="required" onchange="Role_Related_Selection($(this));">
          <option value="">{{trans('common.Select')}} {{trans('common.Role')}}</option>
          @foreach($roles as $role)
            <option value="{{$role->id}}">{{$role->name}}</option>
          @endforeach
        </select>
        @if($errors->has('role'))
          <p class="help-block">{{$errors->first('role')}}</p>
        @endif
      </div><!-- /.form-group -->
      
      <div class="form-group" id="agencie" style="display:none;">
        <label>{{ trans('common.Agencie')}}</label>
        <select class="form-control" name="agency_id" >
          <option value="">{{trans('common.Select')}} {{trans('common.Agencie')}}</option>
          @foreach($agencies as $agencie)
            <option value="{{$agencie->id}}">{{$agencie->public_name}}</option>
          @endforeach
        </select>
        @if($errors->has('agencie'))
          <p class="help-block">{{$errors->first('agencie')}}</p>
        @endif
      </div><!-- /.form-group -->
    
      <div class="form-group">
        <label>Password :</label>
        <input type="password" name="password" class="form-control" autocomplete="off" required>
        @if($errors->has('password'))
          <p class="help-block">{{$errors->first('password')}}</p>
        @endif
      </div><!-- /.form-group -->
      
      <button class="btn btn-default"  style="submit">{{ trans('common.Create') }}</button>
    </div>
  </form>
</div>
@endsection

@section('extra_scripts')
<script>
  function Role_Related_Selection (object) {
      //default properties
      $('#agencie').hide().find('select').removeAttr('required').val("");
      
      if (object.val() == 3) $('#agencie').show().find('select').attr('required','true'); 
  }
</script>
@endSection
