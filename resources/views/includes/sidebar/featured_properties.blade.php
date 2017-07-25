@php $featuredProperties = App\Models\Property::featuredProperties(4); @endphp
@if($featuredProperties->count())
<aside id="featured-properties">
    <header><h3>{{ trans('common.FeaturedProperties') }}</h3></header>

      @foreach($featuredProperties as $getProperty)
        <div class="property small">
            <a href="{{$getProperty->detailPageURL()}}">
                <div class="property-image">
                    <img alt="" src="{{ $getProperty->main_image_url }}">
                </div>
            </a>
            <div class="info">
                <a href="{{$getProperty->detailPageURL()}}"><h4>{{$getProperty->texts->langSubject()}}</h4></a>
                <figure>{{$getProperty->langAddress()}}</figure>
                @if($getProperty->originalPrice() != $getProperty->exchangePrice())
                <div class="tag price">{{$getProperty->exchangePrice()}}</div>
                @else
                <div class="tag price">{{$getProperty->originalPrice()}}</div>
                @endif
                <!--div class="tag price">{{$getProperty->originalPrice()}}</div -->
            </div>
        </div><!-- /.property -->
      @endforeach
</aside><!-- /#featured-properties -->
@endif

