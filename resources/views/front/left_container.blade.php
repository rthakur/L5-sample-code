<div class="home-left-container property-detail-container">
  <button type="button" class="close close-lg detail-container-close"><i class="glyph-icon flaticon-cancel-music"></i></button>
  <div id="propertyDetailContainer">
    @if(isset($property))
      @include('common.property.selected')
    @endif
  </div>
  @if(!isset($property))
  <div class="video-container hidden-xs">
    <video autoplay loop poster="">
      <source src="/assets/mov/moved-in-smaller-qval.mp4" type="video/mp4">
      {{ trans('common.BrowserNotSupportVideo') }}.
    </video>
  </div>
  @endif
</div>