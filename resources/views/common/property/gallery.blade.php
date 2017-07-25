<div class="addInner-Sec">
    <div class="addheading-sec">
        <h2><span><i class="glyph-icon flaticon-gallery"></i></span>{{trans('common.Gallery')}}</h2>
    </div>
    
    @include('common.property.header')                                
</div>

<div class="addProSection">
    <div class="gallery-sec formData gallery-images" id="images-upload-area">
        @include('common.property.images')
    </div>
    
    @if(!$property->preview_mode)
        <div class="btmCenterBtn">
            <a href="{{SITE_LANG}}/property/{{$property->id}}/edit/choose_agent" class="blueGradientBtn">{{trans('common.Continue')}}</a>
        </div>
    @endif
</div>