<a class="btn btn-info toggleBtn">
  <span class="toggleClass {{ empty($area_type) || $area_type == 'sq.m.' ? 'showbtn' : '' }}" @if(!empty($area_type) && $area_type != 'sq.m.') style="display:none" @endif>
    m<sup>2</sup>
    <input type="radio" name="{{$field_name}}" value="sq.m." class="hidden dont-style" {{ empty($area_type) || $area_type == 'sq.m.' ? 'checked' : '' }}>
  </span>
  <span class="toggleClass {{ !empty($area_type) && $area_type == 'sq.ft.' ? 'showbtn' : '' }}" @if(empty($area_type) || $area_type != 'sq.ft.') style="display:none" @endif>
    ft<sup>2</sup>
    <input type="radio" name="{{$field_name}}" value="sq.ft." class="hidden dont-style" {{ !empty($area_type) && $area_type == 'sq.ft.' ? 'checked' : '' }}>
  </span>
   <i class="fa fa-sort spinner-btn"></i>
</a>