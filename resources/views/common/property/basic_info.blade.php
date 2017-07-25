@php use App\Models\PropertyTypes; @endphp

<div class="addInner-Sec">
    <div class="addheading-sec">
        <h2><span><i class="glyph-icon flaticon-file"></i></span>{{ trans('common.BasicInfo') }}</h2>
        <br>
        <small style="float:left;"><sup><span class="asterix">*</span></sup><span style="color:##2D2E2F;">{{trans('common.RequiredMsg')}}</span></small>
    </div>

    @include('common.property.header')
</div>

<div class="addProSection">
    <div class="formData">
        <div class="language-main">
            <div class="language-text">
                <h3>{{ trans('common.TitleLanguage') }}<sup><span class="asterix">*</span></sup> :</h3>
            </div>
            <div class="language-clr">
                <div class="changeOne clr">
                    <div class="btn-group" style="margin:0px;"> <!-- CURRENCY, BOOTSTRAP DROPDOWN -->
                        <!--<a class="btn btn-primary" href="javascript:void(0);">Currency</a>-->
                        @php
                         if(isset($property))
                             $propertyTexts = $property->texts;

                         $siteLang = str_replace('/','',SITE_LANG);
                         $currLang = (!empty($propertyTexts) && $propertyTexts->checkTextExists('subject_')) ? $propertyTexts->checkTextExists('subject_') : $siteLang;
                        @endphp

                        <a class="btn btn-primary dropdown-toggle btn-drop cv" data-toggle="dropdown" href="#" data-lang="{{$currLang}}">
                            <img src="/assets/img/flags/{{$currLang}}.png" />{{App\Models\Language::getLangByCountryCode($currLang)}}
                            <span> <i class="glyph-icon flaticon-arrow-down-sign-to-navigate"></i></span>
                        </a>

                        <input type="hidden" name="lang" value="{{$currLang}}">

                        <ul class="dropdown-menu changeOne">
                            @foreach(App\Models\Language::getAllLanguages() as $name => $shortName)
                                <li>
                                    <a href="javascript:void(0);" data-lang-code="{{$shortName}}" data-{{$shortName}} class="change-title-lang" data-title="{{isset($property) ?  $property->texts->getLangSubject($shortName) : '' }}">
                                        <img src="/assets/img/flags/{{$shortName}}.png"/>
                                        <span>{{$name}}</span>
                                    </a>
                                </li>
                            @endforeach
                            <!-- <li><a href="javascript:void(0);"> <img src="/assets/img/property_edit/flag2.jpg" /> <span>Français</span></a></li> -->
                        </ul>


                    </div>

                    @if ($errors->has('lang'))
                        <span class="help-block"><strong>{{ $errors->first('lang') }}</strong></span>
                    @endif

                </div>
            </div>
        </div>
        <div class="form-group form_inner1">
            <label for="lang-title">{{ trans('common.Title') }}<sup><span class="asterix">*</span></sup> :</label>
            <textarea id="lang-title" name="title" class="form-control custom_bg" rows="1"></textarea>
            @if ($errors->has('title'))<span class="help-block"><strong>{{ $errors->first('title') }}</strong></span> @endif
        </div>
    </div>
</div>

