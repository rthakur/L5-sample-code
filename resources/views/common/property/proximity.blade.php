<div class="addInner-Sec">
    <div class="addheading-sec">
        <h2><span><i class="glyph-icon flaticon-holidays"></i></span>{{trans('common.InProximity')}}<sup><span class="asterix">*</span></sup></h2>
        <br>
        <small style="float:left;"><sup><span class="asterix">*</span></sup><span style="color:##2D2E2F;">{{trans('common.RequiredMsg')}}</span></small>
    </div>
    
    @include('common.property.header')
</div>

<div class="addProSection">
    <div class="check-main formData">
        <div class="row">
            <div class="col-sm-12">
                <div class="chk-inner">
                    <ul>
                        @php $propertyProximities = $property->proximities->pluck('proximity_id')->toArray(); @endphp

                        @foreach(App\Models\Proximities::all() as $proximity)
                        <li>
                            <div class="btn-group" data-toggle="buttons">
                                <label class="btn chk-btn {{in_array($proximity->id, $propertyProximities) ? 'active' : ''}}">
                                    <input type="checkbox" name="proximities[]" value="{{$proximity->id}}" autocomplete="off" {{in_array($proximity->id, $propertyProximities) ? 'checked' : ''}}>
                                    {{$proximity->langName()}}
                                    @if(trans('explainers.proximities_explain_'.$proximity->search_key))
                                    <div class="tooltips"><i class="fa fa-info-circle" aria-hidden="true"></i>
                                        <span class="tooltiptext">
                                          {{ trans('explainers.proximities_explain_'.$proximity->search_key)}}
                                        </span>
                                    </div>
                                    @endif
                                </label>
                            </div>
                        </li>
                        @endforeach
                        @if ($errors->has('proximities'))<span class="help-block"><strong>{{ $errors->first('proximities') }}</strong></span> @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="btmCenterBtn"><button class="blueGradientBtn">{{ (empty($property) || (isset($property) && (!$property->preview_mode) || ($property->preview_mode && empty($property->getEditCompleted('gallery'))))) ? trans('common.Save&Continue') : trans('common.Save') }}</button></div>
