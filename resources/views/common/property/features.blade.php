<div class="addInner-Sec">
    <div class="addheading-sec">
        <h2><span><i class="glyph-icon flaticon-favorite"></i></span>{{trans('common.PropertyFeatures')}}<sup><span class="asterix">*</span></sup></h2>
        <br>
        <small style="float:left;"><sup><span class="asterix">*</span></sup><span style="color:##2D2E2F;">{{trans('common.RequiredMsg')}}</span></small>
    </div>
    
    @include('common.property.header')
</div>

<div class="addProSection">
    <div class="formData check-main">
        <div class="row">
            <div class="col-sm-12">
                <div class="chk-inner">
                    <ul>
                        @php $propertyFeatures = $property->features->pluck('feature_id')->toArray(); @endphp

                        @foreach(App\Models\Features::all() as $feature)
                            <li>
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn chk-btn {{in_array($feature->id, $propertyFeatures) ? 'active' : ''}}">
                                        <input type="checkbox" name="features[]" value="{{$feature->id}}" {{in_array($feature->id, $propertyFeatures) ? 'checked' : ''}} autocomplete="off">
                                        {{ $feature->langName() }}
                                        @if(trans('explainers.feature_explain_'.$feature->search_key))
                                          <div class="tooltips"><i class="fa fa-info-circle" aria-hidden="true"></i>
                                              <span class="tooltiptext">
                                                {{ trans('explainers.feature_explain_'.$feature->search_key)}}
                                              </span>
                                          </div>
                                        @endif 
                                    </label>
                                </div>
                            </li>
                        @endforeach
                        @if ($errors->has('features'))<span class="help-block"><strong>{{ $errors->first('features') }}</strong></span> @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="btmCenterBtn"><button class="blueGradientBtn">{{ (empty($property) || (isset($property) && (!$property->preview_mode) || ($property->preview_mode && empty($property->getEditCompleted('views'))))) ? trans('common.Save&Continue') : trans('common.Save') }}</button></div>
