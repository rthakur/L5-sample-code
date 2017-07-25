@if(isset($property) && $property)
    @php
        $getFeatures = $property->getFeatureValues();
        $lang = Session::has('site_lang')? Session::get('site_lang') : 'en';
    @endphp

    <div class="last-viewed-property-info">
        <h4>{{ $property->texts->langSubject() }}</h4>
        <p>{{ $property->langAddress() }}</p>
        <!-- <img src="{{Session::get('last_property_viewed')['main_image']}}" /> -->
        <section id="property-gallery" style="width: 100%;">

            <div class="prop-slider-contain">
                @if(count($property->propertyImagesWithMainImage) > 1)
                    <div class="owl-carousel property-carousel" data-property-images="{{$property->propertyImagesWithMainImage}}">
                        @foreach($property->propertyImagesWithMainImage as $key => $image)
                            <div class="property-slide">
                                <div class="overlay"></div>
                                <img alt="" src="{{$image->s3_url}}">
                            </div><!-- /.property-slide -->
                        @endforeach
                    </div><!-- /.property-carousel -->

                @else
                    <div>
                        <img alt="" src="{{count($property->propertyImages) == 1 ? $property->propertyImages[0]->s3_url : $property->main_image_url}}">
                    </div>
                @endif
            </div>

            <div class="slide-prop-gallery-btns" style="margin-top: 10px;">
                @if(count($property->propertyImagesWithMainImage))
                    <a href="/{{$lang}}/property/{{$property->id}}/all-images" class="btn btn-default view-all-images-btn hidden" style="margin-right: 15px;">{{ trans('common.AllImages') }}</a>
                @endif
                <a class="btn btn-default view-all-images-btn" href="{{$property->detailPageURL()}}">{{ trans('viewproperty.FullView') }}</a>
            </div>
        </section>
    </div>
    <hr>

    <section id="quick-summary" class="clearfix">
        <dl>
            @if(isset($property->address))
                <dt>{{ trans('common.Location') }} :  </dt>
                <dd> {{$property->address}} </dd>
            @endif

            @php
                $propertyOriginalPrice = $property->originalPrice();
                $propertyExchangePrice = $property->exchangePrice();
            @endphp

            @if($propertyOriginalPrice != $propertyExchangePrice)
                <dt>{{ trans('common.OriginalPrice') }}: </dt>
                <dd><span class="tag price">{{$propertyOriginalPrice}}</span></dd>

                <dt>{{ $property->exchangeCurreny() }}: </dt>
                <dd><span class="tag price"> {{$propertyExchangePrice}}</span></dd>
            @else
                <dt>{{ trans('common.Price') }}: </dt>
                <dd><span class="tag price"> {{$propertyOriginalPrice}}</span></dd>
            @endif

            <dt>{{ trans('common.PropertyType') }}:</dt>
            <dd>{{isset($property->type)? trans('common.'. $property->type->name) : '&nbsp;'}}</dd>
            <dt>{{ trans('common.Status') }}:</dt>
            <dd>{{ ($property->mark_as_sold == '1')? trans('common.Sold') : trans('common.ForSale') }}</dd>

            @if($property->total_living_area)
                <dt>{{ trans('common.Area') }}:</dt>
                <dd>{{$property->total_living_area}} {{$property->total_living_area_type== 'sq.m.' ? 'm' : 'ft' }}<sup>2</sup></dd>
            @endif

            @if($property->total_garden_area)
                <dt>{{ trans('common.GardenArea') }}:</dt>
                <dd>{{$property->total_garden_area}} {{$property->total_garden_area_type == 'sq.m.' ? 'm' : 'ft' }}<sup>2</sup></dd>
            @endif

            @if($property->rooms)
                <dt>{{ trans('common.Rooms') }}:</dt>
                <dd>{{ $property->rooms }}</dd>
            @endif

            @if($property->build_year)
                <dt>{{ trans('common.BuildYear') }}:</dt>
                <dd>{{ $property->build_year }}</dd>
            @endif

            @php $propViews = $property->views; @endphp

            @if($propViews->count())
                @foreach($propViews as $view)
                    <dt>{{App\Models\View::getLangName($view->name)}}</dt><dd>Yes</dd>
                @endforeach
            @endif

        </dl>

        @if($property->agency)
            <a href="{{$property->agency->detailPageURL()}}">
                @if($property->agency->logo && file_exists($property->agency->logo))
                    <img alt="" class="agency_logo" src="{{$property->agency->logo}}">
                @else
                    <b>{{$property->agency->public_name}}</b>
                @endif
            </a>
        @endif
    </section>

    <div>
        <header><h2>{{ trans('viewproperty.Propertydescription') }}</h2></header>
        <div class="property-detail-text">{!! $property->texts->langDescription() !!}</div>

        @if($property->property_url)
            <div class="alert alert-info">
                <a target="_new" href="{{$property->property_url}}">{{ trans('common.ReadMoreAboutProperty') }} <i class="fa fa-external-link"></i></a>
            </div>
        @endif

    </div>

    @if(count($property->features))
        <section id="property-features">
            <header><h2>{{ trans('viewproperty.PropertyFeatures') }}</h2></header>
            <ul class="list-unstyled property-features-list">
                @foreach($property->features as $features)
                    <li>
                        {{$features->feature->langName()}}

                        @if(trans('explainers.feature_explain_'.$features->feature->search_key))
                            <div class="tooltips" data-placement="bottom"><i class="fa fa-info-circle" aria-hidden="true"></i>
                                <span class="tooltiptext">
                                    {{ trans('explainers.feature_explain_'.$features->feature->search_key)}}
                                </span>
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        </section>
    @endif

@else
    <center>{{trans('common.NoPropertySelectedYet')}}</center>
@endif
