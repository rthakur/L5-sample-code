<div class="row">
  <div class="col-sm-12" id="lastviewedContainer" data-site-lang="{{SITE_LANG}}" data-link-value="{{ trans('common.ShowPropertyDetails') }}">
    @if(Session::has('last_property_viewed') && Session::get('last_property_viewed')['main_image'])
      <div class="last-viewed-property-info">
        <h4>{{Session::get('last_property_viewed')['subject']}}</h4>
        <p>{{Session::get('last_property_viewed')['address']}}</p>
        
        @if($property && $property->originalPrice() != $property->exchangePrice())
        <span class="tag price" style="top: 60%;">{{Session::get('last_property_viewed')['price']}}</span>
        <span class="tag price" style="top: 70%;">{{$property->exchangePrice()}}</span>
        @else
        <span class="tag price" style="top: 70%;">{{Session::get('last_property_viewed')['price']}}</span>
        @endif
        
        <img src="{{Session::get('last_property_viewed')['main_image']}}" />
        <br><br>
        <a href="{{SITE_LANG}}/property/view/{{Session::get('last_property_viewed')['id']}}" class="btn btn-default">{{ trans('common.ShowPropertyDetails') }}</a>
      </div>
    @else
      <video width="100%" height="200" autoplay loop>
        <source src="/assets/mov/moved-in-smaller-qval.mp4" type="video/mp4">
        {{ trans('common.BrowserNotSupportVideo') }}.
      </video>
    @endif
  </div>
</div>
