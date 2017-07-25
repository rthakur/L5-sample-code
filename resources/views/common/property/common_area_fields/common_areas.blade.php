<div class="col-md-6 col-sm-6">
    <div class="form-group">
        <label for="{{$area_field_name}}">{{$area_view_title}}</label>
        <div class="input-group">
            <input type="text" class="form-control" id="{{$area_field_name}}" name="{{$area_field_name}}" value="{{ old($area_field_name) ?: $area_field_database_value }}" pattern="\d*">
            <span class="input-group-addon">                
                @php
                  $area_type = $area_type_field_database_value;
                  $field_name = $area_field_name.'_type';
                @endphp
                
                @include('common.property.area_type_toggle')
            </span>
            @if($errors->has($area_field_name))
              <p class="help-block">{{$errors->first($area_field_name)}}</p>
            @endif
        </div>
    </div><!-- /.form-group -->
</div><!-- /.col-md-6 -->