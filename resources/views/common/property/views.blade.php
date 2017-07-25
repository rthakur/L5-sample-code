<div class="addInner-Sec">
    <div class="addheading-sec">
        <h2><span><i class="glyph-icon flaticon-medical"></i></span>{{trans('common.PropertyViews')}}<sup><span class="asterix">*</span></sup></h2>
        <br>
        <small style="float:left;"><sup><span class="asterix">*</span></sup><span style="color:##2D2E2F;">{{trans('common.RequiredMsg')}}</span></small>
    </div>
    
    @include('common.property.header')
</div>

<div class="addProSection">
    <div class="formData check-main property-chk">
        <div class="row">
            <div class="col-sm-12">
                <div class="chk-inner">
                    <ul>
                        @php $propertyViews = $property->views->pluck('view_id')->toArray(); @endphp

                        @foreach(App\Models\View::all() as $view)
                        <li>
                            <div class="btn-group" data-toggle="buttons">
                                <label class="btn chk-btn {{in_array($view->id, $propertyViews) ? 'active' : ''}}">
                                    <input type="checkbox" name="views[]" value="{{$view->id}}" autocomplete="off" {{in_array($view->id, $propertyViews) ? 'checked' : ''}}>
                                    {{$view->langName()}}
                                </label>
                            </div>
                        </li>
                        @endforeach
                        @if ($errors->has('views'))<span class="help-block"><strong>{{ $errors->first('views') }}</strong></span> @endif
                    </ul>
                </div>
            </div></div>
    </div>
</div>
<div class="btmCenterBtn"><button class="blueGradientBtn">{{ (empty($property) || (isset($property) && (!$property->preview_mode) || ($property->preview_mode && empty($property->getEditCompleted('proximity'))))) ? trans('common.Save&Continue') : trans('common.Save') }}</button></div>
