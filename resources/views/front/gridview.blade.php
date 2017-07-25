<input type="hidden" id="status-hidden" value="{{$status}}">
<input type="hidden" id="properties-count-hidden" value="{{$propertiesCount}}">
<!-- <input type="hidden" id="lang-hidden" value="{{$lang}}"> -->
<input type="hidden" id="filter-string-hidden" value="{{!empty($filterString) ? json_encode($filterString) : ''}}">
@php use App\Helpers\CommonHelper; @endphp

@if(count($properties))

@foreach($properties as $property)

    @php $agentUrl = '/' . $lang . '/' . trans('seolinks.realestateagent') . '/' . $property->user_id . '/' . CommonHelper::cleanString($property->agency_name) . '/' . CommonHelper::cleanString($property->agent_name); @endphp

    <article class="white-panel noPadding">
        <div class="listingOuter-sec">

            <div style="position: relative">
                <a href="{{ '/' . $lang .'/property/view/'. $property->id}}">
                    <img src="{{$property->s3_url}}">
                </a>
            </div>

            <div>
                <div class="subject">
                    <a href="{{ '/' . $lang .'/property/view/'. $property->id}}">{{ CommonHelper::filterString($property->texts->langSubject()) }}</a>
                </div>
            </div>

            <div class="locationInfo"><i class="fa fa-map-marker"></i>&nbsp;{{CommonHelper::propertyLangAddress($property)}}</div>

            @if ($property->agent_name)
                <div class="agent-detail detailSec">
                    <div class="img-radius">
                        <a href="{{$agentUrl}}">
                            <img src="{{$property->agent_profile_picture}}" onerror=this.src="/assets/img/agent-01.jpg">
                        </a>
                    </div>
                    <span class="profilName">
                        <a href="{{$agentUrl}}">{{$property->agent_name}}</a>
                    </span>
                </div>
            @endif

            @php
                $checkSameCurrency = ($property->price_currency_id != Session::get('currency'));
            @endphp

            <div class="priceSection">
                <a href="{{$property->detailPageURL()}}">{{ trans('common.ReadMore') }}</a>

                <div class="property-list-bottom">
                    <div class="tag price {{$checkSameCurrency ? 'old-price' : 'new-price'}}">
                        <span>{{$property->originalPrice()}}</span>

                        @if ($checkSameCurrency)
                            <span class="orginial-price-txt">(Original Price)</span>
                        @endif
                    </div>

                    @if ($checkSameCurrency)
                        <div class="tag price new-price">{{$property->exchangePrice()}}</div>
                    @endif

                </div>
            </div>

        </div>

        <div class="property-more-tags more-tags">

            <li>
                <a class="property-more-tags-btn">
                    <i class="glyph-icon flaticon-up-arrow"></i> {{$property->total_living_area}} {{$property->total_living_area_type == 'sq.m.' ? 'm' : 'ft'}}<sup>2</sup>
                </a>
            </li>

            @if ($property->rooms)
            <li>
                <a class="property-more-tags-btn">
                    <i class="glyph-icon flaticon-rest"></i> {{$property->rooms}} {{ trans('common.Rooms') }}
                </a>
            </li>
            @endif

            <li>
                <a href="{{$property->PropertyMapURL()}}" class="property-more-tags-btn view-map-btn">
                    <i class="glyph-icon flaticon-pin"></i>
                    {{ trans('common.ViewOnMap') }}
                </a>
            </li>
        </div>

    </article>
@endforeach

@else
    <h3 class="text-center" style="margin: 0px auto; margin-top: 200px;">{{trans('common.NoPropertiesFound')}}</h3>
@endif
