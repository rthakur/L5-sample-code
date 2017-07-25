<div class="addInner-Sec">
    <div class="addheading-sec">
        <h2><span><i class="glyph-icon flaticon-signs"></i></span>Property Description</h2>
        <br>
        <small style="float:left;"><sup><span class="asterix">*</span></sup><span style="color:##2D2E2F;">{{trans('common.RequiredMsg')}}</span></small>
        
    </div>
    
    @include('common.property.header')
</div>

<div class="addProSection">
    <div class="language-main">
        <div class="language-text">
            <h3>{{ trans('common.DescriptionLanguage') }}<sup><span class="asterix">*</span></sup> :</h3>
        </div>
        <div class="language-clr">
            <div class="changeOne clr">
                <div class="btn-group" style="margin:0px;"> <!-- CURRENCY, BOOTSTRAP DROPDOWN --> 
                    <!--<a class="btn btn-primary" href="javascript:void(0);">Currency</a>--> 
                    @php 
                        $propertyTexts = $property->texts;
                        $siteLang = str_replace('/','',SITE_LANG);
                        $currLang = (!empty($propertyTexts) && $propertyTexts->checkTextExists('description_')) ? $propertyTexts->checkTextExists('description_') : $siteLang;
                    @endphp
                    
                    <a class="btn btn-primary dropdown-toggle btn-drop cv" data-toggle="dropdown" href="#" data-lang="{{$currLang}}">
                        <img src="/assets/img/flags/{{$currLang}}.png" />{{App\Models\Language::getLangByCountryCode($currLang)}}
                        <span> <i class="glyph-icon flaticon-arrow-down-sign-to-navigate"></i></span>
                    </a>
                    
                    <input type="hidden" name="lang" value="{{$currLang}}">
                    
                    <ul class="dropdown-menu changeOne">
                        @foreach(App\Models\Language::getAllLanguages() as $name => $shortName)
                            <li>
                                <a href="javascript:void(0);" class="change-description-lang" data-lang-code="{{$shortName}}" data-{{$shortName}} data-description="{{ $property->texts->getLangDescription($shortName) }}">
                                    <img src="/assets/img/flags/{{$shortName}}.png"/> 
                                    <span>{{$name}}</span>
                                </a> 
                            </li>
                        @endforeach
                        <!-- <li><a href="javascript:void(0);"> <img src="/assets/img/property_edit/flag2.jpg" /> <span>Français</span></a></li> -->
                    </ul>
                    
                    @if ($errors->has('lang'))
                        <span class="help-block"><strong>{{ $errors->first('lang') }}</strong></span>
                    @endif
                    
                </div>
            </div>
        </div>
    </div>
    
    <div class="formData">
        <div class="form-group form_inner1">
            <label for="lang-description">{{trans('common.Description')}}<sup><span class="asterix">*</span></sup> :</label>
            <textarea id="lang-description" name="description" class="form-control custom_bg" rows="6"></textarea>
            @if ($errors->has('description'))<span class="help-block"><strong>{{ $errors->first('description') }}</strong></span> @endif
        </div>
    </div>
</div>

<div class="btmCenterBtn"><button class="blueGradientBtn">{{ (empty($property) || (isset($property) && (!$property->preview_mode) || ($property->preview_mode && empty($property->getEditCompleted('location'))))) ? trans('common.Save&Continue') : trans('common.Save') }}</button></div>

@section('inner_extra_scripts')
<script>

$(document).ready(function(){
    var getLang = $('[data-lang]').data('lang');
    $('#lang-description').val($("[data-"+ getLang +"]").data('description'));
});

$('.change-description-lang').on('click',function(){
    $('[name="lang"]').val($(this).data('lang-code'));
    $('#lang-description').val($(this).data('description'));
});
</script>
@endsection