<div class="addProSection">

    <div class="titleMain">
        <h2>
            <span><i class="glyph-icon flaticon-real-estate"></i></span>
            {{ trans('common.PropertyType') }}<sup><span class="asterix">*</span></sup>

            @if ($errors->has('type'))
                <span class="help-block"><strong>{{ $errors->first('type') }}</strong></span>
            @endif

        </h2>
    </div>


    <div class="formData">

        @php $allTypes = PropertyTypes::getAllTypes(); @endphp

        @foreach(App\Models\PropertyTypes::getGroupedTypes()['living'] as $category => $typeData)

                <div class="property-info">
                    <h2>{{ $category }}</h2>
                    <div class="chooseProType">
                        <ul>
                            @foreach($typeData as $type)
                            <li>
                                <div class="customOption-sec">
                                    <div class="btn-group" data-toggle="buttons">
                                        <label class="btn {{(!empty($property->property_type_id) && $property->type->search_key == $type) ? 'active' : ''}}">
                                            <input name="type" class="type-item" value="{{$type}}" autocomplete="off" type="radio" @if(!empty($property->property_type_id) && $property->type->search_key == $type) checked @endif>
                                            <i class="glyph-icon {{PropertyTypes::$typeIcons[$type]}}"></i> <span>{{ trans('common.'. $allTypes[$type]) }}</span>
                                        </label>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

        @endforeach

        <div class="property-info">
            <h2>{{ trans('common.Others') }}</h2>

            <div class="chooseProType col-sm-12">
                <ul>
                    @foreach(App\Models\PropertyTypes::getGroupedTypes()['others'] as $type)
                    <li>
                        <div class="customOption-sec">
                            <div class="btn-group" data-toggle="buttons">
                                <label class="btn {{(!empty($property->property_type_id) && $property->type->search_key == $type) ? 'active' : ''}}">
                                    <input name="type" class="type-item" value="{{$type}}" autocomplete="off" type="radio" @if(!empty($property->property_type_id) && $property->type->search_key == $type) checked @endif>
                                    <i class="glyph-icon {{PropertyTypes::$typeIcons[$type]}}"></i>
                                    <span>{{ trans('common.'. $allTypes[$type]) }}</span>
                                </label>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-md-12 col-sm-12">
              <div class="form-group">
                <div class="checkbox">
                  <input type="checkbox" id="mark_as_sold" name="mark_as_sold" value="1" @if(isset($property) && $property->mark_as_sold) checked="" @endif>
                  <label for="mark_as_sold">{{ trans('common.MarkAsSold') }}</label>
                </div>
              </div>
            </div>
        </div>

        <div class="row @if(empty($property->mark_as_sold)) hide @endif" id="soldPriceContainer">
            <hr />
            <div class="col-md-6 col-sm-6">
                <div class="form-group">
                    <label for="submit-area">{{ trans('common.SoldCurrency') }}</label>
                    <div class="input-group">
                      <select name="sold_price_currency" id="sold-price-currency">
                          @foreach($allCurrency as $currency)
                            <option value="{{$currency->id}}" data-symbol="{{$currency->symbol}}"
                              @if(old('type') == $currency->currency)
                                selected
                              @elseif(isset($property) && $property->sold_price_currency_id == $currency->id)
                                selected
                              @elseif($currency->currency == 'USD')
                                selected
                              @endif
                              >{{$currency->currency}}</option>
                          @endforeach
                      </select>
                      @if($errors->has('sold_price_currency'))
                       <p class="help-block">{{$errors->first('sold_price_currency')}}</p>
                      @endif
                    </div>
                </div><!-- /.form-group -->
            </div><!-- /.col-md-6 -->
            <div class="col-md-6 col-sm-6">
                  <div class="form-group">
                      <label for="sold-price">{{ trans('common.SoldPrice')}}</label>
                      <div class="input-group">
                          <span class="input-group-addon sold-price-currency-symbol">{{isset($property) ? $property->soldCurrencySign() : ''}}</span>
                          <input type="text" class="form-control" id="sold-price" name="sold_price" value="{{ old('sold_price', isset($property) ? $property->sold_price : '') }}" pattern="\d*">
                          @if($errors->has('sold_price'))
                            <p class="help-block">{{$errors->first('price')}}</p>
                          @endif
                      </div>
                  </div><!-- /.form-group -->
            </div><!-- /.col-md-6 -->
        </div><!-- /.row -->

    </div>

</div>

<div class="btmCenterBtn"><button class="blueGradientBtn">{{ (empty($property) || (isset($property) && (!$property->preview_mode) || ($property->preview_mode && empty($property->getEditCompleted('description'))))) ? trans('common.Save&Continue') : trans('common.Save') }}</button></div>

<div class="descriptionSpace">
    <div class="descriptionBox">
        <div class="descriptionTitle"><i class="glyph-icon flaticon-information-button"></i> {{ trans('common.Note') }}</div>
        <div class="descriptionText">
            <p> {{ trans('common.PropertyEditNote') }}</p>
        </div>
    </div>
</div>

@section('inner_extra_scripts')
<script>

$(document).ready(function(){
    var getLang = $('[data-lang]').data('lang');
    $('#lang-title').val($("[data-"+ getLang +"]").data('title'));

    $('.type-item').on('change', function(){
        $('.type-item').each(function(){
            $(this).parent().removeClass('active');
        });
    })
});

$('.change-title-lang').on('click',function(){
    $('[name="lang"]').val($(this).data('lang-code'));
    $('#lang-title').val($(this).data('title'));
});
</script>
@endsection
