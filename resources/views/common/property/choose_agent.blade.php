<div class="addInner-Sec">
    <div class="addheading-sec">
        <h2 style="text-transform: uppercase;"><span ><i class="glyph-icon flaticon-user-1"></i></span>{{trans('common.ChooseRealEstateAgent')}}</h2>
        <br>
        <small style="float:left;"><sup><span class="asterix">*</span></sup><span style="color:##2D2E2F;">{{trans('common.RequiredMsg')}}</span></small>
    </div>

    @include('common.property.header')
</div>

<div class="addProSection">
    <div class="formData">
        <div  class="form-row">
            <div class="row">
                <div class="col-sm-4 pricing-col">
                    <div class="form-group">
                        <label for="exampleInputName1">{{trans('common.Agent')}} :</label>
                        <div class="selectOption">
                            <select name="agent">
                                <option value="" @if(!isset(Auth::user()->agency)) selected @endif>{{trans('common.ChooseAgent')}}</option>
                                @if(isset(Auth::user()->agency))
                                    @foreach(Auth::user()->agency->agents as $agent)
                                        <option value="{{$agent->id}}" @if($agent->id == $property->agent_id) selected @endif>{{$agent->name}}</option>
                                    @endforeach
                                @elseif(Auth::user()->role_id == 1)
                                    @php $allAgents = App\User::getAllAgents(); @endphp
                                    @foreach($allAgents as $agent)
                                        <option value="{{$agent->id}}" @if($agent->id == $property->agent_id) selected @endif>{{$agent->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                            @if ($errors->has('agent'))<span class="help-block"><strong>{{ $errors->first('agent') }}</strong></span> @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="titleMain">
        <h2><span><i class="glyph-icon flaticon-file-1"></i></span>{{trans('common.Summary')}}</h2>
    </div>
    <div class="formData">
        <div  class="form-row">
            <div class="row">
                <div class="col-sm-4 pricing-col">
                    <div class="form-group">
                        <label for="exampleInputName1">{{trans('common.PublishStatus')}}<sup><span class="asterix">*</span></sup> :</label>
                        <div class="selectOption">
                            
                            <select name="preview_mode">
                                <option value="" @if(!isset($property)) selected @endif>{{trans('common.SelectPublishStatus')}}</option>
                                @foreach(App\Models\Property::getPreviewModes() as $mode => $modeId)
                                  <option value="{{$modeId}}" @if($property->preview_mode == $modeId) selected @endif>{{$mode}}</option>
                                @endforeach
                            </select>
                            
                            @if ($errors->has('preview_mode'))
                                <span class="help-block">
                                    <strong>
                                        {{ str_replace('preview mode','publish status',$errors->first('preview_mode')) }}
                                    </strong>
                                </span>
                            @endif
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="btmCenterBtn"><button class="blueGradientBtn">{{trans('common.Save')}}</button></div>
